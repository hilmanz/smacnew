<?php
global $APP_PATH;
class profile extends App{
	function home(){
		
		sendRedirect("index.php");
		exit;
		
	}
	
	function popup(){
	
		$id = $_GET['id'];
		
		$pro = $this->api->getProfile($id,$_SESSION['campaign_id'],$this->user->account_id);
		
		//print_r($pro);exit;
		
		$prof = array();
		
		$prof['image'] = (String) $pro->image;
		$prof['name'] = (String) $pro->name;
		$prof['username'] = (String) $pro->username;
		$prof['location'] = (String) $pro->location;
		$prof['about'] = (String) $pro->about;
		$prof['timezone'] = (String) $pro->timezone;
		$prof['follower'] = (String) $pro->follower;
		$prof['mention'] = (String) $pro->mention;
		$prof['rt'] = (String) $pro->rt;
		$prof['rt_impression'] = (String) $pro->rt_impression;
		$prof['total_impression'] = (String) $pro->impressi->positive;
		$prof['impressi'] = (String) $pro->impression;
		$prof['rank'] = number_format(trim((String) $pro->rank));
		$prof['share_percentage'] = (String) $pro->share_percentage;
		
		$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'exclude_person','campaign_id'=>$_SESSION['campaign_id'],'author_id'=>$id,'ajax'=>1);
		$prof['remove_link'] = $this->Request->encrypt_params($c);
		
		
		
		//impact score
		$imp = $this->api->profileImpactScore($this->user->account_id,$_SESSION['campaign_id'],$id);
		
		//print_r($imp);exit;
		
		$data = array();
		
		$data['success'] = 1;
		
		$data['data'] = $prof;
		
		$data['word'] = $dwc;
		
		$data['impact_score'] = (String) $imp->score;
	
		echo json_encode($data);		
			
