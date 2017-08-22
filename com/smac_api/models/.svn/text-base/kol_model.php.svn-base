<?php
include_once $APP_PATH."/smac/helper/FBHelper.php";
class kol_model extends base_model{
	var $twitter_kols;
	var $fb_kols;
	var $web_kols;
	private $request;
	function setRequestHandler($req){
		$this->request = $req;
	}
	function twitter($campaign_id,$exclude=0){
		$data = array("top_kol"=>$this->twitter_top_kol($campaign_id,$exclude),
					  "top_impression"=>$this->twitter_impression($campaign_id),
					  "top_mention"=>$this->twitter_mention($campaign_id),
					  "top_influencers"=>$this->twitter_influencers($campaign_id,$exclude)
					  );
		return $data;
	}
	function twitter_top_kol($campaign_id,$exclude=0){
		//total impressions
		$sql = "SELECT SUM(total_impression_twitter) AS true_reach
		FROM smac_report.campaign_rule_volume_history 
		WHERE campaign_id={$campaign_id};";
		$campaign = $this->fetch($sql);
		$total_impression = $campaign['true_reach'];
		$campaign=null;
		
		//--> top KOL
		if($exclude==1){
				$sql = "SELECT * FROM smac_report.twitter_top_authors
				WHERE campaign_id = {$campaign_id}
				AND author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=1)
				ORDER BY total_impression desc
				LIMIT 10;";
		}else if($exclude==2){
			$sql = "SELECT * FROM smac_report.twitter_top_authors
				WHERE campaign_id = {$campaign_id}
				AND author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=2)
				ORDER BY total_impression desc
				LIMIT 10;";
		}else{
			$sql = "SELECT * FROM smac_report.twitter_top_authors
				WHERE campaign_id = {$campaign_id}
				ORDER BY total_impression desc
				LIMIT 10;";
		}

		$rs = $this->fetch($sql,1);
		$kol = array();
		$this->twitter_kols = ""; //list of kols
		foreach($rs as $n=>$r){
			if($n>0){
				$this->twitter_kols.=",";
			}
			$this->twitter_kols.="'{$r['author_id']}'";
			$kol[] = array("id"=>$r['author_id'],
						   "name"=>$r['author_name'],
						   "impression"=>$r['total_impression'],
						   "share"=>round(($r['total_impression'] / $total_impression) * 100,2),
						   "img"=>$r['author_avatar'],
						   "rt_percentage"=>round($r['total_rt_mentions']/($r['total_mentions'] + $r['rt_mention']) * 100,2));
		}
		unset($rs);
		return $kol;	
	}
	function twitter_impression($campaign_id){
		$kol = array();
		if(strlen($this->twitter_kols)>0){
			$sql = "SELECT 
						author_id,
						author_name,
						total_impression AS total  
					FROM 
						smac_report.twitter_top_authors 
					WHERE 
						campaign_id=".intval($campaign_id)." AND 
						author_id IN (".($this->twitter_kols).") 
					GROUP BY 
						author_name 
					ORDER BY 
						author_name ASC";
						
			$rs = $this->fetch($sql,1);			
			for($i=0;$i<sizeof($rs);$i++){
				$r = $rs[$i];
				$kol[] = array("id"=>$r['author_id'],
							   "name"=>$r['author_name'],
							   "total"=>$r['total']);
			}
		}
		return $kol;
	}
	function twitter_mention($campaign_id){
		$sql = "SELECT 
					author_id,
					author_name,
					total_mentions AS total  
				FROM 
					smac_report.twitter_top_authors 
				WHERE 
					campaign_id=".intval($campaign_id)." AND 
					author_id IN (".($this->twitter_kols).") 
				GROUP BY 
					author_name 
				ORDER BY 
					author_name ASC";
		$rs = $this->fetch($sql,1);
		$kol = array();
		foreach($rs as $r){
			$kol[] = array("id"=>$r['author_id'],
						   "name"=>$r['author_name'],
						   "total"=>$r['total']);
		}
		return $kol;
	}
	function twitter_daily_stats($campaign_id,$authors){
		$person = explode(",",mysql_escape_string(cleanXSS($authors)));
		$data = array();
		$n_total = sizeof($person);
		//we can only allow 10 person to be queried.
		if($n_total>10){
			$n_total = 10;
		}
		$ts = array();
		for($i=0;$i<$n_total;$i++){
			$author_id = trim($person[$i]);
			$sql = "SELECT dtpost,SUM(imp) AS impression,SUM(rt_imp) AS rt_impression,SUM(total_mentions) AS mentions,
					UNIX_TIMESTAMP(dtpost) AS ts 
					FROM smac_report.campaign_author_daily_stats 
					WHERE campaign_id={$campaign_id} AND author_id='{$author_id}'
					GROUP BY dtpost;";
			$rs = $this->fetch($sql,1);
			if(sizeof($rs)>0){
				foreach($rs as $r){
					$ts[$r['ts']]=1;
				}
			}
			$data[$author_id] = $rs;
		}
		//equalized the date range data for each author
		if(sizeof($ts)>0){
			foreach($ts as $t=>$tv){
				foreach($data as $n=>$a){
					$found = false;
					foreach($a as $v){
						if($v['ts']==$t){
							$found=true;
						}
					}
					if(!$found){
						$data[$n][] = array('dtpost'=>date("Y-m-d",$t),"impression"=>0,"rt_impression"=>0,"mentions"=>0,"ts"=>$t);
					}
				}	
			}
		}
		return $data;
	}
	function twitter_sentiment($campaign_id){
		$sql = "SELECT 
					author_id,
					author_name,
					total_mentions AS total  
				FROM 
					smac_report.twitter_top_authors 
				WHERE 
					campaign_id=".intval($campaign_id)." AND 
					author_id IN (".($this->twitter_kols).") 
				GROUP BY 
					author_name 
				ORDER BY 
					author_name ASC";
		$rs = $this->fetch($sql,1);
		$kol = array();
		foreach($rs as $r){
			$kol[] = array("id"=>$r['author_id'],
						   "name"=>$r['author_name'],
						   "total"=>$r['total']);
		}
		return $kol;
	}
	function twitter_influencers($campaign_id,$exclude=0,$total=10){
		//ambassador
		
		if($exclude==1){
			$sql = "SELECT author_id as author,author_avatar as pic,sentiment as total,pii_score 
				FROM smac_supporter.campaign_ambas_{$campaign_id}
				WHERE author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=1)
				ORDER BY sentiment DESC LIMIT {$total}";
			
		}else if($exclude==2){
			
			$sql = "SELECT author_id as author,author_avatar as pic,sentiment as total,pii_score 
				FROM smac_supporter.campaign_ambas_{$campaign_id}
				WHERE author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=2)
				ORDER BY sentiment DESC LIMIT {$total}";
		
		}else{
			$sql = "SELECT author_id as author,author_avatar as pic,sentiment as total,pii_score 
					FROM smac_supporter.campaign_ambas_{$campaign_id}
					ORDER BY sentiment DESC LIMIT {$total}";
			
		}
		
		$positive = $this->fetch($sql,1);
		//trolls
		
		if($exclude==1){
			$sql = "SELECT author_id as author,author_avatar as pic,sentiment as total,pii_score 
				FROM smac_supporter.campaign_trolls_{$campaign_id}
				WHERE author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=1)
				ORDER BY sentiment ASC LIMIT {$total}";
		}else if($exclude==2){
			$sql = "SELECT author_id as author,author_avatar as pic,sentiment as total,pii_score 
				FROM smac_supporter.campaign_trolls_{$campaign_id}
				WHERE author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=2)
				ORDER BY sentiment ASC LIMIT {$total}";
		}else{
			$sql = "SELECT author_id as author,author_avatar as pic,sentiment as total,pii_score 
				FROM smac_supporter.campaign_trolls_{$campaign_id}
				ORDER BY sentiment ASC LIMIT {$total}";	
		}
			
		$negative = $this->fetch($sql,1);
		
		$ambas = array();
		for($i=0;$i<sizeof($positive);$i++){
				$sql = "SELECT SUM(total_mentions) AS mentions,SUM(imp) AS impression,SUM(rt_imp) AS rt_imp,SUM(rt_mention) AS rt
							FROM smac_report.campaign_author_daily_stats WHERE campaign_id={$campaign_id} 
							AND author_id='{$positive[$i]['author']}' GROUP BY campaign_id;";
				$stat = $this->fetch($sql);
				$ambas[] = array("img"=>$positive[$i]['pic'],
								  "name"=>$positive[$i]['author'],
								  "total"=>$positive[$i]['total'],
								  "stats"=>$stat);
		}
		unset($positive);
		$trolls = array();
		for($i=0;$i<sizeof($negative);$i++){
			$sql = "SELECT SUM(total_mentions) AS mentions,SUM(imp) AS impression,SUM(rt_imp) AS rt_imp,SUM(rt_mention) AS rt
							FROM smac_report.campaign_author_daily_stats WHERE campaign_id={$campaign_id} 
							AND author_id='{$negative[$i]['author']}' GROUP BY campaign_id;";
				$stat = $this->fetch($sql);
				$trolls[] = array("img"=>$negative[$i]['pic'],
								  "name"=>$negative[$i]['author'],
								  "total"=>$negative[$i]['total'],
								  "stats"=>$stat);
		}
		unset($negative);
		return array("positive"=>$ambas,"negative"=>$trolls);
	}
	function twitter_all_people($campaign_id,$req){
			$aColumns = array( 'author_id', 'author_id','author_name', 'total_impression','total_impression');

			$sql = "SELECT COUNT(*) as total FROM smac_author.author_summary_{$campaign_id}  LIMIT 1";
			$row = $this->fetch($sql);
			$total = intval($row['total']);
			
			//LIMIT
			$start = 0;
			$limit = 10;
			
			if ($req->getParam('iDisplayLength') != '-1' )
			{
				$start = intval($req->getParam('iDisplayStart'));
				$limit = intval($req->getParam('iDisplayLength'));
			}
			
			/*
			 * Ordering
			 */
			$sOrder = "";
			if ( $req->getParam('iSortCol_0')!=null )
			{
				$sOrder = "ORDER BY  ";
				for ( $i=0 ; $i<intval( $req->getParam('iSortingCols') ) ; $i++ )
				{
					if ( $req->getParam('bSortable_'.intval($req->getParam('iSortCol_'.$i)) ) == "true" )
					{
						//echo "Masuk<br />";
						$sOrder .= $aColumns[ intval( $req->getParam('iSortCol_'.$i) ) ]." ". $req->getParam('sSortDir_'.$i) .", ";
					}
				}
				
				$sOrder = substr_replace( $sOrder, "", -2 );
				if ( $sOrder == "ORDER BY" )
				{
					$sOrder = "";
				}
			}
			/**
			 * conditions
			 */
			$sWhere = "";
			if ($req->getParam('sSearch') != "" )
			{
				$sWhere = "WHERE (";
				for ( $i=0 ; $i<count($aColumns) ; $i++ )
				{
					$sWhere .= $aColumns[$i]." LIKE '%". $req->getParam('sSearch') ."%' OR ";
				}
				$sWhere = substr_replace( $sWhere, "", -3 );
				$sWhere .= ')';
			}
			//wrapping up
			$dat = $this->_getAllTwitterAuthors($campaign_id,$start,$limit,$sOrder,$sWhere,$exclude);
			
			$data = array(
				"sEcho" => intval($req->getParam('sEcho')),
				"iTotalRecords" => $total,
				"iTotalDisplayRecords" => $total,
				"aaData" => array()
			);
			
			
			
			foreach($dat as $k){
				$idx = $k['id'];
				
				$data['aaData'][] = array( "<div class=\"smallthumb\"><a href=\"#\" onclick=\"twitterPopup('{$k['author_id']}', '{$k['author_name']}'); return false;\" rel=\"profile\"><img src='".htmlspecialchars($k['author_avatar'])."' /></a></div>",
												$k['author_id'],
												$k['author_name'],
												"<span style=\"float:right\">".number_format($k['impression'])."</span>",
												"<span style=\"float:right\">".$k['share']."</span>",
												"<span style=\"float:right\">".$k['pii']."</span>"
												);
				
			}
		

		return $data;
	}
	
	private function _getAllTwitterAuthors($campaign_id,$start,$total,$order,$where,$exclude){
			//total impressions
			$sql = "SELECT SUM(total_impression_twitter) AS true_reach
			FROM smac_report.campaign_rule_volume_history 
			WHERE campaign_id={$campaign_id};";
			
			$campaign = $this->fetch($sql);
			$total_impression = $campaign['true_reach'];
			$campaign=null;
			
			//--->
			
			$order = mysql_escape_string($order);
			if($order == ''){
				$order = "";
			}
			

			$qry = "select *,0 as share 
			from smac_author.author_summary_{$campaign_id}
			{$where}
			{$order} 
			LIMIT ".$start.",".$total;
			
			$rs = $this->fetch($qry,1);
			$data = array();
			for($i=0;$i<sizeof($rs);$i++){
				$r = $rs[$i];
				if($r['total_impression']==null){
						$r['total_impression']=0;
				}
				if($r['total_rt_impression']==null){
					$r['total_rt_impression']=0;
				}
				
				$share = round(($r['total_impression'])/$total_impression*100,2);
				$author_id = $r['author_id'];
				
				$sql1 = "SELECT a.author_id,SUM(pii) AS pii_total 
						FROM smac_feeds.campaign_feeds_{$campaign_id} a
						INNER JOIN smac_sentiment.campaign_feed_sentiment_{$campaign_id} b
							ON a.id = b.feed_id 
						WHERE a.campaign_id={$campaign_id} AND a.author_id='".mysql_escape_string($r['author_id'])."' 
						GROUP BY a.author_id LIMIT 1;";
				$pii = $this->fetch($sql1);
				
				$data[] = array("id" => intval($r['id']),
										  "author_id" => $r['author_id'],
										  "author_avatar" => $r['author_avatar'],
										  "total_mentions" => intval($r['total_mentions']),
										  "impression" => $r['total_impression'],
										  "total_imp" => $r['total_impression'],
										  "rt_impression" => $r['total_rt_impression'],
										  "rt_mention" => intval($r['total_rt_mentions']),
										  "share" => $share,
										  "pii_total"=>$pii['pii_total'],
										  "pii" => round($pii['pii_total']/$r['total_mentions'],2),
										  "author_name" => $r['author_name'],
										  "overall_imp"=>$total_impression
								);
			}
			unset($rs);
			unset($pii);
			return $data;
	}
	function fb($campaign_id,$exclude){
		$data = array("top_post"=>$this->fb_top_post($campaign_id,$exclude),
					  "top_like"=>$this->fb_top_like($campaign_id, $exclude)
					  );
		return $data;
	}
	function fb_top_post($campaign_id,$exclude){
		//--> top KOL
		if($exclude==1){
				$sql= "SELECT 
					dfps.author_id 
					,dfps.author_name
					,SUM(dfps.mentions) AS mentions 
					,SUM(dfps.mentions)/(SELECT SUM(mentions) FROM smac_fb.daily_fb_people_stat WHERE campaign_id={$campaign_id}) 
										AS mentions_percentage
				FROM
					smac_fb.daily_fb_people_stat dfps
				WHERE 
					dfps.campaign_id={$campaign_id} AND dfps.author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=1)
				GROUP BY
					dfps.author_id
				ORDER BY 
					SUM(dfps.mentions) DESC
					LIMIT 10
				;";
			
		}else if($exclude==2){
			$sql= "SELECT 
					dfps.author_id 
					,dfps.author_name
					,SUM(dfps.mentions) AS mentions 
					,SUM(dfps.mentions)/(SELECT SUM(mentions) FROM smac_fb.daily_fb_people_stat WHERE campaign_id={$campaign_id}) 
										AS mentions_percentage
				FROM
					smac_fb.daily_fb_people_stat dfps
				WHERE 
					dfps.campaign_id={$campaign_id} AND dfps.author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=2)
				GROUP BY
					dfps.author_id
				ORDER BY 
					SUM(dfps.mentions) DESC
					LIMIT 10
				;";
		}else{
			
			$sql= "SELECT 
				dfps.author_id 
				,dfps.author_name
				,SUM(dfps.mentions) AS mentions 
				,SUM(dfps.mentions)/(SELECT SUM(mentions) FROM smac_fb.daily_fb_people_stat WHERE campaign_id={$campaign_id}) 
									AS mentions_percentage
				FROM
					smac_fb.daily_fb_people_stat dfps
				WHERE 
					dfps.campaign_id={$campaign_id} 
				GROUP BY
					dfps.author_id
				ORDER BY 
					SUM(dfps.mentions) DESC
					LIMIT 10
				;";
		}

		$rs = $this->fetch($sql,1);
		$kol = array();
		$this->fb_kols = ""; //list of kols
		for($n=0;$n<sizeof($rs);$n++){
			$r = $rs[$n];
			if($n>0){
				$this->fb_kols.=",";
			}
			$this->fb_kols.="'{$r['author_id']}'";
			$kol[] = array("id"=>$r['author_id'],
						   "name"=>$r['author_name'],
						   "total"=>$r['total'],
						   "share"=>round($r['mentions_percentage'] * 100,2),
						   "img"=>"https://graph.facebook.com/{$r['author_id']}/picture");
		}
		unset($rs);
		return $kol;	
	}
	function fb_top_like($campaign_id,$exclude){
		//--> top KOL
		if($exclude==1){
				$sql = "SELECT
					        dfps.author_id
					        ,dfps.author_name
					        ,SUM(dfps.likes) AS occurance
					FROM
					        smac_fb.daily_fb_people_stat dfps
					WHERE
					        dfps.campaign_id={$campaign_id}
					        AND dfps.author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=1) 
					GROUP BY
					        dfps.author_id
					ORDER BY occurance  DESC
					LIMIT 10
					;";
			
		}else if($exclude==2){
			$sql = "SELECT
					        dfps.author_id
					        ,dfps.author_name
					        ,SUM(dfps.likes) AS occurance
					FROM
					        smac_fb.daily_fb_people_stat dfps
					WHERE
					        dfps.campaign_id={$campaign_id}
					        AND dfps.author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=2) 
					GROUP BY
					        dfps.author_id
					ORDER BY occurance  DESC
					LIMIT 10
					;";
			
		}else{		
			$sql = "SELECT
					        dfps.author_id
					        ,dfps.author_name
					        ,SUM(dfps.likes) AS occurance
					FROM
					        smac_fb.daily_fb_people_stat dfps
					WHERE
					        dfps.campaign_id={$campaign_id}
					GROUP BY
					        dfps.author_id
					ORDER BY occurance  DESC
					LIMIT 10
					;";
		}

		$rs = $this->fetch($sql,1);
		$kol = array();
		$this->fb_kols = ""; //list of kols
		for($n=0;$n<sizeof($rs);$n++){
			$r = $rs[$n];
			if($n>0){
				$this->fb_kols.=",";
			}
			$this->fb_kols.="'{$r['author_id']}'";
			$kol[] = array("id"=>$r['author_id'],
						   "name"=>$r['author_name'],
						   "total"=>$r['occurance'],
						   "img"=>"https://graph.facebook.com/{$r['author_id']}/picture");
		}
		unset($rs);
		return $kol;	
	}
	function fb_top_influencers($campaign_id,$exclude){
		
	}
	function fb_all_people($campaign_id,$req){
			$aColumns = array( 'author_id', 'author_id','author_name', 'mentions','likes');
			$sql = "SELECT COUNT(author_id) AS total FROM (SELECT author_id
					FROM smac_fb.daily_fb_people_stat WHERE campaign_id={$campaign_id} 
					GROUP BY author_id) a LIMIT 1";
			$row = $this->fetch($sql);
			$total = intval($row['total']);
			
			//LIMIT
			$start = 0;
			$limit = 10;
			$req = $this->request;
			if ($req->getParam('iDisplayLength') != '-1' )
			{
				$start = intval($req->getParam('iDisplayStart'));
				$limit = intval($req->getParam('iDisplayLength'));
			}
			
			if($limit==0){
				$limit=10;
			}
			/*
			 * Ordering
			 */
			$sOrder = "";
			if ( $req->getParam('iSortCol_0')!=null )
			{
				$sOrder = "ORDER BY  ";
				for ( $i=0 ; $i<intval( $req->getParam('iSortingCols') ) ; $i++ )
				{
					if ( $req->getParam('bSortable_'.intval($req->getParam('iSortCol_'.$i)) ) == "true" )
					{
						//echo "Masuk<br />";
						$sOrder .= $aColumns[ intval( $req->getParam('iSortCol_'.$i) ) ]." ". $req->getParam('sSortDir_'.$i) .", ";
					}
				}
				
				$sOrder = substr_replace( $sOrder, "", -2 );
				if ( $sOrder == "ORDER BY" )
				{
					$sOrder = "";
				}
			}
			/**
			 * conditions
			 */
			$sWhere = "";
			if ($req->getParam('sSearch') != "" )
			{
				$sWhere = "WHERE (";
				for ( $i=0 ; $i<count($aColumns) ; $i++ )
				{
					$sWhere .= $aColumns[$i]." LIKE '%". $req->getParam('sSearch') ."%' OR ";
				}
				$sWhere = substr_replace( $sWhere, "", -3 );
				$sWhere .= ')';
			}
			//wrapping up
			$dat = $this->_getAllFBAuthors($campaign_id,$start,$limit,$sOrder,$sWhere,$exclude);
			
			$data = array(
				"sEcho" => intval($req->getParam('sEcho')),
				"iTotalRecords" => $total,
				"iTotalDisplayRecords" => $total,
				"aaData" => array()
			);
			
			
			
			foreach($dat as $k){
				$idx = $k['id'];
				
				$data['aaData'][] = array( "<div class=\"smallthumb\"><a href=\"http://www.facebook.com/{$k['author_id']}\" target=\"_blank\" class=\"poplight\" rel=\"profile\"><img src='".htmlspecialchars($k['author_avatar'])."' /></a></div>",
												$k['author_id'],
												$k['author_name'],
												"<span style=\"float:right\">".number_format($k['mentions'])."</span>",
												"<span style=\"float:right\">".number_format($k['likes'])."</span>"
												);
				
			}
		return $data;
	}
	
	private function _getAllFBAuthors($campaign_id,$start,$total,$order,$where,$exclude){
			
			
			$order = mysql_escape_string($order);
			if($order == ''){
				$order = "";
			}
			
			$where = $where;
			
			if($where == ''){
				$where = " WHERE campaign_id=".$campaign_id." ";
			}else{
				$where .= " AND campaign_id=".$campaign_id." ";
			}
			

			$qry = "SELECT author_id,author_name,SUM(likes) AS total_likes,SUM(mentions) AS total_mentions,0 as share 
					FROM smac_fb.daily_fb_people_stat 
					{$where} 
					GROUP BY author_id
					{$order} 
					LIMIT ".$start.",".$total;
			
			$rs = $this->fetch($qry,1);		
			$data = array();
			for($i=0;$i<sizeof($rs);$i++){
				$r = $rs[$i];
				$author_id = $r['author_id'];
				$data[] = array("id" => intval($r['author_id']),
										  "author_id" => $r['author_id'],
										  "author_avatar" => "https://graph.facebook.com/{$r['author_id']}/picture",
										  "mentions" => intval($r['total_mentions']),
										  "likes" => intval($r['total_likes']),
										  "author_name" => $r['author_name']
								);
			}
			unset($rs);
			return $data;
	}
	function video($campaign_id,$exclude){
		$data = array("top_post"=>$this->video_top_post($campaign_id,$exclude),
					  "top_comments"=>array(),
					  "top_influences"=>array()
					  );
		return $data;
	}
	function video_top_post($campaign_id,$exclude){
		$sql = "SELECT author_id, sum(view_count)  as occurance,author_uri
				FROM smac_youtube.campaign_youtube
				WHERE campaign_id = {$campaign_id}
				GROUP BY author_id
				ORDER BY occurance DESC
				LIMIT 10;";
		$rs = $this->fetch($sql,1);
		$kol = array();
		$this->web_kols = ""; //list of kols
		for($n=0;$n<sizeof($rs);$n++){
			$r = $rs[$n];
			if($n>0){
				$this->web_kols.=",";
			}
			$this->web_kols.="'{$r['author_id']}'";
			$kol[] = array("id"=>$r['author_id'],
						   "name"=>$r['author_id'],
						   "link"=>$r['author_uri'],
						   "total"=>rand(10,100));
		}
		unset($rs);
		return $kol;	
	}
	function web($campaign_id,$exclude){
		$data = array("top_post"=>$this->web_top_post($campaign_id,$exclude),
					  "top_comments"=>array(),
					  "top_influences"=>array()
					  );
		return $data;
	}
	function web_top_post($campaign_id,$exclude){
		//--> top KOL
		if($exclude==1){
				$sql = "SELECT author_name as author_id, COUNT(*) AS occurance
					FROM smac_report.campaign_web_feeds
					WHERE campaign_id = {$campaign_id}
					AND author_name NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=1)
					GROUP BY author_name
					ORDER BY occurance DESC
					LIMIT 10;";
			
			
		}else if($exclude==2){
	
			$sql = "SELECT author_name as author_id, COUNT(*) AS occurance
					FROM smac_report.campaign_web_feeds
					WHERE campaign_id = {$campaign_id}
					AND author_name NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=2)
					GROUP BY author_name
					ORDER BY occurance DESC
					LIMIT 10;";
			
		}else{
			
			$sql = "SELECT author_name as author_id, COUNT(*) AS occurance
					FROM smac_report.campaign_web_feeds
					WHERE campaign_id = {$campaign_id}
					GROUP BY author_name
					ORDER BY occurance DESC
					LIMIT 10;";
		}
		$rs = $this->fetch($sql,1);
		$kol = array();
		$this->web_kols = ""; //list of kols
		for($n=0;$n<sizeof($rs);$n++){
			$r = $rs[$n];
			if($n>0){
				$this->web_kols.=",";
			}
			$this->web_kols.="'{$r['author_id']}'";
			$kol[] = array("id"=>$r['author_id'],
						   "name"=>$r['author_id'],
						   "total"=>intval($r['occurance']));
		}
		unset($rs);
		return $kol;	
	}
	function web_all_sites($campaign_id,$req){
			$aColumns = array( 'author_id', 'author_id','author_name', 'occurance');
			
			$sql = "SELECT COUNT(*) as total FROM smac_report.top_authors WHERE campaign_id=".$campaign_id." AND channel=3 LIMIT 1";
			$sql = "SELECT COUNT(*) as total
					FROM smac_report.campaign_web_feeds
					WHERE campaign_id = {$campaign_id}";
			$row = $this->fetch($sql);
			$total = intval($row['total']);
			$req = $this->request;
			//LIMIT
			$start = 0;
			$limit = 10;
			if ( $req->getParam('iDisplayLength') != '-1' )
			{
				$start = intval($req->getParam('iDisplayStart'));
				$limit = intval($req->getParam('iDisplayLength'));
			}
			if($limit==0){
				$limit = 10;
			}
			
			/*
			 * Ordering
			 */
			$sOrder = "";
			if ( $req->getParam('iSortCol_0')!=null )
			{
				$sOrder = "ORDER BY  ";
				for ( $i=0 ; $i<intval( $req->getParam('iSortingCols') ) ; $i++ )
				{
					if ( $req->getParam('bSortable_'.intval($req->getParam('iSortCol_'.$i)) ) == "true" )
					{
						//echo "Masuk<br />";
						$sOrder .= $aColumns[ intval( $req->getParam('iSortCol_'.$i) ) ]." ". $req->getParam('sSortDir_'.$i) .", ";
					}
				}
				
				$sOrder = substr_replace( $sOrder, "", -2 );
				if ( $sOrder == "ORDER BY" )
				{
					$sOrder = "";
				}
			}
			/**
			 * conditions
			 */
			$sWhere = "";
			if ($req->getParam('sSearch') != "" )
			{
				$sWhere = "WHERE (";
				for ( $i=0 ; $i<count($aColumns) ; $i++ )
				{
					$sWhere .= $aColumns[$i]." LIKE '%". $req->getParam('sSearch') ."%' OR ";
				}
				$sWhere = substr_replace( $sWhere, "", -3 );
				$sWhere .= ')';
			}
			//wrapping up
			$dat = $this->_getAllWebAuthors($campaign_id,$start,$limit,$sOrder,$sWhere,$exclude);
			
			$data = array(
				"sEcho" => intval($req->getParam('sEcho')),
				"iTotalRecords" => $total,
				"iTotalDisplayRecords" => $total,
				"aaData" => array()
			);
			
			
			
			foreach($dat as $k){
				$idx = $k['id'];
				
				$data['aaData'][] = array( "<div class=\"smallthumb\"><a href=\"http://{$k['author_id']}\" target=\"_blank\" class=\"poplight\" rel=\"profile\"><img src='".htmlspecialchars($k['author_avatar'])."' /></a></div>",
												$k['author_id'],
												$k['author_name'],
												"<span style=\"float:right\">".number_format($k['occurance'])."</span>"
												);
				
			}
		

		return $data;
	}
	
	private function _getAllWebAuthors($campaign_id,$start,$total,$order,$where,$exclude){
			$order = mysql_escape_string($order);
			if($order == ''){
				$order = "";
			}
			
			$where = $where;
			
			if($where == ''){
				$where = " WHERE campaign_id=".$campaign_id." ";
			}else{
				$where .= " AND campaign_id=".$campaign_id."";
			}
			
			$qry = "select *,0 as share 
			from smac_report.top_authors
			{$where}
			{$order} 
			LIMIT ".$start.",".$total;
			
			$qry = "SELECT author_name as author_id, COUNT(*) AS occurance
					FROM smac_report.campaign_web_feeds
					{$where}
					GROUP BY author_name
					{$order}
					LIMIT ".$start.",".$total;
					
			$rs = $this->fetch($qry,1);
			$data = array();
			for($i=0;$i<sizeof($rs);$i++){
				$r = $rs[$i];
				$author_id = $r['author_id'];
				$data[] = array("id" => intval($r['id']),
										  "author_id" => $r['author_id'],
										  "author_avatar" => "images/iconWeb2.png",
										  "occurance" => intval($r['occurance']),
										  "author_name" => $r['author_id']
								);
			}
			unset($rs);
			return $data;
	}
	function twitter_ambas($campaign_id,$exclude=0,$start=0,$total=10){
		$start = intval($start);
		//ambassador
		if($exclude==1){
			$sql = "SELECT author_id as author,author_avatar as pic,sentiment as total,pii_score 
				FROM smac_supporter.campaign_ambas_{$campaign_id}
				WHERE author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=1)
				ORDER BY sentiment DESC LIMIT {$start},{$total}";
				
			$sql2 = "SELECT COUNT(*) as total
				FROM smac_supporter.campaign_ambas_{$campaign_id}
				WHERE author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=1)";
			
			
		}else if($exclude==2){
			$sql = "SELECT author_id as author,author_avatar as pic,sentiment as total,pii_score 
				FROM smac_supporter.campaign_ambas_{$campaign_id}
				WHERE author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=2)
				ORDER BY sentiment DESC LIMIT {$start},{$total}";
				
			$sql2 = "SELECT COUNT(*) as total
				FROM smac_supporter.campaign_ambas_{$campaign_id}
				WHERE author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=2)";
		
		}else{
			$sql = "SELECT author_id as author,author_avatar as pic,sentiment as total,pii_score 
					FROM smac_supporter.campaign_ambas_{$campaign_id}
					ORDER BY sentiment DESC LIMIT {$start},{$total}";
					
			$sql2 = "SELECT COUNT(*) as total
				FROM smac_supporter.campaign_ambas_{$campaign_id}";
		}
		
		$positive = $this->fetch($sql,1);
		$row = $this->fetch($sql2);
		$ambas = array();
		for($i=0;$i<sizeof($positive);$i++){
			$sql = "SELECT SUM(total_mentions) AS mentions,SUM(imp) AS impression,SUM(rt_imp) AS rt_imp,SUM(rt_mention) AS rt
							FROM smac_report.campaign_author_daily_stats WHERE campaign_id={$campaign_id} 
							AND author_id='{$positive[$i]['author']}' GROUP BY campaign_id;";
				$stat = $this->fetch($sql);
				$ambas[] = array("img"=>$positive[$i]['pic'],
								  "name"=>$positive[$i]['author'],
								  "total"=>$positive[$i]['total'],
								  "stats"=>$stat);
		}
		unset($positive);
		return array("rows"=>$ambas,"total"=>$row['total']);
	}
	function twitter_troll($campaign_id,$exclude=0,$start=0,$total=10){
		$start = intval($start);
		//ambassador
		if($exclude==1){
			$sql = "SELECT author_id as author,author_avatar as pic,sentiment as total,pii_score 
				FROM smac_supporter.campaign_trolls_{$campaign_id}
				WHERE author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=1)
				ORDER BY sentiment DESC LIMIT {$start},{$total}";
				
			$sql2 = "SELECT COUNT(*) as total
				FROM smac_supporter.campaign_trolls_{$campaign_id}
				WHERE author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=1)";
			
			
		}else if($exclude==2){
			$sql = "SELECT author_id as author,author_avatar as pic,sentiment as total,pii_score 
				FROM smac_supporter.campaign_trolls_{$campaign_id}
				WHERE author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=2)
				ORDER BY sentiment DESC LIMIT {$start},{$total}";
				
			$sql2 = "SELECT COUNT(*) as total
				FROM smac_supporter.campaign_trolls_{$campaign_id}
				WHERE author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=2)";
		
		}else{
			$sql = "SELECT author_id as author,author_avatar as pic,sentiment as total,pii_score 
					FROM smac_supporter.campaign_trolls_{$campaign_id}
					ORDER BY sentiment DESC LIMIT {$start},{$total}";
					
			$sql2 = "SELECT COUNT(*) as total
				FROM smac_supporter.campaign_trolls_{$campaign_id}";
		}
		
		$negative = $this->fetch($sql,1);
		$row = $this->fetch($sql2);
		$trolls = array();
		for($i=0;$i<sizeof($negative);$i++){
			$sql = "SELECT SUM(total_mentions) AS mentions,SUM(imp) AS impression,SUM(rt_imp) AS rt_imp,SUM(rt_mention) AS rt
							FROM smac_report.campaign_author_daily_stats WHERE campaign_id={$campaign_id} 
							AND author_id='{$negative[$i]['author']}' GROUP BY campaign_id;";
				$stat = $this->fetch($sql);
				$trolls[] = array("img"=>$negative[$i]['pic'],
								  "name"=>$negative[$i]['author'],
								  "total"=>$negative[$i]['total'],
								  "stats"=>$stat);
		}
		unset($negative);
		return array("rows"=>$trolls,"total"=>$row['total']);
	}
	function fb_ambas($campaign_id,$exclude=0,$start=0,$total=10){
		$start = intval($start);
		//ambassador
		if($exclude==1){
			$sql = "SELECT author_id, author_name, sentiment, pii_score 
					FROM smac_fb.fb_campaign_ambas
					WHERE campaign_id = {$campaign_id} AND
					author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=1)
					ORDER BY sentiment DESC LIMIT {$start},{$total}";	
			
			$sql2 = "SELECT COUNT(*) as total
					FROM smac_fb.fb_campaign_ambas
					WHERE campaign_id = {$campaign_id} AND 
					author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=1)";
					
			
			
			
		}else if($exclude==2){
			$sql = "SELECT author_id, author_name, sentiment, pii_score 
					FROM smac_fb.fb_campaign_ambas
					WHERE campaign_id = {$campaign_id} AND
					author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=2)
					ORDER BY sentiment DESC LIMIT {$start},{$total}";	
			
			$sql2 = "SELECT COUNT(*) as total
					FROM smac_fb.fb_campaign_ambas
					WHERE campaign_id = {$campaign_id} AND 
					author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=2)";
		
		}else{
			$sql = "SELECT author_id, author_name, sentiment, pii_score 
					FROM smac_fb.fb_campaign_ambas
					WHERE campaign_id = {$campaign_id}
					ORDER BY sentiment DESC LIMIT {$start},{$total}";	
			
			$sql2 = "SELECT COUNT(*) as total
					FROM smac_fb.fb_campaign_ambas
					WHERE campaign_id = {$campaign_id}";
			
		}
		
		$positive = $this->fetch($sql,1);
		$row = $this->fetch($sql2);
		$ambas = array();
		for($i=0;$i<sizeof($positive);$i++){
				$ambas[] = array("img"=>"https://graph.facebook.com/{$positive[$i]['author_id']}/picture",
								  "name"=>$positive[$i]['author_name'],
								  "total"=>$positive[$i]['sentiment']);
		}
		unset($positive);
		return array("rows"=>$ambas,"total"=>$row['total']);
	}
	
	function fb_trolls($campaign_id,$exclude=0,$start=0,$total=10){
		$start = intval($start);
		//ambassador
		if($exclude==1){
			$sql = "SELECT author_id, author_name, sentiment, pii_score 
					FROM smac_fb.fb_campaign_trolls
					WHERE campaign_id = {$campaign_id} AND
					author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=1)
					ORDER BY sentiment DESC LIMIT {$start},{$total}";	
			
			$sql2 = "SELECT COUNT(*) as total
					FROM smac_fb.fb_campaign_trolls
					WHERE campaign_id = {$campaign_id} AND 
					author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=1)";
					
			
			
			
		}else if($exclude==2){
			$sql = "SELECT author_id, author_name, sentiment, pii_score 
					FROM smac_fb.fb_campaign_trolls
					WHERE campaign_id = {$campaign_id} AND
					author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=2)
					ORDER BY sentiment DESC LIMIT {$start},{$total}";	
			
			$sql2 = "SELECT COUNT(*) as total
					FROM smac_fb.fb_campaign_trolls
					WHERE campaign_id = {$campaign_id} AND 
					author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=2)";
		
		}else{
			$sql = "SELECT author_id, author_name, sentiment, pii_score 
					FROM smac_fb.fb_campaign_trolls
					WHERE campaign_id = {$campaign_id}
					ORDER BY sentiment DESC LIMIT {$start},{$total}";	
			
			$sql2 = "SELECT COUNT(*) as total
					FROM smac_fb.fb_campaign_trolls
					WHERE campaign_id = {$campaign_id}";
			
		}
		
		$negative = $this->fetch($sql,1);
		$row = $this->fetch($sql2);
		$trolls = array();
		for($i=0;$i<sizeof($negative);$i++){
				$trolls[] = array("img"=>"https://graph.facebook.com/".$negative[$i]['author_id']."/picture",
								  "name"=>$negative[$i]['author_name'],
								  "total"=>$negative[$i]['sentiment']);
		}
		unset($negative);
		return array("rows"=>$trolls,"total"=>$row['total']);
	}
	function web_ambas($campaign_id,$exclude=0,$start=0,$total=10){
		$start = intval($start);
		//ambassador
		if($exclude==1){
			$sql = "SELECT author_id, sentiment, pii_score
					 FROM smac_gcs.gcs_campaign_ambas
					WHERE campaign_id = {$campaign_id} AND
					author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=1)
					ORDER BY sentiment DESC LIMIT {$start},{$total}";	
			
			$sql2 = "SELECT COUNT(*) as total
					FROM smac_gcs.gcs_campaign_ambas
					WHERE campaign_id = {$campaign_id} AND
					author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=1)";
					
			
			
			
		}else if($exclude==2){
			$sql = "SELECT author_id, sentiment, pii_score
					 FROM smac_gcs.gcs_campaign_ambas
					WHERE campaign_id = {$campaign_id} AND
					author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=2)
					ORDER BY sentiment DESC LIMIT {$start},{$total}";	
			
			$sql2 = "SELECT COUNT(*) as total
					FROM smac_gcs.gcs_campaign_ambas
					WHERE campaign_id = {$campaign_id} AND
					author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=2)";
					
			
		
		}else{
			$sql = "SELECT author_id, sentiment, pii_score
					 FROM smac_gcs.gcs_campaign_ambas
					WHERE campaign_id = {$campaign_id}
					ORDER BY sentiment DESC LIMIT {$start},{$total}";	
			
			$sql2 = "SELECT COUNT(*) as total
					FROM smac_gcs.gcs_campaign_ambas
					WHERE campaign_id = {$campaign_id}";
			
		}
		
		$positive = $this->fetch($sql,1);
		$row = $this->fetch($sql2);
		$ambas = array();
		for($i=0;$i<sizeof($positive);$i++){
				$ambas[] = array("img"=>$positive[$i]['pic'],
								  "name"=>$positive[$i]['author_id'],
								  "total"=>$positive[$i]['sentiment']);
		}
		unset($positive);
		return array("rows"=>$ambas,"total"=>$row['total']);
	}
	function web_trolls($campaign_id,$exclude=0,$start=0,$total=10){
		$start = intval($start);
		//ambassador
		if($exclude==1){
			$sql = "SELECT author_id, sentiment, pii_score
					 FROM smac_gcs.gcs_campaign_trolls
					WHERE campaign_id = {$campaign_id} AND
					author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=1)
					ORDER BY sentiment DESC LIMIT {$start},{$total}";	
			
			$sql2 = "SELECT COUNT(*) as total
					FROM smac_gcs.gcs_campaign_trolls
					WHERE campaign_id = {$campaign_id} AND
					author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=1)";
					
			
			
			
		}else if($exclude==2){
			$sql = "SELECT author_id, sentiment, pii_score
					 FROM smac_gcs.gcs_campaign_trolls
					WHERE campaign_id = {$campaign_id} AND
					author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=2)
					ORDER BY sentiment DESC LIMIT {$start},{$total}";	
			
			$sql2 = "SELECT COUNT(*) as total
					FROM smac_gcs.gcs_campaign_trolls
					WHERE campaign_id = {$campaign_id} AND
					author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=2)";
					
			
		
		}else{
			$sql = "SELECT author_id, sentiment, pii_score
					 FROM smac_gcs.gcs_campaign_trolls
					WHERE campaign_id = {$campaign_id}
					ORDER BY sentiment DESC LIMIT {$start},{$total}";	
			
			$sql2 = "SELECT COUNT(*) as total
					FROM smac_gcs.gcs_campaign_trolls
					WHERE campaign_id = {$campaign_id}";
			
		}
		
		$negative = $this->fetch($sql,1);
		$row = $this->fetch($sql2);
		$trolls = array();
		for($i=0;$i<sizeof($negative);$i++){
				$trolls[] = array("img"=>$negative[$i]['pic'],
								  "name"=>$negative[$i]['author_id'],
								  "total"=>$negative[$i]['sentiment']);
		}
		unset($negative);
		return array("rows"=>$trolls,"total"=>$row['total']);
	}
	function video_ambas($campaign_id,$exclude=0,$start=0,$total=10){
		$start = intval($start);
		
		
		
		$sql = "SELECT author_id, author_name,sentiment,pii_score
				FROM smac_youtube.youtube_comment_ambas
				WHERE campaign_id = {$campaign_id}
				ORDER BY sentiment DESC
				LIMIT {$start},{$total}";
		
		$sql2 = "SELECT COUNT(*) as total
				FROM smac_youtube.youtube_comment_ambas
				WHERE campaign_id = {$campaign_id}";

		$positive = $this->fetch($sql,1);
		$row = $this->fetch($sql2);
		$ambas = array();
		for($i=0;$i<sizeof($positive);$i++){
				$ambas[] = array("img"=>$positive[$i]['pic'],
								  "name"=>$positive[$i]['author_id'],
								  "total"=>$positive[$i]['sentiment']);
		}
		unset($positive);
		return array("rows"=>$ambas,"total"=>$row['total']);
	}
	function video_trolls($campaign_id,$exclude=0,$start=0,$total=10){
		$start = intval($start);
		//trolls
		
		$sql = "SELECT author_id, author_name,sentiment,pii_score
				FROM smac_youtube.youtube_comment_trolls
				WHERE campaign_id = {$campaign_id}
				ORDER BY sentiment DESC
				LIMIT {$start},{$total}";
		
		$sql2 = "SELECT COUNT(*) as total
				FROM smac_youtube.youtube_comment_trolls
				WHERE campaign_id = {$campaign_id}";

		$negative = $this->fetch($sql,1);
		$row = $this->fetch($sql2);
		$trolls = array();
		for($i=0;$i<sizeof($negative);$i++){
				$trolls[] = array("img"=>$negative[$i]['pic'],
								  "name"=>$negative[$i]['author_id'],
								  "total"=>$negative[$i]['sentiment']);
		}
		unset($negative);
		return array("rows"=>$trolls,"total"=>$row['total']);
	}
	function twitter_profile($campaign_id,$person){
		$result = array();
		$person = mysql_escape_string(cleanXSS($person));
		$c = array("subdomain"=>$this->request->getParam('subdomain'),
					'page' => 'workflow',
					'act'=>'exclude_person',
					'campaign_id'=>$campaign_id,
					'author_id'=>$person,
					'ajax'=>1);
		$remove_link = $this->request->encrypt_params($c);
		if(strlen($person)>0){
			$stats = $this->twitter_profile_summary($campaign_id,$person);
			return array("summary"=>$this->twitter_profile_summary($campaign_id, $person),
						 "wordcloud"=>$this->twitter_profile_wordcloud($campaign_id,$person),
						 "statistics"=>$this->twitter_profile_daily($campaign_id,$person),
						 "sentiment"=>$this->twitter_profile_sentiment($campaign_id,$person),
						 "remove_link"=>$remove_link);
		}
	}
	function twitter_profile_sentiment($campaign_id,$person){
		//positive sentiment
		$sql = "SELECT published_date,SUM(sentiment) as total,
				SUM(freq) as freq,SUM(pii) as pii 
				FROM smac_feeds.campaign_feeds_{$campaign_id} a
				INNER JOIN smac_sentiment.campaign_feed_sentiment_{$campaign_id} b
				ON a.id = b.feed_id
				WHERE a.author_id='{$person}' 
				AND b.sentiment > 0 
				GROUP BY a.author_id,a.published_date;";
		$p_sentiment = $this->fetch($sql,1);
		
		if(sizeof($p_sentiment)>0){
			foreach($p_sentiment as $n=>$v){
				$p_sentiment[$n]['ts'] = strtotime($v['published_date']);
			}
		}
		$sql = "SELECT published_date,SUM(sentiment) as total,
				SUM(freq) as freq,SUM(pii) as pii 
				FROM smac_feeds.campaign_feeds_{$campaign_id} a
				INNER JOIN smac_sentiment.campaign_feed_sentiment_{$campaign_id} b
				ON a.id = b.feed_id
				WHERE a.author_id='{$person}' 
				AND b.sentiment < 0 
				GROUP BY a.author_id,a.published_date;";
		$n_sentiment = $this->fetch($sql,1);
		if(sizeof($n_sentiment)>0){
			foreach($n_sentiment as $n=>$v){
				$n_sentiment[$n]['ts'] = strtotime($v['published_date']);
			}
		}
		//equalize the results
		$ts = array();
		for($i=0;$i<sizeof($p_sentiment);$i++){
			$ts[$p_sentiment[$i]['ts']] = 1;
		}
		for($i=0;$i<sizeof($n_sentiment);$i++){
			$ts[$n_sentiment[$i]['ts']] = 1;
		}
		if(sizeof($ts)>0){
			foreach($ts as $t=>$v){
				$found = false;
				for($i=0;$i<sizeof($p_sentiment);$i++){
					if($p_sentiment[$i]['ts']==$t){
						$found=true;
						break;
					}
				}
				if(!$found){
					$p_sentiment[] = array("ts"=>$t,"published_date"=>date("Y-m-d",$t),"total"=>0,"freq"=>0,"pii"=>0);
				}
				
				$found = false;
				for($i=0;$i<sizeof($n_sentiment);$i++){
					if($n_sentiment[$i]['ts']==$t){
						$found=true;
						break;
					}
				}
				if(!$found){
					$n_sentiment[] = array("ts"=>$t,"published_date"=>date("Y-m-d",$t),"total"=>0,"freq"=>0,"pii"=>0);
				}
			}	
		}
		return array("positive"=>$p_sentiment,"negative"=>$n_sentiment);
	}
	function twitter_profile_daily($campaign_id,$person){
		$sql = "SELECT dtpost as published_date,
				SUM(total_mentions) as mentions,SUM(imp) as impression,SUM(rt_imp) as rt_impression,SUM(rt_mention) as rt 
				FROM 
				smac_report.campaign_author_daily_stats 
				WHERE campaign_id={$campaign_id} 
				AND author_id='{$person}' GROUP BY dtpost LIMIT 1000";
		$stats = $this->fetch($sql,1);
		for($i=0;$i<sizeof($stats);$i++){
			$viral_effect = round($stats[$i]['rt_impression']/($stats[$i]['impression']+$stats[$i]['rt_impression']),2);
			$stats[$i]['viral'] = $viral_effect;
		}
		return $stats;
	}
	function twitter_profile_wordcloud($campaign_id,$person){
		$sql = "SELECT a.keyword,keyword_total as total,b.weight as sentiment 
				FROM smac_word.campaign_people_wordcloud_{$campaign_id} a
				LEFT JOIN smac_sentiment.sentiment_setup_{$campaign_id} b
				ON a.keyword = b.keyword
				WHERE a.author_id='{$person}' ORDER BY a.keyword_total 
				DESC LIMIT 100;";
		$rs = $this->fetch($sql,1);
		for($i=0;$i<sizeof($rs);$i++){
			$rs[$i]['sentiment'] = intval($rs[$i]['sentiment']);
		}
		return $rs;
	}
	function twitter_profile_summary($campaign_id,$person){
		//total impressions	
			$sql = "SELECT SUM(total_impression_twitter+total_rt_impression_twitter) AS true_reach
			FROM smac_report.campaign_rule_volume_history 
			WHERE campaign_id={$campaign_id};";
			$campaign = $this->fetch($sql);
			$total_impression = $campaign['true_reach'];
			unset($campaign);
			//getting user stats 
			$sql  ="SELECT * FROM smac_author.author_summary_{$campaign_id} WHERE author_id='{$person}'";
			$stats = $this->fetch($sql);
			
			//use these only for getting user's rank (temporary solution)
			$sql = 
				"SELECT b.rank 
				 FROM smac_report.campaign_people_summary a
				 LEFT JOIN smac_report.campaign_people_rank b
				 ON a.id = b.ref_id AND b.campaign_id = {$campaign_id}
				 WHERE a.campaign_id=".$campaign_id."
				 AND author_id='".$person."' LIMIT 1";
			
			//$rank = $this->fetch($sql);
			//$stats['rank'] = $rank['rank'];
			unset($rank);
			$stats['followers'] = round($stats['total_impression'] / $stats['total_mentions']);
			$stats['total_impression'] = ($stats['total_impression']+$stats['total_rt_impression']);
			$stats['share_percentage'] = round(($stats['total_impression'] / $total_impression) * 100,2);
			$stats['rt_percentage'] = round($stats['total_rt_mentions'] / ($stats['total_rt_mentions']+$stats['total_mentions']) * 100,2);
			$stats['potential_impact'] = 0;
			//we need to grab the info about user
			$response = curl_get("https://api.twitter.com/1/users/show.json?screen_name=".$stats['author_id']);
			$profile_obj = json_decode($response);
			$author_timezone = $profile_obj->time_zone;
			$author_location = $profile_obj->location;
			$author_about = $profile_obj->description;
			$arr_raw = explode(":",$author_location);
			$arr_loc = @explode(",",$arr_raw[1]);
			if(is_array($arr_loc)){
				$coordinate_x = @trim($arr_loc[0]);
				$coordinate_y = @trim($arr_loc[1]);
			}
			//geo
			$sql = "SELECT location,coordinate_x,coordinate_y 
							FROM smac_feeds.campaign_feeds_{$campaign_id}
							WHERE campaign_id={$campaign_id} 
							AND author_id='{$person}' LIMIT 1";
							
			$hist_profile = $this->fetch($sql);
			$stats['about'] = $author_about;
			$stats['timezone'] = $author_timezone;
			if(floatval($hist_profile['coordinate_x'])>0||floatval($hist_profile['coordinate_y'])>0){
				//check from our database first
				
				//not found, so we use google.
				$uri = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$hist_profile['coordinate_x'].",".$hist_profile['coordinate_y']."&sensor=false";
				$glmap_response = file_get_contents($uri);
				$map_obj = json_decode($glmap_response);
				
				if($map_obj->status=="OK"){
			
					$address = $map_obj->results[0]->formatted_address;
					$author_location = $address;
				}else{
					//try our geo database
					$sql = "SELECT country,iso FROM smac_data.geo_country 
						WHERE {$hist_profile['coordinate_x']} BETWEEN y1 AND y2 AND {$hist_profile['coordinate_y']}
						BETWEEN x1 AND x2 LIMIT 3;";
					$geo = $this->fetch($sql,1);
					if(sizeof($geo)==1){
						$author_location = $geo[0]['country'];
					}
				}
			}else if(floatval($coordinate_x)>0||floatval($coordinate_y)>0){
				$uri = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$coordinate_x.",".$coordinate_y."&sensor=false";
				
				$glmap_response = file_get_contents($uri);
				$map_obj = json_decode($glmap_response);
				
				if($map_obj->status=="OK"){
					$address = $map_obj->results[0]->formatted_address;
					$author_location = $address;
				}else{
					//try our geo database
					$sql = "SELECT country,iso FROM smac_data.geo_country 
						WHERE {$coordinate_x} BETWEEN y1 AND y2 AND {$coordinate_y}
						BETWEEN x1 AND x2 LIMIT 2;";
					$geo = $this->fetch($sql,1);
					if(sizeof($geo)==1){
						$author_location = $geo[0]['country'];
					}
				}
			}
			$stats['location'] = $author_location;
			return $stats;
	}
	
	function twitter_profile_feeds($campaign_id,$person,$start,$limit=10){
		$start = intval($start);
		$person = mysql_escape_string(cleanXSS($person));
		//get devices
		$sql = "SELECT * FROM smac_data.device_lookup";
		$devices = $this->fetch($sql,1);
		//total impressions
		$sql = "SELECT SUM(total_impression_twitter+total_rt_impression_twitter) AS true_reach
		FROM smac_report.campaign_rule_volume_history 
		WHERE campaign_id={$campaign_id};";
		$campaign = $this->fetch($sql);
		$total_impression = $campaign['true_reach'];
		$campaign=null;
		unset($campaign);
		
		$sql = "SELECT feed_id,local_rt_count as rt_total,
				local_rt_impresion as rt_imp,
				author_id,
				author_name,
				author_avatar AS avatar_pic, 
				published_date,
				followers AS imp,generator,content
			FROM smac_feeds.campaign_feeds_{$campaign_id} 
			WHERE author_id = '{$person}' AND is_active = 1
			ORDER BY followers desc
			LIMIT {$start},{$limit};";
		
		$sql2 = "SELECT SUM(total_mentions) as total 
				FROM smac_report.campaign_author_daily_stats 
				WHERE campaign_id={$campaign_id} AND author_id='{$person}';";
		
		$rs = $this->fetch($sql,1);
		$rows = $this->fetch($sql2);
		$posts = array();
		foreach($rs as $r){
			$share = round((($r['imp']+$r['rt_imp'])/$total_impression)*100,4);
			//check for flag
			$flag = $this->is_workflow_flag($campaign_id,$r['feed_id'],1);
			//-->end of check
			$posts[] = array("id"=>$r['feed_id'],
						   "published"=>$r['published_date'],
						   "author_id"=>$r['author_id'],
						   "name"=>htmlspecialchars($r['author_name']),
						   "txt"=>htmlspecialchars($r['content']),
						   "device"=>$this->get_device($r['generator'],$devices),
						   "profile_image_url"=>$r['avatar_pic'],
						   "impression"=>$r['imp'],
						   "rt"=>$r['rt_total'],
						   "rt_imp"=>$r['rt_imp'],
						   "share"=>$share,
						   "flag"=>$flag);		
		}
		return array("feeds"=>$posts,"total_rows"=>$rows['total']);
	}
	function fb_profile_feeds($campaign_id,$person,$start,$limit=10){
		$start = intval($start);
		$person = mysql_escape_string(cleanXSS($person));
		//get devices
		$sql = "SELECT * FROM smac_data.device_lookup";
		$devices = $this->fetch($sql,1);
		
		
		$sql = "SELECT id,feeds_facebook_id as feed_id,keyword_id as rule_id,from_object_id as author_id,
						from_object_name as author_name,'' as author_avatar,created_time_ts,message,description,application_object_name as generator,
						likes_count
			FROM smac_fb.campaign_fb
			WHERE campaign_id = {$campaign_id} AND from_object_id = '{$person}' AND is_active = 1
			LIMIT {$start},{$limit};";
		
		
		$sql2 = "SELECT SUM(mentions) AS total FROM smac_fb.daily_fb_people_stat 
				WHERE campaign_id={$campaign_id} AND author_id='{$person}'";
		$rs = $this->fetch($sql,1);
		$rows = $this->fetch($sql2);
		
		$posts = array();
		foreach($rs as $r){
			//check for flag
			$flag = $this->is_workflow_flag($campaign_id,$r['feed_id'],2);
			//-->end of check
			$posts[] = array("id"=>$r['feed_id'],
						   "published"=>date("d/m/Y H:i:s",$r['created_time_ts']),
						   "author_id"=>$r['author_id'],
						   "name"=>htmlspecialchars($r['author_name']),
						   "txt"=>htmlspecialchars($r['message']." ".$r['description']),
						   "device"=>$this->get_device($r['generator'],$devices),
						   "profile_image_url"=>"https://graph.facebook.com/{$r['author_id']}/picture",
						   "likes"=>$r['likes_count'],
						   "flag"=>$flag);		
		}
		return array("feeds"=>$posts,"total_rows"=>$rows['total']);
	}
	function website_feeds($campaign_id,$person,$start,$limit=10){
		$start = intval($start);
		$person = mysql_escape_string(cleanXSS($person));
		//get devices
		$sql = "SELECT * FROM smac_data.device_lookup";
		$devices = $this->fetch($sql,1);
		
		
		$sql = "SELECT id,feed_id,link,author_name,author_uri,comments,title,summary as message,
			published_ts,generator
			FROM smac_report.campaign_web_feeds
			WHERE campaign_id = {$campaign_id} AND author_name = '{$person}' AND is_active = 1
			LIMIT {$start},{$limit};";
		
		
		$sql2 = "SELECT COUNT(id) as total FROM smac_report.campaign_web_feeds
				WHERE campaign_id={$campaign_id} AND author_name='{$person}'";
		$rs = $this->fetch($sql,1);
		$rows = $this->fetch($sql2);
		
		$posts = array();
		foreach($rs as $r){
			//check for flag
			$flag = $this->is_workflow_flag($campaign_id,$r['feed_id'],3);
			//-->end of check
			$posts[] = array("id"=>$r['feed_id'],
						   "published"=>date("d/m/Y H:i:s",$r['published_ts']),
						   "author_id"=>$r['author_name'],
						   "name"=>htmlspecialchars($r['author_name']),
						    "link"=>htmlspecialchars($r['link']),
						   "txt"=>htmlspecialchars($r['message']),
						   "device"=>$this->get_device($r['generator'],$devices),
						   "comments"=>$r['comments'],
						   "flag"=>$flag);		
		}
		return array("feeds"=>$posts,"total_rows"=>$rows['total']);
	}
	
	function video_all_people($campaign_id,$req){
			$aColumns = array('author_id', 'occurance');
					
			$sql = "SELECT COUNT(DISTINCT author_uri) 
					FROM smac_youtube.campaign_youtube
					WHERE campaign_id = {$campaign_id}";
					
			$row = $this->fetch($sql);
			$total = intval($row['total']);
			$req = $this->request;
			//LIMIT
			$start = 0;
			$limit = 10;
			if ( $req->getParam('iDisplayLength') != '-1' )
			{
				$start = intval($req->getParam('iDisplayStart'));
				$limit = intval($req->getParam('iDisplayLength'));
			}
			if($limit==0){
				$limit = 10;
			}
			
			/*
			 * Ordering
			 */
			$sOrder = "";
			if ( $req->getParam('iSortCol_0')!=null )
			{
				$sOrder = "ORDER BY  ";
				for ( $i=0 ; $i<intval( $req->getParam('iSortingCols') ) ; $i++ )
				{
					if ( $req->getParam('bSortable_'.intval($req->getParam('iSortCol_'.$i)) ) == "true" )
					{
						//echo "Masuk<br />";
						$sOrder .= $aColumns[ intval( $req->getParam('iSortCol_'.$i) ) ]." ". $req->getParam('sSortDir_'.$i) .", ";
					}
				}
				
				$sOrder = substr_replace( $sOrder, "", -2 );
				if ( $sOrder == "ORDER BY" )
				{
					$sOrder = "";
				}
			}
			/**
			 * conditions
			 */
			$sWhere = "";
			if ($req->getParam('sSearch') != "" )
			{
				$sWhere = "WHERE (";
				for ( $i=0 ; $i<count($aColumns) ; $i++ )
				{
					$sWhere .= $aColumns[$i]." LIKE '%". $req->getParam('sSearch') ."%' OR ";
				}
				$sWhere = substr_replace( $sWhere, "", -3 );
				$sWhere .= ')';
			}
			//wrapping up
			$dat = $this->_getAllVideoAuthors($campaign_id,$start,$limit,$sOrder,$sWhere,$exclude);
			
			$data = array(
				"sEcho" => intval($req->getParam('sEcho')),
				"iTotalRecords" => $total,
				"iTotalDisplayRecords" => $total,
				"aaData" => array()
			);
			
			
			
			foreach($dat as $k){
				$idx = $k['id'];
				
				$data['aaData'][] = array( 
						// "<div class=\"smallthumb\"><a href=\"http://{$k['author_id']}\" target=\"_blank\" class=\"poplight\" rel=\"profile\"><img src='".htmlspecialchars($k['author_avatar'])."' /></a></div>",
						$k['author_id'],
						// $k['author_name'],
						"<span style=\"float:right\">".number_format($k['occurance'])."</span>"
						);
				
			}
		

		return $data;
	}
	
	private function _getAllVideoAuthors($campaign_id,$start,$total,$order,$where,$exclude){
			$order = mysql_escape_string($order);
			if($order == ''){
				$order = "";
			}
			
			$where = $where;
			
			if($where == ''){
				$where = " WHERE campaign_id=".$campaign_id." ";
			}else{
				$where .= " AND campaign_id=".$campaign_id."";
			}			
			$qry = "SELECT author_id, sum(view_count)  as occurance
					FROM smac_youtube.campaign_youtube
					{$where} 
					GROUP BY author_id
					{$order}
					LIMIT ".$start.",".$total;
			$rs = $this->fetch($qry,1);
			$data = array();
			for($i=0;$i<sizeof($rs);$i++){
				$r = $rs[$i];
				$author_id = $r['author_id'];
				$data[] = array("id" => intval($r['id']),
										  "author_id" => $r['author_id'],
										  "author_avatar" => "images/iconVideo.png",
										  "occurance" => intval($r['occurance']),
										  "author_name" => $r['author_id']
								);
			}
			unset($rs);
			return $data;
	}
	
	function get_device($subject,$devices){
		foreach($devices as $device){
			if(eregi($device['descriptor'],$subject)){
				return $device['device_type'];
			}
		}
		return "other";
	}
	
	
	//this is for the new web channel.
	function site($campaign_id,$type,$exclude){
		$type = intval($type);
		$data = array("top_post"=>$this->site_top_post($campaign_id,$type,$exclude),
					  "top_comments"=>array(),
					  "top_influences"=>array()
					  );
		return $data;
	}
	function site_top_post($campaign_id,$type,$exclude){
		//--> top KOL
		if($exclude==1){
			$sql = "SELECT author_name as author_id,author_uri,COUNT(author_name) as occurance 
			FROM smac_report.campaign_web_feeds 
			WHERE campaign_id={$campaign_id} AND group_type_id={$type}
			AND author_name NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=1)
			GROUP BY author_name 
			ORDER BY occurance DESC
			LIMIT 10";
			
		}else if($exclude==2){
			$sql = "SELECT author_name as author_id,author_uri,COUNT(author_name) as occurance 
					FROM smac_report.campaign_web_feeds 
					WHERE campaign_id={$campaign_id} AND group_type_id={$type}
					AND author_name NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=2)
					GROUP BY author_name 
					ORDER BY occurance DESC
					LIMIT 10";
			
		}else{		
			$sql = "SELECT author_name as author_id,author_uri,COUNT(author_name) as occurance 
					FROM smac_report.campaign_web_feeds 
					WHERE campaign_id={$campaign_id} AND group_type_id={$type} 
					GROUP BY author_name 
					ORDER BY occurance DESC
					LIMIT 10";
		}
		$rs = $this->fetch($sql,1);
		$kol = array();
		$this->web_kols = ""; //list of kols
		for($n=0;$n<sizeof($rs);$n++){
			$r = $rs[$n];
			if($n>0){
				$this->web_kols.=",";
			}
			$this->web_kols.="'{$r['author_id']}'";
			$kol[] = array("id"=>$r['author_id'],
						   "name"=>$r['author_id'],
						   "total"=>intval($r['occurance']));
		}
		unset($rs);
		return $kol;	
	}
	function site_all_sites($campaign_id,$req){
			$aColumns = array( 'author_id', 'author_id','author_name', 'occurance');
			$type = intval($req->getParam("type"));
			$sql = "SELECT COUNT(*) as total
					FROM smac_report.campaign_web_feeds
					WHERE campaign_id = {$campaign_id} AND group_type_id={$type}";
			$row = $this->fetch($sql);
			$total = intval($row['total']);
			$req = $this->request;
			//LIMIT
			$start = 0;
			$limit = 10;
			if ( $req->getParam('iDisplayLength') != '-1' )
			{
				$start = intval($req->getParam('iDisplayStart'));
				$limit = intval($req->getParam('iDisplayLength'));
			}
			if($limit==0){
				$limit = 10;
			}
			
			/*
			 * Ordering
			 */
			$sOrder = "";
			if ( $req->getParam('iSortCol_0')!=null )
			{
				$sOrder = "ORDER BY  ";
				for ( $i=0 ; $i<intval( $req->getParam('iSortingCols') ) ; $i++ )
				{
					if ( $req->getParam('bSortable_'.intval($req->getParam('iSortCol_'.$i)) ) == "true" )
					{
						//echo "Masuk<br />";
						$sOrder .= $aColumns[ intval( $req->getParam('iSortCol_'.$i) ) ]." ". $req->getParam('sSortDir_'.$i) .", ";
					}
				}
				
				$sOrder = substr_replace( $sOrder, "", -2 );
				if ( $sOrder == "ORDER BY" )
				{
					$sOrder = "";
				}
			}
			/**
			 * conditions
			 */
			$sWhere = "";
			if ($req->getParam('sSearch') != "" )
			{
				$sWhere = "WHERE (";
				for ( $i=0 ; $i<count($aColumns) ; $i++ )
				{
					$sWhere .= $aColumns[$i]." LIKE '%". $req->getParam('sSearch') ."%' OR ";
				}
				$sWhere = substr_replace( $sWhere, "", -3 );
				$sWhere .= ')';
			}
			//wrapping up
			$dat = $this->_getAllSiteAuthors($campaign_id,$type,$start,$limit,$sOrder,$sWhere,$exclude);
			
			$data = array(
				"sEcho" => intval($req->getParam('sEcho')),
				"iTotalRecords" => $total,
				"iTotalDisplayRecords" => $total,
				"aaData" => array()
			);
			
			
			
			foreach($dat as $k){
				$idx = $k['id'];
				
				$data['aaData'][] = array( "<div class=\"smallthumb\"><a href=\"http://{$k['author_id']}\" target=\"_blank\" class=\"poplight\" rel=\"profile\"><img src='".htmlspecialchars($k['author_avatar'])."' /></a></div>",
												$k['author_id'],
												$k['author_name'],
												"<span style=\"float:right\">".number_format($k['occurance'])."</span>"
												);
				
			}
		

		return $data;
	}
	
	private function _getAllSiteAuthors($campaign_id,$type,$start,$total,$order,$where,$exclude){
			$order = mysql_escape_string($order);
			if($order == ''){
				$order = "";
			}
			
			$where = $where;
			
			if($where == ''){
				$where = " WHERE campaign_id=".$campaign_id." AND group_type_id={$type}";
			}else{
				$where .= " AND campaign_id=".$campaign_id." AND group_type_id={$type}";
			}
			
			$qry = "select *,0 as share 
			from smac_report.top_authors
			{$where}
			{$order} 
			LIMIT ".$start.",".$total;
			
			$qry = "SELECT author_name as author_id, COUNT(*) AS occurance
					FROM smac_report.campaign_web_feeds
					{$where}
					GROUP BY author_name
					{$order}
					LIMIT ".$start.",".$total;
					
			$rs = $this->fetch($qry,1);
			$data = array();
			for($i=0;$i<sizeof($rs);$i++){
				$r = $rs[$i];
				$author_id = $r['author_id'];
				$data[] = array("id" => intval($r['id']),
										  "author_id" => $r['author_id'],
										  "author_avatar" => "images/iconWeb2.png",
										  "occurance" => intval($r['occurance']),
										  "author_name" => $r['author_id']
								);
			}
			unset($rs);
			return $data;
	}
	function site_ambas($campaign_id,$type,$exclude=0,$start=0,$total=10){
		$start = intval($start);
		$type = intval($type);
		//ambassador
		if($exclude==1){
			$sql = "SELECT author_id, sentiment, pii_score
					 FROM smac_gcs.gcs_campaign_ambas
					WHERE campaign_id = {$campaign_id} AND group_type_id = {$type} 
					AND
					author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=1)
					ORDER BY sentiment DESC LIMIT {$start},{$total}";	
			
			$sql2 = "SELECT COUNT(*) as total
					FROM smac_gcs.gcs_campaign_ambas
					WHERE campaign_id = {$campaign_id} AND group_type_id = {$type}  
					AND author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=1)";
					
			
			
			
		}else if($exclude==2){
			$sql = "SELECT author_id, sentiment, pii_score
					 FROM smac_gcs.gcs_campaign_ambas
					WHERE campaign_id = {$campaign_id} AND group_type_id = {$type}  
					AND author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=2)
					ORDER BY sentiment DESC LIMIT {$start},{$total}";	
			
			$sql2 = "SELECT COUNT(*) as total
					FROM smac_gcs.gcs_campaign_ambas
					WHERE campaign_id = {$campaign_id} AND group_type_id = {$type} 
					AND author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=2)";
					
			
		
		}else{
			$sql = "SELECT author_id, sentiment, pii_score
					 FROM smac_gcs.gcs_campaign_ambas
					WHERE campaign_id = {$campaign_id} AND group_type_id = {$type} 
					ORDER BY sentiment DESC LIMIT {$start},{$total}";	
			
			$sql2 = "SELECT COUNT(*) as total
					FROM smac_gcs.gcs_campaign_ambas
					WHERE campaign_id = {$campaign_id} AND group_type_id = {$type} ";
			
		}
		
		$positive = $this->fetch($sql,1);
		$row = $this->fetch($sql2);
		$ambas = array();
		for($i=0;$i<sizeof($positive);$i++){
				$ambas[] = array("img"=>$positive[$i]['pic'],
								  "name"=>$positive[$i]['author_id'],
								  "total"=>$positive[$i]['sentiment']);
		}
		unset($positive);
		return array("rows"=>$ambas,"total"=>$row['total']);
	}
	function site_trolls($campaign_id,$type,$exclude=0,$start=0,$total=10){
		$start = intval($start);
		$type = intval($type);
		//ambassador
		if($exclude==1){
			$sql = "SELECT author_id, sentiment, pii_score
					 FROM smac_gcs.gcs_campaign_trolls
					WHERE campaign_id = {$campaign_id} AND group_type_id = {$type} 
					AND author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=1)
					ORDER BY sentiment DESC LIMIT {$start},{$total}";	
			
			$sql2 = "SELECT COUNT(*) as total
					FROM smac_gcs.gcs_campaign_trolls
					WHERE campaign_id = {$campaign_id}
					AND group_type_id = {$type}  
					AND author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=1)";
					
			
			
			
		}else if($exclude==2){
			$sql = "SELECT author_id, sentiment, pii_score
					 FROM smac_gcs.gcs_campaign_trolls
					WHERE campaign_id = {$campaign_id} 
					AND group_type_id = {$type}  
					AND author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=2)
					ORDER BY sentiment DESC LIMIT {$start},{$total}";	
			
			$sql2 = "SELECT COUNT(*) as total
					FROM smac_gcs.gcs_campaign_trolls
					WHERE campaign_id = {$campaign_id}
					AND group_type_id = {$type}   
					AND author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=2)";
					
			
		
		}else{
			$sql = "SELECT author_id, sentiment, pii_score
					 FROM smac_gcs.gcs_campaign_trolls
					WHERE campaign_id = {$campaign_id}
					AND group_type_id = {$type}  
					ORDER BY sentiment DESC LIMIT {$start},{$total}";	
			
			$sql2 = "SELECT COUNT(*) as total
					FROM smac_gcs.gcs_campaign_trolls
					WHERE campaign_id = {$campaign_id}
					AND group_type_id = {$type}";
			
		}
		
		$negative = $this->fetch($sql,1);
		$row = $this->fetch($sql2);
		$trolls = array();
		for($i=0;$i<sizeof($negative);$i++){
				$trolls[] = array("img"=>$negative[$i]['pic'],
								  "name"=>$negative[$i]['author_id'],
								  "total"=>$negative[$i]['sentiment']);
		}
		unset($negative);
		return array("rows"=>$trolls,"total"=>$row['total']);
	}
}
?>