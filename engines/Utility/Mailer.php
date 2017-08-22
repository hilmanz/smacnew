<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/smtp/SMTPMailer.php";
include_once $ENGINE_PATH."Utility/postmark/Postmark.php";
class Mailer extends SMTPMailer{
	var $headers;
	var $subject;
	var $message;
	var $from;
	var $to;
	var $helper;
	var $is_postmark;
	function Mailer(){
		$this->is_postmark = false; //from now on, we default to postmarkapp to send the email
		$this->setDefaultHeaders();
	}
	function setDefaultHeaders(){
		global $CONFIG;
		$headers  = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-Type: text/html". "\r\n".
                   //"Content-Transfer-Encoding: 7bit". "\r\n";
		//$headers .= "List-Unsubscribe: <mailto:".$CONFIG['MAIL_UNSUBSCRIBE'].">\r\n";
		$headers .= "X-campaignID: ".base64_encode(date("YmdHis"))."\r\n";
		//$headers .= "Message-ID: <".date("YmdHis").".mailer@".$CONFIG['MAILER'].">\r\n";
		
		$this->headers = $headers;
	}
	function use_postmark($f){
		if($f==true){
			$this->helper = new Mail_Postmark();
		}
		$this->is_postmark = $f;
	}
	
	function send($params=null){
		if($this->is_postmark){
			// /$this->helper->debug(Mail_Postmark::DEBUG_VERBOSE);
			return $this->helper->addTo($params['to']['email'], $params['to']['name'])
					    ->subject($params['subject'])
					    ->messagePlain($params['plainText'])
					    ->messageHtml($params['htmlText'])
					    ->send();
		}
		else{
			return parent::send();
		}
		return false;
	}
}
?>