<?
header ("Content-type: image/png");
$imagem = imagecreate (150, 100);
$corFundo = imagecolorallocate ($imagem, 255, 255, 200);
$corLinha = imagecolorallocate ($imagem, 0, 0, 0);
$corLinha1 = imagecolorallocate ($imagem, 255, 0, 0);
imageline ($imagem, 10, 90, 50, 20, $corLinha1);
$corLinha2 = imagecolorallocate ($imagem, 0, 255, 0);
imageline ($imagem, 50, 20, 70, 40, $corLinha2);
$corLinha3 = imagecolorallocate ($imagem, 0, 0, 255);
imageline ($imagem, 70, 40, 80, 30, $corLinha3);
imagepng ($imagem);
imagedestroy($imagem);
?>