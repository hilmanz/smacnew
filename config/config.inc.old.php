<?php
$CONFIG['LOG_DIR'] = "../logs/";
$GLOBAL_PATH = "../";
$APP_PATH = "../com/";
$ENGINE_PATH = "../engines/";
$WEBROOT = "../html/";

//set aplikasi yang digunakan
define('APPLICATION','smac');
$CONFIG['DEVELOPMENT'] = false;
$CONFIG['MAINTENANCE'] = true;

//WEB APP BASE DOMAIN
$CONFIG['BASE_DOMAIN'] = "smacapp.com";

//SMAC DATA API
$CONFIG['API_BASEURL'] = "http://localhost/flex/";

//authentication API
$CONFIG['AUTH_API'] = "http://localhost/smac/web/authentication/";

if($CONFIG['DEVELOPMENT']){
	$CONFIG['DATABASE'][0]['HOST'] 	= "localhost";
	$CONFIG['DATABASE'][0]['USERNAME'] 	= "root	";
	$CONFIG['DATABASE'][0]['PASSWORD'] 	= "root";
	$CONFIG['DATABASE'][0]['DATABASE'] 	= "smac_web";
}else{
	$CONFIG['DATABASE'][0]['HOST'] 				= "localhost";
	$CONFIG['DATABASE'][0]['USERNAME'] 	= "root";
	$CONFIG['DATABASE'][0]['PASSWORD'] 	= "root";
	$CONFIG['DATABASE'][0]['DATABASE'] 	= "smac_web";
}
$CONFIG['MAINTENANCE_MODE'] = false;
$CONFIG['BASEURL'] = "http://smacapp.loc/";
/* API BASEURL */
$CONFIG['API_BASEURL'] = "http://dev.smac.me/api/";
$CONFIG['MARKET_API_BASEURL'] = "http://dev.smac.me/api2/";

/* DATETIME SET */
$timeZone = 'Asia/Jakarta';
date_default_timezone_set($timeZone);

$CONFIG['EMAIL_PROSES'] = false;
$CONFIG['EMAIL_AGENCY'] = "agency@smac.me";

$SMAC_SECRET = sha1("12toinfinityandbeyond34");
$SMAC_HASH = sha1("gettingolderallthetime");

/* SMTP */
$CONFIG['EMAIL_SMTP_HOST'] = "localhost";
$CONFIG['EMAIL_SMTP_PORT'] = "25";   
$CONFIG['EMAIL_SMTP_SSL'] = false;  
$CONFIG['EMAIL_SMTP_USER'] = "";    
$CONFIG['EMAIL_SMTP_PASSWORD'] = "";      
$CONFIG['EMAIL_FROM_DEFAULT'] = "info@smac.me";

//TWITTER OAUTH
$TWITTER['KEY'] = "09PTyfAEVmnpde3n9xtBbw";
$TWITTER['SECRET'] = "nemonUAXkcCAJQv7Bc1tRuX3Xzx35a4D5SEdCoVkCU";


//TRIAL ACCOUNT CONFIG
$TRIAL['TOPIC_LIMIT'] = 2;


//ACCOUNT COSTS
//ini diimplement nanti.
$TRIAL['COST'] = 0;
$TRIAL['CREDITS'] = 0;
$PAID['COST'] = 450; //in US dollar
$PAID['CREDITS'] = 1;
$ENT['COST'] = 450; //in US dollar
$ENT['CREDITS'] = 1;
?>
