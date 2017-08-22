<?php
header("Content-type: text/xml"); 
$xml = '<?xml version="1.0" encoding="utf-8"?>';
$xml .= '<categories>';
$xml .= 		'<category>1</category>';
$xml .= 		'<category>2</category>';
$xml .= 		'<category>3</category>';
$xml .= 		'<category>4</category>';
$xml .= 		'<category>5</category>';
$xml .= 		'<category>6</category>';
$xml .= '</categories>';

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