<?php
header("Content-type: image/png");

$alt_img = 200;
$imagem = ImageCreate(400, $alt_img);

$fundo = ImageColorAllocate($imagem, 255, 255, 180);
$azul = ImageColorAllocate($imagem, 20, 93, 233);
$verm = ImageColorAllocate($imagem, 233, 93, 20);
$verde = ImageColorAllocate($imagem, 93, 233, 20);

$rating1 = 1800;
$rating2 = 1850;
$rating3 = 1900;
$rating4 = 1820;

$min = min($rating1,$rating2,$rating3,$rating4);
$max = max($rating1,$rating2,$rating3,$rating4);
$base = $min - 10; //****
$teto = $max - 10;
$var_max = $teto-$base;
$v1 = ($var_max) / ($rating1-$base);

ImageFilledRectangle($imagem, 10, $alt_img-80, 20, $alt_img-10, $azul);
ImageFilledRectangle($imagem, 30, 140, 40, 190, $verm);
ImageFilledRectangle($imagem, 50, 13, 60, 190, $verde);
ImageFilledRectangle($imagem, 110, $alt_img-((1800-1700)), 120, $alt_img-((1800-$base)), $azul);

ImageFilledRectangle($imagem, 130, 182, 140, 190, $verde);
ImageFilledRectangle($imagem, 150, $alt_img-($v1), 160, $alt_img-10, $verde);

ImagePng($imagem);
ImageDestroy($imagem);
?>