<?php
global $APP_PATH,$ENGINE_PATH;
require_once $APP_PATH . APPLICATION . "/helper/menuHelper.php";
//require_once $APP_PATH . APPLICATION . "/helper/headerHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/sidebarHelper.php";
require_once $APP_PATH . APPLICATION . "/widgets/favoriteWordWidget.php";
require_once $APP_PATH . APPLICATION . "/widgets/keyOpinionWidget.php";
require_once $APP_PATH . APPLICATION . "/widgets/tabNetworkWidget.php";
require_once $APP_PATH . APPLICATION . "/helper/apiHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/MarketHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/RealtimeUpdater.php";
require_once $APP_PATH . APPLICATION . "/widgets/dateFilterWidget.php";
include_once $ENGINE_PATH."Utility/Paginate.php";
class sentiment extends App{
	
	var $Request;
	
	var $View;
	
	var $menuHelper;
	
	//var $headerHelper;
	
	var $sidebarHelper;
	var $dateFilterWidget;
	var $api;
	
	function __construct($req){
		
		$this->Request = $req;
		
		$this->View = new BasicView();
		
		$this->setVar();
		
		$this->menuHelper = new menuHelper($this->user,$req);
		
		//$this->headerHelper = new headerHelper($this->user,$req);
		
		//$this->favoriteWordWidget = new favoriteWordWidget($this->user,$req);
		
		$this->sidebarHelper = new sidebarHelper($this->user,$req);
		$this->dateFilterWidget = new dateFilterWidget($this->user,$req);
		if(strlen($_SESSION['geo'])>0){
			$this->api = new marketHelper();
		}else{
			$this->api = new apiHelper();
		}
		
		
	}
	
