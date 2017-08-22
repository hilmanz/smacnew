<?php
error_reporting(E_ERROR | E_PARSE);
/*==================================
Get url content and response headers (given a url, follows all redirections on it and returned content and response headers of final url)

@return    array[0]    content
        array[1]    array of response headers
==================================*/
function get_url( $url,  $javascript_loop = 0, $timeout = 5 )
{
    $url = str_replace( "&amp;", "&", urldecode(trim($url)) );

    $cookie = tempnam ("/tmp", "CURLCOOKIE");
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie );
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

    if ($response['http_code'] == 301 || $response['http_code'] == 302)
    {
        ini_set("user_agent", "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");

        if ( $headers = get_headers($response['url']) )
        {
            foreach( $headers as $value )
            {
                if ( substr( strtolower($value), 0, 9 ) == "location:" )
                    return get_url( trim( substr( $value, 9, strlen($value) ) ) );
            }
        }
    }

    if (    ( preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i", $content, $value) || preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i", $content, $value) ) &&
            $javascript_loop < 5
    )
    {
        return get_url( $value[1], $javascript_loop+1 );
    }
    else
    {
        return array( $content, $response );
    }
}

function xml2ary(&$string) {
    $parser = xml_parser_create();
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parse_into_struct($parser, $string, $vals, $index);
    xml_parser_free($parser);

    $mnary=array();
    $ary=&$mnary;
    foreach ($vals as $r) {
        $t=$r['tag'];
        if ($r['type']=='open') {
            if (isset($ary[$t])) {
                if (isset($ary[$t][0])) $ary[$t][]=array(); else $ary[$t]=array($ary[$t], array());
                $cv=&$ary[$t][count($ary[$t])-1];
            } else $cv=&$ary[$t];
            if (isset($r['attributes'])) {foreach ($r['attributes'] as $k=>$v) $cv['_a'][$k]=$v;}
            $cv['_c']=array();
            $cv['_c']['_p']=&$ary;
            $ary=&$cv['_c'];

        } elseif ($r['type']=='complete') {
            if (isset($ary[$t])) { // same as open
                if (isset($ary[$t][0])) $ary[$t][]=array(); else $ary[$t]=array($ary[$t], array());
                $cv=&$ary[$t][count($ary[$t])-1];
            } else $cv=&$ary[$t];
            if (isset($r['attributes'])) {foreach ($r['attributes'] as $k=>$v) $cv['_a'][$k]=$v;}
            $cv['_v']=(isset($r['value']) ? $r['value'] : '');

        } elseif ($r['type']=='close') {
            $ary=&$ary['_p'];
        }
    }    
    
    _del_p($mnary);
    return $mnary;
}

// _Internal: Remove recursion in result array
function _del_p(&$ary) {
    foreach ($ary as $k=>$v) {
        if ($k==='_p') unset($ary[$k]);
        elseif (is_array($ary[$k])) _del_p($ary[$k]);
    }
}

// Array to XML
function ary2xml($cary, $d=0, $forcetag='') {
    $res=array();
    foreach ($cary as $tag=>$r) {
        if (isset($r[0])) {
            $res[]=ary2xml($r, $d, $tag);
        } else {
            if ($forcetag) $tag=$forcetag;
            $sp=str_repeat("\t", $d);
            $res[]="$sp<$tag";
            if (isset($r['_a'])) {foreach ($r['_a'] as $at=>$av) $res[]=" $at=\"$av\"";}
            $res[]=">".((isset($r['_c'])) ? "\n" : '');
            if (isset($r['_c'])) $res[]=ary2xml($r['_c'], $d+1);
            elseif (isset($r['_v'])) $res[]=$r['_v'];
            $res[]=(isset($r['_c']) ? $sp : '')."</$tag>\n";
        }
        
    }
    return implode('', $res);
}

// Insert element into array
function ins2ary(&$ary, $element, $pos) {
    $ar1=array_slice($ary, 0, $pos); $ar1[]=$element;
    $ary=array_merge($ar1, array_slice($ary, $pos));
}

