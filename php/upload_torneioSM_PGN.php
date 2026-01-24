<?php
	$parametros = $_SERVER['QUERY_STRING'];
  //$nomearquivoSM
	//echo "$parametros";exit;
	$list_par = split("&", $parametros);
	$qt = count($list_par);
	for($i=0;$i<$qt;$i++)
		{
			$tam = strlen($list_par[$i]);
			$pos = stripos($list_par[$i], "=");
			$list_par[$i] = substr($list_par[$i],$pos+1);
			//echo "$i - $list_par[$i]<br>";
		}
		//echo "<br>xxxx<br>";
  
	$nomearquivoSM=$list_par[0];
	
	//$clube = strtoupper(trim($_POST['clube']));
	$target = "../TorneiosSM/arquivos/"; 
	//$nomedoarquivo = $HTTP_POST_VARS['userfilename'];
	//$prog_atual = $HTTP_POST_VARS['prog'];
	//$remetente_atual = $HTTP_POST_VARS['remetente'];
	//$email_atual = $HTTP_POST_VARS['email']; 
	
	$nomedoarquivo = $_POST['userfilename'];
	//echo "$nomedoarquivo<br>";exit;
	
	$prog_atual = $_POST['prog'];
	$remetente_atual = $_POST['remetente'];
	$email_atual = $_POST['email'];
	$dir_orig=$target;
	$dir_dest="../TorneiosSM/";
	
	
	//echo "<br><br>" . $target . " -- " . $nomedoarquivo . " -- " . $_FILES["uploaded"]["name"] . "<br><br>";
	$nomedoarquivo = $_FILES["uploaded"]["name"];
	//echo "$nomedoarquivo<br>";exit;
	
	
	//$extarq=strtolower(substr($nomedoarquivo,strlen($nomedoarquivo)-4,4));
	//$extarq=strtolower(substr($nomedoarquivo,strlen($nomedoarquivo)-3,3));
	
	$extarq=trim(strtolower(substr($nomedoarquivo,strpos($nomedoarquivo,".")+1,4)));
	//echo "*$nomedoarquivo* - *$extarq* - *" . strlen($extarq) . "*<br>";exit;
	
	if($extarq!='pgn')
	{
		echo '<br><br><center>O arquivo deve ser um "Portable Game Notation", com a extensão \'<b>pgn</b>\'!!<br>';
		echo ' ---- Arquivo <b>NÃO</b> transferido!!<br>';
		echo "<a href='#' onclick='history.go(-1);return false;'>Voltar à página anterior!</a><br><br>";
		exit;
	}
	$uploaded_nome=$_FILES["uploaded"]["name"];	$uploaded_tam=$_FILES["uploaded"]["size"];
	
	if($uploaded_tam>1048576)
	{
		echo '<br><br><center>O tamanho máximo do arquivo é de <b>1MB (1048576 bytes)</b>, o arquivo em referência tem <b>' . $uploaded_tam . ' bytes</b> !!<br>';
		echo ' ---- Arquivo <b>NÃO</b> transferido!! ---- <br>';
		echo "<a href='#' onclick='history.go(-1);return false;'>Voltar à página anterior!</a><br><br>";
	}
	//echo "<br><font size='3'>Nome do Arquivo:<font size='+1'> $nomedoarquivo</font><br><br>";
	//echo "Destino: $target<br><br>";
	
	for($i=0;$i<=0;$i++)
	// **********
	{
		//echo $target . " -- " . $nomedoarquivo . "<br><br>";exit;
		$target = $target . $nomedoarquivo;
		//if(!move_uploaded_file($HTTP_POST_FILES['uploaded']['tmp_name'], $target))
		if(!move_uploaded_file($_FILES['uploaded']['tmp_name'], $target))
		{
			echo "Desculpe, houve um problema na recepção do seu arquivo. Tente novamente";
			echo "<a href='#' onclick='history.go(-1);return false;'>Voltar à página anterior!</a><br><br>";
			echo "<br>1== " . $uploaded_nome . "<br>2== " . $uploaded_tam . " <br>3== " . $_FILES['uploaded']['tmp_name'] . "<br>4== " . $target . " - ";exit;
		}
	}
	// Lendo String de conexão
	$file = "../config/conexao_ca.cfg";
	$fh = fopen($file, 'r');
	$conteudo = explode("*", fread($fh, filesize($file)));
	$strconexao = trim($conteudo[0]);
	$codificacao = trim($conteudo[1]);
	fclose($fh);
	//echo "<option value=''> ******* $strconexao - $resultado *********</option>";exit;
	$conexao=pg_connect($strconexao) or die("erro na conexão");
	//Selecionando registros
	$sql =pg_query($conexao,"SELECT reg, nome, remetente, arquivo, pgn FROM torneios WHERE status='A' ORDER BY nome") or die("erro de SELECT");
	$resultado=pg_num_rows($sql);
	$gravar='';
	
	//$nomearq=$nomedoarquivo;
	$nomearq=$nomearquivoSM;
	
	//$argumentos="narq=$nomedoarquivo&remet=$remetente_atual&email=$email_atual&orig=$dir_orig&dest=$dir_dest&prog=$prog_atual&grav=$gravar";
	$argumentos="narq=$nomearq&pgn=$pgn&remet=$remetente_atual&email=$email_atual&orig=$dir_orig&dest=$dir_dest&prog=$prog_atual&grav=$gravar";
	//echo "$argumentos";exit;
	//echo "$resultado";exit;
	for ($i=0;$i<$resultado;$i++)
	{
		$reg = pg_result($sql,$i,'reg');
		$nome = trim(pg_result($sql,$i,'nome'));
		$remetente = trim(pg_result($sql,$i,'remetente'));
		$arquivo = trim(pg_result($sql,$i,'arquivo'));
		$pgn = trim(pg_result($sql,$i,'pgn'));
		if($pgn==$nomedoarquivo)
		{
			//echo " 1111  $argumentos";exit;
			echo "<div style='width:500;'>
				<form name='form_resposta' method='post' action='verificar_arq_transfSM_PGN.php?$argumentos'>
					<fieldset>
						<legend>Já existe Arquivo de Torneio com o nome \"<b><u>$nomedoarquivo</u></b>\". Enviado por <b><u>$remetente</u></b><br><br></legend>
						<center>
							<input type='radio' name='sobrescrever' value='U'> Sobreescrever  &nbsp;
							<input type='radio' name='sobrescrever' value='N'> Cancelar
						</center>
						<br><br>
					</fieldset>
					<input type='submit' value='Confirmar'>
				</form>
			</div>
			";
			//exit;
		}
	}
		//$gravar='I';
		//$argumentos="narq=$nomedoarquivo&remet=$remetente_atual&email=$email_atual&orig=$dir_orig&dest=$dir_dest&prog=$prog_atual&grav=$gravar";
		if($pgn=='') {$pgn=$nomedoarquivo;}
		$argumentos="narq=$nomearq&pgn=$pgn&remet=$remetente_atual&email=$email_atual&orig=$dir_orig&dest=$dir_dest&prog=$prog_atual&grav=$gravar";
		//echo "$pgn 2222  $argumentos";exit;
		echo"<meta http-equiv='refresh' content='0;URL=verificar_arq_transfSM_PGN.php?$argumentos'>";

		exit;
?>