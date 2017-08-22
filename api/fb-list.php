<?php
header("Content-type: text/xml");
$xml = '<?xml version="1.0" encoding="utf-8"?>';

$xml .= "<rows>";
$xml .= "	<mentions>0</mentions>";
/*
$xml .= "	<row>";
$xml .= "		<id>8.3182391941792E</id>";
$xml .= "		<profile_image_url>http://a3.twimg.com/profile_images/1402593057/Cindy_20Manda_20Gir_normal.jpg</profile_image_url>";
$xml .= "		<name>cindygirsang</name>";
$xml .= "		<txt>@ezholiceo done eze, accept</txt>";
$xml .= "	</row>";
$xml .= "	<row>";
$xml .= "		<id>8.3182391941792E</id>";
$xml .= "		<profile_image_url>http://a0.twimg.com/profile_images/1405301380/php6uVMMm_normal</profile_image_url>";
$xml .= "		<name>ersarasee</name>";
$xml .= "		<txt>jjrgktGbsdthnlg.Sktht :'(</txt>";
$xml .= "	</row>";
$xml .= "	<row>";
$xml .= "		<id>8.3182391941792E</id>";
$xml .= "		<profile_image_url>http://a0.twimg.com/profile_images/1396577981/307466942_normal.jpg</profile_image_url>";
$xml .= "		<name>YasmienWA</name>";
$xml .= "		<txt>Panggil @solusisehat bu.. They're the best! ? @fannieelias</txt>";
$xml .= "	</row>";
*/
$xml .= "</rows>";

echo $xml;
exit;