function open_db($n){
	global $DATABASE;
	$host = $DATABASE[$n]['host'];
	$user = $DATABASE[$n]['user'];
	$password = $DATABASE[$n]['password'];
	$conn = mysql_connect($host,$user,$password);
	return $conn;
}
function close_db($conn){
	mysql_close($conn);
}
function retrieve_coordinate($google_location){
	$str = str_replace('ÜT:','',$google_location);
	$arr = explode(",",$str);
	if(sizeof($arr)==2){
		if(eregi("([0-9\.]+)",$arr[0])&&eregi("([0-9\.]+)",$arr[1])){
			return array(trim($arr[0]),trim($arr[1]));
		}
	}
}
function get_standard_feeds($campaign_id,$last_id=NULL,$limit=0){
	global $conn,$SCHEMA_WEB;
	
	if($last_id==NULL){
		if($campaign_id>0){
			if($limit>0){
				$sql = "SELECT * 
					FROM smac_data.standard_feeds a
					INNER JOIN smac_web.tbl_campaign_keyword b ON a.tag_id = b.keyword_id
					WHERE b.campaign_id = ".$campaign_id." AND a.retrieve_date >= (NOW() - INTERVAL 1 HOUR) ORDER BY a.id ASC
					LIMIT ".$limit;
			}else{
				$sql = "SELECT * 
					FROM smac_data.standard_feeds a
					INNER JOIN  smac_web.tbl_campaign_keyword b ON a.tag_id = b.keyword_id
					WHERE b.campaign_id = ".$campaign_id." AND a.retrieve_date >= (NOW() - INTERVAL 1 HOUR) ORDER BY a.id ASC
					LIMIT 1000";
			}
			
		}else{
			//$sql = "SELECT * FROM ".$SCHEMA.".standard_feeds WHERE retrieve_date >= (NOW() - INTERVAL 1 HOUR) ORDER BY id ASC LIMIT 1000";
		}
	}else{
		
		if($campaign_id>0){
			if($limit>0){
				$sql = "SELECT * 
					FROM smac_data.standard_feeds a
					INNER JOIN  smac_web.tbl_campaign_keyword b ON a.tag_id = b.keyword_id
					WHERE b.campaign_id = ".$campaign_id." AND a.id > ".$last_id." ORDER BY a.id ASC
					LIMIT ".$limit;
			}else{
				$sql = "SELECT * 
					FROM smac_data.standard_feeds a
					INNER JOIN  smac_web.tbl_campaign_keyword b ON a.tag_id = b.keyword_id
					WHERE b.campaign_id = ".$campaign_id." AND a.id > ".$last_id." ORDER BY a.id ASC
					LIMIT 1000";
			}
			
		}else{
			//$sql = "SELECT * FROM ".$SCHEMA.".standard_feeds WHERE id > ".$last_id." ORDER BY id ASC LIMIT 1000";
		}
	}
	
	if(strlen($sql)>0){
		
		$q = mysql_query($sql,$conn);
		
		$str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$str.="<rows>";
		$n=0;
	
		
	
		while($fetch = mysql_fetch_assoc($q)){
			
			$str.="<row>";
			$str.="<id>".$fetch['id']."</id>";
			$str.="<published>".$fetch['published']."</published>";
			$str.="<name>".htmlspecialchars($fetch['author_name'])."</name>";
			$str.="<txt>".htmlspecialchars($fetch['content'])."</txt>";
			$str.="<keyword>".htmlspecialchars($fetch['matching_rule'])."</keyword>";
			$str.="<lat>".$fetch['coordinate_x']."</lat>";
			$str.="<lon>".$fetch['coordinate_y']."</lon>";
			$str.="<profile_image_url>".htmlspecialchars($fetch['author_avatar'])."</profile_image_url>";
			$str.="<insert_date>".date("d-m-Y H:i:s",strtotime($fetch['retrieve_date']))."</insert_date>";
			$str.="<author_id>".htmlspecialchars($fetch['author_id'])."</author_id>";
			$str.="</row>";
			$n++;
		
		}
	
		mysql_free_result($q);
	
		if($n==0){
			//artinya data kosong
			$sql = "SELECT * 
					FROM smac_data.standard_feeds a
					INNER JOIN  smac_web.tbl_campaign_keyword b ON a.tag_id = b.keyword_id
					WHERE b.campaign_id = ".$campaign_id." AND a.retrieve_date < NOW() ORDER BY a.id DESC
					LIMIT 1";
		
		
			//$sql = "SELECT id FROM ".$SCHEMA.".standard_feeds WHERE retrieve_date < NOW() ORDER BY id DESC LIMIT 1";
			$q = mysql_query($sql,$conn);
			$rs = mysql_fetch_assoc($q);
		
		
			mysql_free_result($q);
		
			$str.="<row>";
			$str.="<id>".intval($rs['id'])."</id>";
			$str.="<published></published>";
			$str.="<name></name>";
			$str.="<txt></txt>";
			$str.="<keyword></keyword>";
			$str.="<lat></lat>";
			$str.="<lon></lon>";
			$str.="<profile_image_url></profile_image_url>";
			$str.="<insert_date></insert_date>";
			$str.="</row>";
		}
		$str.="</rows>";
	}else{
		$str.="<result>0</result>";
	}
	return $str;
}
/**
 * this is a function to retrieve livestream data.
 * livestream contains both realtime and historical data
 * @param unknown_type $campaign_id
 * @param unknown_type $tgl_start
 * @param unknown_type $tgl_end
 * @param unknown_type $last_id
 * @param unknown_type $start
 * @param unknown_type $limit
 * @return string
 */
