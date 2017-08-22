<?php
global $APP_PATH;
require_once $APP_PATH . APPLICATION . "/helper/menuHelper.php";
//require_once $APP_PATH . APPLICATION . "/helper/headerHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/sidebarHelper.php";
require_once $APP_PATH . APPLICATION . "/widgets/favoriteWordWidget.php";
require_once $APP_PATH . APPLICATION . "/widgets/keyOpinionWidget.php";
require_once $APP_PATH . APPLICATION . "/widgets/tabNetworkWidget.php";
require_once $APP_PATH . APPLICATION . "/helper/apiHelper.php";
require_once $APP_PATH . APPLICATION . "/widgets/dateFilterWidget.php";
require_once $APP_PATH . APPLICATION . "/helper/wordcloudHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/MarketHelper.php";

class keywordanalysis extends App{
	
	var $Request;
	var $View;
	var $menuHelper;
	//var $headerHelper;
	var $sidebarHelper;
	var $dateFilterWidget;
	var $api;
	var $is_default_range;
	function __construct($req){
		$this->Request = $req;
		$this->is_default_range = 0;
		$this->View = new BasicView();
		$this->setVar();
		$this->menuHelper = new menuHelper($this->user,$req);
		//$this->headerHelper = new headerHelper($this->user,$req);
		$this->sidebarHelper = new sidebarHelper($this->user,$req);
		$this->dateFilterWidget = new dateFilterWidget($this->user,$req);
		
		
		if(strlen($_SESSION['geo'])>0){
			
			$this->api = new marketHelper();
		}else{
			$this->api = new apiHelper();
		}
		
	}
	function init(){
		$this->assign('sidebar', $this->sidebarHelper->show() );
		$this->assign('menu', $this->menuHelper->showMenu(true) );
		
		$filter = intval($this->Request->getParam('filter'));
		$this->View->assign('filter',$filter);
		
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keywordanalysis','act' => 'home','filter' => 0);
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('filter0',$link);
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keywordanalysis','act' => 'top','filter' => 1);
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('filter1',$link);
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keywordanalysis','act' => 'top','filter' => 2);
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('filter2',$link);
		
		//datefilter
		$this->dateFilterWidget->setPage('keywordanalysis');
		$this->assign("widget_datefilter",$this->dateFilterWidget->show());
		if($this->dateFilterWidget->from_date()==null){
			$this->is_default_range = 1;
		}
		$filter_date_from = $this->dateFilterWidget->from_date() != '' ? $this->dateFilterWidget->from_date() : $this->dateFilterWidget->getStartDate();
		$filter_to_date = $this->dateFilterWidget->to_date() != '' ? $this->dateFilterWidget->to_date() : $this->dateFilterWidget->getEndDate();
		
		
		$this->View->assign("filter_from_date",$filter_date_from);
		$this->View->assign("filter_to_date",$filter_to_date);
		$this->View->assign("default_range",$this->is_default_range);
	}
	function home(){
		$this->init();
		//get Keyword List
		$response = $this->api->getKeywordAnalysisList($_SESSION['campaign_id'],$this->user->account_id,$filter,$_SESSION['geo']);
		
		$data = json_decode($response);
		$kw = array();
		if(is_array($data)){
			foreach($data as $d){
				$kw[] = array("keyword_id"=>$d->keyword_id,"label"=>$d->label,
							"keyword"=>$d->keyword,"total"=>$d->total,
							"n_status"=>$d->n_status);
			}
		}
		$this->View->assign("n_keyword",sizeof($kw));
		$this->View->assign('kw',$kw);
		
		
		
		$data_available = false;
		if(sizeof($kw)>0){
			$data_available = true;
		}
		if($data_available){
			$arrKey = '';
			$st = 0;
			
			foreach($kw as $k){
				
				if($st > 0){
					$arrKey .= ",";
				}
				
				$arrKey .= "'".$k['keyword_id']."'";
				
				$st++;
			}
			
			$this->View->assign('arrKey',$arrKey);
			
			//PINDAHAN halaman Sentiment
			$data = $this->api->summaryImpact($this->user->account_id,$_SESSION['campaign_id'],$_SESSION['language'],$_SESSION['geo']);
			$this->assign('impact_score', $data->impact );
			
			
		}
		if(strlen($_SESSION['geo'])==2){
			try{
				$market = ucfirst($this->api->getCountryName($_SESSION['geo']));
			}catch(Exception $e){
				
			}
		}
		$this->assign("market",$market);
		$this->View->assign('geo',$_SESSION['geo']);
		$this->View->assign("data_available",$data_available);
		return $this->View->toString(APPLICATION.'/key-word-analysis.html');
	
	}
	
