<?php
	// Lendo String de conexão
	//$file = "../../../config/conexao.cfg"; //UERJ
	$file = "../../config/conexao_ca.cfg";
	$fh = fopen($file, 'r');
	$conteudo = explode("*", fread($fh, filesize($file)));
 $strconexao = trim($conteudo[0]);
	$codificacao = trim($conteudo[1]);
	fclose($fh);
	echo "<option value=''> ******* $strconexao - $resultado *********</option>";
	$conexao=pg_connect($strconexao) or die("erro na conexão");
	//Selecionando registros
	$sql =pg_query($conexao,"SELECT reg, nome, arquivo, remetente, email, prog, data_rec, status FROM torneios WHERE status='A' ORDER BY nome") or die("erro de SELECT");
	$resultado=pg_num_rows($sql);
	echo "--- $resultado";exit;
?>