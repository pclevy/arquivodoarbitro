<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

	//$clube = strtoupper(trim($_POST['clube']));
	$target = "../TorneiosSM/arquivos/"; 
	//$nomedoarquivo = $HTTP_POST_VARS['userfilename'];
	//$prog_atual = $HTTP_POST_VARS['prog'];
	//$remetente_atual = $HTTP_POST_VARS['remetente'];
	//$email_atual = $HTTP_POST_VARS['email']; 
	$nomedoarquivo = $_POST['userfilename'];
	$prog_atual = $_POST['prog'];
	$remetente_atual = $_POST['remetente'];
	$email_atual = $_POST['email'];
	$dir_orig=$target;
	$dir_dest="../TorneiosSM/";
	
	
	//echo "<br><br>" . $target . " -- " . $nomedoarquivo . " -- " . $_FILES["uploaded"]["name"] . "<br><br>";
	$nomedoarquivo = $_FILES["uploaded"]["name"];	
	
	$extarq=strtolower(substr($nomedoarquivo,strlen($nomedoarquivo)-4,4)); 
	if($extarq!='tunx' && $extarq!='turx' && $extarq!='tumx' && $extarq!='tutx')
	{
		echo '<br><br><center>O arquivo deve ser originário do Swiss Manager \'<b>TUNx</b>\', \'<b>TURx</b>\', \'<b>TUMx</b>\' ou \'<b>TUTx</b>\'!!<br>';
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
	
	//echo " ******* $strconexao *********";
	//$conexao=pg_connect($strconexao) or die("erro na conexão: $strconexao");
	//echo " ******* $strconexao *********";
	
	$conexao=pg_connect("host=24.144.125.141 port=5432 dbname=pclevy_dbxadrez user=esfinge_arbitro password=scly1411"); // or die("erro na conexão: ");
	
	if (!$conexao) {
    error_log("Erro ao conectar ao banco de dados: " . pg_last_error());
    die("Erro de conexão. Contate o administrador do sistema.");
}
	
	//host=localhost dbname=esfinge_arbitro user=esfinge_arbitro password=scly1411
	echo " ******* $strconexao *********";
	
	/*
	$file = "../config/conexao_ca.cfg";
	$fh = fopen($file, 'r');
	$conteudo = explode("*", fread($fh, filesize($file)));
	$strconexao = trim($conteudo[0]);
	$codificacao = trim($conteudo[1]);
	fclose($fh);
	//echo "$strconexao<br><br>";exit;
	//Conectando com o DB
	$conexao=pg_connect($strconexao) or die("erro na conexão");
	*/
	
	//Selecionando registros
	$sql =pg_query($conexao,"SELECT reg, nome, remetente, arquivo FROM torneios WHERE status='A' ORDER BY nome") or die("erro de SELECT");
	$resultado=pg_num_rows($sql);
	$gravar='';
	$nomearq=$nomedoarquivo;
	//$argumentos="narq=$nomedoarquivo&remet=$remetente_atual&email=$email_atual&orig=$dir_orig&dest=$dir_dest&prog=$prog_atual&grav=$gravar";
	$argumentos="narq=$nomearq&remet=$remetente_atual&email=$email_atual&orig=$dir_orig&dest=$dir_dest&prog=$prog_atual&grav=$gravar";
	for ($i=0;$i<$resultado;$i++)
	{
		$reg = pg_fetch_result($sql,$i,'reg');
		$nome = trim(pg_fetch_result($sql,$i,'nome'));
		$remetente = trim(pg_fetch_result($sql,$i,'remetente'));
		$arquivo = trim(pg_fetch_result($sql,$i,'arquivo'));
		if($arquivo==$nomedoarquivo)
		{
			echo "<div style='width:500;'>
				<form name='form_resposta' method='post' action='verificar_arq_transfSM.php?$argumentos'>
					<fieldset>
						<legend>Já existe Arquivo de Torneio com o nome \"<b><u>$nomedoarquivo</u></b>\".<br>Enviado por <b><u>$remetente</u></b><br><br></legend>
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
			exit;
		}
	}
		$gravar='I';
		//$argumentos="narq=$nomedoarquivo&remet=$remetente_atual&email=$email_atual&orig=$dir_orig&dest=$dir_dest&prog=$prog_atual&grav=$gravar";
		$argumentos="narq=$nomearq&remet=$remetente_atual&email=$email_atual&orig=$dir_orig&dest=$dir_dest&prog=$prog_atual&grav=$gravar";
		echo "<br><br>$argumentos";
		echo "<br><br>$argumentos";

		echo "<meta http-equiv='refresh' content='0;URL=verificar_arq_transfSM.php?$argumentos'>";
		exit;
?>