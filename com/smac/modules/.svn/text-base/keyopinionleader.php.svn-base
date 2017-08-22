<?php
global $APP_PATH;
require_once $APP_PATH . APPLICATION . "/helper/menuHelper.php";
//require_once $APP_PATH . APPLICATION . "/helper/headerHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/sidebarHelper.php";
require_once $APP_PATH . APPLICATION . "/widgets/favoriteWordWidget.php";
require_once $APP_PATH . APPLICATION . "/widgets/keyOpinionWidget.php";
require_once $APP_PATH . APPLICATION . "/widgets/tabNetworkWidget.php";
require_once $APP_PATH . APPLICATION . "/helper/apiHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/MarketHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/KOLHelper.php";

class keyopinionleader extends App{
	
	var $Request;
	
	var $View;
	
	var $menuHelper;
	
	//var $headerHelper;
	
	var $sidebarHelper;
	
	var $api;
	
	function __construct($req){
		
		$this->Request = $req;
		
		$this->View = new BasicView();
		
		$this->setVar();
		
		$this->menuHelper = new menuHelper($this->user,$req);
		
		//$this->headerHelper = new headerHelper($this->user,$req);
		
		$this->sidebarHelper = new sidebarHelper($this->user,$req);
		$this->kol = new KOLHelper($this->user,$req);
		if(strlen($_SESSION['geo'])>0){
			
			$this->api = new marketHelper();
		}else{
			$this->api = new apiHelper();
		}
		
	}
	
	function home(){
		$exclude = intval($this->Request->getParam('exclude'));
		$data = $this->api->getTopData($this->user->account_id,$_SESSION['campaign_id'],$_SESSION['language'],$exclude,$_SESSION['geo']);
		$kols = $this->kol->getPaidKOL($_SESSION['campaign_id']);
		$paid_kols = array();
		foreach($kols as $v){
			$paid_kols[$v['author_id']] = 1;
		}
		
		unset($kols);
		//print_r($data);exit;
		
		$top = array();
		
		$arrTop = "[";
		
		$arrStart = 0;
		
		$topHigh = 0;
		
		$arrName = '';
		$arrRT = "[";
		
		$data_available = true;
		
		if(sizeof($data)==0){
			$data_available=false;
		}
		if($data_available){
			
			foreach($data->user as $k){
			
				if($arrStart == 0){
					$arrStart = 1;
				}else{
					$arrTop .= ',';
					$arrName .= ",";
					$arrRT.=",";
				}
				$is_paid = false;
				$author_id = trim((String) $k->person);
				if(isset($paid_kols[$author_id])){
					$is_paid = true;
				}
				$top[] = array('person'=> (String) $author_id,
								'name'=> (String) $k->person,
								'real_name'=> (String) $k->name,
								'total'=> (String) $k->total,
								'rt'=> (String) $k->rt,
								'rt_percentage'=> (String) $k->rt_percentage,
								'rt_impression'=> (String) $k->rt_impression,
								'total_impression'=> (String) $k->total_impression,
								'impression'=> (String) $k->impression,
								'share'=> (String) $k->share,
								'img'=> (String) $k->img,
								'is_paid'=>$is_paid
								);
				
				$arrTop .= (String) $k->total_impression;
				
				$arrName .= "'".(String) $k->person."'";
				$arrRT.="'".(String) $k->share."'";
				
				if($topHigh < (int) $k->total_impression){
				
					$topHigh = (int) $k->total_impression;
				
				}
			
			}
			
			$arrTop .= ']';
			$arrRT .= ']';
			
			$this->View->assign('top',$top);
			$this->View->assign('arrTop',$arrTop);
			$this->View->assign('arrName',$arrName);
			$this->View->assign('arrRT',$arrRT);
			$this->View->assign("campaign_id",$_SESSION['campaign_id']);
			$this->View->assign('topHigh',($topHigh));
			
			$data = $this->api->womTotalMentions($this->user->account_id,$_SESSION['campaign_id'],$_SESSION['language'],$exclude,$_SESSION['geo']);
			
			$this->View->assign('total_mentions',smac_number($data->total));
			
			$this->View->assign('total_mentions_true',$data->total);
			$this->View->assign('mention_change',$data->mention_change);
			
			$data = $this->api->womAmbassador($this->user->account_id,$_SESSION['campaign_id'],$_SESSION['language'],$exclude,$_SESSION['geo']);
			
			$ambas = array();
			
			
			foreach($data->category[0]->row as $k){
			
				$ambas[] = array("img" => (String) $k->img,
								"name" => (String) $k->name,
								"rt" => (String) $k->rt,
								"impressi" => (String) $k->impressi,
								"positive" => (String) $k->positive
								);
			
			}
			
			$troll = array();
			
			foreach($data->category[1]->row as $k){
			
				$troll[] = array("img" => (String) $k->img,
								"name" => (String) $k->name,
								"rt" => (String) $k->rt,
								"impressi" => (String) $k->impressi,
								"positive" => (String) $k->positive
								);
			
			}
			
			$this->View->assign('ambas',$ambas);
			
			$this->View->assign('troll',$troll);
			
		
		}
		
		
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keyopinionleader');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlkeyopinion',$link);
		
