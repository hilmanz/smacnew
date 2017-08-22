<?php
header("Content-type: text/xml");
$xml = '<?xml version="1.0" encoding="utf-8"?>';

$xml .= "<rows>";
$xml .= "	<mentions>0</mentions>";
/*
$xml .= "	<row>";
$xml .= "		<id>8.3182391941792E</id>";
$xml .= "		<url>http://kompas.co.id/</url>";
$xml .= "		<txt>Lorem ipsum dollor is amet lorem ipsum dollor is amet</txt>";
$xml .= "		<link>http://kompas.co.id/lorem-ipsum/</link>";
$xml .= "	</row>";
$xml .= "	<row>";
$xml .= "		<id>8.3182391941792E</id>";
$xml .= "		<url>http://detik.com/</url>";
$xml .= "		<txt>Lorem ipsum dollor is amet lorem ipsum dollor is amet</txt>";
$xml .= "		<link>http://kompas.co.id/lorem-ipsum/</link>";
$xml .= "	</row>";
$xml .= "	<row>";
$xml .= "		<id>8.3182391941792E</id>";
$xml .= "		<url>http://endonesia.com/</url>";
$xml .= "		<txt>Lorem ipsum dollor is amet lorem ipsum dollor is amet</txt>";
$xml .= "		<link>http://endonesia.co.id/lorem-ipsum/</link>";
$xml .= "	</row>";
*/
$xml .= "</rows>";

echo $xml;
exit;