<?php
global $ENGINE_PATH;
require_once $ENGINE_PATH . "/Utility/Paginate.php";
class credits extends SQLData{
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		//$this->User = new UserManager();
	}
	public function admin(){
		if($this->Request->getPost('act')=="submit"){
			return $this->add_credits();
		}
		if($this->Request->getParam("act")=="add"){
			//add credits
			return $this->add();
		}else if($this->Request->getParam("act")=="transactions"){
			//user's transactions
			return $this->transactions();
		}else{
			//browse credits
			return $this->browse();
		}
	}
	public function add(){
		if(intval($this->Request->getParam("account_id"))>0){
			$account_id = intval($this->Request->getParam("account_id"));
			$account = $this->fetch("SELECT name FROM smac_web.smac_account 
						WHERE id = {$account_id} LIMIT 1");
			$this->View->assign("name",$account['name']);
			$this->View->assign("account_id",$account_id);
			return $this->View->toString("smac/admin/credits_add.html");
		}else{
			return $this->View->showMessage("Invalid user account !", "?s=credits&act=browse");
		}
	}
	public function add_credits(){
		$account_id = intval($this->Request->getPost('account_id'));
		$amount = intval($this->Request->getPost('amount'));
		$transaction_detail = cleanXSS(mysql_escape_string($this->Request->getPost('reason')));
		$sql = "INSERT INTO `smac_billing`.`tbl_credits` 
				(
				`account_id`, 
				`amount`, 
				`submit_date`, 
				`transaction_detail`
				)
				VALUES
				(
				{$account_id}, 
				{$amount}, 
				NOW(), 
				'{$transaction_detail}'
				);";
		$q = $this->query($sql);
		$sql = "SELECT name FROM smac_web.smac_account WHERE id={$account_id} LIMIT 1";
		$acc = $this->fetch($sql);
		if($q){
			return $this->View->showMessage("The credit has been added to {$acc['name']}'s account","?s=credits&act=browse");
		}else{
			return $this->View->showMessage("Cannot add new credit to {$acc['name']}'s account. Please try again later !","?s=credits&act=browse");
		}
	}
	public function transactions($total=50){
		
		$account_id = intval($this->Request->getParam('account_id'));
		$start = intval($this->Request->getParam("st"));
		if($account_id==0){
			return $this->View->showMessage("Invalid user account !", "?s=credits&act=browse");
		}else{
			$sql = "SELECT name FROM smac_web.smac_account WHERE id={$account_id} LIMIT 1";
			$acc = $this->fetch($sql);
			$this->View->assign("name",$acc['name']);
			
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
			$rs = $this->fetch($sql,1);
			foreach($rs as $n=>$t){
				$rs[$n]['transaction_date'] = date("d/m/Y H:i",$t['transaction_time']);
			}
			$total_rows = $this->get_total_transactions($account_id);
			$this->View->assign("data",$rs);
			$paging = new Paginate();
			
			$this->View->assign("paging",$paging->generate($start,$total,$total_rows,"?s=credits&act=transactions&account_id={$account_id}"));
			return $this->View->toString("smac/admin/credits_transactions.html");
		}
	}
	public function get_total_transactions($account_id){
		
		$sql = "SELECT COUNT(*) as total FROM smac_billing.tbl_credits WHERE account_id=".$account_id." LIMIT 1";
		$credits = $this->fetch($sql);
		
		$sql = "SELECT COUNT(*) as total FROM smac_billing.tbl_debets WHERE account_id=".$account_id." LIMIT 1";
		$debets = $this->fetch($sql);
		
		return (intval($credits['total']) + intval($debets['total']));
	}
	public function browse($total = 50){
		$start = intval($this->Request->getParam("st"));
		$data = $this->_browseData($start,$total);
		$rs = array();
		$sql = "SELECT COUNT(id) as total FROM smac_web.smac_account";
		$rows = $this->fetch($sql);
		$account = $this->fetch($sql,1);
		if(sizeof($data)>0){
			foreach($data as $account_id=>$d){
				$account_id = intval($account_id);
				$sql = "SELECT name,email FROM smac_web.smac_account WHERE id={$account_id} LIMIT 1";
				$acc = $this->fetch($sql);
				$rs[] = array('account_id'=>$account_id,'name'=>$acc['name'],'email'=>$acc['email'],'credits'=>$d['credits'],'debits'=>$d['debits']);
				unset($data[$account_id]);
				unset($acc);
			}
		}
		unset($data);
		$this->View->assign("data",$rs);
		$paging = new Paginate();
		$this->View->assign("paging",$paging->generate($start,$total,$rows['total'],"?s=credits&act=browse"));
		return $this->View->toString('smac/admin/credits.html');
	}
	private function _browseData($start,$total){
		$sql = "SELECT id AS account_id FROM smac_web.smac_account LIMIT {$start},{$total};";
		$account = $this->fetch($sql,1);
		$n = sizeof($account);
		$account_id = array();
		for($i=0;$i<$n;$i++){
			$account_id[] = $account[$i]['account_id'];
			
		}
		$str = implode(',',$account_id);
		$sql = "SELECT account_id,SUM(amount) AS credit,0 AS debet 
				FROM smac_billing.tbl_credits 
				WHERE account_id IN ({$str})
				GROUP BY account_id;";
		$credits = $this->fetch($sql,1);
		$sql = "SELECT account_id,0 as credit,SUM(amount) AS debet 
				FROM smac_billing.tbl_debets 
				WHERE account_id IN ({$str})
				GROUP BY account_id;";
		$debets = $this->fetch($sql,1);
		$n = sizeof($account_id);
		$acc = array();
		for($i=0;$i<$n;$i++){
			$acc[$account_id[$i]] = array('credits'=>0,'debits'=>0);
		}
		unset($account_id);
		$n=sizeof($credits);
		for($i=0;$i<$n;$i++){
			$acc[$credits[$i]['account_id']]['credits']+=intval($credits[$i]['credit']);
		}
		unset($credits);
		$n=sizeof($debets);
		for($i=0;$i<$n;$i++){
			$acc[$debets[$i]['account_id']]['debits']+=intval($debets[$i]['debet']);
		}
		return $acc;
	}
}
?>