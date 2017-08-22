<?php

/**
 * 
 * check if the url is valid
 * @param $url
 * @return boolean
 */
function isValidUrl($url){
	$url = mysql_escape_string($url);
	$hostname = str_replace("http://","", $url);
	$hostname = str_replace("https://","",$hostname);
	$foo = explode("/",$hostname);
	$hostname = $foo[0];
	//print $hostname."<br/>";
	
	if(checkdnsrr($hostname,"A")){
		return true;
	}else{
		return false;
	}
}

function clean($str){
	return mysql_escape_string($str);
}
/**
 * 
 * clean string from mysql related keywords
 * @param $str
 */
function cleanString($str){
	$str = eregi_replace("INSERT","", $str);
	$str = eregi_replace("DROP","", $str);
	$str = eregi_replace("SELECT","", $str);
	$str = eregi_replace("DELETE","",$str);
	$str = eregi_replace("UPDATE","",$str);
	$str = eregi_replace("UNION ALL","",$str);
	$str = eregi_replace("UNION","",$str);
	$str = eregi_replace("WHERE","",$str);
	$str = eregi_replace("AND","",$str);
	$str = eregi_replace("JOIN","",$str);
	return mysql_escape_string($str);
}
function sendRedirect($url){
	print' <meta http-equiv="refresh" content="1;URL='.$url.'" />';

}
function isImage($filename){
	if(eregi("\.jpeg|\.gif|\.jpg|\.png",$filename)){
		return true;
	}	
}
function LoadModule($moduleName,$req){
	global $APP_PATH,$ENGINE_PATH;
	$moduleName = mysql_escape_string($moduleName);
	if(file_exists($APP_PATH.$moduleName."/".$moduleName.".php")){
		include_once $APP_PATH.$moduleName."/".$moduleName.".php";	
		$obj = new $moduleName($req);	
		return $obj;
	}else{
		print "OBJECT NOT FOUND !";
		die();	
	}
}
function isLocal($filename){
	if(is_file($filename)){
		return true;
	}
}
/**
 * fungsi untuk mencari perbedaan tanggal.
 */
function datediff($interval, $datefrom, $dateto, $using_timestamps = false)
{

  /*
  $interval can be:
  yyyy - Number of full years
  q - Number of full quarters
  m - Number of full months
  y - Difference between day numbers
  (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33".
                 The datediff is "-32".)
  d - Number of full days
  w - Number of full weekdays
  ww - Number of full weeks
  h - Number of full hours
  n - Number of full minutes
  s - Number of full seconds (default)
  */

  if (!$using_timestamps) {
    $datefrom = strtotime($datefrom, 0);
    $dateto = strtotime($dateto, 0);
  }
  $difference = $dateto - $datefrom; // Difference in seconds

  switch($interval) {
    case 'yyyy': // Number of full years
    $years_difference = floor($difference / 31536000);
    if (mktime(date("H", $datefrom),
                              date("i", $datefrom),
                              date("s", $datefrom),
                              date("n", $datefrom),
                              date("j", $datefrom),
                              date("Y", $datefrom)+$years_difference) > $dateto) {

    $years_difference--;
    }
    if (mktime(date("H", $dateto),
                              date("i", $dateto),
                              date("s", $dateto),
                              date("n", $dateto),
                              date("j", $dateto),
                              date("Y", $dateto)-($years_difference+1)) > $datefrom) {

    $years_difference++;
    }
    $datediff = $years_difference;
    break;

    case "q": // Number of full quarters
    $quarters_difference = floor($difference / 8035200);
    while (mktime(date("H", $datefrom),
                                   date("i", $datefrom),
                                   date("s", $datefrom),
                                   date("n", $datefrom)+($quarters_difference*3),
                                   date("j", $dateto),
                                   date("Y", $datefrom)) < $dateto) {

    $months_difference++;
    }
    $quarters_difference--;
    $datediff = $quarters_difference;
    break;

    case "m": // Number of full months
    $months_difference = floor($difference / 2678400);
    while (mktime(date("H", $datefrom),
                                   date("i", $datefrom),
                                   date("s", $datefrom),
                                   date("n", $datefrom)+($months_difference),
                                   date("j", $dateto), date("Y", $datefrom)))
                        { // Sunday
    $days_remainder--;
    }
    if ($odd_days > 6) { // Saturday
    $days_remainder--;
    }
    $datediff = ($weeks_difference * 5) + $days_remainder;
    break;

    case "ww": // Number of full weeks
    $datediff = floor($difference / 604800);
    break;

    case "h": // Number of full hours
    $datediff = floor($difference / 3600);
    break;

    case "n": // Number of full minutes
    $datediff = floor($difference / 60);
    break;

    default: // Number of full seconds (default)
    $datediff = $difference;
    break;
  }

  return $datediff;
}

