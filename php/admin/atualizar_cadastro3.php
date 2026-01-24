<?php
	/* ***** CA - Nome,Titulo,Rating,Clube ***** */
	// *** 2015/09/30 *** 2013/11/26 ***
	// *** atualiza Ratings no Cadastro ***
	
	//echo "Início<br><br>";exit;
	
	// Lendo String de conexão
	$file = "../config/conexao_ca.cfg";
	$fh = fopen($file, 'r');
	$conteudo = explode("*", fread($fh, filesize($file)));
	$strconexao = trim($conteudo[0]);
	$codificacao = trim($conteudo[1]);
	fclose($fh);
	
	//Conectando com o DB
	$conexao=pg_connect($strconexao) or die("erro na conexão");
	
  //Listando tabelas de Rating
	$sqltabs=pg_query($conexao,"SELECT nome_tab FROM tabelas_rating ORDER BY nome_tab") or die("Sem Tabelas!");
	$resultabs=pg_num_rows($sqltabs);
	//echo "Qt. Tabelas: $resultabs <br><br>";
	for($i=0;$i<$resultabs;$i++)
	{
		$data_base[$i] = substr(pg_result($sqltabs,$i,'nome_tab'),1,8);
		//echo "$i - $data_base[$i]";
	}
	$tabini=0;$tabfim=$resultabs-1;
	for($i=$tabini;$i<=$tabfim;$i++)
	{
		atualizar_cadastro($conexao,$data_base[$i]);
	}
	
	echo "Alterações Executadas";
	
	$Erro=pg_last_error($conexao);
	if($Erro!="")
		{echo "<br> ... Erro: $Erro ...<br><br>";} 
	exit;
	// --------------------------------------------------------------------------------		
	function atualizar_cadastro($conexaoL,$data_baseL)
	{
		//echo "<br>$data_baseL<br>";
		
		$sqlexprR="SELECT reg,rating,rpd,blz,clube FROM r" . $data_baseL . " ORDER BY reg";
		$sqlR=pg_query($conexaoL,$sqlexprR) or die("Sem Registros!");
		$resultado=pg_num_rows($sqlR);
		//echo "Enx. rating: $resultado <br>";
		
		if ($resultado<1)
		{
			echo "<font size='4'><br><br><center>Não foram encontrados resultados que satisfaçam a pesquisa!<br><br>";
			return;
		}
		else
		{
			//echo "<br><br>Teste: $resultado<br>";
			$i=0;
			while ($i<$resultado)
			{
				$reg = pg_result($sqlR,$i,'reg')*1;
				//echo "reg: $reg<br>";
				$rating = pg_result($sqlR,$i,'rating');
				if(pg_result($sqlR,$i,'rpd')>0) {$rpd = pg_result($sqlR,$i,'rpd');} else {$rpd = 0;}
				if(pg_result($sqlR,$i,'blz')>0) {$blz = pg_result($sqlR,$i,'blz');} else {$blz = 0;}
				$clube = pg_result($sqlR,$i,'clube');
				//*2015/09/30* $status="A";
				$i++;
				
				//*2015/09/30* $sqlexprC = "UPDATE cadastro SET rating='$rating',clube='$clube',rapido='$rpd',blitz='$blz',status='$status' WHERE reg='" . $reg . "'";
				$sqlexprC = "UPDATE cadastro SET rating='$rating',clube='$clube',rapido='$rpd',blitz='$blz' WHERE reg='" . $reg . "'";	//*2015/09/30*
				//echo "$conexaoL - $sqlexprC - " . pg_result($sqlR,$i,'rpd') . "<br>";
				$sqlC=pg_query($conexaoL,$sqlexprC) or die("Sem Cadastros!");
				$resultadoC=pg_affected_rows($sqlC);
				//echo "$resultadoC <br>";
			}
		}
	}
?>
