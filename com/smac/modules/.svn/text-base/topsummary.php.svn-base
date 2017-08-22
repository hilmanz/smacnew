<?php
global $APP_PATH,$ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
class topsummary extends App{
	
	function home($total=20){
		if($this->Request->getParam("next")==1){
			if($this->Request->getParam('reload')!=1){
				sendRedirect("?".$_SERVER['QUERY_STRING']."&reload=1#tabs/rawPost");
				die();
			}
		}
		
		$this->assign('sidebar', $this->sidebarHelper->show() );
		
		$this->assign('menu', $this->menuHelper->showMenu(true) );
		
		//datefilter widget
		//datefilter
		$this->dateFilterWidget->setPage('topsummary');
		$this->assign("widget_datefilter",$this->dateFilterWidget->show());
		$filter_date_from = $this->dateFilterWidget->from_date() != '' ? $this->dateFilterWidget->from_date() : $this->dateFilterWidget->getStartDate();
		$filter_to_date = $this->dateFilterWidget->to_date() != '' ? $this->dateFilterWidget->to_date() : $this->dateFilterWidget->getEndDate();
		$this->View->assign("filter_from_date",$filter_date_from);
		$this->View->assign("filter_to_date",$filter_to_date);
		
		
		
		
		if(strlen($_SESSION['geo'])==2){
			try{
				$market = ucfirst($this->api->getCountryName($_SESSION['geo']));
			}catch(Exception $e){
				
			}
		}
		
		
		$this->assign("market",$market);
		
		$this->View->assign('data_available',1);
		$a = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'topsummary',
					'act' => "people_count","keywords"=>$sKeywords,'start_date'=>$filter_date_from,'end_date'=>$filter_to_date);
		
		$people_lookup_url = 'index.php?'.$this->Request->encrypt_params($a);
		$this->View->assign("people_lookup_url",$people_lookup_url);
		
		$raw  = $this->download_raw_list($this->Request->getParam('st'),$total);
		
		$this->View->assign("raw",$raw['rs']);
		$this->View->assign("total_raw",$raw['total']);
		$this->View->assign("topic_id",$_SESSION['campaign_id']);
		
		//pagination for raw download list
		$paging = new Paginate();
		$this->View->assign("paging",$paging->generate(intval($this->Request->getParam('st')),
															$total,
															$raw['total'],
															"index.php?page=topsummary&next=1"));
		
		
		
