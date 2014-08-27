<?php
/************* Membership V1.0 *******************/
/*
Released by AwesomePHP.com, under the GPL License, a
copy of it should be attached to the zip file, or
you can view it on http://AwesomePHP.com/gpl.txt
*/
/************* Membership V1.0 *******************/	

//Call Database & Connect
require_once('headers/database.php');
connect();

//We need functions
require_once('headers/functions.php');

//Get Admin Values
$get_cd = mysql_query("SELECT * FROM `memb_config`");
while($each = mysql_fetch_assoc($get_cd)){$$each['config_name'] = $each['config_value'];}

//Disconnect Database
disconnect_data();

//Get String
$code = hex2bin($_GET['code']);
$textstr = encode_decode($code,$CF_ENCDEC);

//Select Font & Size
$fonts = explode(',',$CF_FONTS);
$random = rand(0,count($fonts)-1);
$font = $fonts[$random];
$size = $CF_SIZE;

//Select Backgound
$backgounds = explode(',',$CF_BACKGROUNDS);
$random = rand(0,count($backgounds)-1);
$background = $backgounds[$random];

$im = imagecreatefrompng($background);

//Make Image
$angle = rand(-5, 5);
$color = imagecolorallocate($im, rand(0, 100), rand(0, 100), rand(0, 100));
$textsize = imagettfbbox($size, $angle, $font, $textstr);
$twidth = abs($textsize[2]-$textsize[0]);
$theight = abs($textsize[5]-$textsize[3]);
$x = (imagesx($im)/2)-($twidth/2)+(rand(-20, 20));
$y = (imagesy($im))-($theight/2);

imagettftext($im, $size, $angle, $x, $y, $color, $font, $textstr);

//Output PNG Image
header("Content-Type: image/png");
ImagePNG($im);

//Destroy the image to free memory
imagedestroy($im);

//End Output
exit;

?>