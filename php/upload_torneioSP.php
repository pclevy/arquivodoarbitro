<?php 
	
 //$clube = strtoupper(trim($_POST['clube']));
	$target_tmp = "../TorneiosSP/arquivos/";
 
	////$nomedoarquivo = $HTTP_POST_VARS['userfilename'];
 //$prog_atual = $HTTP_POST_VARS['prog'];
	//$remetente_atual = $HTTP_POST_VARS['remetente'];
 //$email_atual = $HTTP_POST_VARS['email'];
 
	//$nomedoarquivo = $_POST['userfilename'];
 $prog_atual = $_POST['prog'];
	$remetente_atual = $_POST['remetente'];
 $email_atual = $_POST['email'];
	
	//echo '***'.$remetente_atual.'***';exit;

 for($i=0;$i<3;$i++)
	 {
			$nomedearquivos[$i] = $_FILES['userfile']['name'][$i];
			$nome_arq_tempo[$i] = $_FILES['userfile']['tmp_name'][$i];
			$tam_do_arquivo[$i] = $_FILES['userfile']['size'][$i];
			$tipo_do_arquiv[$i] = $_FILES['userfile']['type'][$i];
		}
	
	$dir_orig=$target_tmp;
	$dir_dest="../TorneiosSP/";
	
 for($i=0;$i<3;$i++)
	 {
			$extarq[$i]=strtolower(substr($nomedearquivos[$i],strlen($nomedearquivos[$i])-4,4));
			
			if($extarq[$i]!='.ini' && $extarq[$i]!='.trn' && $extarq[$i]!='.sco')
				{
					echo '<br><br><center>Os arquivos devem ser originários do Swiss Perfect \'<b>INI</b>\', \'<b>TRN</b>\', \'<b>SCO</b>\'!!<br>';
					echo ' ---- Arquivos <b>NÃO</b> transferidos!!<br>';
					echo "<a href='#' onclick='history.go(-1);return false;'>Voltar à página anterior!</a><br><br>";
					exit;
				}
		}
 
 for($i=0;$i<3;$i++)
	 {
			$uploaded_nome[$i]=$nomedearquivos[$i];
		}
	
 for($i=0;$i<3;$i++)
	 {
			$uploaded_tam[$i]=$tam_do_arquivo[$i];
			if($uploaded_tam[$i]>1048576)
				{
					echo '<br><br><center>O tamanho máximo de cada arquivo é de <b>1MB (1048576 bytes)</b>, o arquivo em referência tem <b>' . $uploaded_tam . ' bytes</b> !!<br>';
					echo ' ---- Arquivo <b>NÃO</b> transferido!! ---- <br>';
					echo "<a href='#' onclick='history.go(-1);return false;'>Voltar à página anterior!</a><br><br>";
				}
		}
 
 //echo "<br><font size='3'>Nome do Arquivo:<font size='+1'> $nomedoarquivo</font><br><br>";
 //echo "Destino: $target<br><br>";
	
 for($i=0;$i<3;$i++)
	{
		$target[$i] = $target_tmp . $nomedearquivos[$i];
		echo $target[$i] . ' - ' . $nomedearquivos[$i] . '<br>';
		if(!move_uploaded_file($nome_arq_tempo[$i], $target[$i]))
		{
			echo "Desculpe, houve um problema na recepção do seu arquivo. Tente novamente!";
			echo "<a href='#' onclick='history.go(-1);return false;'>Voltar à página anterior!</a><br><br>";
		}
	}
	
	// Lendo String de conexão
	$file = "../../config/conexao_ca.cfg";
	$fh = fopen($file, 'r');
	$conteudo = explode("*", fread($fh, filesize($file)));
	$strconexao = trim($conteudo[0]);
	$codificacao = trim($conteudo[1]);
	fclose($fh);
	//echo "<option value=''> ******* $strconexao - $resultado *********</option>";
	$conexao=pg_connect($strconexao) or die("erro na conexão");
	//Selecionando registros
	$sql =pg_query($conexao,"SELECT reg, nome, remetente, arquivo FROM torneios WHERE status='A' ORDER BY nome") or die("erro de SELECT");
	$resultado=pg_num_rows($sql);

	$gravar='';
	$nomearq=substr($nomedearquivos[0],0,strlen($nomedearquivos[0])-4);
	//echo 'y' . $nomedearquivos[0] . 'y' . $nomearq . 'y';exit;

	//$argumentos="narq=$nomedearquivos[0]&remet=$remetente_atual&email=$email_atual&orig=$dir_orig&dest=$dir_dest&grav=$gravar";
	$argumentos="narq=$nomearq&remet=$remetente_atual&email=$email_atual&orig=$dir_orig&dest=$dir_dest&prog=$prog_atual&grav=$gravar";
	
	for ($i=0;$i<$resultado;$i++)
		{
			$reg = pg_result($sql,$i,'reg');
			$nome = trim(pg_result($sql,$i,'nome'));
			$remetente = trim(pg_result($sql,$i,'remetente'));
			$arquivo = trim(pg_result($sql,$i,'arquivo'));
			
			if($arquivo==$nomedearquivos[0])
				{
					/*
						echo "<div style='width:500;'>
													<form name='form_resposta' method='post' action='verificar_arq_transfSP.php?$argumentos'>
													<fieldset>
														<legend>Já existe Arquivo de Torneio com o nome \"<b><u>$nomedearquivos[0]</u></b>\".<br>Enviado por <b><u>$remetente</u></b><br><br></legend>
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
					*/
					exit;
				}
		}
	$gravar='I';
	
	$argumentos="narq=$nomearq&remet=$remetente_atual&email=$email_atual&orig=$dir_orig&dest=$dir_dest&prog=$prog_atual&grav=$gravar";
	echo "<meta http-equiv='refresh' content='0;URL=verificar_arq_transfSP.php?$argumentos'>";
 exit;
?>