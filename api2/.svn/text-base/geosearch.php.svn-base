<?php

// Example demonstrating how to use Yahoo's Geoplanet API (via Tyler Hall's PHP wrapper)
// to emulate the 'near' parameter that's available in the Twitter search UI, but not
// through the API.
//
// To install it:
// 1) Put this file and class.geoplanet.php on your server
// 2) Get a free application ID from Yahoo - http://developer.yahoo.com/geo/
// 3) Copy the ID where this file has <Put your own app id in here>
// 4) Remove the die() statement just above it
//
// To use it:
// Call the URL of the file as you would http://search.twitter.com/search.atom
// All the normal supported arguments are passed through to Twitter's API
// If 'near=' is specified, then the place name string is passed to Geoplanet
// The latitude and longitude of the result are passed as 'geocode=' to Twitter
// If you also specify 'radius=' then that is passed, otherwise '25km' is used
//
// Gotchas:
// - It uses a redirect header, so for command-line curl you'll need -L to follow
// - There's zero error or exception handling
//
// Example:
// http://yourserver.com/geosearch.php?q=biking&near=boulder&radius=15km
   
require_once("class.geoplanet.php");

// If the named parameter was present on the input URL, append it to $url
function copy_url_input($name, $url)
{
	if (isset($_GET[$name]))
		$url .= "$name=".urlencode($_GET[$name])."&";
	
	return $url;
}

$searchurl = "http://search.twitter.com/search.json?rpp=15&";

// If the near parameter is present, then call Geoplanet to turn it into lat,long
if (isset($_GET['near']))
{
	$placename = $_GET['near'];

	//die('You need to put your own app id here and then remove this statement');
	$geoplanet = new GeoPlanet('dj0yJmk9cUJTMjlXV2tnNWFhJmQ9WVdrOVdHTnZUelozTm1jbWNHbzlNVGcxTXpnd09URTJNZy0tJnM9Y29uc3VtZXJzZWNyZXQmeD02Mw--');
	$placelist = $geoplanet->getPlaces($placename);

	$topplace = $placelist[0];
	$centroid = $topplace['centroid'];
	$lat = $centroid['lat'];
	$lng = $centroid['lng'];
	
	// The radius parameter is optional. If it's not present then default to 25km
	if (isset($_GET['radius']))
		$radius = $_GET['radius'];
	else
		$radius = '25km';
	
	$searchurl .= "geocode=".urlencode("$lat,$lng,$radius")."&";
}
else
{
	$searchurl = copy_url_input('geocode', $searchurl);
}

$searchurl = copy_url_input('q', $searchurl);
$searchurl = copy_url_input('lang', $searchurl);
$searchurl = copy_url_input('rpp', $searchurl);
$searchurl = copy_url_input('page', $searchurl);
$searchurl = copy_url_input('sinceid', $searchurl);
$searchurl = copy_url_input('show_user', $searchurl);

// Request a redirect to the URL with the lat,long added
//header("Location: ".$searchurl);

$data = file_get_contents($searchurl);
//print_r(json_decode($data));
$dataArr=json_decode($data);

$xml = '<?xml version="1.0" encoding="utf-8"?>';
$xml .= '<rows>';

foreach($dataArr->results as $k){
	if(eregi('T:',$k->location)) {
		
		//ambil lat & lon nya
		$t = explode(' ',$k->location);
		$t = explode(',',$t[1]);
		
		$xml .= '<row>';
		
		$xml .= '<from_user_id_str>' . $k->from_user_id_str . '</from_user_id_str>';
		$xml .= '<location>'.$k->location.'</location>';
		$xml .= '<lat>'.$t[0].'</lat>';
		$xml .= '<lon>'.$t[1].'</lon>';
		$xml .= '<profile_image_url>'.$k->profile_image_url.'</profile_image_url>';
		$xml .= '<created_at>'.$k->created_at.'</created_at>';
		$xml .= '<from_user>'.$k->from_user.'</from_user>';
		$xml .= '<id_str>'.$k->id_str.'</id_str>';
		$xml .= '<to_user_id>'.$k->to_user_id.'</to_user_id>';
		$xml .= '<text>'.$k->text.'</text>';
		$xml .= '<id>'.$k->id.'</id>';
		$xml .= '<from_user_id>'.$k->from_user_id.'</from_user_id>';
		$xml .= '<to_user>'.$k->to_user.'</to_user>';
		$xml .= '<iso_language_code>'.$k->iso_language_code.'</iso_language_code>';
		$xml .= '<to_user_id_str>'.$k->to_user_id_str.'</to_user_id_str>';
		$xml .= '<source>'.$k->source.'</source>';
		
		$xml .= '</row>';
	}
}
$xml .= '</rows>';
header("Content-type: text/xml"); 
echo $xml;
exit;