		exit;
		
	}
	
	function getstate(){
		
		$data = array();
	
		if($_GET['ajax'] == 1){
		
			$id = $_GET['id'];
			
			$this->open(0);
			
			$sql = "SELECT * FROM state WHERE 1 AND country_id='$id' ORDER BY state_name;";
		
			//echo $sql;exit;
		
			$r = $this->fetch($sql,1);
		
			$data['success'] = 1;
			
			$data['data'] = $r;
			
			$this->close();		
		
		}else{
		
			$data['success'] = 0;
		
		}
		
		echo json_encode($data);
		
		exit;
	
	}
	
	function getcity(){
		
		$data = array();
	
		if($_GET['ajax'] == 1){
		
			$state = intval($_GET['state']);
			
			$country = $_GET['country'];
			
			$this->open(0);
			
			$sql = "SELECT * FROM city WHERE 1 AND country_id='$country' AND state_id=$state ORDER BY city_name;";
		
			//echo $sql;exit;
		
			$r = $this->fetch($sql,1);
		
			$data['success'] = 1;
			
			$data['data'] = $r;
			
			$this->close();		
		
		}else{
		
			$data['success'] = 0;
		
		}
		
		echo json_encode($data);
		
		exit;
	
	}
	
	function chartmentions(){
	
		$ajax = intval($this->Request->getParam('ajax'));
		$person = $this->Request->getParam('person');
		$type = $this->Request->getParam('type');
		$campaign_id = $_SESSION['campaign_id'];
		
		if($ajax == 1){
			/*
			$sql = "SELECT tgl as _date,author_id,author_avatar,SUM(followers) as impression,COUNT(author_id) as total_mention 
					FROM (SELECT a.feed_id,DATE(published_date) as tgl,author_id,author_avatar,followers,following,total_mentions
					FROM smac_report.campaign_history a
					INNER JOIN smac_data.feeds b ON a.feed_id = b.id
					WHERE a.campaign_id = ".$_SESSION['campaign_id']."
					AND b.author_id =  '".mysql_escape_string($person)."') aa
					GROUP BY tgl
							";
			*/
			$sql="SELECT published_date as _date,author_id,author_avatar,total_mentions as total_mention,imp as impression 
					FROM smac_report.campaign_people_daily_stats WHERE campaign_id=".$campaign_id." 
					AND author_id='".mysql_escape_string($person)."' ORDER BY published_date ASC";
			/*$sql = "SELECT 
						b.author_id,
						DATE(b.published) AS _date,
						COUNT(b.author_id) AS total_mention,
						SUM(b.followers) AS impression 
					FROM 
						smac_report.campaign_history a
					INNER JOIN smac_data.feeds b
					ON a.feed_id = b.id
					WHERE a.campaign_id=".$_SESSION['campaign_id']."
					AND b.author_id = '".mysql_escape_string($person)."'
					GROUP BY b.author_id,DATE(b.published)
					ORDER BY b.author_id;";
					*/
			//echo $sql;
			//exit;
			
			$this->open(0);
			
			$rs = @$this->fetch($sql,1);
			$num = count($rs);
			
			$xml = '<chart>';
			$xml .= '<categories>';
			
			//add minus 1 date
			$d = strtotime( '-1 day', strtotime($rs[0]['_date']) );
			$date = new DateTime( date( 'd-m-Y', $d) );
			$xml .= '<item>'.$date->format('d M').'</item>';
			
			for($i=0;$i<$num;$i++){
				
				$date = new DateTime($rs[$i]['_date']);
				
				$xml .= '<item>'.$date->format('d M').'</item>';
				
			}
			
			//add plus 1 date
			$d = strtotime( '+1 day', strtotime($rs[$num-1]['_date']) );
			$date = new DateTime( date( 'd-m-Y', $d) );
			$xml .= '<item>'.$date->format('d M').'</item>';
			
			$xml .= '</categories>';
		
			//$rs = $this->fetch($sql,1);
			//$num = count($rs);
			
			$name = '';
			$start = 0;
		
			for($i=0;$i<$num;$i++){
			
				if($name != $rs[$i]['author_id']){
					
					$name = $rs[$i]['author_id'];
					
					if($start == 0){
					
						$start = 1;
					
					}else{
					
						$xml .= '<point>0</point>';
						$xml .= '</data></series>';
					
					}
					
					$xml .= '<series>';
					$xml .= '<name>'.$name.'</name>';
					$xml .= '<data>';
					$xml .= '<point>0</point>';
				
				}
				
				if(	$type == 'mentions' ){
					$xml .= '<point>'.intval($rs[$i]['total_mention']).'</point>';
				}else{
					$xml .= '<point>'.intval($rs[$i]['impression']).'</point>';
				}
				
			}
			
			$xml .= '<point>0</point>';
			$xml .= '</data></series>';
			$xml .= '</chart>';
			
			$this->close();
		
		}
		
		header("Content-type: text/xml"); 
		print '<?xml version="1.0"?>';
		print $xml;
		exit;
	
	}
	
	function chartrt(){
	
		$ajax = intval($_GET['ajax']);
		$person = $_GET['person'];
		
		if($ajax == 1){
			$campaign_id = intval($_SESSION['campaign_id']);
			
			
			$sql="SELECT published_date as the_date,author_id as author,rt_mention as RT_total,rt_imp as RT_impression 
					FROM smac_report.campaign_people_daily_stats WHERE campaign_id=".$campaign_id." 
					AND author_id='".mysql_escape_string($person)."' ORDER BY published_date ASC";	
			
			//exit;
			
			$this->open(0);
			
			$rs = $this->fetch($sql,1);
			$num = count($rs);
			
			if( $num > 0 ){
				$xml = '<chart>';
				$xml .= '<categories>';
			
				//add minus 1 date
				$d = strtotime( '-1 day', strtotime($rs[0]['the_date']) );
				$date = new DateTime( date( 'd-m-Y', $d) );
				$xml .= '<item>'.$date->format('d M').'</item>';
			
				for($i=0;$i<$num;$i++){
				
					$date = new DateTime($rs[$i]['the_date']);
				
					$xml .= '<item>'.$date->format('d M').'</item>';
				
				}
			
				//add plus 1 date
				$d = strtotime( '+1 day', strtotime($rs[$num-1]['the_date']) );
				$date = new DateTime( date( 'd-m-Y', $d) );
				$xml .= '<item>'.$date->format('d M').'</item>';
			
				$xml .= '</categories>';
			
				$name = '';
				$start = 0;
		
				for($i=0;$i<$num;$i++){
			
					if($name != $rs[$i]['author']){
					
						$name = $rs[$i]['author'];
					
						if($start == 0){
					
							$start = 1;
					
						}else{
					
							$xml .= '<point>0</point>';
							$xml .= '</data></series>';
					
						}
					
						$xml .= '<series>';
						$xml .= '<name>'.$name.'</name>';
						$xml .= '<data>';
						$xml .= '<point>0</point>';
				
					}
				
					$xml .= '<point>'.intval($rs[$i]['RT_total']).'</point>';
				
				}
				$xml .= '<point>0</point>';
				$xml .= '</data></series>';
				$xml .= '</chart>';
			
				//echo $xml;exit;
			}else{
				$xml = '<chart><categories><item></item></categories><series><name/><data><point/></data></series></chart>';
			}
			
			$this->close();
		
		}
		
		header("Content-type: text/xml"); 
		print '<?xml version="1.0"?>';
		print $xml;
		exit;
	
	}
	function chart_sentiment_overtime(){
	
		$ajax = intval($this->Request->getParam('ajax'));
		$person = $this->Request->getParam('person');
		$type = $this->Request->getParam('type');
		$campaign_id = $_SESSION['campaign_id'];
		
		if($ajax == 1){
			$positive = array();
			$negative = array();
			$this->open(0);
			if($this->is_new_feeds($campaign_id)==1){
				$FEEDS = "smac_feeds.campaign_feeds_{$campaign_id}";
			}else{
				$FEEDS = "smac_report.campaign_feeds";
			}
			//positive sentiment
			$sql = "SELECT a.campaign_id,author_id,author_avatar,published_date as tgl,SUM(sentiment) as total_sentiment,
					SUM(freq) as freq,SUM(pii) as pii 
					FROM {$FEEDS} a
					INNER JOIN smac_report.campaign_feed_sentiment b
					ON a.id = b.feed_id
					WHERE a.campaign_id=".$campaign_id." 
					AND a.author_id='".mysql_escape_string($person)."' 
					AND b.sentiment > 0 
					GROUP BY a.author_id,a.published_date";
		
			
			$p_sentiment = @$this->fetch($sql,1);
			$this->close();
			//var_dump($p_sentiment);
			//print $sql;
			//negative sentiment
			$sql = "SELECT a.campaign_id,author_id,author_avatar,published_date as tgl,SUM(sentiment) as total_sentiment,
					SUM(freq) as freq,SUM(pii) as pii 
					FROM {$FEEDS} a
					INNER JOIN smac_report.campaign_feed_sentiment b
					ON a.id = b.feed_id
					WHERE a.campaign_id=".$campaign_id." AND a.author_id='".mysql_escape_string($person)."' AND b.sentiment < 0 
					GROUP BY a.author_id,a.published_date";
		
			
			$n_sentiment = @$this->fetch($sql,1);
		//	var_dump($n_sentiment);
			
			foreach($p_sentiment as $p){
				$positive[$p['tgl']] = array("total"=>$p['total_sentiment']);
				if($negative[$p['tgl']]==null){
					$negative[$p['tgl']] = array("total"=>0);
				}
			}
			foreach($n_sentiment as $p){
				$negative[$p['tgl']] = array("total"=>$p['total_sentiment']);
				if($positive[$p['tgl']]==null){
					$positive[$p['tgl']] = array("total"=>0);
				}
			}
			
			
			foreach($positive as $name=>$val){
				$v_positive = $val['total'];
				$v_negative = $negative[$name]['total'];
				$rs[$name] = array(
									"date" => $name,
									"positive"=>$v_positive,
									"negative"=>$v_negative,
									"sum"=>($v_positive-$v_negative),
									"avg"=>abs(($v_positive+$v_negative)/(abs($v_positive)+abs($v_negative))));
			}
		}
		
		//echo "test <hr />";
		//print_r($rs);exit;
		
		//BIKIN XML
			$xml = '<chart>';
			$xml .= '<categories>';
			$num = 0;
				
			foreach($rs as $k){
			
				//echo $k['date']. '<hr />';
				
				if($num == 0){
					$d = strtotime( '-1 day', strtotime($k['date']) );
					$date = new DateTime( date( 'd-m-Y', $d) );
					$xml .= '<item>'.$date->format('d M').'</item>';
					$num = 1;
				}
			
				$date = new DateTime($k['date']);
				$xml .= '<item>'.$date->format('d M').'</item>';
				
				$last_date = $k['date'];
				
			}
			
			//add plus 1 date
			$d = strtotime( '+1 day', strtotime($last_date) );
			$date = new DateTime( date( 'd-m-Y', $d) );
			$xml .= '<item>'.$date->format('d M').'</item>';
			$xml .= '</categories>';
			
			if($type=='sentiment'){
				
				//positive 
				
				$xml .= '<series>';
				$xml .= '<name>Positive</name>';
				$xml .= '<data>';
				$xml .= '<point>0</point>';
				
				foreach($rs as $k){
						$xml .= '<point>'.intval($k['positive']).'</point>';
				}
				
				$xml .= '<point>0</point>';
				$xml .= '</data>';
				$xml .= '</series>';
				
				//negative 
				
				$xml .= '<series>';
				$xml .= '<name>Negative</name>';
				$xml .= '<data>';
				$xml .= '<point>0</point>';
				
				foreach($rs as $k){
						$xml .= '<point>'.intval($k['negative']).'</point>';
				}
				
				$xml .= '<point>0</point>';
				$xml .= '</data>';
				$xml .= '</series>';
			}elseif($type=='mention'){
				
				//SUM 
				
				$xml .= '<series>';
				$xml .= '<name>SUM</name>';
				$xml .= '<data>';
				$xml .= '<point>0</point>';
				
				foreach($rs as $k){
						$xml .= '<point>'.intval($k['sum']).'</point>';
				}
				
				$xml .= '<point>0</point>';
				$xml .= '</data>';
				$xml .= '</series>';
				
				//AVG 
				
				$xml .= '<series>';
				$xml .= '<name>AVG</name>';
				$xml .= '<data>';
				$xml .= '<point>0</point>';
				
				foreach($rs as $k){
						$xml .= '<point>'.intval($k['avg']).'</point>';
				}
				
				$xml .= '<point>0</point>';
				$xml .= '</data>';
				$xml .= '</series>';
				
			}
			
			$xml .= '</chart>';
		
		header("Content-type: text/xml"); 
		print '<?xml version="1.0"?>';
		print $xml;
		exit;
		
		/*
		//header('Content-type: application/json');
		print json_encode($rs);
		exit;
		*/
	}

	function getprofiletweets(){
		
		$ajax = $this->Request->getParam('ajax');
		$data = array();
		if($ajax == 1){
			$start = intval($this->Request->getParam('start'));
			$perpage = intval($this->Request->getParam('perpage'));
			$person = mysql_escape_string($this->Request->getParam('person'));
			$dat = $this->api->getProfileTweets($_SESSION['campaign_id'],$start,$perpage,$person);
			
			echo $dat;
			exit;
			
		}else{
			$data['succes'] = 0;
		}
		
		echo json_encode($data);
		exit;
		
	}
	function get_wordcloud(){
		if($this->Request->getParam("ajax")=="1"){
			$id = mysql_escape_string($this->Request->getParam("id"));
			//wordcloud
			$word = $this->api->getProfileWordcloud($id,$_SESSION['campaign_id']);
			
			$dat = array();
			foreach($word as $k => $v){
				$dat[] = array('keyword'=>$v->keyword,'total'=>$v->total, 'sentiment' => $v->sentiment, 'is_main' => $v->is_main);
			}
			//Worldcloud Baru ===============
			$wc = array();
			$m=0;
			$mm=0;
			foreach($dat as $w){
				$m = max($m,$w['total']);
				$mm = min($mm,$w['total']);
				$wc[] = array("txt"=>$w['keyword'],"amount"=>$w['total'],"weight"=>$w['total'],"url"=>"link","is_main"=> $w['is_main'],"sentiment"=> $w['sentiment'],"title"=> "");
			}
			foreach($wc as $n=>$v){
				$weight = ceil(($v['amount']/($m-$mm))*9);
				$wc[$n]['weight'] = $weight;
				$wc[$n]['max'] = $m;
				$wc[$n]['min'] = $mm;
			}
			
			//$_GET['key'] = "'$kw'";
			//$_GET['id'] = "'nouse'";
			$wordcloud = new wordcloudHelper(300,300);
			$wordcloud->urlto="javascript:void(0);";
			//$wordcloud->callback_func="addKeyword";
			$wordcloud->setHandler('homewordcloud');
			$wordcloud->set_sentiment_style(array("positive"=>"wcstat1","negative"=>"wcstat2","neutral"=>"wcstat0","main_keyword"=>"wcstat3"));
			$dwc = $wordcloud->draw($wc);
			//============================
			print $dwc;
		}
		die();
		
	}
	/**
	 * check if the campaign is under new schema (dynamic feed_wordlist etc)
	 * @param $campaign_id
	 * @return unknown_type
	 */
	function is_new_schema($campaign_id){
		$sql = "SELECT n_status FROM smac_report.tbl_feed_wordlist_flag WHERE campaign_id={$campaign_id} LIMIT 1";
		$rs = $this->fetch($sql);
		return $rs['n_status'];
	}
	/**
	 * check if the campaign is using new campaign_feeds schema
	 * @param $campaign_id
	 * @return unknown_type
	 */
	function is_new_feeds($campaign_id){
		$sql = "SELECT n_status FROM smac_report.campaign_feeds_split_flag WHERE campaign_id={$campaign_id} LIMIT 1";
		$rs = $this->fetch($sql);
		if($rs['n_status']==1){
			return true;
		}
	}
	
}
?>