function getRealIP(){
  if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
  {
   $ip=$_SERVER['HTTP_CLIENT_IP'];
  }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
  {
     $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
  }else
  {
    $ip=$_SERVER['REMOTE_ADDR'];
  }
  return $ip;
}

// Parameters:
// $text = The text that you want to encrypt.
// $key = The key you're using to encrypt.
// $alg = The algorithm.
// $crypt = 1 if you want to crypt, or 0 if you want to decrypt.

function cryptare($text, $key, $alg, $crypt)
{
    $encrypted_data="";
    switch($alg)
    {
        case "3des":
            $td = mcrypt_module_open('tripledes', '', 'ecb', '');
            break;
        case "cast-128":
            $td = mcrypt_module_open('cast-128', '', 'ecb', '');
            break;   
        case "gost":
            $td = mcrypt_module_open('gost', '', 'ecb', '');
            break;   
        case "rijndael-128":
            $td = mcrypt_module_open('rijndael-128', '', 'ecb', '');
            break;       
        case "twofish":
            $td = mcrypt_module_open('twofish', '', 'ecb', '');
            break;   
        case "arcfour":
            $td = mcrypt_module_open('arcfour', '', 'ecb', '');
            break;
        case "cast-256":
            $td = mcrypt_module_open('cast-256', '', 'ecb', '');
            break;   
        case "loki97":
            $td = mcrypt_module_open('loki97', '', 'ecb', '');
            break;       
        case "rijndael-192":
            $td = mcrypt_module_open('rijndael-192', '', 'ecb', '');
            break;
        case "saferplus":
            $td = mcrypt_module_open('saferplus', '', 'ecb', '');
            break;
        case "wake":
            $td = mcrypt_module_open('wake', '', 'ecb', '');
            break;
        case "blowfish-compat":
            $td = mcrypt_module_open('blowfish-compat', '', 'ecb', '');
            break;
        case "des":
            $td = mcrypt_module_open('des', '', 'ecb', '');
            break;
        case "rijndael-256":
            $td = mcrypt_module_open('rijndael-256', '', 'ecb', '');
            break;
        case "xtea":
            $td = mcrypt_module_open('xtea', '', 'ecb', '');
            break;
        case "enigma":
            $td = mcrypt_module_open('enigma', '', 'ecb', '');
            break;
        case "rc2":
            $td = mcrypt_module_open('rc2', '', 'ecb', '');
            break;   
        default:
            $td = mcrypt_module_open('blowfish', '', 'ecb', '');
            break;                                           
    }
   
    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    $key = substr($key, 0, mcrypt_enc_get_key_size($td));
    @mcrypt_generic_init($td, $key, $iv);
   
    if($crypt)
    {
        $encrypted_data = @mcrypt_generic($td, $text);
    }
    else
    {
        $encrypted_data = @mdecrypt_generic($td, $text);
    }
   
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);
   
    return $encrypted_data;
} 
function convertBase64($str){
	$str = str_replace("=",".",$str);
	$str = str_replace("+","-",$str);
	$str = str_replace("/","_",$str);
	return $str;
}
function realBase64($str){
	$str = str_replace(".","=",$str);
	$str = str_replace("-","+",$str);
	$str = str_replace("_","/",$str);
	return $str;
}
function urlencode64($str){
	global $SMAC_HASH;
	$key = $SMAC_HASH;
	$hash = cryptare($str,$key,'des',1);
	$str = convertBase64(base64_encode($hash));
	return $str;
}
function urldecode64($str){
	global $SMAC_HASH;
	$key = $SMAC_HASH;
	$secret = base64_decode(realBase64($str));
	$str = cryptare($secret,$key,'des',0);
	return trim($str);
}
function get_correct_utf8_mysql_string($s) 
{ 
    if(empty($s)) return $s; 
    $s = preg_match_all("#[\x09\x0A\x0D\x20-\x7E]| 
[\xC2-\xDF][\x80-\xBF]| 
\xE0[\xA0-\xBF][\x80-\xBF]| 
[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}| 
\xED[\x80-\x9F][\x80-\xBF]#x", $s, $m ); 
    return implode("",$m[0]); 
}
function is_token_valid($token){
	$salt = urlencode64($_COOKIE['PHPSESSID']);
	$data = json_decode(urldecode64($_SESSION[$salt]));
	$secret = urlencode64($_COOKIE['PHPSESSID'].$token);
	if($data->token==$token&&$data->secret==$secret){
		$_SESSION[$salt] = null;
		return true;
	}
}
/**
 * SMAC formated number
 * @return String
 */
function smac_number($n){
	$num = abs($n);
	if($num>1000000){
		$str = round($n/1000000)."M";
	}else if($num>1000){
		$str = round($n/1000)."K";
	}else{
		$str = @number_format($n);
	}
	return $str;
}

/**
 * @param $str
 * @param $flag ALPHANUMERIC,CHAR_AND_NUMERIC,CHARACTER_ONLY,NUMERIC_ONLY,EMAIL,NOT_EMPTY
 */
function validate($str,$flag='NOT_EMPTY'){
	switch($flag){
		case 'NOT_EMPTY':
			if(strlen($str)>0){
				return true;
			}
		break;
		case 'EMAIL':	
			if (ereg("^.+@.+..+$", $str)) { 
				return true;
			}
		break;
		case 'NUMERIC_ONLY':
			
			if(eregi("^([0-9]+)$",$str)){
				return true;
			}
		break;
		case 'CHARACTER_ONLY':
			if(eregi("^[A-Za-z\ ]+$",$str)){
				return true;
			}
		break;
		case 'CHAR_AND_NUMBER':
			if(eregi("^[A-Za-z0-9\ ]+$",$str)){
				return true;
			}
		break;
		case 'ALPHANUMERIC':
			if(eregi("^[A-Za-z0-9\+\=\_\-\)\(\*\&\%\$\#\@\!\.\,\:\ ]+$",$str)){
				return true;
			}
		break;
		default:
			return false;
		break;
	}
}
function cleanXSS($val) {
	//$filter_sql = mysql_real_escape_string(stripslashes($val));
	$filter_sql = str_replace("\r\n","<br />",$val);
	$filter_sql = str_replace("<script"," ",$filter_sql);
	$filter_sql = str_replace("&lt;script"," ",$filter_sql);
	$filter_sql = str_replace("</script"," ",$filter_sql);
	$filter_sql = str_replace("&lt;/script"," ",$filter_sql);
	$filter_sql = str_replace("<iframe"," ",$filter_sql);
	$filter_sql = str_replace("&lt;iframe"," ",$filter_sql);
	$filter_sql = str_replace("</iframe"," ",$filter_sql);
	$filter_sql = str_replace("&lt;/iframe"," ",$filter_sql);
	return $filter_sql;
} 
function reformat_rule($str){
	$str = @eregi_replace("(lang\:.*)","",$str);
	$str = translate_rule($str); 
	return $str;
}

/*
function translate_rule($str){
	$str = @eregi_replace("(lang\:.*)","",$str);
	preg_match('/\(.*\)/i', $str, $matches);
	$str2 = $matches[0];
	
	$matches = null;
	$ANDs = str_replace($str2,"",$str);
	
	preg_match('/\-\(.*\)/i', $str2, $matches);
	$negates = $matches[0];
	$matches = null;
	$ORs = str_replace($negates,"",$str2);
	
	preg_match_all('/\-\(([a-zA-Z0-9\'\"\ ]+)\)/i',$negates,$matches);
	$excludes = $matches[1];
	
	$ORs = str_replace(array("(",")"),"",$ORs);
	
	$a_ORs = explode("OR",$ORs);
	
	//reformating
	$rules = "";
	$n=0;
	if(sizeof($a_ORs)>0){
		foreach($a_ORs as $a){
			if(strlen($a)>0){
				if($n==1){
					$rules.=" OR ";
				}
				$rules.=$ANDs." ".trim($a);
				$n=1;
			}
		}
	}
	if(strlen($rules)==0){
		$rules.=$ANDs;
	}
	if(sizeof($excludes)>0){
		$rules.=" Excludes : ";
		$n=0;
		foreach($excludes as $e){
			if(strlen($e)>0){
				if($n==1){
					$rules.=", ";
				}
				$rules.=trim($e);
				$n=1;
			}
		}
	}
	
	return $rules;
}
*/
function translate_rule($txt){
	$txt = stripslashes($txt);
	$txt = strip_tags($txt);
	
	$txt = preg_replace('/\(bio_location_contains\:[a-zA-Z]+\)/i',"",$txt);
	$txt = preg_replace('/\(lang\:[a-zA-Z]+\)/i',"",$txt);
	
	$txt = preg_replace('/([a-zA-Z0-9\"\ \)]+)(\ \()/i',"$1 AND$2",$txt);
	$txt = reformat_gnip_clauses($txt);
	
	return $txt;
}
/**
 * this function will help in converting gnip rules into ours
 * @return string of formated rules
 */
function reformat_gnip_clauses($txt){
		
	//we grab the negators first
	preg_match_all('/\-\(\w+[^\)]\)/i', $txt,$negators);
	$Negs = str_replace(array("(",")","-"),"",implode(",",$negators[0]));
	for($i=0;$i<sizeof($negators[0]);$i++){
		$txt = str_replace("{$negators[0][$i]}","",$txt);
	}
	$splits = explode("AND",$txt);
	$ORs = array();
	$ANDs = array();
	for($i=0;$i<sizeof($splits);$i++){
		if(@eregi("OR",$splits[$i])){
			$s = str_replace(array("(",")"),"",$splits[$i]);
			$ORs = explode("OR",$s);
		}else{
			$ANDs[] = $splits[$i];
		}
	}
	$str = "";
	//combine every ANDs with ORs
	if(sizeof($ORs)>0){
		if(sizeof($ANDs)>0){
			for($i=0;$i<sizeof($ANDs);$i++){
				if($i>0){
					$str.=" AND ";
				}
				for($j=0;$j<sizeof($ORs);$j++){
					if($j>0){
						$str.=" AND ";
					}
					$str.="{$ANDs[$i]} {$ORs[$j]}";
				}
			}
		}else{
			for($j=0;$j<sizeof($ORs);$j++){
				if($j>0){
					$str.=" OR ";
				}
				$str.="{$ORs[$j]}";
			}
		}
	}else{
		for($i=0;$i<sizeof($ANDs);$i++){
			if($i>0){
				$str.=" AND ";
			}
			$str.=$ANDs[$i];	
		}
	}
	if(strlen($Negs)>0){
		$str.=PHP_EOL.". Excluding : {$Negs}";
	}
	unset($ANDs);
	unset($ORs);
	return $str;
}
function text($name,$params=null,$lang='en'){
	ob_start();
	include("../config/locale.inc.php");
	ob_end_clean();
	return $LOCALE[$lang][$name];
}
/**
 * convert gmt +0 to gmt +7
 * @param $str
 */
function jakarta_time($str){

	$a = explode(" ",trim($str));
	
	$d = explode("/",$a[0]);
	$t = $a[1];
	$s = $d[2]."-".$d[1]."-".$d[0]." ".$t;
	
	
	
	$st = strtotime($s);
	$st+= 60*60*7;
	$strDate = date("d/m/Y H:i:s",$st);
	return $strDate;
}

function dateUnixToIndo($str){
	if(eregi("-",$str)){
			$a1 = explode("-",$str);
			$formatted = $a1[2].'/'.$a1[1].'/'.$a1[0];
			return $formatted;
	}else{
		return $str;
	}
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
function arr_assoc_sort_date($a,$format){
	$b = array();
	$c = array();
	foreach($a as $k=>$v) {
		$b[strtotime($k)] = $v;
	}
	unset($a);
	uksort($b,function($a,$b){
		if(intval($a)<intval($b)){
			return -1;
		}else if(intval($a)>intval($b)){
			return 1;
		}else{
			return 0;
		}
	});
	foreach($b as $key=>$val) {
		$c[date($format,intval($key))] = $val;
	}
	unset($b);
	
	return $c;
}
/**
 * a helper function to help sorting an array based on its key's value reversely
 * @param $a
 * @param $subkey
 */
function subval_rsort($a,$subkey) {
	foreach($a as $k=>$v) {
		$b[$k] = strtolower($v[$subkey]);
	}
	arsort($b);
	foreach($b as $key=>$val) {
		$c[] = $a[$key];
	}
	return $c;
}
function curlGet($url,$params,$timeout=15){
	if(count($params) > 0){
		$url .= "?".http_build_query($params);
	}
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_TIMEOUT,$timeout);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);	
	curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec ($ch);
	$info = curl_getinfo($ch);
	curl_close ($ch);
	return $response;
}
function curlPost($url,$params,$timeout=15){
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_TIMEOUT,$timeout);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);	
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
	$response = curl_exec ($ch);
	$info = curl_getinfo($ch);
	curl_close ($ch);
	return $response;
}
function post_json($url,$data){
	$data_string = json_encode($data);
	$ch = curl_init($url);                                                                      
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
	curl_setopt($ch, CURLOPT_POST, 1);                                                                  
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
	    'Content-Type: application/json',                                                                                
	    'Content-Length: ' . strlen($data_string))                                                                       
	);                                                                                                                   
	$result = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);
	return array("response"=>$result,"info"=>$info);
}
function post_xml($url,$xml_string){
	$ch = curl_init($url);                                                                      
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");    
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_string);                                                                  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
	$result = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);
	return array("response"=>$result,"info"=>$info);
}
function extract_url($str){
	$pattern = "((https?|ftp|gopher|telnet|file):((//)|(\\\\))+[\\w\\d:#@%/;$()~_?\\+-=\\\\\\.&]*)";
	$str = preg_replace($pattern,"",$str);
	return $str;
}
/**
 * extract words from romanic charactres
 * @param $str
 * @return unknown_type
 */