function get_historical_all($campaign_id,$tgl_start,$tgl_end,$last_id,$start,$limit){
	global $conn;$SCHEMA_WEB;
	$last_id = intval($_REQUEST['last_id']);
	$start = intval($_REQUEST['start']);
	if($tgl_start==null){
		$last_hour = date("YmdHis",mktime(date("H")-1,date("i"),date("s"),date("m"),date("d"),date("Y")));
	}else{
		$last_hour = date("YmdHis",strtotime($tgl_start));
	}
	if($tgl_end==null){
		$until_hour = date("YmdHis");
	}else{
		$until_hour = date("YmdHis",strtotime($tgl_end));
	}
	if($campaign_id>0){
			$sql = "SELECT *,DATE_FORMAT(published_datetime,'%d/%m/%Y %H:%i:%s') as published_dt 
				FROM smac_report.campaign_feeds
				WHERE id > ".$last_id." AND
				campaign_id = ".$campaign_id."
				ORDER BY id DESC
				LIMIT ".$start.",".$limit;
			
	}
	
	if(strlen($sql)>0){
		$q = mysql_query($sql,$conn);
		//print mysql_error();
	
		$str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$str.="<rows>";
		$n=0;
	
		
		while($fetch = mysql_fetch_assoc($q)){
			$arr = explode(",",$fetch['wordlist']);
			$str.="<row>";
			$str.="<id>".$fetch['id']."</id>";
			$str.="<published>".$fetch['published']."</published>";
			$str.="<published_datetime>".$fetch['published_dt']."</published_datetime>";
			$str.="<hour_time>".$fetch['hour_time']."</hour_time>";
			$str.="<name>".htmlspecialchars($fetch['author_name'])."</name>";
			$str.="<txt>".htmlspecialchars($fetch['content'])."</txt>";
			$str.="<keyword>".htmlspecialchars($fetch['matching_rule'])."</keyword>";
			$str.="<author_avatar>".htmlspecialchars($fetch['author_avatar'])."</author_avatar>";
			$str.="<reach>".htmlspecialchars($fetch['followers'])."</reach>";
			$str.="<lat>".$fetch['coordinate_x']."</lat>";
			$str.="<lon>".$fetch['coordinate_y']."</lon>";
			$str.="<wordlist>".$fetch['wordlist']."</wordlist>";
			$str.="<author_id>".htmlspecialchars($fetch['author_id'])."</author_id>";
			$str.="<profile_image_url>".htmlspecialchars($fetch['author_avatar'])."</profile_image_url>";
			$str.="<insert_date>".date("d-m-Y H:i:s",strtotime($fetch['retrieve_date']))."</insert_date>";
			$str.="<api>get_historical</api>";
			$str.="<offset>".$start."</offset>";
			$str.="<nextOffset>".($start+$limit)."</nextOffset>";
			$str.="<prevOffset>".($start-$limit)."</prevOffset>";
			$str.="</row>";
			$n++;
		}
		mysql_free_result($q);
		mysql_query($words,$conn);
		$sql = "DELETE FROM smac_report.tmp_livetrack_words WHERE keyword IN (SELECT kata FROM smac_data.tb_stop)";
		mysql_query($sql,$conn);
		$words = null;
		$str.="</rows>";
	}else{
		$str.="<result>0</result>";
	}
	return $str;
}
function get_historical($campaign_id,$tgl_start,$tgl_end,$last_id,$limit,$geo=''){
	global $conn;$SCHEMA_WEB;
	$last_id = intval($_REQUEST['last_id']);
	if(strlen($geo)!=2){
		$geo = '';
	}
	if($tgl_start==null){
		$last_hour = date("YmdHis",mktime(date("H")-1,date("i"),date("s"),date("m"),date("d"),date("Y")));
	}else{
		$last_hour = date("YmdHis",strtotime($tgl_start));
	}
	if($tgl_end==null){
		$until_hour = date("YmdHis");
	}else{
		$until_hour = date("YmdHis",strtotime($tgl_end));
	}
	if($campaign_id>0){
			$sql = "SELECT a.*,DATE_FORMAT(published_datetime,'%d/%m/%Y %H:%i:%s') as published_dt  
				FROM smac_report.campaign_feeds a
				INNER JOIN smac_report.country_twitter b
				ON a.feed_id = b.feed_id
				WHERE a.id > ".$last_id." AND
				a.campaign_id = ".$campaign_id."
				AND b.country_code='{$geo}' 
				AND (coordinate_x > 0 OR coordinate_y > 0) 
				AND hour_time >= ".$last_hour." AND hour_time <= ".$until_hour."
				ORDER BY id DESC
				LIMIT ".$limit;
		
	}
	//print $sql;
	if(strlen($sql)>0){
		$q = mysql_query($sql,$conn);
		//print mysql_error();
	
		$str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$str.="<rows>";
		$n=0;
	
		
		while($fetch = mysql_fetch_assoc($q)){
			$arr = explode(",",$fetch['wordlist']);
			$str.="<row>";
			$str.="<id>".$fetch['id']."</id>";
			$str.="<published>".$fetch['published']."</published>";
			$str.="<published_datetime>".$fetch['published_dt']."</published_datetime>";
			$str.="<hour_time>".$fetch['hour_time']."</hour_time>";
			$str.="<name>".htmlspecialchars($fetch['author_name'])."</name>";
			$str.="<txt>".htmlspecialchars($fetch['content'])."</txt>";
			$str.="<keyword>".htmlspecialchars($fetch['matching_rule'])."</keyword>";
			$str.="<reach>".htmlspecialchars($fetch['followers'])."</reach>";
			$str.="<lat>".$fetch['coordinate_x']."</lat>";
			$str.="<lon>".$fetch['coordinate_y']."</lon>";
			$str.="<wordlist>".$fetch['wordlist']."</wordlist>";
			$str.="<author_id>".htmlspecialchars($fetch['author_id'])."</author_id>";
			$str.="<profile_image_url>".htmlspecialchars($fetch['author_avatar'])."</profile_image_url>";
			$str.="<insert_date>".date("d-m-Y H:i:s",strtotime($fetch['retrieve_date']))."</insert_date>";
			$str.="<api>get_historical</api>";
			$str.="</row>";
			$n++;
		}
		mysql_free_result($q);
		////mysql_query($words,$conn);
		//$sql = "DELETE FROM smac_report.tmp_livetrack_words WHERE keyword IN (SELECT kata FROM smac_data.tb_stop)";
		//mysql_query($sql,$conn);
		//$words = null;
		$str.="</rows>";
	}else{
		$str.="<result>0</result>";
	}
	return $str;
}
function get_map_history($campaign_id,$last_id,$limit){
	global $conn;$SCHEMA_WEB;
	$last_id = intval($_REQUEST['last_id']);
	$last_hour = date("YmdHis",mktime(date("H")-1,date("i"),date("s"),date("m"),date("d"),date("Y")));
	if($campaign_id>0){
			$sql = "SELECT *,DATE_FORMAT(published_datetime,'%d/%m/%Y %H:%i:%s') as published_dt 
				FROM smac_report.campaign_feeds
				WHERE id > ".$last_id." AND
				campaign_id = ".$campaign_id." AND (coordinate_x > 0 OR coordinate_y > 0) 
				AND hour_time >= ".$last_hour."
				ORDER BY id DESC
				LIMIT ".$limit;
		
	}
	$people = array();
	if(strlen($sql)>0){
		$q = mysql_query($sql,$conn);
		//print mysql_error();
	
		$str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$str.="<rows>";
		$n=0;
	
		//print mysql_error();
		$words = "INSERT INTO smac_report.tmp_livetrack_words(campaign_id,keyword,occurance) VALUES";
		$p=0;
		while($fetch = mysql_fetch_assoc($q)){
			$arr = explode(",",$fetch['wordlist']);
			foreach($arr as $w){
				if(strlen($w)>2){
					if($p==1){
						$words.=",";
					}
					$words.="(".$campaign_id.",'".mysql_escape_string(trim($w))."',1)";
					$p=1;
				}
			}
			
			$str.="<row>";
			$str.="<id>".$fetch['id']."</id>";
			$str.="<published>".$fetch['published']."</published>";
			$str.="<hour_time>".$fetch['hour_time']."</hour_time>";
			$str.="<published_datetime>".$fetch['published_dt']."</published_datetime>";
			$str.="<name>".htmlspecialchars($fetch['author_name'])."</name>";
			$str.="<txt>".htmlspecialchars($fetch['content'])."</txt>";
			$str.="<keyword>".htmlspecialchars($fetch['matching_rule'])."</keyword>";
			$str.="<reach>".htmlspecialchars($fetch['followers'])."</reach>";
			$str.="<lat>".$fetch['coordinate_x']."</lat>";
			$str.="<lon>".$fetch['coordinate_y']."</lon>";
			$str.="<wordlist>".$fetch['wordlist']."</wordlist>";
			$str.="<profile_image_url>".htmlspecialchars($fetch['author_avatar'])."</profile_image_url>";
			$str.="<author_id>".htmlspecialchars($fetch['author_id'])."</author_id>";
			
			$str.="<insert_date>".date("d-m-Y H:i:s",strtotime($fetch['retrieve_date']))."</insert_date>";
			$str.="<api>get_map_history</api>";
			$str.="</row>";
			$n++;
		}
		mysql_free_result($q);
		
		mysql_query($words,$conn);
		$sql = "DELETE FROM smac_report.tmp_livetrack_words WHERE keyword IN (SELECT kata FROM smac_data.tb_stop)";
		mysql_query($sql,$conn);
		$words = null;
		$str.="</rows>";
	}else{
		$str.="<result>0</result>";
	}
	return $str;
}
function get_realtime_feeds($campaign_id,$last_id,$limit){
	global $conn;$SCHEMA_WEB;
	$last_id = intval($_REQUEST['last_id']);
	
	if($campaign_id>0){
			$sql = "SELECT *,DATE_FORMAT(published_datetime,'%d/%m/%Y %H:%i:%s') as published_dt  
				FROM smac_report.campaign_feeds
				WHERE id > ".$last_id." AND
				campaign_id = ".$campaign_id." ORDER BY id DESC
				LIMIT ".$limit;
	}
	if(strlen($sql)>0){
		$q = mysql_query($sql,$conn);
		//print mysql_error();
	
		$str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$str.="<rows>";
		$n=0;
	
		//print mysql_error();
	
		while($fetch = mysql_fetch_assoc($q)){
			$str.="<row>";
			$str.="<id>".$fetch['id']."</id>";
			$str.="<published>".$fetch['published']."</published>";
			$str.="<name>".htmlspecialchars($fetch['author_name'])."</name>";
			$str.="<txt>".htmlspecialchars($fetch['content'])."</txt>";
			$str.="<hour_time>".$fetch['hour_time']."</hour_time>";
			$str.="<published_datetime>".$fetch['published_dt']."</published_datetime>";
			$str.="<keyword>".htmlspecialchars($fetch['matching_rule'])."</keyword>";
			$str.="<lat>".$fetch['coordinate_x']."</lat>";
			$str.="<lon>".$fetch['coordinate_y']."</lon>";
			$str.="<reach>".$fetch['followers']."</reach>";
			$str.="<profile_image_url>".htmlspecialchars($fetch['author_avatar'])."</profile_image_url>";
			$str.="<insert_date>".date("d-m-Y H:i:s",strtotime($fetch['retrieve_date']))."</insert_date>";
			$str.="<author_id>".htmlspecialchars($fetch['author_id'])."</author_id>";
			$str.="<wordlist>".$fetch['wordlist']."</wordlist>";
			$str.="<api>get_realtime_feeds</api>";
			$str.="</row>";
			$n++;
		}
		mysql_free_result($q);
		$str.="</rows>";
	}else{
		$str.="<result>0</result>";
	}
	return $str;
}
function get_power_feeds($campaign_id,$last_id=NULL,$limit=0){
	global $conn;$SCHEMA_WEB;
	if($last_id==NULL){
		if($campaign_id>0){
			if($limit>0){
				/*$sql = "SELECT * 
					FROM smac_data.feeds a
					INNER JOIN smac.tbl_campaign_keyword b ON a.tag_id = b.keyword_id
					WHERE b.campaign_id = ".$campaign_id." AND a.retrieve_date >= (NOW() - INTERVAL 1 HOUR) ORDER BY a.id ASC
					LIMIT ".$limit;
				*/
				$sql = "SELECT * 
					FROM smac_data.feeds a
					INNER JOIN ".$SCHEMA_WEB.".tbl_campaign_keyword b ON a.tag_id = b.keyword_id
					WHERE b.campaign_id = ".$campaign_id." ORDER BY a.id ASC
					LIMIT ".$limit;
			}else{
				/*
				$sql = "SELECT * 
					FROM smac_data.feeds a
					INNER JOIN smac.tbl_campaign_keyword b ON a.tag_id = b.keyword_id
					WHERE b.campaign_id = ".$campaign_id." AND a.retrieve_date >= (NOW() - INTERVAL 1 HOUR) ORDER BY a.id ASC
					LIMIT 1000";
				*/
				$sql = "SELECT * 
					FROM smac_data.feeds a
					INNER JOIN ".$SCHEMA_WEB.".tbl_campaign_keyword b ON a.tag_id = b.keyword_id
					WHERE b.campaign_id = ".$campaign_id." ORDER BY a.id ASC
					LIMIT 1000";
			}
		}else{
			//$sql = "SELECT * FROM ".$SCHEMA.".standard_feeds WHERE retrieve_date >= (NOW() - INTERVAL 1 HOUR) ORDER BY id ASC LIMIT 1000";
		}
	}else{
		if($campaign_id>0){
			if($limit>0){
				$sql = "SELECT * 
					FROM smac_data.feeds a
					INNER JOIN ".$SCHEMA_WEB.".tbl_campaign_keyword b ON a.tag_id = b.keyword_id
					WHERE b.campaign_id = ".$campaign_id." AND a.id > ".$last_id." ORDER BY a.id ASC
					LIMIT ".$limit;
			}else{
				$sql = "SELECT * 
					FROM smac_data.feeds a
					INNER JOIN ".$SCHEMA_WEB.".tbl_campaign_keyword b ON a.tag_id = b.keyword_id
					WHERE b.campaign_id = ".$campaign_id." AND a.id > ".$last_id." ORDER BY a.id ASC
					LIMIT 1000";
			}
		}else{
			//$sql = "SELECT * FROM ".$SCHEMA.".standard_feeds WHERE id > ".$last_id." ORDER BY id ASC LIMIT 1000";
		}
	}
	if(strlen($sql)>0){
		$q = mysql_query($sql,$conn);
	
		$str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$str.="<rows>";
		$n=0;
	
		//print mysql_error();
	
		while($fetch = mysql_fetch_assoc($q)){
			$str.="<row>";
			$str.="<id>".$fetch['id']."</id>";
			$str.="<published>".$fetch['published']."</published>";
			$str.="<name>".htmlspecialchars($fetch['author_name'])."</name>";
			$str.="<txt>".htmlspecialchars($fetch['content'])."</txt>";
			$str.="<keyword>".htmlspecialchars($fetch['matching_rule'])."</keyword>";
			$str.="<lat>".$fetch['coordinate_x']."</lat>";
			$str.="<lon>".$fetch['coordinate_y']."</lon>";
			$str.="<profile_image_url>".htmlspecialchars($fetch['author_avatar'])."</profile_image_url>";
			$str.="<insert_date>".date("d-m-Y H:i:s",strtotime($fetch['retrieve_date']))."</insert_date>";
			$str.="</row>";
			$n++;
		}
	
		mysql_free_result($q);
	
		if($n==0){
			//artinya data kosong
			$sql = "SELECT * 
					FROM smac_data.feeds a
					INNER JOIN ".$SCHEMA_WEB.".tbl_campaign_keyword b ON a.tag_id = b.keyword_id
					WHERE b.campaign_id = ".$campaign_id." AND a.retrieve_date < NOW() ORDER BY a.id DESC
					LIMIT 1";
		
		
			//$sql = "SELECT id FROM ".$SCHEMA.".standard_feeds WHERE retrieve_date < NOW() ORDER BY id DESC LIMIT 1";
			$q = mysql_query($sql,$conn);
			$rs = mysql_fetch_assoc($q);
		
		
			mysql_free_result($q);
		
			$str.="<row>";
			$str.="<id>".intval($rs['id'])."</id>";
			$str.="<published></published>";
			$str.="<name></name>";
			$str.="<txt></txt>";
			$str.="<keyword></keyword>";
			$str.="<lat></lat>";
			$str.="<lon></lon>";
			$str.="<profile_image_url></profile_image_url>";
			$str.="<insert_date></insert_date>";
			$str.="</row>";
		}
		$str.="</rows>";
	}else{
		$str.="<result>0</result>";
	}
	return $str;
}

