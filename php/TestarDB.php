<?php
	// Lendo String de conex�o
	$file = "../../config/conexao_ca.cfg";
	$fh = fopen($file, 'r');
	$conteudo = explode("*", fread($fh, filesize($file)));
	$strconexao = trim($conteudo[0]);
	$codificacao = trim($conteudo[1]);
	fclose($fh);
	
	echo " ****** $strconexao em $file*****<br><br>";
	
	$conexao=pg_connect($strconexao);	// or die("erro na conex�o");
	
	//echo "<br><br> ****** abriu com: $strconexao *****<br><br>";
	
	//$strconexao='host=localhost dbname=pclevy_ca user=pclevy password=scly1411';
	//$strconexao='host=127.0.0.1 dbname=esfinge_arbitro user=esfinge_samuel password=futuro10';
	$strconexao='host=127.0.0.1 dbname=esfinge_arbitro user=esfinge_pclevy password=scly1411';
	$conexao=pg_connect($strconexao) or die("erro na conexao");
	
	echo "<br><br> ****** abriu com: $strconexao *****<br><br>";
	/*
	$strconexao='host=201.49.219.50 dbname=pclevy_ca user=pclevy password=scly1411';
	$conexao=pg_connect($strconexao);	// or die("erro na conex�o");
	echo "<br><br>";
	$strconexao='host=50.22.28.234 dbname=pclevy_ca user=pclevy password=scly1411 port=5432';
	$conexao=pg_connect($strconexao);	// or die("erro na conex�o");
	*/
?>