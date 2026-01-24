<?php
	header("Content-type: image/png");
 
	$parametros = $_SERVER['QUERY_STRING'];
 
	$list_par = split("&", $parametros);
	$qt = count($list_par);
	for($i=0;$i<$qt;$i++)
		{
			$tam = strlen($list_par[$i]);
			$pos = stripos($list_par[$i], "=");
			$list_par[$i] = substr($list_par[$i],$pos+1);
			
			if($list_par[$i]==9999)
				{
					$fim_par=$i;
				}		
		}
	
	for($j=$qt;$j>$fim_par;$j--){$removed_element=array_pop($list_par);};
	$qt = count($list_par);
		
	$v1 = $list_par[0];
	$v2 = $list_par[1];
	
	for($i=2;$i<$qt;$i++)
		{
	  $rating[$i-2] = $list_par[$i];
	 }
	$qtr = count($rating);
 
	for($j=0;$j<$qtr-1;$j++)
	 {
		 $l[$j-2]=$rating[$j];
		}
	asort($l);
 $min=$l[0];$max=$l[$qt-1];
	foreach ($l as $key => $val)
  {
   if($val<$min) { $min=$val; }
   if($val>$max) { $max=$val; }
  }
	
	$larg_img = $v1;$alt_img = $v2;
	$imagem = ImageCreate($larg_img, $alt_img);
	$fundo = ImageColorAllocate($imagem, 255, 255, 180);
	
	$base = $min; $teto = $max;
	$var_max = $teto-$base;
	$var_img = $alt_img - 20;
	
	$verm=50;$verd=0;$azul=0;
	for($i=0;$i<$qtr;$i++)
		{
			//$tt=$i%2;
		 
			if($i%2==0)
			 {$verm = $verm + $i*30;}
			if($i%3==0)
			 {$verd = $verd + $i*30;}
   else
			 {$azul = $azul + $i*30;}
			
			//$cor_bar = ImageColorAllocate($imagem, $i*20, $i*20, 255-$i*40);
			$cor_bar = ImageColorAllocate($imagem, $verm, $verd, $azul);
			$alt_rat = ($rating[$i]-$base+10)*($var_img/$var_max);
			ImageFilledRectangle($imagem, $i*10, $alt_img-10, $i*10+10, $alt_img-$alt_rat, $cor_bar);
			
			//$imagemH = imagerotate($imagem, -90, 0);
			//$branco = ImageColorAllocate($imagem, 255, 255, 255);
			//ImageString($imagem, 3, 3, 3, "P", $branco); 
			//$imagem = imagerotate($imagemH, 90, 0);
			
	 }

	ImagePng($imagem);
	ImageDestroy($imagem);
?>