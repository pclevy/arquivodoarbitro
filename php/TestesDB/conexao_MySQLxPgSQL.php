<?php
	// *** Conexão MySQL x PostGreSQL ***
	
	$servidor=$_SERVER['SERVER_NAME'];
	echo 'Servidor: ' . $servidor . '<br><br>';
	
	$conexao='';
	$url='localhost';$dbnome='pclevy_ca';$senha='scly1411';					// *** 2013/03/22 ***
	
	// *** Conexão MySQL ***
	$sgbd='MySQL';$usuario='pclevy';										// *** 2013/03/22 ***
	ConectarDB();																													// *** 2013/03/18 ***
	
	LerDados();
	mysql_close($conexao);
	
	echo '<br>';
	
 // *** Conexão PostGreSQL ***
	$sgbd='PostGreSQL';$usuario='pclevy';	// *** 2013/03/22 ***
	ConectarDB();																									// *** 2013/03/18 ***
	
	//Selecionando registros
	$sql =pg_query($conexao,"SELECT reg, nome, arquivo, remetente, prog, data_rec, status FROM torneios WHERE (status='A') ORDER BY nome") or die("erro de SELECT");		// *** 2013/03/18 ***
	$resultado=pg_num_rows($sql);
	echo "Qt. torneios PG: $resultado <br>";
	while ($i<$resultado)
	{
		$reg = trim(pg_result($sql,$i,'reg'));
		$nome = trim(pg_result($sql,$i,'nome'));
		$arquivo = trim(pg_result($sql,$i,'arquivo'));
		$remetente = trim(pg_result($sql,$i,'remetente'));
		$prog = trim(pg_result($sql,$i,'prog'));
		$datarec = trim(pg_result($sql,$i,'data_rec'));
		$status = trim(pg_result($sql,$i,'status'));								// *** 2013/03/18 ***
		echo $reg . ' - ' .$nome . ' - ' .$arquivo . ' - ' .$remetente . ' - ' .$prog . ' - ' .$datarec . ' - ' .$status . '<br>';		// *** 2013/03/18 ***
		$i++;
	}
	pg_close($conexao);
	
	exit;
	
	// *********** Funções *****************
	function ConectarDB()																																		// *** 2013/03/18 ***		
	{
		global $conexao,$sgbd,$dbnome,$usuario,$senha,$url;
		switch ($sgbd)
		{
			case 'MySQL':
				$conexao = mysql_connect($url, $usuario, $senha);															// *** 2013/03/22 ***
				if(!$conexao)
					{die('Não foi possível conectar: ' . mysql_error());}
				else
					{
						echo 'MySQL - Conexão bem sucedida';
						mysql_select_db($dbnome);																																					// *** 2013/03/18 ***
					}
				break;
			case 'PostGreSQL':
				$strconexao="host=" . $url . " dbname=".$dbnome . " user=".$usuario . " password=".$senha;						// *** 2013/03/22 ***
				$conexao=pg_connect($strconexao);		// or die ("erro na conex?o");																															// *** 2013/03/18 ***
				if(!$conexao)
					{die('Não foi possível conectar: ' . pg_last_error($conexao));}
				else
					{echo 'PostGreSQL - Conexão bem sucedida<br>';}
				break;
			default:
		}
	}

	function LerDados()
	{
		global $sgbd,$conexao,$dbnome;
		switch ($sgbd)
		{
			case 'MySQL':
				mysql_select_db($dbnome);
				$strsql = "SELECT reg, nome, arquivo, remetente, prog, data_rec, status FROM torneios WHERE (status='A') ORDER BY nome";
				$result = mysql_query($strsql,$conexao);
				echo '<br>';
				while ($row = mysql_fetch_assoc($result))
				{
					$reg = $row["reg"];															// *** 2013/03/18 ***
					$nome = $row["nome"];													// *** 2013/03/18 ***
					$arquivo = $row["arquivo"];							// *** 2013/03/18 ***
					$remetente = $row["remetente"];			// *** 2013/03/18 ***
					$prog = $row["prog"];													// *** 2013/03/18 ***
					$datarec = $row["data_rec"];						// *** 2013/03/18 ***
					$status = $row["status"];									// *** 2013/03/18 ***
					echo $reg . ' - ' .$nome . ' - ' .$arquivo . ' - ' .$remetente . ' - ' .$prog . ' - ' .$datarec . ' - ' .$status . '<br>';		// *** 2013/03/18 ***
				}
				mysql_free_result($result);
				break;
				
			case 'PostGreSQL':
				$conexao=pg_connect("host=localhost dbname=pclevy_ca user=pclevy password=scly1411");		// or die ("erro na conex?o"); //RjHost
				if(!$conexao)
					{die('Não foi possível conectar: ' . pg_last_error($conexao));}
				else
					{echo 'PostGreSQL - Conexão bem sucedida<br>';}
				break;
			default:
		}
	}

?>