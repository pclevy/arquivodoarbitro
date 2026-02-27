<?php
header("Content-Type: image/png");

$img = imagecreate(300, 100);
$bg = imagecolorallocate($img, 255, 255, 255);
$blue = imagecolorallocate($img, 0, 0, 255);

imageline($img, 10, 50, 290, 50, $blue);

imagepng($img);
imagedestroy($img);
?>