	function top(){
		$this->assign('sidebar', $this->sidebarHelper->show() );
		$this->assign('menu', $this->menuHelper->showMenu() );
		
		$filter = intval($this->Request->getParam('filter'));
		$top = ($filter == 1) ? 10 : 50;
		$this->View->assign('filter',$filter);
		
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keywordanalysis','act' => 'home','filter' => 0);
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('filter0',$link);
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keywordanalysis','act' => 'top','filter' => 1);
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('filter1',$link);
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keywordanalysis','act' => 'top','filter' => 2);
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('filter2',$link);
		
		//datefilter
		$this->dateFilterWidget->setPage('keywordanalysis','top',$filter);
		$this->assign("widget_datefilter",$this->dateFilterWidget->show());
		$filter_date_from = $this->dateFilterWidget->from_date() != '' ? $this->dateFilterWidget->from_date() : $this->dateFilterWidget->getStartDate();
		$filter_to_date = $this->dateFilterWidget->to_date() != '' ? $this->dateFilterWidget->to_date() : $this->dateFilterWidget->getEndDate();
		
		$this->View->assign("filter_from_date",$filter_date_from);
		$this->View->assign("filter_to_date",$filter_to_date);
		
		$response = $this->api->getKeywordAnalysisList($_SESSION['campaign_id'],$this->user->account_id,$filter,$_SESSION['geo']);
		$data = json_decode($response);
		$kw = array();
		if(is_array($data)){
			foreach($data as $d){
				$kw[] = array("keyword_id"=>md5($d->keyword),"keyword"=>$d->keyword,"total"=>$d->total,"n_status"=>$d->n_status);
			}
		}
		$arrKey = '';
		$st = 0;
		if(sizeof($kw)>0){
			foreach($kw as $k){
				
				if($st > 0){
					$arrKey .= ",";
				}
				
				$arrKey .= "'".addslashes($k['keyword'])."'";
				
				$st++;
			}
			$this->View->assign('arrKey',$arrKey);
		}
		$this->View->assign('kw1',$kw);
		$data = null;
		$kw = null;
		
		//get Keyword List
		$response = $this->api->getTopKeywordStat($_SESSION['campaign_id'],$top,$filter_date_from,$filter_to_date,$_SESSION['language'],$_SESSION['geo']);
		
		$data = json_decode($response);
		
		//category
		$kategori = array();
		foreach($data as $d => $v){
			$kategori[] = '"'.$v->keyword.'"';
		}
		$kategori = implode(',',$kategori);
		$this->View->assign('kategori', $kategori);
		
		$kw = array();
		$men = array();
		$rt = array();
		$imp = array();
		foreach($data as $d => $v){
			$men[] = $v->mention;
			$rt[] = $v->rt_mention;
			$imp[] = $v->total_impression;
		}
		$kw['mentions'] = implode(',',$men);
		$kw['rt'] = implode(',',$rt);
		$kw['imp'] = implode(',',$imp);
		
		$this->View->assign('kw',$kw);
		
		$data_available = false;
		if(sizeof($data)>0){
			$data_available = true;
		}
		$this->View->assign("data_available",$data_available);
		
		if(strlen($_SESSION['geo'])==2){
			try{
				$market = ucfirst($this->api->getCountryName($_SESSION['geo']));
			}catch(Exception $e){
				
			}
		}
		$this->assign("market",$market);
		
		return $this->View->toString(APPLICATION.'/key-word-analysis-top.html');
	
	}
	
