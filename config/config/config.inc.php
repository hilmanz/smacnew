<?php
error_reporting(0);
include_once "error.inc.php";
$CONFIG['LOG_DIR'] = "../logs/";
$GLOBAL_PATH = "../";
$APP_PATH = "../com/";
$ENGINE_PATH = "../engines/";
$WEBROOT = "../html/";

//set aplikasi yang digunakan
define('APPLICATION','smac');
$CONFIG['DEVELOPMENT'] = true;
$CONFIG['MAINTENANCE'] = false;
//WEB APP BASE DOMAIN
$CONFIG['BASE_DOMAIN'] = "smacapp.com";

if($CONFIG['DEVELOPMENT']){
	$CONFIG['DATABASE'][0]['HOST'] 		= "dev.smac.me";
	$CONFIG['DATABASE'][0]['USERNAME'] 	= "root";
	$CONFIG['DATABASE'][0]['PASSWORD'] 	= "coppermine";
	$CONFIG['DATABASE'][0]['DATABASE'] 	= "smac_web";
}else{
	$CONFIG['DATABASE'][0]['HOST'] 		= "dev.smac.me";
	$CONFIG['DATABASE'][0]['USERNAME'] 	= "root";
	$CONFIG['DATABASE'][0]['PASSWORD'] 	= "coppermine";
	$CONFIG['DATABASE'][0]['DATABASE'] 	= "smac_web";
}

//* DATETIME SET */
$timeZone = 'Asia/Jakarta';
date_default_timezone_set($timeZone);

//$CONFIG['BASEURL'] = "http://dev.smac.me/public_html/";
$CONFIG['BASEURL'] = "http://".$_SERVER['HTTP_HOST']."/public_html/";
$CONFIG['ACTIVATION_URL'] = "http://dev.smac.me/public_html/";
/* API BASEURL */
$CONFIG['API_BASEURL'] = "http://localhost/2012/SMAC_DEV/new_web/api/";
// $CONFIG['API_BASEURL'] = "http://dev.smac.me/api/";
$CONFIG['MARKET_API_BASEURL'] = "http://dev.smac.me/api2/";

//authentication API
$CONFIG['AUTH_API'] = "http://dev.smac.me/authentication/";

$CONFIG['GOOGLE_MAP_KEY'] = "ABQIAAAA9yJ3CNx9oi9Minqgs570YhT2f68vqXKsL-DpQvYrDvDxRuO_PRQ1PlS-MhfQszz7YA7gIM7lamdyWw"; //buat smac.me

/* EMAIL SETTING */
$CONFIG['EMAIL_PROSES'] = true;
$CONFIG['EMAIL_AGENCY'] = "dufronte@gmail.com";
$CONFIG['EMAIL_SYSTEM'] = "info@smac.me";
$CONFIG['EMAIL_SUPPORT'] = "support@smacapp.com";

$CONFIG['BASEURL_NO_HTTP'] = "dev.smac.me/public_html"; 

$SMAC_SECRET = sha1("12toinfinityandbeyond34");
$SMAC_HASH = sha1("gettingolderallthetime");

/* SMTP */
$CONFIG['EMAIL_SMTP_HOST'] = "127.0.0.1";
$CONFIG['EMAIL_SMTP_PORT'] = "25";   
$CONFIG['EMAIL_SMTP_SSL'] = false;  
$CONFIG['EMAIL_SMTP_USER'] = "";    
$CONFIG['EMAIL_SMTP_PASSWORD'] = "";      
$CONFIG['EMAIL_FROM_DEFAULT'] = "info@smac.me";

/*download folder*/
$CONFIG['download_url'] = "/home/smac/dev/shared_doc/";

//TWITTER OAUTH
$TWITTER['KEY'] = "09PTyfAEVmnpde3n9xtBbw";
$TWITTER['SECRET'] = "nemonUAXkcCAJQv7Bc1tRuX3Xzx35a4D5SEdCoVkCU";

?>
