<?php
	// Lendo String de conexão
	$file = "../config/conexao_ca.cfg";
	$fh = fopen($file, 'r');
	$conteudo = explode("*", fread($fh, filesize($file)));
	$strconexao = trim($conteudo[0]);
	$codificacao = trim($conteudo[1]);
	fclose($fh);
	
	//Conectando com o DB
	$conexao=pg_connect($strconexao) or die("erro na conexão");
	
	$sqltabs=pg_query($conexao,"SELECT nome_tab FROM tabelas_rating ORDER BY nome_tab"); // or die("Sem Resultados!");
	$resultabs=pg_num_rows($sqltabs);
	$i = 0;
	while ($i<$resultabs)
	{
		$data_base[$i] = substr(pg_result($sqltabs,$i,'nome_tab'),1,8);
		$i++;
	}
	$ibQ=$resultabs;$ib=0;
	
	while ($ib<$ibQ)
	{
		$retorno=add_coluna($conexao,$data_base[$ib]);
		$ib++;
	}
	
	$Erro=pg_last_error($conexao);
	if($Erro!="")
		{echo "<br> ... Erro: $Erro ...<br><br>";} 
	exit;
	// --------------------------------------------------------------------------------		
	function add_coluna($conexao,$data_baseL)
	{
		//$sqlhist="ALTER TABLE r" . $data_baseL . " ADD rpd smallint DEFAULT 0";
		//$sqlhist="ALTER TABLE r" . $data_baseL . " ADD blz smallint DEFAULT 0";
		$sqlhist="ALTER TABLE r" . $data_baseL . " ADD status char DEFAULT 'A'";
		
		$sqlH=pg_query($conexao,$sqlhist); // or die("Sem Resultados!");
		$resulthist=pg_num_rows($sqlH);

		return $resulthist;
	}
?>