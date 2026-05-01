<?php
session_start();

$captcha = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ0123456789"),0,5);
$_SESSION['captcha'] = $captcha;

$img = imagecreate(150,50);
$bg = imagecolorallocate($img,255,255,255);
$txt = imagecolorallocate($img,0,0,0);

imagestring($img,5,40,15,$captcha,$txt);
header("Content-type: image/png");
imagepng($img);
imagedestroy($img);
?>