	function home(){
		
		$start = intval($this->Request->getParam('st'));
		$total = 0;
		$totalx = $this->api->getKeywordsTotal($this->user->account_id,$_SESSION['campaign_id'],$_SESSION['geo']);
		foreach($totalx->keyword as $k){
			$total = (int) $k->attributes()->total;
		}
		$data = $this->api->getKeywords($this->user->account_id,$_SESSION['campaign_id'],0,1000);
		
		
		$data_available = false;
		
		
		
		$keyword = array();
		
		foreach($data->keyword as $k){
			
			$keyword[] = array('id' => (int) $k->attributes()->id,
								'word' => (String) $k->attributes()->word,
								'category' => (String) $k->attributes()->category,
								'occurance' => (int) $k->attributes()->occurance,
								'value' => (int) $k->attributes()->value);
			
		}
		
		if(sizeof($keyword)>0){
			$data_available = true;
		}
		if($data_available){	
			
			$this->assign('keyword', $keyword);
			
			$data = $this->api->summaryImpact($this->user->account_id,$_SESSION['campaign_id']);
			
			$this->assign('impact_score', $data->impact );
			
			
			
			$data = json_decode($this->api->getSentimentTop($this->user->account_id,$_SESSION['campaign_id']));
			
			$data = $this->objectToArray($data);
			
			$dat = array();
			
			foreach($data as $k){
				$dat[] = $this->objectToArray($k);
			}
			
			$num = count($dat);
			
			for($i=0;$i<$num;$i++){
				$dat[$i]['total_number'] = number_format($dat[$i]['total']);
			}
			
			//print_r($dat);exit;
			$this->assign('kw', $dat );
		}
		
		
		$this->View->assign("data_available",$data_available);
		$this->assign('sidebar', $this->sidebarHelper->show() );
			
		$this->assign('menu', $this->menuHelper->showMenu() );
		//link sentiment
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'sentiment');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, 50, $total, $link));
		
		return $this->View->toString(APPLICATION.'/sentiment.html');
	
	}
	
	function getgraph(){
	
		$ajax = intval($this->Request->getParam('ajax'));
		
		//$person = "'vidialdiano','andreasjuliant','RollingStoneINA','onyitkawanku','JavaSoulnation','hafiyanfaza','Gita2313','djemima','theodoreedaniel','elvinamarleen'";
		//datefilter
		$this->dateFilterWidget->setPage('keywordanalysis');
		$filter_date_from = $this->dateFilterWidget->from_date() != '' ? $this->dateFilterWidget->from_date() : $this->dateFilterWidget->getStartDate();
		$filter_to_date = $this->dateFilterWidget->to_date() != '' ? $this->dateFilterWidget->to_date() : $this->dateFilterWidget->getEndDate();
		
		if($ajax == 1){
		
			$data = $this->api->getSentimentDaily($this->user->account_id,$_SESSION['campaign_id'],$filter_date_from,$filter_to_date,$_SESSION['geo']);
			
			$data = json_decode($data);
			
			//print_r($data);
			//print_r($data->positive);
			$positive = $this->objectToArray($data->positive);
			$negative = $this->objectToArray($data->negative);
			//print_r($positive);
			//exit;
			
			$xml = '<chart>';
			$xml .= '<categories>';
			
			$num = count($positive);
			$i = 0;
			
			foreach($positive as $k => $v){
				
				if($i == 0 && sizeof($positive)<=1 && sizeof($negative)<=1){
					
					//add minus 1 date
					$d = strtotime( '-1 day', strtotime($k) );
					$date = new DateTime( date( 'd-m-Y', $d) );
					$xml .= '<item>'.$date->format('Y-m-d').'</item>';
					
				}
				
				$date = new DateTime($k);
				$xml .= '<item>'.$date->format('Y-m-d').'</item>';
				
				if($i == ($num-1) ){
					
					//add plus 1 date
					$d = strtotime( '+1 day', strtotime($k) );
					$date = new DateTime( date( 'd-m-Y', $d) );
					$xml .= '<item>'.$date->format('Y-m-d').'</item>';	
					
				}
			
				$i++;
			}
			
			$xml .= '</categories>';
			
			$xml .= '<series>';
			$xml .= '<name>Favourable</name>';
			$xml .= '<data>';
			if(sizeof($positive)<=1 && sizeof($negative)<=1){
			$xml .= '<point>0</point>';
			}
			foreach($positive as $k => $v){
				$xml .= '<point>'.$v.'</point>';
			}
			
				$xml .= '<point>0</point>';
			
			$xml .= '</data></series>';
			
			$xml .= '<series>';
			$xml .= '<name>Unfavourable</name>';
			$xml .= '<data>';
			
			if(sizeof($positive)<=1 && sizeof($negative)<=1){
			$xml .= '<point>0</point>';
			}
			
			foreach($negative as $k => $v){
				$xml .= '<point>-'.$v.'</point>';
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
	
	function getsave(){
		
		$ajax = intval($this->Request->getParam('ajax'));
		$data = array();
		$data['success'] = 0;
		if($ajax == 1){
			$dat = $this->api->editSentiment($this->user->account_id,$_SESSION['campaign_id'],intval($_GET['id']),$_GET['value']);
			
			//print_r($data);exit;
			
			if(intval($dat->status) == 2){
				$updater = new RealtimeUpdater(null);
				if($updater->update_sentiment($_SESSION['campaign_id'],time()+60)){
					$data['success'] = 1;
				}
			}
		}
		
		echo json_encode($data);
		exit;
		
	}
	
	function getsentiment(){
		
		$ajax = intval($this->Request->getParam('ajax'));
		$data = array();
		
		if($ajax > 0){
			
			$aColumns = array( 'keyword', 'weight','occurance', 'weight');
		
			$totalx = $this->api->getKeywordsTotal($this->user->account_id,$_SESSION['campaign_id']);
			foreach($totalx->keyword as $k){
				$total = (int) $k->attributes()->total;
			}
			
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
			
			$dat = $this->api->getKeywords($this->user->account_id,$_SESSION['campaign_id'],$start,$limit,$sOrder,$sWhere);
			
			$data = array(
				"sEcho" => intval($_GET['sEcho']),
				"iTotalRecords" => $total,
				"iTotalDisplayRecords" => $total,
				"aaData" => array()
			);
			
			$keyword = array();
			foreach($dat->keyword as $k){
				/*
				$keyword[] = array('id' => (int) $k->attributes()->id,
									'word' => (String) $k->attributes()->word,
									'category' => (String) $k->attributes()->category,
									'occurance' => (int) $k->attributes()->occurance,
									'value' => (int) $k->attributes()->value);
				*/
				
				$idx = (int) $k->attributes()->id;
				
				$data['aaData'][] = array((String) $k->attributes()->word,
															'<div id="c-'.$idx.'">'.(String) $k->attributes()->category.'</div>',
															(int) $k->attributes()->occurance,
															'<div id="w-'.$idx.'">'.(int) $k->attributes()->value.'</div>',
															'<input id="btn-'.$idx.'" type="button" value="edit" onclick="javascript:sentiment.edit('.$idx.');" />
															<div id="edit-'.$idx.'" style="display:none;">
																<input type="text" id="val-'.$idx.'" name="put-'.$idx.'" size="3" value="'.(int) $k->attributes()->value.'" />
																<input type="button" value="save" onclick="javascript:sentiment.save('.$idx.');"/>
															</div>');
				
			}
			
		
		}else{
			$data['succes'] = 0;
		}
		
		echo json_encode($data);
		exit;
		
	}
	
	function getsentimenttweet(){
		
		$ajax = $this->Request->getParam('ajax');
		$data = array();
		if($ajax == 1){
			$start = intval($this->Request->getParam('start'));
			$perpage = intval($this->Request->getParam('perpage'));
			$date = mysql_escape_string($this->Request->getParam('day'));
			$type = intval($this->Request->getParam('type'));
			$dat = $this->api->getSentimentTweet($_SESSION['campaign_id'],$start,$perpage,$date,$type,$this->Request->getParam('geo'));
			
			echo $dat;
			exit;
			
		}else{
			$data['succes'] = 0;
		}
		
		echo json_encode($data);
		exit;
		
	}
	
	function getlisttweet(){
		
		$ajax = $this->Request->getParam('ajax');
		$data = array();
		if($ajax == 1){
			$start = intval($this->Request->getParam('start'));
			$perpage = intval($this->Request->getParam('perpage'));
			$date = mysql_escape_string($this->Request->getParam('day'));
			$type = intval($this->Request->getParam('type'));
			$dat = $this->api->getSentimentTweet($_SESSION['campaign_id'],$start,$perpage,$date,$type,$this->Request->getParam('geo'));
			
			echo $dat;
			exit;
			
		}else{
			$data['succes'] = 0;
		}
		
		echo json_encode($data);
		exit;
		
	}

}
?>