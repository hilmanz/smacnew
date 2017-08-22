<?php
class MarketHelper extends Application{
	
	var $baseurl = '';
	
	var $parameter = array();
	
	var $url = '';
	
	var $auth_url = '';
	var $timeout = 30;
	var $keywords = "";
	function __construct($req=null){
		
		global $CONFIG;
		$this->Request = $req;
		$this->apiurl =  $CONFIG['API_BASEURL'];
		$this->baseurl = $CONFIG['MARKET_API_BASEURL'];
		$this->auth_url = $CONFIG['AUTH_API'];
	}
	
	function set(){
	}
	
	function goPost(){
		global $logger;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch,CURLOPT_TIMEOUT,$this->timeout);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY); 
		curl_setopt($ch,CURLOPT_POST,1); 
		curl_setopt($ch,CURLOPT_POSTFIELDS,$this->parameter);
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec ($ch);
		$logger->info(json_encode($this->parameter));
		$logger->info(json_encode(curl_getinfo($ch)));
		curl_close ($ch);
		
		return $response;
	}
	
	function goGet($debug=null){
	
		if(count($this->parameter) > 0){
			$this->url .= "?".http_build_query($this->parameter);
		}
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL,$this->url);
		curl_setopt($ch,CURLOPT_TIMEOUT,$this->timeout);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		//curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY); 
		
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$response = curl_exec ($ch);
		$info = curl_getinfo($ch);
		curl_close ($ch);
		
		if($debug){
			print 'url: ' . $this->url . '<br />';
			print_r($this->parameter);
			print '<br />';
			var_dump($info);
			print $response;exit;
		}
		
		return $response;
		
	}
	function goLogin($username,$password,$subdomain){
		/*
		$this->url = $this->baseurl . 'login.php';
		$this->parameter = array("username"=>$username,"password"=>$password);
		$doc = new SimpleXmlElement($this->goPost(), LIBXML_NOCDATA);
		return intval($doc->status);
		*/
		
		$this->url = $this->auth_url;
		$this->parameter = array("method"=>"user_login","email"=>$username,"password"=>$password,
						"subdomain"=>$subdomain,"account_id"=>$this->get_subdomain_owner());
		
		$response = json_decode($this->goGet());
		
		if($response->status==1){
			return $response;
		}
	}
	
	function getCampaignList($client_id=null){
		
		$this->url = $this->apiurl . 'campaign_list.php?client_id='.$client_id;
		
		//$doc = new SimpleXmlElement($this->goGet(), LIBXML_NOCDATA);
		$doc = simplexml_load_string($this->goGet());
		
		return $doc;
		
	}
	function set_keywords($keywords){
		$this->keywords = $keywords;
	}
	function topSummary($client_id=null,$campaign_id=null,$language=null,$action=null,$geo='',$start_date='',$end_date=''){
		
		$this->url = $this->baseurl . 'summary.php?client_id='.$client_id.'&campaign_id='.$campaign_id.'&lang='.$language.'&action='.$action."&geo={$geo}&start_date={$start_date}&end_date={$end_date}";
		
		$doc = $this->goGet();
		
		//echo $this->url;exit;
		
		return $doc;
		
	}
	
	function summaryImpact($client_id=null,$campaign_id=null,$lang='all',$geo=''){
		$this->url = $this->baseurl . 'impact.php?client_id='.$client_id.'&campaign_id='.$campaign_id.'&lang='.$lang.'&geo='.$geo;
		$response = $this->goGet();
		$doc = simplexml_load_string($response);
		
		//print $response;
		return $doc;
	}
	function summaryImpactDaily($client_id=null,$from_date,$to_date,$campaign_id=null,$lang='all',$geo=''){
		$this->url = $this->baseurl . 'impact_daily.php?client_id='.$client_id.'&campaign_id='.$campaign_id.
									  '&from='.$from_date.'&to='.$to_date.'&lang='.$lang.'&geo='.$geo;
		
		$doc = simplexml_load_string($this->goGet());
		return $doc;
	}
	function getWordcloud($client_id=null,$campaign_id=null,$lang='all',$geo='',$from='',$to='',$limit=50){
	
		//http://smac.me/flex/wordcloud.php?client_id=5&campaign_id=16&limit=50
		$this->url = $this->baseurl . 'wordcloud.php?client_id='.$client_id.'&campaign_id='.$campaign_id.'&lang='.$lang.'&limit='.$limit."&geo={$geo}&from={$from}&to={$to}";
		
		//$doc = simplexml_load_string($this->goGet());
		$doc = $this->goGet();
		//print_r($doc);exit;
		
		return $doc;
	
	}
	function getLivetrackWordcloud($client_id=null,$campaign_id=null,$lang='all',$reset=0,$limit=50){
		$this->url = $this->baseurl . 'livetrack_wordcloud.php?client_id='.$client_id.'&campaign_id='.$campaign_id.'&lang='.$lang.'&reset='.$reset.'&limit='.$limit;
		$doc = $this->goGet();		
		return $doc;
	
	}
	
	function getSummaryOpinion($client_id=null,$campaign_id=null,$lang='all',$geo=''){
		
		//http://smac.me/flex/summary-opinion.php?client_id=5&campaign_id=16
		
		$this->url = $this->baseurl . 'summary-opinion.php?client_id='.$client_id.'&campaign_id='.$campaign_id.'&lang='.$lang."&geo={$geo}";
		
		
		$doc = simplexml_load_string($this->goGet());
		
		//print_r($doc);exit;
		
		return $doc;
	
	}
	function getSummaryOpinionDaily($client_id=null,$from_date,$to_date,$campaign_id=null,$lang='all',$geo=''){
		
		//http://smac.me/flex/summary-opinion.php?client_id=5&campaign_id=16
		
		$this->url = $this->baseurl . 'summary-opinion-daily.php?client_id='.$client_id.'&from='.$from_date.'&to='.$to_date.'&campaign_id='.$campaign_id.'&lang='.$lang."&geo={$geo}";
		
		$doc = simplexml_load_string($this->goGet());
		
		//print_r($doc);exit;
		
		return $doc;
	
	}
	function getTwitterList($client_id=null,$campaign_id=null,$lang='all',$limit=5,$geo=''){
		$this->url = $this->baseurl . 'important-conversation.php?client_id='.$client_id.'&campaign_id='.$campaign_id.'&lang='.$lang.'&limit='.$limit."&geo={$geo}";
		$doc = simplexml_load_string($this->goGet());
		
		//print_r($doc);exit;
		
		return $doc;
	
	}
	function getTwitterDailyList($client_id=null,$from_date,$to_date,$campaign_id=null,$lang='all',$limit=5,$geo=''){
	
		//http://smac.me/flex/important-conversation.php?client_id=5&campaign_id=16&limit=10
		
		$this->url = $this->baseurl . 'important-conversation-daily.php?client_id='.$client_id.'&campaign_id='.$campaign_id.'&from='.$from_date.'&to='.$to_date.'&lang='.$lang.'&limit='.$limit."&geo={$geo}";
	
		$doc = simplexml_load_string($this->goGet());
		
		//print_r($doc);exit;
		
		return $doc;
	
	}
	function getFBList($client_id=null,$campaign_id=null,$lang='all',$limit=5){
		global $logger;
		//http://smac.me/flex/important-conversation.php?client_id=5&campaign_id=16&limit=10
		
		$this->url = $this->baseurl . 'fb-important-conversation.php?client_id='.$client_id.'&campaign_id='.$campaign_id.'&lang='.$lang.'&limit='.$limit;
		$str = $this->goGet();
		//$logger->info($_SESSION['campaign_id']."-fb\n".$str."\n");
		//$doc = simplexml_load_string($str);
		
		//print_r($doc);exit;
		
		//return $doc;
		return $str;
	
	}
	function getFBDailyList($client_id=null,$from_date,$to_date,$campaign_id=null,$lang='all',$limit=5){
	
		//http://smac.me/flex/important-conversation.php?client_id=5&campaign_id=16&limit=10
		
		$this->url = $this->baseurl . 'fb-important-conversation-daily.php?client_id='.$client_id.'&campaign_id='.$campaign_id.'&from='.$from_date.'&to='.$to_date.'&lang='.$lang.'&limit='.$limit;
		
		//$doc = simplexml_load_string($this->goGet());
		
		//print_r($doc);exit;
		
		return $this->goGet();
	
	}
	function getBlogList($client_id=null,$campaign_id=null,$limit=5,$geo=''){
	
		//http://smac.me/flex/important-conversation.php?client_id=5&campaign_id=16&limit=10
		
		$this->url = $this->baseurl . 'blog-important-conversation.php?client_id='.$client_id.'&campaign_id='.$campaign_id.'&limit='.$limit."&geo={$geo}";
		
		//$doc = simplexml_load_string($this->goGet());
		return $this->goGet();
		//print_r($doc);exit;
		
		//return $doc;
	
	}
	function getSourceCount($client_id=null,$from_date,$to_date,$campaign_id=null,$lang='all',$geo=''){
	
	
		//http://smac.me/flex/important-conversation.php?client_id=5&campaign_id=16&limit=10
		
		$this->url = $this->baseurl . 'source.php?client_id='.$client_id.'&campaign_id='.$campaign_id.'&lang='.$lang."&geo={$geo}&from=".$from_date."&to=".$to_date;
		
		
		$doc = simplexml_load_string($this->goGet());
		
		//print_r($doc);exit;
		
		return $doc;
	
	
	}
	function womTotalMentions($client_id=null,$campaign_id=null,$lang='all',$exclude=0,$geo=''){
	
		//http://smac.me/flex/wom-categories-total.php?client_id=5&campaign_id=16&limit=10
		
		$this->url = $this->baseurl . 'wom-categories-total.php?client_id='.$client_id.'&campaign_id='.$campaign_id.'&lang='.$lang."&exclude=".$exclude."&geo=".$geo;
		
		$doc = simplexml_load_string($this->goGet());
		
		
		return $doc;
	
	}
	
	function womAmbassador($client_id=null,$campaign_id=null,$lang='all',$exclude=0,$geo=''){
	
		//http://smac.me/flex/wom-ambassador.php?client_id=5&campaign_id=16
		
		$this->url = $this->baseurl . 'wom-ambassador.php?client_id='.$client_id.'&campaign_id='.$campaign_id."&lang=".$lang."&exclude=".$exclude."&geo={$geo}";
		
		$doc = simplexml_load_string($this->goGet());
		
		//print_r($doc);exit;
		
		return $doc;
	
	}
	
	function livetrackStatus($client_id=null,$campaign_id=null){
	
		//http://smac.me/flex/activation-total-stats.php?client_id=5&campaign_id=16
		
		$this->url = $this->baseurl . 'activation-total-stats.php?client_id='.$client_id.'&campaign_id='.$campaign_id;
		
		$doc = simplexml_load_string($this->goGet());
		
		return $doc;
	
	}
	function livestreamstats($client_id=null,$campaign_id=null){
	
		//http://smac.me/flex/activation-total-stats.php?client_id=5&campaign_id=16
		
		$this->url = $this->baseurl . 'livestream_stats.php?client_id='.$client_id.'&campaign_id='.$campaign_id;
		
		return $this->goGet();
	
	}
	function getKeywords($client_id=null,$campaign_id=null,$start=null,$limit=null,$order='',$where=''){
	
		$this->url = $this->baseurl . 'keyword.php?client_id='.$client_id.'&campaign_id='.$campaign_id.'&start='.$start.'&total='.$limit.'&order='.urlencode($order).'&where='.urlencode($where);
		
		$doc = simplexml_load_string($this->goGet());
		
		//echo $this->url;//exit;
		//print_r($doc);
		//exit;
		
		return $doc;
	
	}
	
	function getKeywordsTotal($client_id=null,$campaign_id=null,$geo=''){
	
		$this->url = $this->baseurl . 'keyword-total.php?client_id='.$client_id.'&campaign_id='.$campaign_id."&geo={$geo}";
		
		$doc = simplexml_load_string($this->goGet());
		
		return $doc;
	
	}
	
	function getAllPeopleTotal($client_id=null,$campaign_id=null,$lang='all',$exclude=0,$geo=''){
	
		$this->url = $this->baseurl . 'all-people-total.php?client_id='.$client_id.'&campaign_id='.$campaign_id.'&exclude='.$exclude."&geo={$geo}";
		
		$doc = $this->goGet();
		
		//echo $doc;exit;
		
		return $doc;
	
	}
	
	function getAllPeople($client_id=null,$campaign_id=null,$lang='all',$start=null,$limit=null,$order='',$where='',$exclude=0,$geo=''){
		if($lang=='all'){
			$this->url = $this->baseurl . 'all-people.php?client_id='.$client_id.'&campaign_id='.$campaign_id.'&lang='.$lang.'&start='.$start.'&total='.$limit.'&order='.urlencode($order).'&where='.urlencode($where).'&exclude='.$exclude."&geo={$geo}";
		}else{
			$this->url = $this->baseurl . 'all-people-lang.php?client_id='.$client_id.'&campaign_id='.$campaign_id.'&lang='.$lang.'&start='.$start.'&total='.$limit.'&order='.urlencode($order).'&where='.urlencode($where)."&geo={$geo}";
		}
		
		//echo $this->url;
		//echo $doc;
		//exit;
		
		$doc = $this->goGet();
		
		return $doc;
	
	}
	
	function getTopData($client_id=null,$campaign_id=null,$lang='all',$exclude=0,$geo){
	
		$this->url = $this->baseurl . 'wom-topdata.php?client_id='.$client_id.'&campaign_id='.$campaign_id."&lang=".$lang."&exclude=".$exclude."&geo={$geo}";
		
		$doc = simplexml_load_string($this->goGet());
		
		return $doc;
	
	}
	
	function getProfile($person=null,$campaign_id=null,$client_id=null){
		
		//http://smac.me/api/top-profile.php?person=hafiyanfaza&campaign_id=16
		
		$this->url = $this->baseurl . 'top-profile.php?person='.$person.'&campaign_id='.$campaign_id.'&client_id='.$client_id;
		
		$doc = simplexml_load_string($this->goGet());
		
		return $doc;
		
	}
	
	function getProfileWordcloud($person=null,$campaign_id=null){
	
		//http://smac.me/api/wom-popup-profile-wordcloud.php?person=hafiyanfaza&campaign_id=16
		
		$this->url = $this->baseurl . 'wom-popup-profile-wordcloud.php?person='.$person.'&campaign_id='.$campaign_id;
		
		//$doc = simplexml_load_string($this->goGet());
		$doc = json_decode($this->goGet());
		return $doc;
	
	}
	function getPersonGlobalWordcloud($person=null,$campaign_id=null,$type=0){
	
		//http://smac.me/api/wom-popup-profile-wordcloud.php?person=hafiyanfaza&campaign_id=16
		
		$this->url = $this->baseurl . 'profile_wordcloud.php?person='.$person.'&campaign_id='.$campaign_id.'&type='.$type;
		
		//$doc = simplexml_load_string($this->goGet());
		$doc = json_decode($this->goGet());
		return $doc;
	
	}
	function getFeed($client_id=null,$campaign_id=null,$last_id=null){
	
		//http://smac.me/api/feed.php?client_id=5&campaign_id=16&last_id=45126
		
		$this->url = $this->baseurl . 'feed.php?client_id='.$client_id.'&campaign_id='.$campaign_id.'&last_id='.$last_id;
		
		$doc = simplexml_load_string($this->goGet());
		
		return $doc;
	
	}
	function getMapFeed($client_id=null,$campaign_id=null,$last_id=null){
	
		//http://smac.me/api/feed.php?client_id=5&campaign_id=16&last_id=45126
		
		$this->url = $this->baseurl . 'map-feed.php?client_id='.$client_id.'&campaign_id='.$campaign_id.'&last_id='.$last_id;
		
		$doc = simplexml_load_string($this->goGet());
		
		return $doc;
	
	}
	function getMapFeedHistorical($client_id=null,$campaign_id=null,$last_id=null,$from_date=null,$to_date=null,$limit=10,$geo=''){
	
		//http://dev.smac.me/api/historical.php?campaign_id=16&client_id=5&from_date=2011-01-01&to_date=2011-12-01&last_id=0&limit=10
		
		if($from_date == null || $from_date == '') $from_date = date('Y-m-d', mktime(0, 0, 0, date("m"),date("d")-1,date("Y")));
		
		if($to_date == null || $to_date == '') $to_date = date('Y-m-d');
		
		$this->url = $this->baseurl . 'historical.php?client_id='.$client_id.'&campaign_id='.$campaign_id.'&last_id='.$last_id.'&from_date='.$from_date.'&to_date='.$to_date.'&limit='.$limit."&geo={$geo}";
		
		//echo $this->url;exit;
		
		$doc = simplexml_load_string($this->goGet());
		
		return $doc;
	
	}
	function getAllFeeds($client_id=null,$campaign_id=null,$last_id=null,$from_date=null,$to_date=null,$start=0,$limit=10){
		
		$this->url = $this->baseurl . 'historical_all_data.php?client_id='.$client_id.'&campaign_id='.$campaign_id.'&last_id='.$last_id.'&from_date='.$from_date.'&to_date='.$to_date.'&start='.$start.'&limit='.$limit;
		$doc = simplexml_load_string($this->goGet());
		//echo $this->url;exit;
		return $doc;
	}
	function getCampaignListInfo($client_id=null){
	
		//http://www.smac.me/api/campaign_list.php?client_id=5
		
		$this->url = $this->baseurl . 'campaign_list.php?client_id='.$client_id;
		
		$doc = simplexml_load_string($this->goGet());
		
		return $doc;
	
	}
	
	function getAccountUsage($client_id=null){
	
		//http://www.smac.me/api/account_usage.php?client_id=5
		
		$this->url = $this->baseurl . 'account_usage.php?client_id='.$client_id;
		
		$doc = @simplexml_load_string($this->goGet());
		
		return $doc;
	
	}
	function getTopicUsage($campaign_id=null){
	
		//http://www.smac.me/api/account_usage.php?client_id=5
		
		$this->url = $this->baseurl . 'topic_usage.php?campaign_id='.$campaign_id;
		
		$doc = @simplexml_load_string($this->goGet());
		
		return $doc;
	
	}
	function profileImpactScore($client_id=null,$campaign_id=null,$person=null){
	
		//http://www.smac.me/api/profile_impact_score.php?campaign_id=16&client_id=5&person=AmarshaDrn
		
		$this->url = $this->baseurl . 'profile_impact_score.php?client_id='.$client_id.'&campaign_id='.$campaign_id.'&person='.$person;
		
		//echo $this->url;exit;
		
		$doc = simplexml_load_string($this->goGet());
		
		return $doc;
	
	}
	
	function getSentimentDaily($client_id=null,$campaign_id=null,$from='',$to='',$geo=''){
		
		//http://smac.me/api/sentiment_mention_daily.php?campaign_id=16&client_id=5
		
		$this->url = $this->baseurl . 'sentiment_mention_daily.php?client_id='.$client_id.'&campaign_id='.$campaign_id."&geo={$geo}&from={$from}&to={$to}";
		
		$doc = $this->goGet();
		
		return $doc;
		
	}
	
	function getSentimentTweet($campaign_id=null,$start=0,$perpage=5,$date=null,$type=0,$geo=''){
		
		//http://dev.smac.me/api/sentiment_tweet.php?campaign_id=16&type=1&start=0&day=2011-09-23&perpage=5
		
		$this->url = $this->baseurl . 'sentiment_tweet.php?campaign_id='.$campaign_id.'&type='.$type.'&start='.$start.'&day='.$date.'&perpage='.$perpage."&geo={$geo}";
		
		//echo $this->url;exit;
		
		$doc = $this->goGet();
		
		return $doc;
		
	}
	
	function getProfileTweets($campaign_id=null,$start=0,$perpage=5,$person=null){
		
		$this->url = $this->baseurl . 'people_tweet.php?campaign_id='.$campaign_id.'&start='.$start.'&person='.$person.'&perpage='.$perpage;
		
		$doc = $this->goGet();
		
		return $doc;
		
	}
	function getProfileTweetsAll($campaign_id=null,$start=0,$perpage=5,$person=null){
		
		$this->url = $this->baseurl . 'people_tweet_all.php?campaign_id='.$campaign_id.'&start='.$start.'&person='.$person.'&perpage='.$perpage;
		
		$doc = $this->goGet();
		
		return $doc;
		
	}
	function editSentiment($client_id=null,$campaign_id=null,$id=null,$value=null){
		
		$this->url = $this->baseurl . 'keyword-service.php?client_id='.$client_id;
		$this->parameter = array("act"=>"edit",'id' => $id, 'value' => $value, 'campaign_id' => $campaign_id);
		
		$doc = simplexml_load_string($this->goPost());
		
		return $doc;
		
	}
	
	function getKeywordOvertime($client_id=null,$campaign_id=null,$from=null,$to=null,$lang='all',$geo=''){

		
		//http://smac.me/api/keyword_over_time.php?campaign_id=16&client_id=5
		
		$this->url = $this->baseurl . 'keyword_over_time.php?client_id='.$client_id.'&campaign_id='.$campaign_id.'&from='.$from.'&to='.$to.'&lang='.$lang."&geo={$geo}";
		
		//echo $this->url;exit;
		
		$doc = $this->goGet();
		
		return $doc;
		
	}
	function getKeywordImpOvertime($client_id=null,$campaign_id=null,$from=null,$to=null,$lang='all',$geo=''){
		
		//http://smac.me/api/keyword_over_time.php?campaign_id=16&client_id=5
		
		$this->url = $this->baseurl . 'keyword_imp_over_time.php?client_id='.$client_id.'&campaign_id='.$campaign_id.'&from='.$from.'&to='.$to.'&lang='.$lang."&geo={$geo}";
		
		$doc = $this->goGet();
		
		return $doc;
		
	}
	function getKeywordPiiOvertime($client_id=null,$campaign_id=null){

		
		//http://smac.me/api/keyword_over_time.php?campaign_id=16&client_id=5
		
		$this->url = $this->baseurl . 'keyword_pii_over_time.php?client_id='.$client_id.'&campaign_id='.$campaign_id;
		
		$doc = $this->goGet();
		
		return $doc;
		
	}
	function getPIIOvertime($client_id=null,$campaign_id=null,$from_date=null,$to_date=null,$geo=''){

		
		//http://smac.me/api/keyword_over_time.php?campaign_id=16&client_id=5
		
		$this->url = $this->baseurl . 'pii_over_time.php?client_id='.$client_id.'&campaign_id='.$campaign_id."&from=".$from_date."&to=".$to_date."&geo={$geo}";
		
		$doc = $this->goGet();
		
		return $doc;
		
	}
	function getKeywordRTOvertime($client_id=null,$campaign_id=null,$from=null,$to=null,$lang='all',$geo=''){
		
		//http://smac.me/api/rt_over_time.php?campaign_id=16&client_id=5
		
		$this->url = $this->baseurl . 'rt_over_time.php?client_id='.$client_id.'&campaign_id='.$campaign_id.'&from='.$from.'&to='.$to.'&lang='.$lang."&geo={$geo}";
		
		$doc = $this->goGet();
		
		return $doc;
		
	}
	
	function getKeywordAnalysisList($campaign_id=null,$client_id=null,$filter=0,$geo=''){
		
		$this->url = $this->baseurl . 'keyword_analysis_list.php?client_id='.$client_id.'&campaign_id='.$campaign_id."&filter=".$filter."&geo={$geo}";
		
		$doc = $this->goGet();
		return $doc;
		
	
	}
	
	function getTopKeywordStat($campaign_id=null,$top=10,$from=null,$to=null,$lang='all',$geo=''){
		$this->url = $this->baseurl . 'top_keyword_stats.php?campaign_id='.$campaign_id.'&top='.$top.'&from='.$from.'&to='.$to.'&lang='.$lang."&geo={$geo}";
		$doc = $this->goGet();
		return $doc;
	}
	function getTop50Wordcloud($campaign_id=null,$key=null,$excludes=null,$geo=''){
		if($excludes!=NULL){
			$arr = explode(",",$excludes);
			$str = "";
			$n=0;
			foreach($arr as $a){
				if($n==1){
					$str.=",";
				}
				$str .= "'".(mysql_escape_string(trim($a)))."'";
				$n=1; 
			}
			$strExclude = " AND related NOT IN (".($str).")";
		}
		$key = mysql_escape_string($key);
		$sql = "SELECT id,keyword,related as related_keyword,total as occurance 
				FROM smac_market.campaign_top50_wordcloud
				WHERE keyword='".$key."'".$strExclude." AND campaign_id=$campaign_id AND geo='{$geo}'
				ORDER BY total DESC
				LIMIT 100;";
		
		
		$this->open(0);
		
		if($this->is_new_schema($campaign_id)==1){
			$DATABANK = "smac_word.campaign_words_databank_{$campaign_id}";
		}else{
			$DATABANK = "smac_report.campaign_words_databank";
		}
		
		$sql = "SELECT fid FROM {$DATABANK} 
				WHERE campaign_id={$campaign_id}
				AND geo='{$geo}' AND keyword='{$key}'";
		//print $sql."<br/>";
		
		$rows = $this->fetch($sql,1);
		$fid = "";
		$n=0;
		foreach($rows as $row){
			if($row['fid']>0){
				if($n==1){
					$fid.=",";
				}
				$fid.="'{$row['fid']}'";
				$n=1;
			}
		}
		$rows = null;
		if($n==1){
			$sql = "SELECT id,'{$key}' as keyword,keyword as related_keyword,COUNT(keyword) as occurance 
				FROM $DATABANK 
				WHERE campaign_id={$campaign_id}
				AND geo='{$geo}' AND fid IN ({$fid}) AND keyword NOT IN ('{$key}')
				GROUP BY keyword
				ORDER BY occurance DESC LIMIT 100";
			$rs = $this->fetch($sql,1);
			$fid = null;
		}
		//print $sql;
		//$rs = $this->fetch($sql,1);
		
		//$rs2 = $this->fetch($sql2);
		
		$this->close();
		
		return array($rs);
		
	}
	function getKeywordAnalysisWordcloud($campaign_id=null,$key=null,$excludes=null,$geo=''){
		$campaign_id = mysql_escape_string($campaign_id);
		$geo = mysql_escape_string($geo);
		$key = mysql_escape_string($key);
		
	
		$sql = "SELECT campaign_id,keyword_id,keyword as related_keyword,occurance 
				FROM smac_report.top_rule_wordcloud_summary a
				WHERE 
				a.campaign_id={$campaign_id} 
				AND 
				keyword_id=$key 
				AND geo='{$geo}'
				ORDER BY occurance DESC 
				LIMIT 100;";
		
		//temporary solution until the dummy data in dev ready to use above query.
		$sql = "SELECT 
				{$campaign_id} as campaign_id,
				keyword_id,
				keyword as related_keyword,
				SUM(occurance) as occurance 
				FROM smac_word.campaign_rule_keyword_history_{$campaign_id}
				WHERE keyword_id={$key} AND geo='{$geo}'  
				GROUP BY keyword 
				ORDER BY occurance DESC 
				LIMIT 100;";
		
		$this->open(0);
		
		$rs = $this->fetch($sql,1);
	
		$this->close();
		
		return array($rs);
		
	}
	
	function addCampaign($client_id=null,$data=array()){
		global $logger;
		$this->url = $this->apiurl . 'save_campaign.php';
		$logger->info("API : ".$this->url);
		$this->parameter = array("campaign_name" => urlencode($data['name']),
								 "campaign_start" => urlencode($data['start']),
								 "campaign_end" => urlencode($data['end']),
								 "channels" => urlencode($data['channels']),
								 "client_id" => $client_id,
								 "keywords" => ($data['keywords']),
								 "tracking_method" => urlencode($data['method']),
								 "desc" => urlencode($data['desc']),
								 "lang" => urlencode($data['lang']),
								 "geo" => urlencode($data['geo']),
								 "twitter_account" => urlencode($data['twitter_account']),
								"group_id" => urlencode($data['group_id']),
								"historical_interval" => urlencode($data['historical_interval']),
								 "hastag" => urlencode($data['hastag']));
		
		
		//echo '<hr />test: ';
		//print $this->goPost();
		//exit;
		
		//$doc = simplexml_load_string($this->goPost());
		$doc = $this->goPost();
		//var_dump($doc);exit;
		$logger->info("response : \n".$doc."\n-----------------\n");
		return $doc;
		
	}
	function people_involved($campaign_id,$client_id,$lang='all'){
		
		$this->url = $this->baseurl . 'people-involved.php?campaign_id='.$campaign_id.'&client_id='.$client_id."&lang=".$lang;

		$doc = simplexml_load_string($this->goGet());
		
		return $doc;
		
	}
	function getCampaignDetail($client_id=null,$campaign_id=null){
		
		//http://smac.me/api/campaign_detail.php?client_id=5&campaign_id=16
		
		$this->url = $this->baseurl . 'campaign_detail.php?client_id='.$client_id.'&campaign_id='.$campaign_id;
		
		$doc = $this->goGet();
		
		return $doc;
		
	}
	
	function editCampaignDescDuration($client_id=null,$data=array()){
		
		$this->url = $this->baseurl . 'campaign_edit_desc_duration.php';
		
		$this->parameter = array("campaign_name" => $data['name'],
								 "campaign_start" => $data['start'],
								 "campaign_end" => $data['end'],
								 "channels" => $data['channels'],
								 "client_id" => $client_id,
								 "keywords" => $data['keywords'],
								 "tracking_method" => $data['method'],
								 "desc" => $data['desc'],
								 "campaign_id" => $data['campaign_id']);
		
		//print_r($this->parameter);
		
		$doc = $this->goPost();
		
		return $doc;
		
	}
	
	function getSentimentTop($client_id=null,$campaign_id=null){
		
		//http://www.smac.me/api/sentiment_top.php?campaign_id=16&client_id=15
		
		$this->url = $this->baseurl . 'sentiment_top.php?client_id='.$client_id.'&campaign_id='.$campaign_id;
		
		$doc = $this->goGet();
		
		return $doc;
		
	}
	
	function changeCampaignStatus($client_id=null,$campaign_id=null,$status=null){
		$status = intval($status);
		
		
		$s = ($status == 1) ? 0 : 1;
		
		$this->url = $this->apiurl . 'change-campaign-status.php?client_id='.$client_id.'&campaign_id='.$campaign_id.'&status='.$s;
	
		$doc = json_decode($this->goGet());
		
		//print_r($doc);
		//exit;
		
		if($doc->success == 1){
			return true;
		}else{
			return false;
		}
	
		//$qry = "UPDATE tbl_campaign SET n_status='$s' WHERE client_id='$client_id' AND id='$campaign_id';";
		/*
		$this->open(0);
		
		if($this->query($qry)){
			return true;
		}else{
			return false;
		}
		*/
		
		
		return false;
		
	}
	
	function getTopicOverview($client_id=null){
		
		$this->url = $this->baseurl . 'topic_overview.php?client_id='.$client_id;
		
		$doc = $this->goGet();
		
		return $doc;
	}
	function get_campaign_duration($campaign_id,$lang='all'){
		$this->url = $this->baseurl . 'campaign_duration.php?campaign_id='.$campaign_id."&lang=".$lang;
		$doc = $this->goGet();
		return $doc;
	}
	function get_workflow_tweets($campaign_id,$keyword,$start,$total=100){
		$this->url = $this->baseurl . 'keyword_mention.php?campaign_id='.$campaign_id."&keyword=".$keyword."&start=".$start."&limit=".$total;
		$doc = $this->goGet();
		return $doc;
	}
	function workflow_flag_tweet($campaign_id,$keyword,$feed_id,$opt,$type){
		$this->url = $this->baseurl . 'flag_tweet.php?campaign_id='.$campaign_id."&keyword=".urlencode($keyword)."&feed_id=".$feed_id."&opt=".$opt."&type=".$type;
		$doc = $this->goGet();
		return $doc;
	}
	function get_workflow_content($campaign_id,$type,$filter,$start,$total=20){
		$this->url = $this->baseurl.'workflow.php?campaign_id='.$campaign_id."&type=".intval($type)."&filter=".$filter."&start=".$start."&limit=".$total;
		$doc = $this->goGet();
		return $doc;
	}
	function influenced_by($campaign_id,$person,$type,$start=0,$total=12){
		$this->url = $this->baseurl.'influenced_by.php?campaign_id='.$campaign_id."&person=".$person."&type=".intval($type)."&start=".$start."&limit=".$total;
		$doc = $this->goGet();
		
		return $doc;
	}
	function influencer_of($campaign_id,$person,$type,$start=0,$total=12){
		$this->url = $this->baseurl.'influencer_of.php?campaign_id='.$campaign_id."&person=".$person."&type=".intval($type)."&start=".$start."&limit=".$total;
		$doc = $this->goGet();
		return $doc;
	}
	function getCountryName($country_code){
		$country_code = mysql_escape_string($country_code);
		$this->open(0);
		$sql = "SELECT country FROM smac_web.geo_country WHERE iso='{$country_code}' LIMIT 1";
		$c = $this->fetch($sql);
		$this->close();
		return $c['country'];
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