<?php
include_once $APP_PATH."/smac/helper/FBHelper.php";
class market_model extends base_model{
	private $request;
	function setRequestHandler($req){
		$this->request = $req;
	}
	function summary($campaign_id){
		$data = array("map"=>$this->map_data($campaign_id),
					  "top_country"=>$this->top_country($campaign_id)
					  );
		return $data;
	}
	function map_data($campaign_id){
		
	}
	function top_country($campaign_id){
		$impression = 0;
		$sql = "SELECT country_id,
				SUM(total_mention) as mentions,
				SUM(total_author) as people,
				SUM(total_impression) as impression
				FROM 
				smac_report.daily_country_volume 
				WHERE 
				campaign_id={$campaign_id}
				GROUP BY country_id
				ORDER BY mentions DESC";
		$result = $this->fetch($sql,1);
		if(sizeof($result)>0){
			foreach($result as $n=>$r){
					$impression+=$r['impression'];
					$sql = "SELECT country FROM smac_data.geo_country WHERE iso='{$r['country_id']}' LIMIT 1";
					$country = $this->fetch($sql);
					$sql = "SELECT SUM(total_mention_positive) as positive,SUM(total_mention_negative) as negative 
							FROM smac_report.daily_country_campaign_sentiment 
							WHERE campaign_id={$campaign_id} AND country_code='{$r['country_id']}';";
					$sentiment = $this->fetch($sql);
					$sql = "SELECT SUM(total_mention_positive) as positive,SUM(total_mention_negative) as negative 
							FROM smac_report.daily_country_campaign_sentiment 
							WHERE campaign_id={$campaign_id} AND country_code='{$r['country_id']}';";
					$sentiment = $this->fetch($sql);
					$sql = "SELECT
							channel,SUM(total_mention) as total
							FROM 
							smac_report.daily_country_volume 
							WHERE 
							campaign_id={$campaign_id} AND country_id='{$r['country_id']}'
							GROUP BY country_id,channel;";
					
					$channel = $this->fetch($sql,1);
					$result[$n]['twitter'] = 0;
					$result[$n]['facebook'] = 0;
					$result[$n]['web'] = 0;
					foreach($channel as $c){
						switch($c['channel']){
							case 2:
								$result[$n]['facebook'] = $c['total'];
							break;
							case 3:
								$result[$n]['web'] = $c['total'];
							break;
							default:
								$result[$n]['twitter'] = $c['total'];
							break;
						}
					}
					$result[$n]['country'] = $country['country'];
					$result[$n]['sentiment_positive'] = intval($sentiment['positive']);
					$result[$n]['sentiment_negative'] = intval($sentiment['negative']);
					$result[$n]['sentiment_neutral'] = abs($r['mentions'] - ($result[$n]['sentiment_positive']+$result[$n]['sentiment_negative']));
			}
		}
		if(sizeof($result)>0){
			foreach($result as $n=>$r){
				$result[$n]['share'] = round(($r['impression'] / $impression)*100,1);
				$result[$n]['resolution'] = 0;
			}
		}
		return $result;
	}
	
	function feeds($campaign_id,$country_code,$start,$total=10){
		$country_code = mysql_escape_string($country_code);
		$start = intval($start);
		$total = intval($total);


		$sql = "SELECT a.feed_id,a.published_datetime,a.author_id,
				a.author_name,a.author_avatar,a.content 
				FROM smac_feeds.campaign_feeds_{$campaign_id} a
				INNER JOIN smac_report.country_twitter b
				ON a.feed_id = b.feed_id AND b.country_code='{$country_code}'
				WHERE a.campaign_id={$campaign_id} 
				ORDER BY a.id DESC LIMIT {$start},{$total}";
		$result = $this->fetch($sql,1);
		
		//total rows
		$sql = "SELECT COUNT(a.id) as total
				FROM smac_feeds.campaign_feeds_{$campaign_id} a
				INNER JOIN smac_report.country_twitter b
				ON a.feed_id = b.feed_id AND b.country_code='{$country_code}'
				WHERE a.campaign_id={$campaign_id} LIMIT 1";
		$rows = $this->fetch($sql);
		
		foreach($result as $n=>$v){
				//check for flag
				$result[$n]['flag'] = $this->is_workflow_flag($campaign_id,$result[$n]['feed_id'],1);
				//-->end of check
				$reply_url = str_replace("req=","",$this->request->encrypt_params($c));
				$result[$n]['reply_url'] = "";
		}
		return array("feeds"=>$result,"total_rows"=>$rows['total']);
	}
}
?>