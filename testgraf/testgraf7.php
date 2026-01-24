<?php
header("Content-type: image/gif"); //Informa ao browser que o arquivo щ uma imagem no formato GIF

$imagem = ImageCreate(40,150); //Cria uma imagem com as dimensѕes 100x20
$vermelho = ImageColorAllocate($imagem, 255, 0, 0); //Cria o segundo plano da imagem e o configura para vermelho

$imagemH = imagerotate($imagem, -90, 0);
$branco = ImageColorAllocate($imagemH, 255, 255, 255); //Cria a cor de primeiro plano da imagem e configura-a para branco

ImageString($imagemH, 3, 13, 13, "PHPBrasil", $branco); 
//Imprime na imagem o texto PHPBrasil na cor branca que estс na variсvel $branco

$imagem = imagerotate($imagemH, 90, 0);

ImageGif($imagem); //Converte a imagem para um GIF e a envia para o browser

ImageDestroy($imagemH); //Destrѓi a memѓria alocada para a construчуo da imagem GIF.
ImageDestroy($imagem); //Destrѓi a memѓria alocada para a construчуo da imagem GIF.
?>