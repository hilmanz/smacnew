<?php
header("Content-type: text/xml"); 
$xml = '<?xml version="1.0" encoding="utf-8"?>';
$xml .= '<rows>';

$xml .= 		'<category icon="1">';
$xml .=					'<row>';
$xml .=						'<img>http://a2.twimg.com/profile_images/1546789643/nevada_normal.jpg</img>';
$xml .=						'<name>John Doe</name>';
$xml .=						'<rt>100</rt>';
$xml .=					'</row>';
$xml .=					'<row>';
$xml .=						'<img>http://a2.twimg.com/profile_images/1528445813/image_normal.jpg</img>';
$xml .=						'<name>John Doe</name>';
$xml .=						'<rt>100</rt>';
$xml .=					'</row>';
$xml .=					'<row>';
$xml .=						'<img>http://a1.twimg.com/profile_images/1569873241/avatar_iyan_ttawh_normal.jpg</img>';
$xml .=						'<name>John Doe</name>';
$xml .=						'<rt>100</rt>';
$xml .=					'</row>';
$xml .=					'<row>';
$xml .=						'<img>http://a1.twimg.com/profile_images/1509745884/267725_10150697784165577_664540576_19283063_2033518_n_normal.jpg</img>';
$xml .=						'<name>John Doe</name>';
$xml .=						'<rt>100</rt>';
$xml .=					'</row>';
$xml .=					'<row>';
$xml .=						'<img>http://a2.twimg.com/profile_images/1481929253/324657705_normal.jpg</img>';
$xml .=						'<name>John Doe</name>';
$xml .=						'<rt>100</rt>';
$xml .=					'</row>';
$xml .=					'<row>';
$xml .=						'<img>http://a3.twimg.com/profile_images/1551777454/twit_normal.jpg</img>';
$xml .=						'<name>John Doe</name>';
$xml .=						'<rt>100</rt>';
$xml .=					'</row>';
$xml .= 		'</category>';

$xml .= 		'<category icon="2">';
$xml .=					'<row>';
$xml .=						'<img>http://a1.twimg.com/profile_images/1514416756/325614184_normal.jpg</img>';
$xml .=						'<name>John Doe</name>';
$xml .=						'<rt>100</rt>';
$xml .=					'</row>';
$xml .=					'<row>';
$xml .=						'<img>http://a3.twimg.com/profile_images/1572375385/LxPete_099_-1_normal.jpg</img>';
$xml .=						'<name>John Doe</name>';
$xml .=						'<rt>100</rt>';
$xml .=					'</row>';
$xml .=					'<row>';
$xml .=						'<img>http://a3.twimg.com/profile_images/1561649226/aunnn_normal.jpg</img>';
$xml .=						'<name>John Doe</name>';
$xml .=						'<rt>100</rt>';
$xml .=					'</row>';
$xml .=					'<row>';
$xml .=						'<img>http://a3.twimg.com/profile_images/1472214543/19b2674528b4d5fa396dcc8c20062871_XL_normal.jpg</img>';
$xml .=						'<name>John Doe</name>';
$xml .=						'<rt>100</rt>';
$xml .=					'</row>';
$xml .=					'<row>';
$xml .=						'<img>http://a1.twimg.com/profile_images/1162369010/206617444_normal.jpg</img>';
$xml .=						'<name>John Doe</name>';
$xml .=						'<rt>100</rt>';
$xml .=					'</row>';
$xml .=					'<row>';
$xml .=						'<img>http://a2.twimg.com/profile_images/1558670769/327021606_normal.jpg</img>';
$xml .=						'<name>John Doe</name>';
$xml .=						'<rt>100</rt>';
$xml .=					'</row>';
$xml .= 		'</category>';

$xml .= 		'<category icon="3">';
$xml .=					'<row>';
$xml .=						'<img>http://a1.twimg.com/profile_images/1529180545/P30-08-11_11-15_5B1_5D_normal.jpg</img>';
$xml .=						'<name>John Doe</name>';
$xml .=						'<rt>100</rt>';
$xml .=					'</row>';
$xml .=					'<row>';
$xml .=						'<img>http://a3.twimg.com/profile_images/1529915975/Screen_shot_2011-09-05_at_10.55.37_PM_normal.png</img>';
$xml .=						'<name>John Doe</name>';
$xml .=						'<rt>100</rt>';
$xml .=					'</row>';
$xml .=					'<row>';
$xml .=						'<img>http://a2.twimg.com/profile_images/1565130474/229190_121135384633377_100002107489861_172177_3323825_n_normal.jpg</img>';
$xml .=						'<name>John Doe</name>';
$xml .=						'<rt>100</rt>';
$xml .=					'</row>';
$xml .=					'<row>';
$xml .=						'<img>http://a0.twimg.com/profile_images/1563611899/andigasmm_normal.jpg</img>';
$xml .=						'<name>John Doe</name>';
$xml .=						'<rt>100</rt>';
$xml .=					'</row>';
$xml .=					'<row>';
$xml .=						'<img>http://a1.twimg.com/profile_images/1384040727/screen2_normal.jpg</img>';
$xml .=						'<name>John Doe</name>';
$xml .=						'<rt>100</rt>';
$xml .=					'</row>';
$xml .=					'<row>';
$xml .=						'<img>http://a3.twimg.com/profile_images/1568111451/Rotation_of_S7301205_normal.JPG</img>';
$xml .=						'<name>John Doe</name>';
$xml .=						'<rt>100</rt>';
$xml .=					'</row>';
$xml .= 		'</category>';

$xml .= '</rows>';

/*
daftar category
1 auto
2 education
3 entertain
4 health
5 politics
6 sport
7 techno
*/

echo $xml;
exit;