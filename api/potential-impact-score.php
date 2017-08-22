<?php
header("Content-type: text/xml");
$xml = '<?xml version="1.0" encoding="utf-8"?>';
$xml .= '<rows>';
$xml .= '	<score>212K</score>';
$xml .= '</rows>';

echo $xml;
exit;