function extract_words($str){
	$str = str_replace("RT"," ",$str);
	$str = strtolower($str);
	$str = extract_url($str);
	$str = strip_non_chars($str);
	$arr = explode(" ",$str);
	$s = "";
	$n=0;
	
	foreach($arr as $a){
		$a = trim($a);
		if(strlen($a)>0){
			if($n==1){
				$s.=",";
			}
			$s.=$a;
			$n=1;
		}
	}
	
	return $s;
}
/**
 * strip non characters from romanic characters
 * @param $str
 * @return unknown_type
 */
function strip_non_chars($str){
	$pattern = "([\~\!\#|$\%\^\&\*\(\)\?\.\,\=\:\;\"\'\*\\\/\[\]\-\_\r\n]+)";
	$str = preg_replace($pattern," ",$str);
	return $str;
}
/**
 * securely hashed the string
 * @param string text to encrypted
 * @return string returned hash
 */
 
function text_encode($n){
	return urlencode64($n);
}
/**
 * securely decode the hash
 * @param string hash text
 * @return the decoded string
 */
function text_decode($n){
	return urldecode64($n);
}
/**
 * encode the data into encrypted string
 * @param array data
 * @return string hash
 */
function data_encode($arr){
	return urlencode64(serialize($arr));
}
/**
 * decode the hash string and returns the data
 * @param string $hash
 * @return array 
 */