	function toggle_keyword(){
		$toggle = intval($this->Request->getParam("toggle"));
		$keyword_id = intval($this->Request->getParam("keyword_id"));
		$campaign_id = $_SESSION['campaign_id'];
		//we gonna use these in the future to validate the campaign ownership
		//$client_id = $this->user->account_id;
		//-->
		$sql = "UPDATE smac_web.tbl_campaign_keyword SET n_status=".$toggle." WHERE campaign_id=".$campaign_id." AND keyword_id=".$keyword_id;
		$this->open(0);
		$this->query($sql);
		$this->close();
		return $this->home();
	}
	function getmentions(){
		
		$ajax = intval($this->Request->getParam('ajax'));
		$start_date = mysql_escape_string($this->Request->getParam('start_date'));
		$end_date = mysql_escape_string($this->Request->getParam('end_date'));
		
		if($ajax == 1){
		
			$data = $this->api->getKeywordOvertime($this->user->account_id,$_SESSION['campaign_id'],
					$start_date,$end_date,$_SESSION['language'],$_SESSION['geo']);
			
			$data = json_decode($data);
			
			
			
			$xml = '<chart>';
			$xml .= '<categories>';
			foreach($data as $k => $v){
				if($v->label!=null){
					$xml.='<item><![CDATA['.$v->label.']]></item>';
				}else{
					$xml.='<item><![CDATA['.reformat_rule($v->keyword).']]></item>';
				}
			}
			$xml .= '</categories>';
			$xml .= '<series>';
			$xml .= '<name>mentions</name>';
			$xml .= '<data>';
			foreach($data as $k => $v){
				$xml .= '<point>'.$v->total_mention.'</point>';
			}
			$xml .= '</data></series>';
			$xml .= '</chart>';
			
		}
		
		header("Content-type: text/xml"); 
		print '<?xml version="1.0"?>';
		print $xml;
		exit;
	
	}
	
	function getkeywordimpovertime(){
	
		$ajax = intval($this->Request->getParam('ajax'));
		$start_date = mysql_escape_string($this->Request->getParam('start_date'));
		$end_date = mysql_escape_string($this->Request->getParam('end_date'));
		
		if($ajax == 1){
		
			$data = $this->api->getKeywordImpOvertime($this->user->account_id,$_SESSION['campaign_id'],$start_date,$end_date,$_SESSION['language'],$_SESSION['geo']);
			
			$data = json_decode($data);
			
			$xml = '<chart>';
			$xml .= '<categories>';
			foreach($data as $k => $v){
				if($v->label!=null){
					$xml.='<item><![CDATA['.htmlentities($v->label).']]></item>';
				}else{
					$xml.='<item><![CDATA['.htmlentities(reformat_rule($v->keyword)).']]></item>';
				}
			}
			$xml .= '</categories>';
			$xml .= '<series>';
			$xml .= '<name>Impression</name>';
			$xml .= '<data>';
			foreach($data as $k => $v){
				$xml .= '<point>'.$v->impression.'</point>';
			}
			$xml .= '</data></series>';
			$xml .= '</chart>';
			
		}
		
		header("Content-type: text/xml"); 
		print '<?xml version="1.0"?>';
		print $xml;
		exit;
	
	}
	
	function getkeywordpiiovertime(){
		
		$ajax = intval($this->Request->getParam('ajax'));
		$from_date = $this->Request->getParam("from_date");
		$to_date = $this->Request->getParam("to_date");
		
		if($ajax == 1){
			$data = $this->api->getPIIOvertime($this->user->account_id,$_SESSION['campaign_id'],$from_date,$to_date,$_SESSION['geo']);
			
			$data = json_decode($data);
			$xml = '<chart>';
			$xml .= '<categories>';
			foreach($data as $d){
				$xml.="<item>".((strtotime($d->tgl)*1000)+(60*60*24*1000))."</item>";
			}
			$xml .= '</categories>';
			$xml .= '<series>';
			$xml .= '<name>Performance Index</name>';
			$xml .= '<data>';
			foreach($data as $d){
				$xml .= '<point>'.round($d->pii_score,2).'</point>';
			}
			$xml .= '</data></series>';
			$xml .= '</chart>';
			
		}
		
		header("Content-type: text/xml"); 
		print '<?xml version="1.0"?>';
		print $xml;
		exit;
	
	}
	