//twitter function
//masih ngambil dari FEED
function get_standard_twitter($campaign_id,$limit=0,$start,$end,$keywords){
	global $conn;
		
		$limit = ( $limit == 0 ) ? '1000' : $limit; 
 		$sql = '';
		if($campaign_id>0){
			$sql = "SELECT * FROM smac_data.feeds a INNER JOIN 
						smac_report.campaign_history b 
						ON a.id = b.feed_id
						WHERE b.campaign_id = '$campaign_id'
						ORDER BY a.id DESC LIMIT $limit;";
			$sql2 = "SELECT * FROM smac_data.feeds a INNER JOIN 
						smac_report.campaign_history b 
						ON a.id = b.feed_id
						WHERE b.campaign_id = '$campaign_id'
						ORDER BY a.id DESC;";
		}
		
		//echo $sql2;exit;
		
	if(strlen($sql)>0){
		
		$q = mysql_query($sql,$conn);
		$q2 = mysql_query($sql2,$conn);
		
		$str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$str.="<rows>";
		
		$num = mysql_num_rows($q2);
		
		$str.="<mentions>".number_format($num)."</mentions>";
		
		$n=0;
		while($fetch = mysql_fetch_assoc($q)){
			
			$str.="<row>";
			$str.="<id>".$fetch['id']."</id>";
			$str.="<published>".$fetch['published']."</published>";
			$str.="<name>".$fetch['author_name']."</name>";
			$str.="<txt>".htmlspecialchars($fetch['content'])."</txt>";
			$str.="<keyword>".htmlspecialchars($fetch['matching_rule'])."</keyword>";
			$str.="<lat>".$fetch['coordinate_x']."</lat>";
			$str.="<lon>".$fetch['coordinate_y']."</lon>";
			$str.="<profile_image_url>".$fetch['author_avatar']."</profile_image_url>";
			$str.="<insert_date>".date("d-m-Y H:i:s",strtotime($fetch['retrieve_date']))."</insert_date>";
			$str.="</row>";
			$n++;
		
		}
	
		mysql_free_result($q);
	
		$str.="</rows>";
	}else{
		$str.="<rows><mentions>0</mentions></rows>";
	}
	return $str;
}
function get_power_twitter($campaign_id,$limit=0,$start,$end){
	global $conn;
		$limit = ( $limit == 0 ) ? '1000' : $limit; 
		$sql='';
		if($campaign_id>0){
				$sql = "SELECT * FROM smac_data.feeds a INNER JOIN 
						smac_report.campaign_history b 
						ON a.id = b.feed_id
						WHERE b.campaign_id = '$campaign_id'
						ORDER BY a.id DESC LIMIT $limit;";
			$sql2 = "SELECT * FROM smac_data.feeds a INNER JOIN 
						smac_report.campaign_history b 
						ON a.id = b.feed_id
						WHERE b.campaign_id = '$campaign_id'
						ORDER BY a.id DESC;";
		}
	
	if(strlen($sql)>0){
		$q = mysql_query($sql,$conn);
		$q2 = mysql_query($sql2,$conn);
		$str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$str.="<rows>";
		
		$num = mysql_num_rows($q2);
		$str.="<mentions>".number_format($num)."</mentions>";
		
		$n=0;
		
		while($fetch = mysql_fetch_assoc($q)){
			$str.="<row>";
			$str.="<id>".$fetch['id']."</id>";
			$str.="<published>".$fetch['published']."</published>";
			$str.="<name>".$fetch['author_name']."</name>";
			$str.="<txt>".htmlspecialchars($fetch['content'])."</txt>";
			$str.="<keyword>".htmlspecialchars($fetch['matching_rule'])."</keyword>";
			$str.="<lat>".$fetch['coordinate_x']."</lat>";
			$str.="<lon>".$fetch['coordinate_y']."</lon>";
			$str.="<profile_image_url>".$fetch['author_avatar']."</profile_image_url>";
			$str.="<insert_date>".date("d-m-Y H:i:s",strtotime($fetch['retrieve_date']))."</insert_date>";
			$str.="</row>";
			$n++;
		}
	
		mysql_free_result($q);
		$str.="</rows>";
	}else{
		$str.="<rows><mentions>0</mentions></rows>";
	}
	return $str;
}
function smac_number($n){
	if($n>1000000){
		$str = round($n/1000000)."M";
	}else if($n>1000){
		$str = round($n/1000)."K";
	}else{
		$str = number_format($n);
	}
	return $str;
}
function get_mentions($txt){
	//$txt = "RT @gelo: woohooo !!! lirik @foobar @may @winners @masters @slavers ";
	preg_match_all('/\@[^\@^\:^\ ]*/',$txt,$matches);
	$mentions = array();
	foreach($matches[0] as $m){
		$mentions[] = str_replace("@","",trim($m));		
	}
	return $mentions;
}
/**
 * @param unknown_type $txt
 * @param unknown_type $sentiments array of sentiment keywords
 * @return int
 */
