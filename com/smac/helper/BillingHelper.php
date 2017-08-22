<?php
/**
 * 
 * @author duf
 * @todo
 * need to build paypal integration
 */
class BillingHelper extends Application{
	function purchase_topic($account_id,$topic_id,$amount){
		$account_id = intval($account_id);
		$ok = false;
		$this->open(0);
		$sql = "INSERT IGNORE INTO smac_billing.tbl_debets(account_id,amount,debit_time,debit_time_ts,topic_id)
				VALUES(".$account_id.",".$amount.",NOW(),".time().",".$topic_id.")";
		$q = $this->query($sql);
		$debet_id = mysql_insert_id();
		if($q){
			$sql = "SELECT account_type FROM smac_web.smac_account WHERE id = {$account_id} LIMIT 1";
			$acc = $this->fetch($sql);
			if($acc['account_type']==99||$acc['account_type']==0){
				$expired_time = time()+(60*60*24*14);
			}else{
				$expired_time = time()+(60*60*24*30);
			}
			$sql = "INSERT INTO smac_billing.tbl_campaign_expiry(campaign_id,expired_ts,expired_dt)
				VALUES(".$topic_id.",".$expired_time.",'".date("Y-m-d H:i:s",$expired_time)."')";
			$q = $this->query($sql);
			if($q){
				$ok = true;
			}
		}else{
			//cancel the debet because the campaign expiry date is failed to get updated.
			$sql = "DELETE FROM smac_billing.tbl_debets WHERE id=".$debet_id;
			$this->query($sql);
		}
		$this->close();
		return $ok;
	}
	/**
	 *@param $payment_type 1->manual credit, 2->paypal credit,0-> free cash
	 */
	function addCredit($account_id,$amount,$payment_type=1,$transaction_id=""){
		if($payment_type==1){
			$str = "Manual Credit";
		}else if($payment_type==2){
			$str = "Credited from Paypal, TransactionID : {$transaction_id}";
		}else{
			$str = "Free Credit";
		}
		$this->open(0);
		$sql = "INSERT INTO `smac_billing`.`tbl_credits` 
				(
				`account_id`, 
				`amount`, 
				`submit_date`, 
				`transaction_detail`
				)
				VALUES
				(
				'{$account_id}', 
				'{$amount}', 
				NOW(), 
				'{$str}'
				);";
		$q = $this->query($sql);
		$this->close();
		if($q){return true;}
	}
	function create_order($account_id,$topic_id,$orders,$n_status=0){
		$transaction_id = $account_id."-".date("YmdHis")."-".$topic_id;
		$this->open(0);
		$sql ="INSERT INTO `smac_billing`.`tbl_order` 
				(
				`transaction_id`, 
				`transaction_date`, 
				`campaign_id`, 
				`paypal_transaction_id`, 
				`paypal_transaction_date`, 
				`last_update`, 
				`n_status`
				)
				VALUES
				(
				'{$transaction_id}', 
				NOW(), 
				$topic_id, 
				'', 
				'', 
				NOW(), 
				{$n_status}
				);";
		$q = $this->query($sql);
		$order_id = mysql_insert_id();
		if($order_id>0){
			if(sizeof($orders)>0){
				foreach($orders as $order){
					$sql = "INSERT INTO `smac_billing`.`tbl_order_item` 
							(
							`order_id`, 
							`item_id`, 
							`description`, 
							`price`
							)
							VALUES
							(
							{$order_id}, 
							{$order['item_id']}, 
							'{$order['description']}', 
							{$order['price']}
							);";
					$this->query($sql);
				}
			}
		}
		$this->close();
		if($q){return true;}
	}
	function is_enough_credits($account_id,$amount){
			$saldo = $this->get_saldo($account_id);
			$diff = $saldo - $amount;
			if($diff>=0){
				return true;
			}
	}
	/**
	 * get current balance
	 * @deprecated
	 * @param $account_id user's account id
	 * @return integer balance
	 */
	function get_saldo($account_id){
		$this->open(0);
		$sql = "SELECT SUM(amount) as total FROM smac_billing.tbl_credits WHERE account_id=".$account_id." LIMIT 1";
		$credits = $this->fetch($sql);
		$sql = "SELECT SUM(amount) as total FROM smac_billing.tbl_debets WHERE account_id=".$account_id." LIMIT 1";
		$debets = $this->fetch($sql);
		$this->close();
		
		$saldo = $credits['total'] - $debets['total'];
		return $saldo;
	}
	/**
	 * @deprecated
	 */
	function getSummary($account_id){
		$this->open(0);
		$sql = "SELECT SUM(amount) as total FROM smac_billing.tbl_credits WHERE account_id=".$account_id." LIMIT 1";
		$credits = $this->fetch($sql);
		
		$sql = "SELECT SUM(amount) as total FROM smac_billing.tbl_debets WHERE account_id=".$account_id."  LIMIT 1";
		$debets = $this->fetch($sql);
		$this->close();
		return array('credit'=>intval($credits['total']),"debet"=>intval($debets['total']),
					"balance"=>intval($credits['total'])-intval($debets['total']));
						
	}
	/**
	 * @deprecated
	 */
	function get_transactions($account_id,$start,$total=50){
		$start = intval($start);
		$total = intval($total);
		$sql = "SELECT transaction_time,transaction_type,amount,topic_id,topic_name as transaction_detail
				FROM (SELECT debit_time_ts as transaction_time,'debet' as transaction_type,amount,
				topic_id,campaign_name as topic_name 
				FROM smac_billing.tbl_debets a
				INNER JOIN smac_web.tbl_campaign b
				ON a.topic_id = b.id
				WHERE a.account_id={$account_id}
				UNION ALL
				SELECT UNIX_TIMESTAMP(submit_date) as transaction_time,
				'credit' as transaction_type,amount,0,transaction_detail
				FROM smac_billing.tbl_credits
				WHERE account_id={$account_id}) a
				ORDER BY transaction_time
				LIMIT {$start},{$total}
				;";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		return $rs;
	}
	function get_total_transactions($account_id){
		$this->open(0);
		$sql = "SELECT COUNT(*) as total FROM smac_billing.tbl_credits WHERE account_id=".$account_id." LIMIT 1";
		$credits = $this->fetch($sql);
		
		$sql = "SELECT COUNT(*) as total FROM smac_billing.tbl_debets WHERE account_id=".$account_id." LIMIT 1";
		$debets = $this->fetch($sql);
		$this->close();
		
		return (intval($credits['total']) + intval($debets['total']));
	}
	function get_orders($account_id,$status,$start,$total=30){
		$this->open(0);
		$status = intval($status);
		$start = intval($start);
		//get all the topics
		if($status==1){
		
		$sql = "SELECT a.id,campaign_name,campaign_start,n_status,MAX(expired_ts) as expired_ts
				FROM smac_web.tbl_campaign a
				LEFT JOIN smac_billing.tbl_campaign_expiry b
				ON a.id = b.campaign_id
				WHERE client_id={$account_id} AND a.n_status < 2 AND (expired_ts IS NULL OR expired_ts > UNIX_TIMESTAMP())
				GROUP BY a.id
				ORDER BY a.id DESC 
				LIMIT {$start},{$total}";
		}else{
			$sql = "SELECT a.id,campaign_name,campaign_start,n_status,MAX(expired_ts) as expired_ts
					FROM smac_web.tbl_campaign a
					LEFT JOIN smac_billing.tbl_campaign_expiry b
					ON a.id = b.campaign_id
					WHERE client_id={$account_id} AND a.n_status < 2 AND expired_ts < UNIX_TIMESTAMP()
					GROUP BY a.id
					ORDER BY a.id DESC;";	
		}
		
		$this->force_utf8(false);
		$topics = $this->fetch($sql,1);
		$this->force_utf8(true);		
		if(is_array($topics)){
			foreach($topics as $n=>$topic){
				$topics[$n]['campaign_name'] = html_entity_decode(mb_convert_encoding($topic['campaign_name'], 'HTML-ENTITIES', 'utf-8'), ENT_QUOTES, 'UTF-8' );
				$topics[$n]['campaign_start'] = date("d/m/Y",strtotime($topics[$n]['campaign_start']));
				if(intval($topics[$n]['expired_ts'])==0){
					$topics[$n]['expired'] = "";
				}else{
					$topics[$n]['expired'] = date("d/m/Y",$topics[$n]['expired_ts']);
				}
				
				$topics[$n]['days_left'] = $this->get_days_left($topics[$n]);
				$topics[$n]['last_order'] = $this->get_latest_order($topic['id']);
				$topics[$n]['checkout_url'] = "?".$this->Request->encrypt_params(array("page"=>"account","act"=>"checkout","id"=>$topics[$n]['last_order']['id']));
				$topics[$n]['extend_url'] = "?".$this->Request->encrypt_params(array("page"=>"account","act"=>"extend","id"=>$topics[$n]['last_order']['id']));
				$topics[$n]['cancel_url'] = "?".$this->Request->encrypt_params(array("page"=>"account","act"=>"cancel_order","id"=>$topics[$n]['last_order']['id']));
			}
		}
		
		$this->close();
		
		return $topics;
	}

