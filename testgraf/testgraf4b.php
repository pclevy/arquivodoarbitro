<html>
<head></head>
<body>
teste<br>
<img src=<php header(\"Content-type: image/png\");	$imagem = ImageCreate(400, 200);	$fundo = ImageColorAllocate($imagem, 255, 255, 180);	$i=0;	$cor_bar = ImageColorAllocate($imagem, $i*20, $i*20, 255-$i*40);	ImageFilledRectangle($imagem, 10, 180, 40, 10, $cor_bar);	ImagePng($imagem);	ImageDestroy($imagem);	?> >
</body>
</html>