<?php
	
	ini_set('display_errors', 0);
	error_reporting(0);
	ob_clean();
	header("Content-Type: image/png");
	//header('Content-type: image/png');
	
	//for($i_seq_bar=0;$i_seq_bar<1;$i_seq_bar++)
	//{	
		$i_cor= 0;$verm[$i_cor]=255;$verd[$i_cor]=  0;$azul[$i_cor]=  0;
		$i_cor= 1;$verm[$i_cor]=255;$verd[$i_cor]=165;$azul[$i_cor]=  0;
		$i_cor= 2;$verm[$i_cor]=255;$verd[$i_cor]=255;$azul[$i_cor]=  0;
		$i_cor= 3;$verm[$i_cor]=  0;$verd[$i_cor]=255;$azul[$i_cor]=  0;
		$i_cor= 4;$verm[$i_cor]=  0;$verd[$i_cor]=255;$azul[$i_cor]=255;
		$i_cor= 5;$verm[$i_cor]=255;$verd[$i_cor]=170;$azul[$i_cor]=  0;
		$i_cor= 6;$verm[$i_cor]=255;$verd[$i_cor]=  0;$azul[$i_cor]=255;
		$i_cor= 7;$verm[$i_cor]=170;$verd[$i_cor]=255;$azul[$i_cor]=255;
		$i_cor= 8;$verm[$i_cor]=  0;$verd[$i_cor]=255;$azul[$i_cor]=077;
		$i_cor= 9;$verm[$i_cor]=255;$verd[$i_cor]=100;$azul[$i_cor]=100;
		$i_cor=10;$verm[$i_cor]=255;$verd[$i_cor]=165;$azul[$i_cor]=100;
		$i_cor=11;$verm[$i_cor]= 77;$verd[$i_cor]=200;$azul[$i_cor]=170;
		$i_cor=12;$verm[$i_cor]=200;$verd[$i_cor]=170;$azul[$i_cor]=  0;
		$i_cor=13;$verm[$i_cor]=200;$verd[$i_cor]=255;$azul[$i_cor]= 77;
		$i_cor=14;$verm[$i_cor]=200;$verd[$i_cor]= 77;$azul[$i_cor]=255;
		$i_cor=15;$verm[$i_cor]= 77;$verd[$i_cor]=200;$azul[$i_cor]=170;
		$i_cor=16;$verm[$i_cor]=255;$verd[$i_cor]= 77;$azul[$i_cor]=  0;
		$i_cor=17;$verm[$i_cor]=  0;$verd[$i_cor]=170;$azul[$i_cor]=255;
		$i_cor=18;$verm[$i_cor]=200;$verd[$i_cor]=170;$azul[$i_cor]=  0;
		$i_cor=19;$verm[$i_cor]=170;$verd[$i_cor]=255;$azul[$i_cor]=200;
		$i_cor=20;$verm[$i_cor]=255;$verd[$i_cor]=  0;$azul[$i_cor]=170;
		$i_cor=21;$verm[$i_cor]=255;$verd[$i_cor]=127;$azul[$i_cor]= 80;
		
		$i_cor_max = $i_cor;
	//}
	
	$parametros = $_SERVER['QUERY_STRING'];
	
	$list_par = explode('&', $parametros);
	$qt = count($list_par);
	for($i=0;$i<$qt;$i++)
	{
		$tam = strlen($list_par[$i]);
		$pos = stripos($list_par[$i], "=");
		$list_par[$i] = substr($list_par[$i],$pos+1);
		if($list_par[$i]==9999) {$fim_par=$i;}		
	}
	
	for($j=$qt;$j>$fim_par;$j--){$removed_element=array_pop($list_par);};
	$qt = count($list_par);
		
	$v1 = $list_par[0]+20;		// *** 2013/11/21 ***
	$v2 = $list_par[1];
	$larg_bar=21;							// *** 2013/11/21 ***
	
	$margem_inf=5;	//10;		// *** 2013/11/21 ***
	$margem_sup=5;	//10;		// *** 2013/11/21 ***
	$alt_min = 50; 
	$alt_max = $v2 - $margem_inf - $margem_sup;
	
	$min=3000;$max=0;
	$j=0;
	for($i=2;$i<$qt;$i=$i+2)
	{
		$rating[$j] = $list_par[$i] * 1;
		$mesano[$j] = $list_par[$i+1];
		if($rating[$j]>0)
		{
			if($rating[$j]<$min) { $min=$rating[$j]; }
			if($rating[$j]>$max) { $max=$rating[$j]; }
		}
		$j++;
	}
	$qtr = count($rating);
	
	$larg_img = $v1;$alt_img = $v2;
	if($max-$min==0)
		{$coef = ($alt_max-$alt_min);}
	else
		{$coef = ($alt_max-$alt_min) / ($max-$min);}
	if($coef>2) {$coef=2;}
	
	$imagem = imagecreatetruecolor($alt_img,$larg_img) or die('Cannot Initialize new GD image stream');
	
	$fundo = imagecolorallocate($imagem, 255, 255, 180);
	$branco = imagecolorallocate($imagem, 255, 255, 255);
	$preto = imagecolorallocate($imagem, 000, 000, 000);
	
	imagefilledrectangle($imagem,0,0,$alt_img-1,$larg_img-1,$fundo);
	
	$base = $min; $teto = $max;
	$var_max = $teto-$base;
	$var_img = $alt_img - 10;		// *** 2013/11/21 ***
	
	$cor_disp=0;
	for($i=0;$i<$qtr;$i++)
	{
		if(imagecolorstotal($imagem)>=255) {
			$fp999 = fopen('log_colors.txt', 'w');
			fwrite($fp999, 'Qt. de cores usadas: ' . imagecolorstotal($imagem));
			fclose($fp999);
		}
				
		$cor_bar = imagecolorallocate($imagem, $verm[$cor_disp], $verd[$cor_disp], $azul[$cor_disp]);
		if($cor_disp<$i_cor_max)
			{$cor_disp++;}
		else
			{$cor_disp=0;}
		
		if($rating[$i]<1)
		{
			imagefilledrectangle($imagem, 4, $i*$larg_bar+2, 40, $i*$larg_bar+$larg_bar, $fundo);
			imagestring($imagem, 2, 5, 3+$i*$larg_bar+2, $mesano[$i], $preto*(-1)); 
		}
		else
		{
			$alt_rat = ($rating[$i]-$min) * $coef + $alt_min;
			imagefilledrectangle($imagem, 4, $i*$larg_bar+2, $alt_rat, $i*$larg_bar+$larg_bar, $cor_bar);

			imagestring($imagem, 2, 5, 3+$i*$larg_bar+2, $mesano[$i], $preto*(-1)); 
		}
		
	}
	
	$imagem = imagerotate($imagem, 90, 0);
	
	for($i=0;$i<$qtr;$i++)
	{
		if($rating[$i]<1)
		{
			imagestring($imagem, 1, 3+$i*$larg_bar+0, 150, " ND", $preto*(-1)); 
		}
		else
		{
			$alt_rat = ($rating[$i]-$min) * $coef + $alt_min;
			imagestring($imagem, 1, 3+$i*$larg_bar+0, $alt_img-$alt_rat-9, $rating[$i], $preto*(-1)); 
		}
	}
	
	imagepng($imagem);
	imagedestroy($imagem);
?>