	function get_order($order_id){
			$this->open(0);
			$sql = "SELECT * FROM smac_billing.tbl_order WHERE id = {$order_id} LIMIT 1";
			
			$rs = $this->fetch($sql);
			//items
			if(is_array($rs)){
				$sql = "SELECT * FROM smac_billing.tbl_order_item WHERE order_id = {$rs['id']} LIMIT 20";
				$items = $this->fetch($sql,1);
				$rs['items'] = $items;
			}
			unset($items);
			$total_price = 0;
			if(is_array($rs['items'])){
				foreach($rs['items'] as $n=>$item){
					$total_price+=intval($item['price']);
				}
			}
			$rs['total_price'] = $total_price;
			
			
			$this->close();
			return $rs;
	}
	function getOrderByTransactionId($transaction_id){
		$transaction_id = mysql_escape_string($transaction_id);
		$this->open(0);
		$sql = "SELECT * FROM smac_billing.tbl_order WHERE transaction_id = '{$transaction_id}' LIMIT 1";
		
		$rs = $this->fetch($sql);
		//items
		if(is_array($rs)){
			$sql = "SELECT * FROM smac_billing.tbl_order_item WHERE order_id = {$rs['id']} LIMIT 20";
			$items = $this->fetch($sql,1);
			$rs['items'] = $items;
		}
		unset($items);
		$total_price = 0;
		if(is_array($rs['items'])){
			foreach($rs['items'] as $n=>$item){
				$total_price+=intval($item['price']);
			}
		}
		$rs['total_price'] = $total_price;
		
		
		$this->close();
		return $rs;
	}
	/**
	 * get latest transaction for the topic.
	 * for old topic, they might not have the latest transaction yet.. so we need to create one for them.
	 * remember.. only for paid account.
	 */
	function get_latest_order($campaign_id){
		$sql = "SELECT * FROM smac_billing.tbl_order WHERE campaign_id={$campaign_id} ORDER BY id DESC";
		$rs = $this->fetch($sql);
		//items
		if(is_array($rs)){
			$sql = "SELECT * FROM smac_billing.tbl_order_item WHERE order_id = {$rs['id']} LIMIT 20";
			$items = $this->fetch($sql,1);
			$rs['items'] = $items;
		}
		unset($items);
		$total_price = 0;
		if(is_array($rs['items'])){
			foreach($rs['items'] as $n=>$item){
				$total_price+=intval($item['price']);
			}
		}
		$rs['total_price'] = $total_price;
		return $rs;
	}
	function get_days_left($topic){
		$today = time();
		$end = $topic['expired_ts'];
		$diff = $end - $today;
		if($diff<0){
			$diff = 0;
		}
		$diff = round($diff/(60*60*24));
		return $diff;
	}
	function get_total_orders($account_id,$status){
		$status = intval($status);
		$this->open(0);
		//get all the topics
		if($status==1){
			$sql = "SELECT COUNT(*) as total
				FROM smac_web.tbl_campaign a
				LEFT JOIN smac_billing.tbl_campaign_expiry b
				ON a.id = b.campaign_id
				WHERE client_id={$account_id} AND a.n_status < 2 AND  (expired_ts IS NULL OR expired_ts > UNIX_TIMESTAMP())
				";
			
		}else{
			$sql = "SELECT COUNT(*) as total
				FROM smac_web.tbl_campaign a
				LEFT JOIN smac_billing.tbl_campaign_expiry b
				ON a.id = b.campaign_id
				WHERE client_id={$account_id} AND a.n_status < 2 AND expired_ts < UNIX_TIMESTAMP()
				";
		}
		$rs = $this->fetch($sql);
		$this->close();
		return intval($rs['total']);
	}
	function order_completed($transaction_id,$paypal_trans_id){
		$this->open(0);
		//get all the topics
		$sql = "UPDATE smac_billing.tbl_order
				SET paypal_transaction_id='{$paypal_trans_id}',paypal_transaction_date = NOW(),
				n_status=1
				WHERE transaction_id='{$transaction_id}';";
		
		$rs = $this->query($sql);
		$this->close();
		return $rs;
	}
	function cancel_order($order_id){
		$this->open(0);
		//get all the topics
		$sql = "UPDATE smac_billing.tbl_order
				SET 
				n_status=2
				WHERE id='{$order_id}';";
		$rs = $this->query($sql);
		$this->close();
		
		return $rs;
	}
	function is_topic_expired($campaign_id){
		$sql = "SELECT expired_ts FROM smac_billing.tbl_campaign_expiry WHERE campaign_id={$campaign_id} ORDER BY id DESC LIMIT 1;";
		$rs = $this->fetch($sql);
		if(is_array($rs)){
			if($rs['expired_ts']<time()){
				return true;
			}
		}
	}
	function is_topic_unpaid($campaign_id){
		$sql = "SELECT n_status FROM smac_billing.tbl_order WHERE campaign_id={$campaign_id} ORDER BY id DESC LIMIT 1;";
		$rs = $this->fetch($sql);
		if($rs['n_status']==0){
			return true;
		}
	}
	function check_topic_status($campaign_id){
		$campaign_id = intval($campaign_id);
		$this->open(0);
		if($this->is_topic_expired($campaign_id)){
			$this->close();
			return 1;
		}else if($this->is_topic_unpaid($campaign_id)){
			$this->close();
			return 2;
		}else{
			return 0;
		}
		return 0;
	}

}
?>