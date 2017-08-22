<?php
class session_helper{
	function __construct(){
		
	}
	function generate_session($user_id){
		global $SMAC_SECRET;
		
		$current_timestamp = time();
		$session_token = urlencode64($current_timestamp."|".$user_id."|".($current_timestamp+(60*60)));
		return $session_token;
	}
	
}
?>