		//exclude filter urls
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keyopinionleader','exclude'=>1);
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlkeyopinion_ex1',$link);
		
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keyopinionleader','exclude'=>2);
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlkeyopinion_ex2',$link);
		
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keyopinionleader','exclude'=>3);
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlkeyopinion_ex3',$link);
		//---->
		
		
		$this->View->assign("data_available",$data_available);
		$this->assign('sidebar', $this->sidebarHelper->show() );
		$this->assign("exclude",$exclude);
		$this->assign('menu', $this->menuHelper->showMenu(true) );
		if(strlen($_SESSION['geo'])==2){
			try{
				$market = ucfirst($this->api->getCountryName($_SESSION['geo']));
			}catch(Exception $e){
				
			}
		}
		$this->assign("market",$market);
		
		return $this->View->toString(APPLICATION.'/key-opinion-leader.html');
	
	}
	
	function overtime(){
	
		$ajax = intval($this->Request->getParam('ajax'));
		
		$person = stripslashes($this->Request->getParam("person"));
		$campaign_id = $this->Request->getParam("campaign_id");
		
		if($ajax == 1){
			/*
			$sql2 = "SELECT author_name FROM smac_report.campaign_people_daily_stats WHERE campaign_id=".intval($campaign_id)."
					AND author_id IN (".($person).") GROUP BY author_name ORDER BY published_date ASC";
			*/
			if(strlen($_SESSION['geo'])==2){
				$geo = mysql_escape_string($_SESSION['geo']);
				$sql2 = "SELECT 
								author_id,
								author_name,
								SUM(total_mentions) AS total  
							FROM 
								smac_market.campaign_people_daily_stats 
							WHERE 
								campaign_id=".intval($campaign_id)." AND 
								author_id IN (".($person).") 
								AND geo='{$geo}'
							GROUP BY 
								author_name 
							ORDER BY 
								author_name ASC;";
			}else{

				$sql2 = "SELECT 
							author_id,
							author_name,
							total_mentions AS total  
						FROM 
							smac_report.twitter_top_authors 
						WHERE 
							campaign_id=".intval($campaign_id)." AND 
							author_id IN (".($person).") 
						GROUP BY 
							author_name 
						ORDER BY 
							author_name ASC";
			}	
			$this->open(0);
			
			$rs2 = $this->fetch($sql2,1);
			
			//echo $sql2.'<hr />';
			//print_r($rs2);
			//exit;
			
			$num = count($rs2);
			
			$xml = '<chart>';
			$xml .= '<categories>';
			
			
			$per = str_replace("'","",$person);
			$per = explode(',',$per);
			
			foreach($per as $v){
				$xml .= '<item>'.$v.'</item>';
			}
			
			//$xml .= '<item>Mentions</item>';
			$xml .= '</categories>';
			
			$xml .= '<series>';
			$xml .= '<name>Mentions</name>';
			$xml .= '<data>';
			//$xml .= '<point>';
			foreach($per as $v){
				$got = false;
				for($i=0;$i<$num;$i++){
					if(strtolower($v) == strtolower($rs2[$i]['author_id'])){
						//$xml .= '<point>'.$rs2[$i]['total'].'</point>';
						$xml .= '<point>'.$rs2[$i]['total'].'</point>';
						$got = true;
						break;
					}
				}
				if(!$got){
					//$xml .= '<series>';
					//$xml .= '<name>'.$v.'</name>';
					//$xml .= '<data>';
					$xml .= '<point>0</point>';
					//$xml .= '</data>';
				}
			}
			$xml .= '</data>';
			$xml .= '</series>';
			$xml .= '</chart>';
			$this->close();
		
		}
		
		header("Content-type: text/xml"); 
		print '<?xml version="1.0"?>';
		print $xml;
		exit;
	
	}
	function impression_overtime(){
	
		$ajax = intval($this->Request->getParam('ajax'));
		
		$person = stripslashes($this->Request->getParam("person"));
		$campaign_id = $this->Request->getParam("campaign_id");
		
		if($ajax == 1){
			
		
			
			$xml = '<chart>';
			$xml .= '<categories>';
			
			
			$per = str_replace("'","",$person);
			$per = explode(',',$per);
			
			foreach($per as $v){
				$xml .= '<item>'.htmlentities($v).'</item>';
			}
			
			//$xml .= '<item>Mentions</item>';
			$xml .= '</categories>';
			
			$xml .= '<series>';
			$xml .= '<name>Impressions</name>';
			$xml .= '<data>';
			$this->open(0);
			foreach($per as $v){
				if(strlen($_SESSION['geo'])==2){
					$geo = mysql_escape_string($_SESSION['geo']);
					$sql = "SELECT SUM(total) AS total FROM (
								SELECT 
									author_id,
									published_date AS _date,
									(COALESCE(imp,0)+COALESCE(rt_imp,0)) as total 
							FROM 
								smac_market.campaign_people_daily_stats 
							WHERE 
								campaign_id=".intval($campaign_id)." AND geo='{$geo}'
								AND author_id IN ('".$v."') 
							ORDER BY 
								published_date ASC) a";
				}else{
				
					
					$sql = "SELECT 
								author_id,
								author_name,
								total_impression AS total  
							FROM 
								smac_report.twitter_top_authors 
							WHERE 
								campaign_id=".intval($campaign_id)." AND 
								author_id IN ('".($v)."') 
							GROUP BY 
								author_name 
							ORDER BY 
								author_name ASC";
				}
				
				//echo $sql;exit;
				
				$r = $this->fetch($sql);
				
				//print_r($r);exit;
				
				$xml .= '<point>'.intval($r['total']).'</point>';
				
				
			}
			//exit;
			$xml .= '</data>';
			$xml .= '</series>';
			$xml .= '</chart>';
			
			$this->close();
		
		}
		
		header("Content-type: text/xml"); 
		print '<?xml version="1.0"?>';
		print $xml;
		exit;
	
	}
	function performance_overtime(){
	
		$ajax = intval($this->Request->getParam('ajax'));
		
		$person = stripslashes($this->Request->getParam("person"));
		$campaign_id = $this->Request->getParam("campaign_id");
		
		if($ajax == 1){
			
			//buat sort date
			
			if(strlen($_SESSION['geo'])==2){
					$geo = mysql_escape_string($_SESSION['geo']);
					$sql2 = "SELECT published_date AS _date
					FROM smac_report.campaign_feeds a
					INNER JOIN smac_report.campaign_feed_sentiment b
					ON a.id = b.feed_id
					INNER JOIN smac_report.country_twitter c
					ON a.feed_id = c.feed_id
					WHERE a.campaign_id=".intval($campaign_id)." AND c.country_code='{$geo}'
					AND a.author_id IN (".($person).")
					GROUP BY a.published_date ORDER BY a.published_date ASC";
			}else{
				$sql2 = "SELECT published_date AS _date
					FROM smac_report.campaign_feeds a
					INNER JOIN smac_report.campaign_feed_sentiment b
					ON a.id = b.feed_id
					WHERE a.campaign_id=".intval($campaign_id)." 
					AND a.author_id IN (".($person).")
					GROUP BY a.published_date ORDER BY a.published_date ASC";
				$sql2 = "";
			}
			$this->open(0);
			
			$rs2 = $this->fetch($sql2,1);
			
			$num = count($rs2);
			
			$xml = '<chart>';
			$xml .= '<categories>';
			
			//add minus 1 date
			$d = strtotime( '-1 day', strtotime($rs2[0]['_date']) );
			$date = new DateTime( date( 'd-m-Y', $d) );
			$xml .= '<item>'.$date->format('d M').'</item>';
			
			for($i=0;$i<$num;$i++){
				
				$date = new DateTime($rs2[$i]['_date']);
				$xml .= '<item>'.$date->format('d M').'</item>';
				
			}
			
			//add plus 1 date
			$d = strtotime( '+1 day', strtotime($rs2[$num-1]['_date']) );
			$date = new DateTime( date( 'd-m-Y', $d) );
			$xml .= '<item>'.$date->format('d M').'</item>';
			
			$xml .= '</categories>';
			
			$p = explode(',',$person);
			//$pnum = count($p);
			
			foreach($p as $k => $v){
				if(strlen($_SESSION['geo'])==2){
					$geo = mysql_escape_string($_SESSION['geo']);
					$sql = "SELECT campaign_id,author_id,author_avatar,published_date as _date,SUM(pii) as total 
							FROM smac_report.campaign_feeds a
							INNER JOIN smac_report.campaign_feed_sentiment b
							ON a.id = b.feed_id
							INNER JOIN smac_report.country_twitter c
							ON a.feed_id = c.feed_id
							WHERE a.campaign_id=".intval($campaign_id)." AND c.country_code='{$geo}'
							AND a.author_id IN (".$v.")
							GROUP BY a.author_id,a.published_date
							ORDER BY a.published_date ASC";
				}else{
					$sql = "SELECT a.campaign_id,author_id,author_avatar,published_date as _date,SUM(pii) as total 
							FROM smac_report.campaign_feeds a
							INNER JOIN smac_report.campaign_feed_sentiment b
							ON a.id = b.feed_id
							WHERE a.campaign_id=".intval($campaign_id)." 
							AND a.author_id IN (".$v.")
							GROUP BY a.author_id,a.published_date
							ORDER BY a.published_date ASC";
					
				}
				
				
				$r = $this->fetch($sql,1);
				
				$xml .= '<series>';
				$xml .= '<name>'.str_replace("'","",$v).'</name>';
				$xml .= '<data>';
				$xml .= '<point>0</point>';
				
				for($i=0;$i<$num;$i++){
				
					$ada = false;
					
					foreach($r as $j){
						
						if( $rs2[$i]['_date'] == $j['_date'] ){
							$xml .= '<point>'.intval($j['total']).'</point>';
							$ada = true;
						}
						
					}
					
					if(!$ada){
						$xml .= '<point>0</point>';
					}
					
				}
				
				$xml .= '<point>0</point>';
				$xml .= '</data></series>';
				
			}
			//exit;
			
			
			$xml .= '</chart>';
			
			$this->close();
		
		}
		
		header("Content-type: text/xml"); 
		print '<?xml version="1.0"?>';
		print $xml;
		exit;
	
	}
	
	function getallpeople(){
		$exclude = intval($this->Request->getParam('exclude'));
		$ajax = intval($this->Request->getParam('ajax'));
		$data = array();
	
		if($ajax > 0){
		
			$aColumns = array( 'author_id', 'author_id','author_name', 'total_impression','total_impression');
			
			$totalx = json_decode($this->api->getAllPeopleTotal($this->user->account_id,$_SESSION['campaign_id'],$_SESSION['language'],$exclude,$_SESSION['geo']));
			$total = intval($totalx->total);
			
			
			//LIMIT
			$start = 0;
			$limit = 10;
			if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
			{
				$start = intval( $_GET['iDisplayStart'] );
				$limit = intval( $_GET['iDisplayLength'] );
			}
			
			/*
			 * Ordering
			 */
			$sOrder = "";
			if ( isset( $_GET['iSortCol_0'] ) )
			{
				$sOrder = "ORDER BY  ";
				for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
				{
					if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
					{
						//echo "Masuk<br />";
						$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]." ". $_GET['sSortDir_'.$i] .", ";
					}
				}
				
				$sOrder = substr_replace( $sOrder, "", -2 );
				if ( $sOrder == "ORDER BY" )
				{
					$sOrder = "";
				}
			}
			
			$sWhere = "";
			if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
			{
				$sWhere = "WHERE (";
				for ( $i=0 ; $i<count($aColumns) ; $i++ )
				{
					$sWhere .= $aColumns[$i]." LIKE '%". $_GET['sSearch'] ."%' OR ";
				}
				$sWhere = substr_replace( $sWhere, "", -3 );
				$sWhere .= ')';
			}
			
			//echo $sWhere;
			//print_r($_GET);
			//echo $sOrder;
			//exit;
			
			$dat = json_decode($this->api->getAllPeople($this->user->account_id,$_SESSION['campaign_id'],'all',$start,$limit,$sOrder,$sWhere,$exclude,$_SESSION['geo']),false);
			
			$data = array(
				"sEcho" => intval($_GET['sEcho']),
				"iTotalRecords" => $total,
				"iTotalDisplayRecords" => $total,
				"aaData" => array()
			);
			
			//print_r($dat);exit;
			
			foreach($dat as $k){
				$idx = $k->id;
				
				$data['aaData'][] = array( "<div class=\"smallthumb\"><a href=\"#?w=650&id=".$k->author_id."\" class=\"poplight\" rel=\"profile\"><img src='".htmlspecialchars($k->author_avatar)."' /></a></div>",
															$k->author_id,
															$k->author_name,
															"<span style=\"float:right\">".number_format($k->impression)."</span>",
															"<span style=\"float:right\">".$k->share."</span>",
															"<span style=\"float:right\">".$k->pii."</span>"
															);
				
			}
		}else{
			$data['succes'] = 0;
		}
		
		//print_r($data);
		//exit;
		echo json_encode($data,JSON_HEX_TAG);
		exit;
		
	}

}
?>