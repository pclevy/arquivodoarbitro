<?php

$parametros = $_SERVER['QUERY_STRING'];
echo $parametros."<br><br><br>";

echo "<img src=\"
 <php
	header(\"Content-type: image/png\");
	$imagem = ImageCreate(400, 200);
	$fundo = ImageColorAllocate($imagem, 255, 255, 180);
	$i=0;
	$cor_bar = ImageColorAllocate($imagem, $i*20, $i*20, 255-$i*40);
	ImageFilledRectangle($imagem, 10, 180, 40, 10, $cor_bar);
	ImagePng($imagem);
	ImageDestroy($imagem);
	?>
	\">";

$list_par = split("&", $parametros);
$qt = count($list_par);
echo $list_par[$qt-1] . "<br>";
echo $qt . "<br><br>";
for($i=0;$i<$qt;$i++)
 {
  echo "y".$list_par[$i]."y - ";
		$tam = strlen($list_par[$i]);
		$pos = stripos($list_par[$i], "=");
  $list_par[$i] = substr($list_par[$i],$pos+1);
  echo "y".$list_par[$i]."y<br>";
		
		if($list_par[$i]==9999)
		 {
			 $fim_par=$i;
				echo "termina aqui<br>";
			}		
	}
	
	for($j=$qt;$j>$fim_par;$j--){$removed_element=array_pop($list_par);};
	
	$qt2 = count($list_par);
 echo "<br>" . $list_par[$qt2-1] . "<br>";
 echo $qt2 . "<br><br>";
	
	for($j=2;$j<$qt2;$j++)
	 {
		 $l[$j-2]=$list_par[$j];
		}
	//$l=$list_par;
	asort($l);
	//echo "<br><br> ---- " . $l[0] . " - " . $l[1] . " - " . $l[2] . " - " . $l[3] . " - " . "----- <br><br>";
 $min=$l[0];$max=$l[$qt-1];
	foreach ($l as $key => $val)
  {
   if($val<$min) { $min=$val; }
   if($val>$max) { $max=$val; }
  }
		echo "<br><br> === min=".$min." === max=".$max."===<br><br>";

//$meu id = $_GET[“id”]
$v1 = $_GET["v1"];
$v2 = $_GET["v2"];
$rating1 = $_GET["r1"];
$rating2 = $_GET["r2"];
$rating3 = $_GET["r3"];
$rating4 = $_GET["r4"];

echo "<br>";
echo $v1."<br>";
echo $v2."<br>";

echo $rating1."<br>";
echo $rating2."<br>";
echo $rating3."<br>";
echo $rating4."<br>";

?>