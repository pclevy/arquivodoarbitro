<?php
  /* ***** Cadastro de Rating ***** */
  // * 2015/03/08 * 2012/01/31 *
	
	// Lendo String de conexão
	$file = "../../../config/conexao_ca.cfg";
	$fh = fopen($file, 'r');
	$conteudo = explode("*", fread($fh, filesize($file)));
	$strconexao = trim($conteudo[0]);
	$codificacao = trim($conteudo[1]);
	fclose($fh);
  
	//Conectando com o DB
  //$conexao=pg_connect("host=localhost dbname=pclevy_ca user=pclevy password=scly1411") or die ("erro na conexão"); //RjHost
	$conexao=pg_connect($strconexao) or die("erro na conexão");
  
  //Selecionando registros da Página
  $sql =pg_query($conexao,"SELECT reg FROM r20150210 ORDER BY reg") or die();
  $resultado=pg_num_rows($sql);
	
  if ($resultado<1)
	{
		echo "<font size='4'><br><br><center>Não foram encontradas inscrições que satisfaçam a pesquisa!<br><br>";
		return;
	}
  else
	{
		$sqltrexp = "UPDATE cadastro SET status='D' WHERE status='A'";
		$sqltr=pg_query($conexao,$sqltrexp);      // or die();
		$i=0;
		while ($i<$resultado)
		{
			$reg = pg_result($sql,$i,'reg');
			$sqltrexp = "UPDATE cadastro SET status='A' WHERE reg='$reg'";
			$sqltr=pg_query($conexao,$sqltrexp);      // or die();
			$i=$i+1;
		}
	}
  
?>
