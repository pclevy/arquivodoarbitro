<?php
	header("Content-type: image/png");
	
	for($i_seq_bar=0;$i_seq_bar<2;$i_seq_bar++)
	{
		$i_cor=0+$i_seq_bar*16;$verm[$i_cor]=255;$verd[$i_cor]=000;$azul[$i_cor]=000;
		$i_cor=1+$i_seq_bar*16;$verm[$i_cor]=000;$verd[$i_cor]=255;$azul[$i_cor]=000;
		$i_cor=2+$i_seq_bar*16;$verm[$i_cor]=200;$verd[$i_cor]=085;$azul[$i_cor]=000;
		$i_cor=3+$i_seq_bar*16;$verm[$i_cor]=255;$verd[$i_cor]=255;$azul[$i_cor]=000;
		$i_cor=4+$i_seq_bar*16;$verm[$i_cor]=255;$verd[$i_cor]=000;$azul[$i_cor]=255;
		$i_cor=5+$i_seq_bar*16;$verm[$i_cor]=000;$verd[$i_cor]=255;$azul[$i_cor]=255;
		$i_cor=6+$i_seq_bar*16;$verm[$i_cor]=255;$verd[$i_cor]=170;$azul[$i_cor]=000;
		$i_cor=7+$i_seq_bar*16;$verm[$i_cor]=170;$verd[$i_cor]=255;$azul[$i_cor]=000;
		$i_cor=8+$i_seq_bar*16;$verm[$i_cor]=170;$verd[$i_cor]=000;$azul[$i_cor]=255;
		$i_cor=9+$i_seq_bar*16;$verm[$i_cor]=170;$verd[$i_cor]=255;$azul[$i_cor]=255;
		$i_cor=10+$i_seq_bar*16;$verm[$i_cor]=200;$verd[$i_cor]=085;$azul[$i_cor]=000;
		$i_cor=11+$i_seq_bar*16;$verm[$i_cor]=085;$verd[$i_cor]=200;$azul[$i_cor]=000;
		$i_cor=12+$i_seq_bar*16;$verm[$i_cor]=085;$verd[$i_cor]=100;$azul[$i_cor]=200;
		$i_cor=13+$i_seq_bar*16;$verm[$i_cor]=200;$verd[$i_cor]=255;$azul[$i_cor]=085;
		$i_cor=14+$i_seq_bar*16;$verm[$i_cor]=200;$verd[$i_cor]=085;$azul[$i_cor]=255;
		$i_cor=15+$i_seq_bar*16;$verm[$i_cor]=085;$verd[$i_cor]=200;$azul[$i_cor]=255;
	}
	
	$parametros = $_SERVER['QUERY_STRING'];
	
	$list_par = split("&", $parametros);
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
	//echo "<br><br>$v2 - ";echo "$alt_max - ";echo "$coef <br><br>";exit;
	
	//$min=$list_par[2];$max=$list_par[$qt-2];
	$min=3000;$max=0;
	$j=0;
	for($i=2;$i<$qt;$i=$i+2)
	{
		$rating[$j] = $list_par[$i] * 1;
		$mesano[$j] = $list_par[$i+1];
		
		//echo "rating: " . $rating[$j] . " - mes: " . $mesano[$j] . "<br>";
		
		if($rating[$j]>0)
		{
			if($rating[$j]<$min) { $min=$rating[$j]; }
			if($rating[$j]>$max) { $max=$rating[$j]; }
		}
		$j++;
	}
	$qtr = count($rating);
	//exit;
	/*
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
	*/
	
	$larg_img = $v1;$alt_img = $v2;
	if($max-$min==0)
		{$coef = ($alt_max-$alt_min);}
	else
		{$coef = ($alt_max-$alt_min) / ($max-$min);}
	if($coef>2) {$coef=2;}
	$imagem = ImageCreate($alt_img,$larg_img);
	$fundo = ImageColorAllocate($imagem, 255, 255, 180);
	$branco = ImageColorAllocate($imagem, 255, 255, 255);
	$preto = ImageColorAllocate($imagem, 000, 000, 000);
	
	$base = $min; $teto = $max;
	$var_max = $teto-$base;
	$var_img = $alt_img - 10;		// *** 2013/11/21 ***
	
	//$verm=0;$verd=20;$azul=0;
	//$larg_bar=23;		// *** 2013/11/21 ***
	
	for($i=0;$i<$qtr;$i++)
	{
		//$tt=$i%2;

		/*
		if($i%2==0)
			{$verm = $verm + $i*$larg_bar;}
		if($i%3==0)
			{$verd = $verd + $i*$larg_bar;}
		else
			{$azul = $azul + $i*$larg_bar;}
		*/
	
		//$cor_bar = ImageColorAllocate($imagem, $i*20, $i*20, 255-$i*40);
		$cor_bar = ImageColorAllocate($imagem, $verm[$i], $verd[$i], $azul[$i]);
	
		if($rating[$i]<1)
		{
			//$alt_rat = ($rating[$i]-$base+10)*($var_img/$var_max);
			//ImageFilledRectangle($imagem, $i*20+2, $alt_img-10, $i*20+20, $alt_img-$alt_rat, $cor_bar);
			ImageFilledRectangle($imagem, 4, $i*$larg_bar+2, 40, $i*$larg_bar+$larg_bar, $fundo);
			
			ImageString($imagem, 2, 5, 3+$i*$larg_bar+2, $mesano[$i], $preto); 
		}
		else
		{
			//$alt_rat = ($rating[$i]-$base+25)*($var_img/$var_max);
			//$alt_rat = ($rating[$i]-$base+100)*($var_max/$var_img);
			$alt_rat = ($rating[$i]-$min) * $coef + $alt_min;
			//ImageFilledRectangle($imagem, $i*20+2, $alt_img-10, $i*20+20, $alt_img-$alt_rat, $cor_bar);
			ImageFilledRectangle($imagem, 4, $i*$larg_bar+2, $alt_rat, $i*$larg_bar+$larg_bar, $cor_bar);

			// Load the gd font and write 'Hello'
			//$font = imageloadfont('./04b.gdf');

			//$imagemH = imagerotate($imagem, -90, 0);
			//$branco = ImageColorAllocate($imagem, 255, 255, 255);
			//$preto = ImageColorAllocate($imagem, 000, 000, 000);
			ImageString($imagem, 2, 5, 3+$i*$larg_bar+2, $mesano[$i], $preto); 
			//$imagem = imagerotate($imagemH, 90, 0);
			//ImageDestroy($imagemH);
		}
	}
	
	$imagem = imagerotate($imagem, 90, 0);
	
	//ImageString($imagem, 2, 5, 3+$i*20+2, $mesano[$i], $preto); 
	//ImageString($imagem, 2, 5, 3+1*20+2, $rating[2], $preto); 
	
	for($i=0;$i<$qtr;$i++)
	{
		if($rating[$i]<1)
		{
			ImageString($imagem, 1, 3+$i*$larg_bar+0, 150, " ND", $preto); 
		}
		else
		{
			$alt_rat = ($rating[$i]-$min) * $coef + $alt_min;
			ImageString($imagem, 1, 3+$i*$larg_bar+0, $alt_img-$alt_rat-9, $rating[$i], $preto); 
		}
	}
	
	ImagePng($imagem);
	ImageDestroy($imagem);
?>