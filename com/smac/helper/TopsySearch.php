<?php
class TopsySearch{
    protected $_user_agent = "Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13";
    //    protected $_url;
    //    protected $_timeout;
    //    protected $_port = 80;
    //    protected $reconnect_if_idle_for = 1;
    //    protected $log_location = "topsy_search.log";
    //    protected $_response;
    //    protected $_status;
    //    protected $_refresh_url = "";
    //    protected $_auto_refresh = false;
    protected $topsy_api_key = "1CED87E3F0814D24870CDC6F628DB27E";
    //protected static $type = "tweet";
    protected static $type = "";
    //    protected $content = null;


    public function __construct(){

    }

    //getter setter
    public function auto_refresh($f=NULL){
        if($f==NULL){
            return $this->_auto_refresh;
        }else{
            $this->_auto_refresh = $f;
        }
    }
   
    public function user_agent($u=NULL){
        if($u==NULL){
            return $this->_user_agent;
        }else{
            $this->_user_agent = $u;
        }
    }
    public function topsy_api_key($u=NULL){
        if($u==NULL){
            return $this->topsy_api_key;
        }else{
            $this->topsy_api_key = $u;
        }
    }
    //end of getter setter
    public function searchCount($keyword){
    	 $url = "http://otter.topsy.com/searchcount.json";
        $params = $this->buildQueryParams($keyword, $start, $num, $lang,$startts);
        $url = $url."?".$params;
        list($content, $response) = $this->getUrlContent($url);
        return $content;
    }
    
    public function search($keyword, $start, $num, $lang="",$startts = -1){
        if ($startts == -1) {
            date_default_timezone_set('UTC');
            $onemonthago = date("Y-m-d", strtotime("-1 month"));
            $dt = new DateTime($onemonthago);
            $startts = $dt->getTimestamp();
        }
        
        $url = "http://otter.topsy.com/search.json";
        $params = $this->buildQueryParams($keyword, $start, $num, $lang,$startts);
        $url = $url."?".$params;
		
        //$url = "http://otter.topsy.com/search.json?mintime=1319821200&page=0&perpage=5&q=mobil&type=tweet";
        //$url = "http://otter.topsy.com/search.json?apikey=5C563DA3965342608A3A7423D2C75688&perpage=10&q=awdsksd&type=tweet";
        //print $url . "\n";
        list($content, $response) = $this->getUrlContent($url);
        if (is_null($content)) {
            unset($content);
            unset($response);
            return json_encode(array("status"=>"0", "data"=>array(), "trackback_date"=>"".$startts, "firstpost_date"=>"".$startts));
        }
        if ($response['http_code'] == 400 || $response['http_code'] == 403 || $response['http_code'] == 404 ||
        $response['http_code'] == 500 || $response['http_code'] == 503) {
            unset($response);
            unset($content);
            return json_encode(array("status"=>"2", "data"=>array(), "trackback_date"=>"".$startts, "firstpost_date"=>"".$startts));
        }
        $headers = get_headers($response['url']);
        //print "RESPONSE::";var_dump($response);print "\n";
        //print "HEADER::";var_dump($headers);print "\n";
        //print($content);
        
        $json_array = json_decode($content, true);
        //print_r($json_array->{'response'});
        $response = $json_array["response"];
        //print "\n" . $response["errors"]  . "\n";
        //print "NO ERRORS\n";
        //print "size?" . sizeof($response["list"]) . "\n";
        
        $data_size = sizeof($response["list"]);
        if ($data_size <= 0) {
            unset($content);
            unset($headers);
            return json_encode(array("status"=>"1", "data"=>array(), "trackback_date"=>"".$startts, "firstpost_date"=>"".$startts));
        } else {
            $trackback_date = $response["list"][0]["trackback_date"];
            $firstpost_date = $response["list"][0]["firstpost_date"];
           // print "trackback_date->" . $trackback_date . "\n";
           // print "firstpost_date->" . $firstpost_date . "\n";
            $concat_content = "";
            foreach ($response["list"] as $data_object) {
                $concat_content .= $data_object["content"] . " ";
            }
			
			$word_assoc = explode(",",extract_words($concat_content));
		
            //print $concat_content . "\n\n\n";
            //print "0::";print_r(str_word_count($concat_content, 0));
            /*
            $word_assoc = str_word_count($concat_content, 1);
			 * * */
            //print "1::";print_r($word_assoc);
            //print "2::";print_r(str_word_count($concat_content, 2));
            $return_word = array();
            foreach($word_assoc as $word){
                if (array_key_exists($word, $return_word)) {
                    $return_word[$word] += 1;
                } else {
                    $return_word[$word] = 1;
                }
            }
			 
            //print "\n::";print_r($return_word);print "\n";
            unset($content);
            unset($headers);
            return json_encode(array("status"=>"1", data=>$return_word, "trackback_date"=>"".$trackback_date, "firstpost_date"=>"".$firstpost_date));
        }
    }

    /*
     public function response(){
     return $this->_response;
     }
     */
    function getUrlContent( $url, $timeout = 5 )
    {
        $url = str_replace( "&amp;", "&", urldecode(trim($url)) );

        //$cookie = tempnam ("/tmp", "CURLCOOKIE");
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
        curl_setopt( $ch, CURLOPT_URL, $url );
        //curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie );
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $ch, CURLOPT_ENCODING, "" );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
        curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
        curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
        $content = curl_exec( $ch );
        $response = curl_getinfo( $ch );
        curl_close ( $ch );
       
        //print "http code::" . $response['http_code'] . "\n";
        /*
         if ( $headers = get_headers($response['url']) ){
         var_dump($headers);
         foreach( $headers as $value ){
         print $value . "\n";
         }
         }
         */
        //        print "\n\n" . $response['url'] . "\n\n";
        //        var_dump($response);
        return array( $content, $response);
    }
    /**
     * Builds query parameters for the request to AWIS.
     * Parameter names will be in alphabetical order and
     * parameter values will be urlencoded per RFC 3986.
     * @return String query parameters for the request
     */
    protected function buildQueryParams($keyword, $start, $num, $lang="",$startts = -1) {
        $clean_keyword = implode('+',explode(" ", $keyword));
        $params = array(
            'type'            => self::$type,
            'mintime'         => "".$startts,
            'q'               => $clean_keyword,
            'perpage'         => "".$num,
            'apikey'		  => $this->topsy_api_key,
            'offset'		  => $start,
            'allow_lang'	  => 'en,id,msa,ar'
        );
        if($lang!=""){
        	$params['allow_lang'] = $lang;
        }
        unset($dt);
        ksort($params);
        $keyvalue = array();
        foreach($params as $k => $v) {
            $keyvalue[] = $k . '=' . rawurlencode($v);
        }
        return implode('&',$keyvalue);
    }

}
/*
 $topsysearch = new TopsySearch();
 $return_json = $topsysearch->search("mobil mewah", 0, 2);
 print $return_json . "\n";
*/

?>
