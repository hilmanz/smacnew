<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
include_once $ENGINE_PATH."Utility/Mailer.php";
class message extends SQLData{
	
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
	}
	
	function admin(){
		$act = $this->Request->getParam('act');
		if( $act == 'add' ){
			return $this->add();
		}elseif( $act == 'edit' ){
			return $this->edit();
		}elseif( $act == 'delete' ){
			return $this->delete();
		}else{
			return $this->messageList();
		}
	}
	
	function messageList(){
		$start = intval($this->Request->getParam('st'));
		$s = mysql_escape_string($this->Request->getParam('find'));
		$where = '';
		$param = '';
		if($s != ''){
			$where = "AND a.name LIKE '%".$s."%' ";
			$param = '&s='.$s;
		}
		
		$qry = "SELECT
						count(*) as total
					FROM 
						smac_web.smac_message m
						LEFT JOIN smac_web.smac_account a
						ON m.account_id=a.id
					WHERE 
						1 $where;";
		$list = $this->fetch($qry);
		$total = $list['total'];
		$total_per_page = 50;
		
		$qry = "SELECT
						m.*,
						a.name
					FROM 
						smac_web.smac_message m
						LEFT JOIN smac_web.smac_account a
						ON m.account_id=a.id
					WHERE 
						1 $where
					ORDER BY
						m.date_add DESC
					LIMIT $start,$total_per_page;";
		$list = $this->fetch($qry,1);
		
		$this->View->assign('list',$list);
		
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=message".$param));
		
		return $this->View->toString("smac/admin/message.html");
		
	}
	
	function add(){
		
		if( intval($this->Request->getPost('add') == 1) ){
			
			$_type = mysql_escape_string($this->Request->getPost('type'));
			$_account_id = intval($this->Request->getPost('account_id'));
			$_notification = mysql_escape_string($this->Request->getPost('notification'));
			
			//echo $_type . ' - ' . $_account_id . ' - ' . $_notification . ' - '. $this->Request->getPost('account_id');
			//exit;
			
			if( $_type != '' and $_account_id > 0 and $_notification != ''){
				
				if( $_type == 'all' ){
					$qry = "INSERT INTO smac_web.smac_message (notification,date_add,type) VALUES ('".$_notification."',NOW(),'all');";
				}else{
					$qry = "INSERT INTO smac_web.smac_message (account_id,notification,date_add,type) VALUES ('".$_account_id."','".$_notification."',NOW(),'personal');";
				}
				
				if( $this->query($qry) ){
					return $this->View->showMessage('Add message success!','?s=message');
				}else{
					//echo mysql_error();exit;
					$this->View->assign('err','Failed add message, please try again!');
				}
				
			}else{
				$this->View->assign('err','Please complete the form!');
			}
			
		}
		
		$qry = "SELECT id,name FROM smac_web.smac_account WHERE n_status='1';";
		$list = $this->fetch($qry,1);
		$this->View->assign('list',$list);
		return $this->View->toString("smac/admin/message-add.html");
	}
	
	function edit(){
	
		$id = intval($this->Request->getParam('id'));
		
		if( intval($this->Request->getPost('edit') == 1) ){
			
			$_type = mysql_escape_string($this->Request->getPost('type'));
			$_account_id = intval($this->Request->getPost('account_id'));
			$_notification = mysql_escape_string($this->Request->getPost('notification'));
			
			//echo $_type . ' - ' . $_account_id . ' - ' . $_notification . ' - '. $this->Request->getPost('account_id');
			//exit;
			
			if( $_type != '' and $_account_id > 0 and $_notification != ''){
				
				if( $_type == 'all' ){
					$qry = "UPDATE smac_web.smac_message SET notification='".$_notification."', type='all' WHERE id='".$id."';";
				}else{
					$qry = "UPDATE smac_web.smac_message SET notification='".$_notification."', type='personal', account_id='".$_account_id."' WHERE id='".$id."';";
				}
				
				//echo $qry;exit;
				
				if( $this->query($qry) ){
					return $this->View->showMessage('Edit message success!','?s=message');
				}else{
					//echo mysql_error();exit;
					$this->View->assign('err','Failed add message, please try again!');
				}
				
			}else{
				$this->View->assign('err','Please complete the form!');
			}
			
		}
		
		$qry = "SELECT * FROM smac_web.smac_message WHERE id='".$id."'";
		$rs = $this->fetch($qry);
		$this->View->assign('rs',$rs);
		$qry = "SELECT id,name FROM smac_web.smac_account WHERE n_status='1';";
		$list = $this->fetch($qry,1);
		$this->View->assign('list',$list);
		$this->View->assign('id',$id);
		return $this->View->toString("smac/admin/message-edit.html");
	}
	
	function delete(){
	
		$id = intval($this->Request->getParam('id'));
		
		$qry = "DELETE FROM smac_web.smac_message WHERE id='".$id."';";
		
		if( $this->query($qry) ){
			return $this->View->showMessage('Delete message success!','?s=message');
		}else{
			return $this->View->showMessage('Delete message failed!','?s=message');
		}
		
	}
	
}
?>