		return $this->View->toString(APPLICATION.'/top-summary.html');
	
	}
	function people_count(){
		$keywords = $this->Request->getParam('keywords');
		$start_date = $this->Request->getParam('start_date');
		$end_date = $this->Request->getParam('end_date');
		$this->api->set_keywords($keywords);
		$data = $this->api->topSummary($this->user->account_id,$_SESSION['campaign_id'],
							$_SESSION['language'],'getTotalPeoplePerKeyword',$_SESSION['geo'],$start_date,$end_date);
		header("content-type:application/json");
		print trim($data);
		die();
		
	}
	
	function topic_summary(){
		if($this->Request->getParam("ajax")=="1"){
			//Topic Summary
			$filter_date_from = $this->Request->getParam("filter_from_date");
			$filter_to_date = $this->Request->getParam("filter_to_date");
			//print $filter_date_from."->".$filter_to_date."<br/>";
			$data = json_decode($this->api->topSummary($this->user->account_id,
								$_SESSION['campaign_id'],$_SESSION['language'],
								'topic_summary',$_SESSION['geo'],$filter_date_from,$filter_to_date));
			$data = $data->data;
			
			$this->View->assign('impact',round($data->potential_impact_score/$data->mentions,2));
			//$this->View->assign('pii_change',$data->pii_score);
			$this->View->assign('mention',$data->mentions);
			$this->View->assign('impressi',$data->true_reach);
			print $this->View->toString("smac/summary-report/top-summary.html");
		}
		die();
	}
	function top_50_keywords(){
		if($this->Request->getParam("ajax")=="1"){
			$filter_date_from = $this->Request->getParam("filter_from_date");
			$filter_to_date = $this->Request->getParam("filter_to_date");
			$data = json_decode($this->api->topSummary($this->user->account_id,$_SESSION['campaign_id'],
								$_SESSION['language'],'top_keywords',$_SESSION['geo'],$filter_date_from,$filter_to_date));
							
			$data = $data->data;
			$keywords = $data->keywords;
			
			$arr = array();
			$sKeywords = "";
			$n=0;
			foreach($data as $k){
				if($n==1){
					$sKeywords.=",";
				}
				$kw = addslashes($k->keyword);
				$sKeywords.="'{$kw}'";
				$arr[] = array('word' => $k->keyword,'percent' => $k->occurance, 'total' => $k->total_people);
				$n=1;
			}
			$a = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'topsummary',
						'act' => "people_count","keywords"=>$sKeywords,'start_date'=>$filter_date_from,'end_date'=>$filter_to_date);
			
			
			//print_r($arr);exit; 
			if(intval(count($arr)) > 0){
				$this->View->assign('data_available',1);
			}
			$this->View->assign('top_keywords',$arr);
			print $this->View->toString("smac/summary-report/top-50-keyword.html");
		}
		die();
	}
	function top_5_conversation(){
		if($this->Request->getParam("ajax")=="1"){
			$filter_date_from = $this->Request->getParam("filter_from_date");
			$filter_to_date = $this->Request->getParam("filter_to_date");
			$data = json_decode($this->api->topSummary($this->user->account_id,$_SESSION['campaign_id'],$_SESSION['language'],'top_keyword_conversation',$_SESSION['geo'],$filter_date_from,$filter_to_date));
			$arr = array();
			$idx = 0;
			
			foreach($data->data as $k => $v){
				$arr[$idx] = array();
				$arr[$idx]['word'] = $k;
				$idx++;
			}
			$idx = 0;
			foreach($data->data as $k){
				$arr[$idx]['data'] = array();
				foreach($k as $v){
					$arr[$idx]['data'][] = array('author_id' => $v->author_id,
																'author_name' => $v->author_name,
																'author_avatar' => $v->author_avatar,
																'content' => $v->content,
																'keyword' => $v->keyword
																);
					
				}
				$idx++;
			}
			//exit;
			//print_r($arr);
			//exit;
			$this->View->assign('top_con',$arr);
			print $this->View->toString("smac/summary-report/top-5-keyword-conversation.html");
		}
		die();
	}
	function top_influencers(){
		if($this->Request->getParam("ajax")=="1"){
			$filter_date_from = $this->Request->getParam("filter_from_date");
			$filter_to_date = $this->Request->getParam("filter_to_date");
			$data = json_decode($this->api->topSummary($this->user->account_id,$_SESSION['campaign_id'],$_SESSION['language'],'top_influencers',$_SESSION['geo'],$filter_date_from,$filter_to_date));
			$ambassador = $data->data->ambassador;
			$troll = $data->data->troll;
			$favourable = array();
			$data = null;
			
			foreach($ambassador as $ambas){
				$favourable[] = array('author_id' => $ambas->author_id,
												'author_name' => $ambas->author,
												'author_avatar' => $ambas->pic
															);
			}
			$ambassador = null;
			
			$unfavourable = array();
			foreach($troll as $trol){
				$unfavourable[] = array('author_id' => $trol->author_id,
												'author_name' => $trol->author,
												'author_avatar' => $trol->pic
												);
			}
			$troll = null;
			
			$this->View->assign('favorable',$favourable);
			$this->View->assign('unfavorable',$unfavourable);
			print $this->View->toString("smac/summary-report/top-25-influencers.html");
		}
		die();
	}
	function key_issues(){
		if($this->Request->getParam("ajax")=="1"){
			$filter_date_from = $this->Request->getParam("filter_from_date");
			$filter_to_date = $this->Request->getParam("filter_to_date");
			$data = json_decode($this->api->topSummary($this->user->account_id,$_SESSION['campaign_id'],$_SESSION['language'],'key_issues',$_SESSION['geo'],$filter_date_from,$filter_to_date));
			$arr = $data->data;
			$issues = array();
			foreach($arr as $a){
				if($a->sentiment>0){
					$symbol = "plus";
				}else if($a->sentiment<0){
					$symbol = "min";
				}else{
					$symbol = "plus";
				}
				$issues[] = array('word' => $a->keyword,
												'occurance' => $a->occurance,
												'sentiment' => $symbol
												);
			}
			$data = null;
			$this->View->assign('issue',$issues);
			print $this->View->toString("smac/summary-report/key-issues.html");
		}
		die();
	}
	function brand(){
		if($this->Request->getParam("ajax")=="1"){
			$filter_date_from = $this->Request->getParam("filter_from_date");
			$filter_to_date = $this->Request->getParam("filter_to_date");
			//branded-twitter-account
			$data = json_decode($this->api->topSummary($this->user->account_id,$_SESSION['campaign_id'],$_SESSION['language'],'branded_account_report',$_SESSION['geo'],$filter_date_from,$filter_to_date));
			$num = count($data);
			
			//print $this->api->topSummary($this->user->account_id,$_SESSION['campaign_id'],$_SESSION['language'],'branded_account_report');
			$tweets = $data->data;
			
			
			$data2 = $this->objectToArray($data->data);
			
			//print_r($data2[0]);exit;
			//var_dump($data2);
			for($i=0;$i<$num;$i++){
			
				
				$branded = array();
				$branded['name'] = $data2[$i]->author_id;
				$branded['followers'] = $data2[$i]->stats->followers;
				$branded['mentions'] = $data2[$i]->stats->total_mentions;
				$branded['total_imp'] = $data2[$i]->stats->total_impression;
				$branded['rt_percent'] = $data2[$i]->stats->rt_percentage;
				$branded['rt_imp'] = $data2[$i]->stats->rt_impression;
				$branded['share'] = $data2[$i]->stats->share_percentage;
				$branded['rank'] = $data2[$i]->stats->rank;
				
				$branded['tweets'] = array();
				
				foreach($data2[$i]->tweets as $v){
					
					$branded['tweets'][] = array('author_id' => $v->author_id,
																	'feed_id' => $v->feed_id,
																	'imp' => $v->imp,
																	'rt' => $v->rt,
																	'rt_imp' => $v->rt_imp,
																	'published_date' => $v->published_date,
																	'content' => $v->content,
																	'author_name' => $v->author_name,
																	'author_avatar' => $v->author_avatar,
																	'share' => $v->share
																	);
					
				}
				
				$this->View->assign('branded',$branded);
				print $this->View->toString("smac/summary-report/branded-twitter-account.html");
			}
		}
		die();
	}
	function top_links(){
		if($this->Request->getParam("ajax")=="1"){
			$filter_date_from = $this->Request->getParam("filter_from_date");
			$filter_to_date = $this->Request->getParam("filter_to_date");
			$data = json_decode($this->api->topSummary($this->user->account_id,$_SESSION['campaign_id'],$_SESSION['language'],'top_50_links',$_SESSION['geo'],$filter_date_from,$filter_to_date));
			$arr = array();
			$idx = 0;
			
			
			foreach($data->data as $k){
				$arr[] = array("no"=>$idx+1,"url"=>$k->url,"impression"=>$k->impression);
				$idx++;
			}
			//var_dump($data->data);
			
			//exit;
			//print_r($arr);
			//exit;
			$this->View->assign('top_links',$arr);
			print $this->View->toString("smac/summary-report/top_links.html");
		}
		die();
	}
	function download_pdf(){
		global $APP_PATH;
		$status=0;
		if($this->Request->getParam("ajax")==1){
			require_once $APP_PATH . APPLICATION . "/helper/pdfHelper.php";
			$filename = "";
			$pdf = new pdfHelper();
			$types = explode(",",$this->Request->getParam("types"));
			$report_types = "";
			$n=0;
			
			foreach($types as $t){
				$t = trim($t);
				if(strlen($t)>0){
					if($n==1){
						$report_types.=",";
					}
					$report_types.=cleanXSS(($t));
					$n=1;
				}
			}
			$label = cleanXSS($this->Request->getParam("label"));
			$dt_from = explode("/",cleanXSS($this->Request->getParam("from")));
			$dt_until= explode("/",cleanXSS($this->Request->getParam("until")));
			$from = $dt_from[2]."-".$dt_from[1]."-".$dt_from[0];
			$until = $dt_until[2]."-".$dt_until[1]."-".$dt_until[0];
			$market = $_SESSION['geo'];
			if(strlen($market)==0){
				$market = 'global';
			}
		
			$filename = "{$market}-report-{$_SESSION['campaign_id']}".date("YmdHis").rand(1000,9999);
			if($pdf->addJob($_SESSION['campaign_id'],$label,$report_types,$filename,$from,$until,$market)){
				$status=1;
			}else{
				$status=0;
			}
		}
		print json_encode(array("status"=>$status));
		die();
	}
	function report_list(){
		global $APP_PATH;
		$campaign_id = $_SESSION['campaign_id'];
		$status=0;
		
		if($this->Request->getParam("ajax")==1){
			require_once $APP_PATH . APPLICATION . "/helper/pdfHelper.php";
			$market = $_SESSION['geo'];
			$start = intval(cleanXSS($this->Request->getParam("start")));
			$pdf = new pdfHelper();
			$reports = $pdf->getReports($campaign_id,$start);
			$a = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'topsummary','act'=>'report_list','market'=>$market,'ajax'=>1,'start'=>$start+30);
			$next_url = $this->Request->encrypt_params($a);
			if($start-30>=0){
				$a = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'topsummary','act'=>'report_list','market'=>$market,'ajax'=>1,'start'=>$start+30);
				$prev_url = $this->Request->encrypt_params($a);
			}
			if(is_array($reports)){
				foreach($reports as $n=>$v){
					
					$reports[$n]['file'] = "contents/".sha1("cmp#".$campaign_id)."/".$v['report_filename'].".pdf";
					if($v['n_status']!=2){
						$is_available=false;
					}else{
						$is_available=true;
					}
					$reports[$n]['isAvailable'] = $is_available;
				}
			}
			$this->View->assign("list",$reports);
			print $this->View->toString("smac/report_list.html");
		}
		//print json_encode(array("status"=>$status));
		die();
	}
	public function download_raw(){
		
		$channel = intval($this->_get('channel'));
		if($channel>0){
			return $this->single_raw_download_job($channel);
		}else{
			return $this->multi_raw_download_job();
		}
	}
	public function workflow_download_raw(){
		$q = $this->add_download_job(intval($this->_get('c')),
									2,
									intval($this->_get('t')),
									intval($this->_get('f'))
									);
							
		$a = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'topsummary');
		$urlto = 'index.php?'.$this->Request->encrypt_params($a);
		if($q){
			return $this->View->showMessage(text('download_raw_success'),$urlto);
		}else{
			return $this->View->showMessage(text('download_document_unavailable'),
											$urlto);
		}
	}
	private function multi_raw_download_job(){
		$a = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'topsummary');
		$urlto = 'index.php?'.$this->Request->encrypt_params($a);
		
		$this->add_download_job(1,intval($this->_get('type')),0,0);
		$this->add_download_job(2,intval($this->_get('type')),0,0);
		$this->add_download_job(3,intval($this->_get('type')),1,0);
		$this->add_download_job(3,intval($this->_get('type')),2,0);
		$this->add_download_job(3,intval($this->_get('type')),3,0);
		$this->add_download_job(3,intval($this->_get('type')),5,0);
		$this->add_download_job(3,intval($this->_get('type')),0,0);
		
		return $this->View->showMessage(text('download_raw_success'),$urlto);
		
	}
	private function single_raw_download_job($channel){
		$a = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'topsummary');
		$urlto = 'index.php?'.$this->Request->encrypt_params($a);
		if($this->add_download_job($channel,
									intval($this->_get('type')),
									intval($this->_get('group_type_id')),
									intval($this->_get('folder_id'))
									)
						
		){
			return $this->View->showMessage(text('download_raw_success'),$urlto);
		}else{
			return $this->View->showMessage(text('download_document_unavailable'),
											$urlto);
		}
	}
	private function add_download_job($channel,$job_type=1,$group_type_id=0,$folder_id=0){
		switch($channel){
			case 1:
				$prefix = "twitter";
			break;
			case 2:
				$prefix = "facebook";
			break;
			case 3:
				$prefix = "web";
			break;
			case 4:
				$prefix = "youtube";
			break;
			default:
				$prefix = "twitter";
			break;
		}
		$filename = "{$prefix}-{$_SESSION['campaign_id']}-{$group_type_id}".date("YmdHis").".csv";
		$sql = "INSERT INTO `smac_web`.`job_report_adhoc` 
				(
					`campaign_id`, 
					`channel`, 
					`group_type_id`, 
					`folder_id`, 
					`job_type`, 
					`last_id`, 
					`final_id`, 
					`filename`, 
					`n_status`, 
					`submit_date`, 
					`last_update`
				)
				VALUES
				(
					{$_SESSION['campaign_id']}, 
					{$channel}, 
					{$group_type_id}, 
					{$folder_id}, 
					{$job_type}, 
					0, 
					0, 
					'{$filename}', 
					0, 
					NOW(), 
					NOW()
				);";
		$this->open(0);
		$q = $this->query($sql);
		$this->close();
		if($q){
			return true;
		}
	}
	private function download_raw_list($start=0,$total = 1){
		$start = intval($start);
		$this->open(0);
		$sql = "SELECT 	`id`, 
				`campaign_id`, 
				`channel`, 
				`group_type_id`, 
				`folder_id`, 
				`job_type`, 
				`last_id`, 
				`final_id`, 
				`filename`, 
				`n_status`, 
				`submit_date`, 
				`last_update`
				FROM 
				`smac_web`.`job_report_adhoc` 
				WHERE campaign_id={$_SESSION['campaign_id']}
				ORDER BY id DESC
				LIMIT {$start}, {$total};";
				
		$rs = $this->fetch($sql,1);
		$rows = $this->fetch("SELECT COUNT(id) as total
				FROM 
				`smac_web`.`job_report_adhoc` 
				WHERE campaign_id={$_SESSION['campaign_id']}
				ORDER BY id DESC
				LIMIT 1");
		$this->close();
		
		if(is_array($rs)){
			foreach($rs as $n=>$v){
				$rs[$n]['submit_date'] = date("d/m/Y H:i:s",strtotime($v['submit_date']));
				if($v['n_status']==2&&$v['last_id']==$v['final_id']){
					$rs[$n]['progress'] = 100;
				}else{
					$rs[$n]['progress'] = floatval(@round($v['last_id']/$v['final_id']*100,2));	
				}
				$rs[$n]['download_dir'] = sha1("cmp#".$_SESSION['campaign_id']);
				$a = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'topsummary');
				$rs[$n]['api_call'] = 'index.php?'.$this->Request->encrypt_params(array("subdomain"=> $this->Request->getParam('subdomain'),
																	'page' => 'topsummary',
																	'act'=>'raw_progress',
																	'id'=>$rs[$n]['id']));
			}
		}
		return array("rs"=>$rs,"total"=>$rows['total']);
	}
	public function raw_progress(){
		$this->use_cache = false;
		$this->open(0);
		$sql = "SELECT 	
				`last_id`, 
				`final_id`,
				n_status
				FROM 
				`smac_web`.`job_report_adhoc` 
				WHERE id={$this->_get('id')}
				LIMIT 1;";
		$v = $this->fetch($sql);
		$this->close();
		$this->use_cache = true;
		if($v['n_status']==2&&$v['last_id']==$v['final_id']){
			print json_encode(array('progress'=>100));
		}else{
			if($v['final_id']>0){
				print json_encode(array('progress'=>round($v['last_id']/$v['final_id']*100,2)));
			}else{
				print json_encode(array('progress'=>0));
			}
		}
		die();
	}
	public function exclusive_reports($total = 20){
		
		$start = intval($this->Request->getParam('st'));
		$sql = "SELECT a.*,b.campaign_name as topic_name 
				FROM smac_web.tbl_report_files a INNER JOIN 
				smac_web.tbl_campaign b ON a.campaign_id = b.id
				WHERE a.account_id={$this->user->account_id} 
				AND a.campaign_id={$_SESSION['campaign_id']}
				LIMIT {$start},{$total}";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		if(is_array($rs)>0){
			foreach($rs as $n=>$v){
				$rs[$n]['submit_date'] = date("d/m/Y",strtotime($rs[$n]['upload_date']));
				$rs[$n]['download_dir'] = sha1("cmp#".$rs[$n]['campaign_id']);
			}
		}
		
		$sql = "SELECT COUNT(a.id) as total
				FROM smac_web.tbl_report_files a INNER JOIN 
				smac_web.tbl_campaign b ON a.campaign_id = b.id
				WHERE a.account_id={$this->user->account_id} 
				AND a.campaign_id={$_SESSION['campaign_id']}
				LIMIT 1";
		$rows = $this->fetch($sql);
		$this->close();
		// $rs[0]['total_row'] = $rows['total'];
		$this->View->assign("exc",$rs);
		
		//pagination
		$paging = new Paginate();
		$this->View->assign("paging",$paging->generate_json("exclusive_reports",
															$start,
															$total,
															$rows['total'],
															"index.php?page=topsummary&act=exclusive_reports&ajax=1"));
		print $this->View->toString("smac/admin/report/exclusive_report.html");
		die();
	}
}
?>	