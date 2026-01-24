<?php
	/* ***** CA - Nome,Titulo,Rating,Clube ***** */
	// * 2013/06/09 * 2012/04/14 * 2012/01/31 *  //Conectando com o DB
	$conexao=pg_connect("host=localhost dbname=pclevy_ca user=pclevy password=scly1411") or die ("erro na conexão"); //RjHost
	
	/*
	$inicio=3885;
	$final=3916;
	echo "<br>Incluindo Novos Registros: de $inicio a $final.<br><br>";
	for($i=$inicio;$i<=$final;$i++)
	{
		$sqltrexp = "INSERT INTO cadastro (reg) VALUES (" . $i . ")";
		echo "$i ";
		$sqltr=pg_query($conexao,$sqltrexp);
	}
	exit;
	*/
	
  //Selecionando registros da Página
  $sql =pg_query($conexao,"SELECT reg,nome,titulo,rating,clube FROM tabela_ajustes ORDER BY reg") or die(); // Rating: 15/03/2012
  $resultado=pg_num_rows($sql);
  if ($resultado<1)
  {
		echo "<font size='4'><br><br><center>Não foram encontradas inscrições que satisfaçam a pesquisa!<br><br>";
		return;
  }
  else
  {
		
		$i=0;
		echo "<br>Teste: $resultado<br>";
		while ($i<$resultado)
			{
				$reg = pg_result($sql,$i,'reg')*1;
				$nome = utf8_decode(pg_result($sql,$i,'nome'));
				$nome = htmlspecialchars($nome, ENT_QUOTES);
				$nome = utf8_encode($nome);
				$titulo = pg_result($sql,$i,'titulo');
				$rating = pg_result($sql,$i,'rating');
				$clube = pg_result($sql,$i,'clube');
				
				$status="A";
				//if($reg<3885)
				//	{
					 $sqltrexp = "INSERT INTO r20130601 (reg,rating,clube,status) VALUES ('" . $reg . "','" . $rating . "','" . $clube . "','" . $status . "')";
					 echo "<br>Reg: $i - $reg";					 
				//	}
				//else
				//	{
				//	 $sqltrexp = "UPDATE cadastro SET nome='$nome',titulo='$titulo',clube='$clube',rating='$rating' WHERE reg='" . $reg . "'";
				//	 echo "<br>Reg: $i - $reg - $nome";					 
				//	}
				
				//echo "<br>Reg: $i -- $reg";
				$sqltr=pg_query($conexao,$sqltrexp);
				// or die("atualizando"); 
				$i=$i+1;
			}
		
	}
	?>
