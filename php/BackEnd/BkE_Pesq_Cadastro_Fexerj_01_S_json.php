<?php
	
	$part_nome_pesq = $_POST['nomepesq'];
	
	/* ---------------------------------------------------------------------- */
	/* Carrega string de conexão                                              */
	/* ---------------------------------------------------------------------- */
	$cfg_file = __DIR__ . "/../../config/conexao_ca.cfg";

	if (!file_exists($cfg_file)) {
		http_response_code(500);
		echo json_encode([
			"erro" => "Arquivo de configuração não encontrado."
		]);
		exit;
	}
	
	$conteudo = explode("*", file_get_contents($cfg_file));
	
	$strconexao = trim($conteudo[0]);
	$codificacao = trim($conteudo[1]);
	
	$conexao = pg_connect($strconexao);
	
	if (!$conexao) {
		http_response_code(500);
		echo json_encode([
			"erro" => "Erro na conexão com o banco."
		]);
		exit;
	}
	
	/* ---------------------------------------------------------------------- */
	
	//$part_nome_pesq = "Levy"; // Deve vir como parâmetro
	
	$sql = pg_query_params(
		$conexao,
		"SELECT reg, sobrenome, nome, clube, municipio, rating
		 FROM cadastro
		 WHERE CONCAT(nome, ' ', sobrenome) ILIKE $1
		 ORDER BY nome",
		["%{$part_nome_pesq}%"]
	);
	
	$listaJogadores = [];
	
	while ($row = pg_fetch_assoc($sql)) {
		$listaJogadores[] = [
			"reg"        => trim($row["reg"]),
			"nome"       => trim($row["nome"] . " " . $row["sobrenome"]),
			"clube"      => trim($row["clube"]),
			"municipio"  => trim($row["municipio"]),
			"rating"  => trim($row["rating"])
		];
	}
	
	header("Content-Type: application/json; charset=utf-8");
	echo json_encode($listaJogadores, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	//echo json_encode($listaJogadores, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	
	pg_close($conexao);
	
?>