function data_decode($hash){
	$data = unserialize(urldecode64($hash));
	return $data;
}
/**
 * print_ar alias
 */
function pr($o){
	print "<pre>";
	print_r($o);
	print "</pre>";
}
function clean_ascii($output){
	return preg_replace('/[^(\x20-\x7F)]*/','', $output);
}
/**
 * a basic curl call
 */
function curl_get($url,$timeout=30){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_TIMEOUT,$timeout);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	
	//curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY); 
	
	curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	$response = curl_exec ($ch);
	$info = curl_getinfo($ch);
	curl_close ($ch);

	return $response;
}
/**
 * a function to deal with utf8 issue with non-latin characters.
 * @param $utf8 a string which contains utf8 code
 */
function utf8tohtml($utf8, $encodeTags=null)
{
    $result = '';
    for ($i = 0; $i < strlen($utf8); $i++)
    {
        $char = $utf8[$i];
        $ascii = ord($char);
        if ($ascii < 128)
        {
            // one-byte character
            $result .= ($encodeTags) ? htmlentities($char , ENT_QUOTES, 'UTF-8') : $char;
        } else if ($ascii < 192)
        {
            // non-utf8 character or not a start byte
        } else if ($ascii < 224)
        {
            // two-byte character
            $result .= htmlentities(substr($utf8, $i, 2), ENT_QUOTES, 'UTF-8');
            $i++;
        } else if ($ascii < 240)
        {
            // three-byte character
            $ascii1 = ord($utf8[$i+1]);
            $ascii2 = ord($utf8[$i+2]);
            $unicode = (15 & $ascii) * 4096 +
                (63 & $ascii1) * 64 +
                (63 & $ascii2);
            $result .= "&#$unicode;";
            $i += 2;
        } else if ($ascii < 248)
        {
            // four-byte character
            $ascii1 = ord($utf8[$i+1]);
            $ascii2 = ord($utf8[$i+2]);
            $ascii3 = ord($utf8[$i+3]);
            $unicode = (15 & $ascii) * 262144 +
                (63 & $ascii1) * 4096 +
                (63 & $ascii2) * 64 +
                (63 & $ascii3);
            $result .= "&#$unicode;";
            $i += 3;
        }
    }
    return $result;
}
?>