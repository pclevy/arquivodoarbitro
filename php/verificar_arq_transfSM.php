<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


echo "Teste<br><br>";

	$parametros = $_SERVER['QUERY_STRING'];
	//$nomedoarquivo,$remetente_atual,$email_atual,$dir_orig,$dir_dest,$prog,$gravar
	
	echo "$parametros<br><br>";;
	//$list_par = split("&", $parametros);	$list_par = explode("&", $parametros);
	$qt = count($list_par);
	for($i=0;$i<$qt;$i++)
		{
			$tam = strlen($list_par[$i]);
			$pos = stripos($list_par[$i], "=");
			$list_par[$i] = substr($list_par[$i],$pos+1);
		}
echo "<br><br>$list_par[0]<br><br>";

 $nomedoarquivo = $list_par[0];
 $remetente_atual = rawurldecode($list_par[1]);
 $email_atual = $list_par[2];
 $dir_orig = $list_par[3];
 $dir_dest = $list_par[4];
 $prog = $list_par[5];
 $gravar = $list_par[6];
 
 echo "	$nomedoarquivo"; //exit;
 
	$sobrescrever = $_POST['sobrescrever'];
	if($gravar!='I') { $gravar = $sobrescrever; }
	// Lendo Título ...
	$filetorneio = $dir_orig . $nomedoarquivo;
	$filetorneio2 = $dir_dest . $nomedoarquivo;
	//echo "<br><br>***$filetorneio<br>$filetorneio2***<br><br>";exit;
	$fht = fopen($filetorneio, 'rb');  
	$blocoNaoIdent = fread($fht, 108);
	$TituloTorneio = LerCampoStr($fht);
	//$TituloTorneio = htmlspecialchars($TituloTorneio, ENT_QUOTES);
	//html_entity_decode($str, ENT_COMPAT, 'UTF-8');
	$TituloTorneio = html_entity_decode($TituloTorneio, ENT_QUOTES, 'UTF-8');
	$SubTituloTorneio = LerCampoStr($fht);
	$SubTituloTorneio = html_entity_decode($SubTituloTorneio, ENT_QUOTES, 'UTF-8');
	// Lendo String de conexão
	$file = "../config/conexao_ca.cfg";
	$fh = fopen($file, 'r');
	$conteudo = explode("*", fread($fh, filesize($file)));
	$strconexao = trim($conteudo[0]);
	$codificacao = trim($conteudo[1]);
	fclose($fh);
	//echo "<option value=''> ******* $strconexao - $resultado *********</option>";
	$conexao=pg_connect($strconexao) or die("erro na conexão2");
	$TituloTorneio=utf8_encode($TituloTorneio);
	$SubTituloTorneio=utf8_encode($SubTituloTorneio);
	//$TituloTorneio = $TituloTorneio . '(' . $SubTituloTorneio . ')';
	$status='A';
	$DataRec=Date('Y/m/d');
	switch ($gravar)
	{
		case 'I':
			//echo "case I";exit;
			$sql = pg_query($conexao,"INSERT INTO torneios (nome,arquivo,remetente,email,prog,data_rec,status) VALUES ('$TituloTorneio','$nomedoarquivo','$remetente_atual','$email_atual','$prog','$DataRec','$status')") or die("erro no encerramento de INSERT");			//pg_close($conexao) or die("erro no encerramento da conexão");			//echo "$TituloTorneio - $nomedoarquivo - $remetente_atual - $email_atual - $prog - $DataRec - $status - executou insert?";//exit;
			if (copy($dir_orig . $nomedoarquivo, $dir_dest . $nomedoarquivo))
				{
					unlink($dir_orig . $nomedoarquivo);
				}
			else
				{
					echo "falha ao copiar $file...\n$dir_orig . $nomedoarquivo\n$dir_dest . $nomedoarquivo";
				}
			break;
		case 'U':
			//echo "case U";exit;
			$sql = pg_query($conexao,"UPDATE torneios SET nome='$TituloTorneio',remetente='$remetente_atual',email='$email_atual',prog='$prog',data_rec='$DataRec',status='$status' WHERE arquivo='$nomedoarquivo'") or die("erro de UPDATE");			if (copy($dir_orig . $nomedoarquivo, $dir_dest . $nomedoarquivo))
				{
					unlink($dir_orig . $nomedoarquivo);
				}
			else
				{
					echo "falha ao copiar $file...\n";
				}
			break;
		case 'N':
			echo "case N";
			echo "<SCRIPT LANGUAGE='JavaScript' TYPE='text/javascript'>history.go(-2);</script>";exit;
			break;
		default:
			echo "<b><u>Falha Inesperada</u></b>!!! <b><u>Informe a pclevybr@yahoo.com.br</u></b>!!";exit;
	}
	$sql = pg_query($conexao,"SELECT * FROM torneios WHERE arquivo='$nomedoarquivo'") or die("erro de SELECT");
	$TituloTorneio = utf8_decode(trim(pg_result($sql,0,'nome')));
	$torneio_reg = trim(pg_result($sql,0,'reg'));
	$remetente = utf8_decode(trim(pg_result($sql,0,'remetente')));
	pg_close($conexao) or die("erro no encerramento da conexão");
 echo
     '<center>
       <font size="3"><b>AF Paulo Levy<b></font><br>
       <font size="4">Arquivo do Árbitro</font><br>
       <font size="3"><b>Consulta a Torneios Swiss Manager</b></font><br>
       <br>
       <font size="3"><b><u>Recepção de Torneios</u></b></font><br><br><br>
     ';
  echo "<br>
	      <font size='3'>Título do Torneio: <font size='+1'>$TituloTorneio</font>
				<br>
	      <font size='3'>Nome do Arquivo: <font size='+1'>$nomedoarquivo</font>
				<br>
	      <font size='3'>Registro: <font size='+1'>$torneio_reg</font>
				<br>
	      <font size='3'>Remetente: <font size='+1'>$remetente</font>
				<br>";
//echo "<meta http-equiv='refresh' content='0;URL=../php/LerArquivoSM.php?arq=$nomedoarquivo&DtRec=$DataRec&Remetente=$remetente_atual'>";
//echo "<meta http-equiv='refresh' content='0;URL=../php/LerArquivoSM.php?arq=$nomedoarquivo&dtrec=$DataRec&remetente=$remetente_atual&torneio_reg=$torneio_reg&janela=1'>";
	echo "<meta http-equiv='refresh' content='0;URL=index.php?page=torneio.php&torneio_reg=$torneio_reg'>";
	exit;
	// -----------------------------------------------------------------------------	
	function LerCampoStr($PointerFile)
		{
			$TamString=ord(fread($PointerFile, 1)) * 2;
			if ($TamString>0)
				{
				 $VrlString1 = fread($PointerFile, $TamString);
				 $VrlString = '';
			  for ($i=1;$i<$TamString+1;$i++)
				  {
						 $b=substr($VrlString1,$i,1);
						 if (ord($b)>0) {$VrlString = $VrlString . $b;}
						}
				}
			else
				{$VrlString = "NULO";}
			$Nulo=fread($PointerFile, 1);
			return $VrlString;
	 }
?>