function get_potential_impact_score($txt,$sentiments){
	$arr_names = get_mentions($txt);
	foreach($arr_names as $names){
		$txt = str_replace("@".$names,"", $txt);	
	}
	$words = str_word_count($txt,1);
	$n=0;
	$score=0;
	foreach($words as $w){
	 	foreach($sentiments as $sentiment){
	 		if(strcmp(trim($w),trim($sentiment['keyword']))==0){
	 			$n_score = $sentiment['score'];
	 			if($sentiment['category']=="unfavourable"){
	 				$n_score*=-1;
	 			}
	 			$score+=$n_score;
	 		}
	 	}	
	}
	return $score;
}
function get_device($subject,$devices){
	foreach($devices as $device){
		if(eregi($device['descriptor'],$subject)){
			return $device['device_type'];
		}
	}
	return "other";
}
function clean_ascii($output){
	return preg_replace('/[^(\x20-\x7F)]*/','', $output);
}
function fetch($sql,$conn){
	$q = mysql_query($sql,$conn);
	$f = mysql_fetch_assoc($q);
	mysql_free_result($q);
	return $f;
}
function fetch_many($sql,$conn){
	$q = mysql_query($sql,$conn);
	$rs = array();
	while($f = mysql_fetch_assoc($q)){
		$rs[] = $f;
	}
	mysql_free_result($q);
	return $rs;
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
function clean($val){
	//print $val."-";
	$val = cleanXSS($val);
	//print $val."-";
	$val = mysql_escape_string($val);
	//print $val;
	return $val;
}
function reformat_rule($str){
	$str = @eregi_replace("(lang\:.*)","",$str);
	return $str;
}
/**
 * check if the campaign is under new schema (dynamic feed_wordlist etc)
 * @param $campaign_id
 * @return unknown_type
 */
function is_new_schema($campaign_id,$conn){
	$sql = "SELECT n_status FROM smac_report.tbl_feed_wordlist_flag WHERE campaign_id={$campaign_id} LIMIT 1";
	$rs = fetch($sql,$conn);
	return $rs['n_status'];
}
/**
 * check if the campaign is using new campaign_feeds schema
 * @param $campaign_id
 * @return unknown_type
 */
function is_new_feeds($campaign_id,$conn){
	$sql = "SELECT n_status FROM smac_report.campaign_feeds_split_flag WHERE campaign_id={$campaign_id} LIMIT 1";
	$rs = fetch($sql,$conn);
	if($rs['n_status']==1){
		return true;
	}
}
?>