	function getretweets(){
	
		$ajax = intval($this->Request->getParam('ajax'));
		$start_date = mysql_escape_string($this->Request->getParam('start_date'));
		$end_date = mysql_escape_string($this->Request->getParam('end_date'));
		
		if($ajax == 1){
		
			$data = $this->api->getKeywordRTOvertime($this->user->account_id,$_SESSION['campaign_id'],$start_date,$end_date,$_SESSION['language'],$_SESSION['geo']);
		
			$data = json_decode($data);
			
			$xml = '<chart>';
			$xml .= '<categories>';
			foreach($data as $k => $v){
				if($v->label!=null){
					$xml.='<item><![CDATA['.$v->label.']]></item>';
				}else{
					$xml.='<item><![CDATA['.reformat_rule($v->keyword).']]></item>';
				}
			}
			$xml .= '</categories>';
			$xml .= '<series>';
			$xml .= '<name>Retweets</name>';
			$xml .= '<data>';
			foreach($data as $k => $v){
				$xml .= '<point>'.$v->retweets.'</point>';
			}
			$xml .= '</data></series>';
			$xml .= '</chart>';
			
		}
		
		header("Content-type: text/xml"); 
		print '<?xml version="1.0"?>';
		print $xml;
		exit;
	}
	
	function getwords(){
		
		$ajax = intval($this->Request->getParam('ajax'));
		
		$data = array();
		
		if($ajax == 1){
			
			//get Keyword List
			$filter = intval($this->Request->getParam('filter'));
			
			if($filter==0){
				$kw = $this->api->getKeywordAnalysisWordcloud($_SESSION['campaign_id'],$_GET['key'],$_GET['exclude'],$_SESSION['geo']);
			}else{
				$kw = $this->api->getTop50Wordcloud($_SESSION['campaign_id'],$_GET['key'],$_GET['exclude'],$_SESSION['geo']);
				
			}
			$rs = array();
			$m=0;
			
			if(is_array($kw[0])){
				foreach($kw[0] as $w){
					$m = max($m,$w['occurance']);
					$mm = min($m,$w['occurance']);
					$rs[] = array("id"=>$w['id'],"txt"=>$w['related_keyword'],"occurance"=>$w['occurance'],"url"=>"#");
				}
			}
			$add  = array("text"=>"","weight"=>15);
			array_push($add,$rs);
			if(sizeof($rs)>0){
				foreach($rs as $n=>$v){
					$weight = @ceil(($rs[$n]['occurance']/($m-$mm))*9);
					$rs[$n]['weight'] = $weight;
					$rs[$n]['ratio'] = @($rs[$n]['occurance']/($m-$mm));
					$rs[$n]['max'] = $m;
					$rs[$n]['min'] = $mm;
				}
			}
			//$data['kw'] = $kw[0];
			
			//$data['total'] = $kw[1];
			
			//$data['success'] = 1;
			//echo json_encode($rs);
			$wordcloud = new wordcloudHelper(300,200);
			//$wordcloud->urlto="javascript:void(0);";
			$wordcloud->urlto="#?w=650&keyword=";
			//$wordcloud->callback_func="toggle_wordcloud";
			$wordcloud->setHandler("wcdiv".str_replace(" ","_",$_GET['key']));
			
			$wordcloud->set_sentiment_style(array("positive"=>"wcstat1","negative"=>"wcstat2","neutral"=>"wcstat0"));
			print $wordcloud->draw($rs,false);
			
		}else{
			
			//$data['success'] = 0;
			
		}
		
		//echo json_encode($data);
		exit;
	
	}
	/**
	 * Total Volume Overtime Model
	 * @return unknown_type
	 */
	function main_keyword_overtime(){
		header("content-type:application/json");
		
		if($this->Request->getParam("ajax")=="1"){
			if($_SESSION['geo']==null){
				$this->main_keyword_overtime_global();
			}else{
				$this->main_keyword_overtime_geo($_SESSION['geo']);
			}
		}
		die();
	}
	function main_keyword_overtime_geo($geo){
		$geo = mysql_escape_string($geo);
		
		$filter_date_from = $this->Request->getParam("from");
		$filter_to_date = $this->Request->getParam("to");
		$default_range = $this->Request->getParam("default_range");
		
		
		$campaign_id = $_SESSION['campaign_id'];
		
		$this->open(0);
		if(intval($default_range)==1){
			//retrieve only from the last 7 days
				$sql = "SELECT dtreport FROM smac_report.daily_country_volume 
						WHERE campaign_id={$campaign_id} AND country_id = '{$geo}'  
						GROUP BY dtreport ORDER BY dtreport DESC LIMIT 7";
				$r_date = $this->fetch($sql,1);
				$filter_date_from = date("Y-m-d 00:00:00",strtotime($r_date[sizeof($r_date)-1]['dtreport']));
				$filter_to_date = date("Y-m-d 23:59:59",strtotime($r_date[0]['dtreport']));
		}
		
		
		
		$sql = "SELECT a.keyword_id,
				SUM(total_mention) as total_mention,
				b.keyword_txt as keyword
				FROM smac_report.daily_country_volume a
				INNER JOIN smac_web.tbl_keyword_power b
				ON a.keyword_id = b.keyword_id
				WHERE 
				a.campaign_id={$campaign_id} 
				AND a.country_id='{$geo}' 
				AND a.dtreport BETWEEN '{$filter_date_from}' AND '{$filter_to_date}'
				GROUP by a.keyword_id 
				ORDER BY total_mention DESC 
				LIMIT 10;";
		
		$keywords = $this->fetch($sql,1);
		$all_dates = array();
		foreach($keywords as $n=>$v){
			$kw = mysql_escape_string(trim($v['keyword_id']));
			$sql = "SELECT dtreport as published_date,'{$v['keyword']}' as keyword,SUM(total_mention) as total_mention 
					FROM smac_report.daily_country_volume 
					WHERE campaign_id={$campaign_id} AND keyword_id={$kw}
					AND country_id='{$geo}' 
					AND dtreport BETWEEN '{$filter_date_from}' AND '{$filter_to_date}'
					GROUP by dtreport;";
			
			
			$data = $this->fetch($sql,1);
			foreach($data as $dn=>$dv){
				@$all_dates[$dv['published_date']] = 1;
				$data[$dn]['ts'] = strtotime($dv['published_date']);
			}
			$sql = "SELECT label FROM smac_web.tbl_campaign_keyword WHERE campaign_id={$campaign_id} AND keyword_id={$kw} LIMIT 1";
			$row = $this->fetch($sql);
			if(strlen($row['label'])>0){
				$keywords[$n]['keyword'] = trim(($row['label']));
			}else{
				$keywords[$n]['keyword'] = trim(translate_rule($keywords[$n]['keyword']));
			}
			$keywords[$n]['data'] = $data;
		}
		$this->close();
		foreach($keywords as $m=>$d){
			$keyword = $d;
			foreach($all_dates as $tgl=>$flag){
				$is_exist = false;
				foreach($keyword['data'] as $n=>$v){
					if($v['published_date']==$tgl){
						$is_exist = true;
					}
				}
				if(!$is_exist){
					$keywords[$m]['data'][]=array('published_date'=>$tgl,'total_mention'=>0,'keyword'=>'','ts'=>strtotime($tgl));
				}
			}
			//print "yey";
			$keywords[$m]['data'] = $this->subval_sort($keywords[$m]['data'],'ts');
		}
		print json_encode($keywords);
		$keywords = null;
	}
	function main_keyword_overtime_global(){
		$filter_date_from = $this->Request->getParam("from");
		$filter_to_date = $this->Request->getParam("to");
		$default_range = $this->Request->getParam("default_range");
		$campaign_id = $_SESSION['campaign_id'];

		$this->open(0);
		if(intval($default_range)==1){
			//retrieve only from the last 7 days
				$sql = "SELECT dtreport FROM smac_report.campaign_rule_volume_history 
						WHERE campaign_id={$campaign_id} GROUP BY dtreport ORDER BY dtreport DESC LIMIT 7";
				$r_date = $this->fetch($sql,1);
				$filter_date_from = $r_date[sizeof($r_date)-1]['dtreport'];
				$filter_to_date = $r_date[0]['dtreport'];
		}
		$sql = "SELECT a.keyword_id,b.keyword_txt as keyword,
				SUM(a.total_mention_twitter+a.total_mention_facebook+a.total_mention_web) as total_mention
				FROM smac_report.campaign_rule_volume_history a
				INNER JOIN smac_web.tbl_keyword_power b
				ON a.keyword_id = b.keyword_id
				WHERE a.campaign_id={$campaign_id} 
				AND dtreport BETWEEN '{$filter_date_from}' AND '{$filter_to_date}'
				GROUP BY a.keyword_id ORDER BY total_mention DESC LIMIT 10;";
		$keywords = $this->fetch($sql,1);
		$all_dates = array();
		foreach($keywords as $n=>$v){
			$kw = mysql_escape_string(trim($v['keyword_id']));
			
			$sql = "SELECT dtreport as published_date,'{$v['keyword']}' as keyword,
					SUM(a.total_mention_twitter+a.total_mention_facebook+a.total_mention_web) as total_mention 
					FROM smac_report.campaign_rule_volume_history a 
					WHERE a.campaign_id={$campaign_id} 
					AND keyword_id = {$kw} 
					AND dtreport BETWEEN '{$filter_date_from}' AND '{$filter_to_date}'
					GROUP BY dtreport;";
			
			$data = $this->fetch($sql,1);
			foreach($data as $dn=>$dv){
				@$all_dates[$dv['published_date']] = 1;
				$data[$dn]['ts'] = strtotime($dv['published_date']);
			}
			$sql = "SELECT label FROM smac_web.tbl_campaign_keyword WHERE campaign_id={$campaign_id} AND keyword_id={$kw} LIMIT 1";
			$row = $this->fetch($sql);
			if(strlen($row['label'])>0){
				$keywords[$n]['keyword'] = trim(($row['label']));
			}else{
				$keywords[$n]['keyword'] = trim(translate_rule($keywords[$n]['keyword']));
			}
			$keywords[$n]['data'] = $data;
		}
		$this->close();
		foreach($keywords as $m=>$d){
			$keyword = $d;
			foreach($all_dates as $tgl=>$flag){
				$is_exist = false;
				foreach($keyword['data'] as $n=>$v){
					if($v['published_date']==$tgl){
						$is_exist = true;
					}
				}
				if(!$is_exist){
					$keywords[$m]['data'][]=array('published_date'=>$tgl,'total_mention'=>0,'keyword'=>'','ts'=>strtotime($tgl));
				}
			}
			//print "yey";
			$keywords[$m]['data'] = $this->subval_sort($keywords[$m]['data'],'ts');
		}
		
		print json_encode($keywords);
		$keywords = null;
	}
	/**
	 * a helper function to help sorting an array based on its key's value
	 * @param $a
	 * @param $subkey
	 */
	function subval_sort($a,$subkey) {
		foreach($a as $k=>$v) {
			$b[$k] = strtolower($v[$subkey]);
		}
		asort($b);
		foreach($b as $key=>$val) {
			$c[] = $a[$key];
		}
		return $c;
	}
	

}
?>