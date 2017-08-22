<?php
header("Content-type: text/xml"); 
$xml = '<?xml version="1.0" encoding="utf-8"?>';
$xml .= '<rows>';
$xml .= 		'<category name="auto" value="30" />';
$xml .= 		'<category name="music" value="40" />';
$xml .= 		'<category name="food" value="30" />';
$xml .= 		'<category name="other" value="20" />';
$xml .= '</rows>';

/*
daftar category
1 sport
2 auto
3 music
4 food
5 tool
6 other
*/

echo $xml;
exit;