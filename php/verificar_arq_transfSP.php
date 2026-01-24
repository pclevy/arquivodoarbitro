<?php 
	$parametros = $_SERVER['QUERY_STRING'];
 //$nomedoarquivo,$remetente_atual,$email_atual,$dir_orig,$dir_dest,$gravar
	//echo "$parametros";exit;
	$list_par = split("&", $parametros);
	$qt = count($list_par);
	for($i=0;$i<$qt;$i++)
		{
			$tam = strlen($list_par[$i]);
			$pos = stripos($list_par[$i], "=");
			$list_par[$i] = substr($list_par[$i],$pos+1);
		}
 $nomedoarquivo = $list_par[0];
 $remetente_atual = rawurldecode($list_par[1]);
 $email_atual = $list_par[2];
 $dir_orig = $list_par[3];
	$dir_dest = $list_par[4];
 $prog = $list_par[5];
 $gravar = $list_par[6];
	/*
	echo '<br><br>';		// ***** teste ****
	for($i=0;$i<$qt;$i++)
		{
			echo $i . ' - ' . $list_par[$i] . '<br>';
		}
	echo '<br><br>';		// ***** teste ****
 //exit;
	*/
	$sobrescrever = $_POST['sobrescrever'];
 //	echo "$sobrescrever";exit;
	if($gravar!='I') { $gravar = $sobrescrever; }
	
	// Lendo Título ...
	$filetorneio = $dir_orig . $nomedoarquivo;
	$InfoINI = parse_ini_file($filetorneio . '.ini', true);
	$TituloTorneio = $InfoINI['Tournament Info']['Name'];
	//$TituloTorneio = htmlspecialchars($TituloTorneio, ENT_QUOTES);
	//echo $TituloTorneio;//exit;
	
	
	
	// Lendo String de conexão
	$file = "../../config/conexao_ca.cfg";
	$fh = fopen($file, 'r');
	$conteudo = explode("*", fread($fh, filesize($file)));
	$strconexao = trim($conteudo[0]);
	$codificacao = trim($conteudo[1]);
	fclose($fh);
	//echo "<option value=''> ******* $strconexao - $resultado *********</option>";
	$conexao=pg_connect($strconexao) or die("erro na conexão");
	
	
	
	$status='A';
	$DataRec=Date('Y/m/d');
	switch ($gravar)
	{
		case 'I':
			//echo "case I";exit;
			$sql = pg_query($conexao,"INSERT INTO torneios (nome,arquivo,remetente,email,prog,data_rec,status) VALUES ('$TituloTorneio','$nomedoarquivo','$remetente_atual','$email_atual','$prog','$DataRec','$status')");

			//if (copy($dir_orig . $nomedoarquivo, $dir_dest . $nomedoarquivo))
			// {
			//		unlink($dir_orig . $nomedoarquivo);
			//	}
			//else
			//	{
			//		echo "falha ao copiar $nomedoarquivo...\n";
			//	}
			$arq_ini = $nomedoarquivo . '.ini';
			$arq_orig_ini = $dir_orig . $arq_ini;
			$arq_dest_ini = $dir_dest . $arq_ini;
			if (copy($arq_orig_ini, $arq_dest_ini))
				{
					unlink($arq_orig_ini);
				}
			else
				{
					echo "falha ao copiar $arq_ini ...\n";
				}
			$arq_trn = $nomedoarquivo . '.trn';
			$arq_orig_trn = $dir_orig . $arq_trn;
			$arq_dest_trn = $dir_dest . $arq_trn;
			if (copy($arq_orig_trn, $arq_dest_trn))
				{
					unlink($arq_orig_trn);
				}
			else
				{
					echo "falha ao copiar $arq_trn ...\n";
				}
			$arq_sco = $nomedoarquivo . '.sco';
			$arq_orig_sco = $dir_orig . $arq_sco;
			$arq_dest_sco = $dir_dest . $arq_sco;
			if (copy($arq_orig_sco, $arq_dest_sco))
				{
					unlink($arq_orig_sco);
				}
			else
				{
					echo "falha ao copiar $arq_sco ...\n";
				}

				break;
		case 'U':
			//echo "case U";exit;
			$sql = pg_query($conexao,"UPDATE torneios SET nome='$TituloTorneio',remetente='$remetente_atual',email='$email_atual',prog='$prog',data_rec='$DataRec',status='$status' WHERE arquivo='$nomedoarquivo'") or die("erro de UPDATE");
			
			//if (copy($dir_orig . $nomedoarquivo, $dir_dest . $nomedoarquivo))
			// {
			//		unlink($dir_orig . $nomedoarquivo);
			//	}
			//else
			//	{
			//		echo "falha ao copiar $file...\n";
			//	}
			$arq_ini = $nomedoarquivo . '.ini';
			$arq_orig_ini = $dir_orig . $arq_ini;
			$arq_dest_ini = $dir_dest . $arq_ini;
			if (copy($arq_orig_ini, $arq_dest_ini))
				{
					unlink($arq_orig_ini);
				}
			else
				{
					echo "falha ao copiar $arq_ini ...\n";
				}
			$arq_trn = $nomedoarquivo . '.trn';
			$arq_orig_trn = $dir_orig . $arq_trn;
			$arq_dest_trn = $dir_dest . $arq_trn;
			if (copy($arq_orig_trn, $arq_dest_trn))
				{
					unlink($arq_orig_trn);
				}
			else
				{
					echo "falha ao copiar $arq_trn ...\n";
				}
			$arq_sco = $nomedoarquivo . '.sco';
			$arq_orig_sco = $dir_orig . $arq_sco;
			$arq_dest_sco = $dir_dest . $arq_sco;
			if (copy($arq_orig_sco, $arq_dest_sco))
				{
					unlink($arq_orig_sco);
				}
			else
				{
					echo "falha ao copiar $arq_sco ...\n";
				}

				break;
		case 'N':
			echo "case N";
			echo "<SCRIPT LANGUAGE='JavaScript' TYPE='text/javascript'>history.go(-2);</script>";exit;
			break;
		default:
			echo "<b><u>Falha Inesperada</u></b>!!! <b><u>Informe a pclevybr@yahoo.com.br</u></b>!!";exit;
	}
	
	echo
		'<center>
				<font size="3"><b>AF Paulo Levy<b></font><br>
				<font size="4">Arquivo do Árbitro</font><br>
				<font size="3"><b>Consulta a Torneios Swiss Perfect</b></font><br>
				<br>
				<font size="3"><b><u>Recepção de Torneios</u></b></font><br><br><br>
		';
	
 echo "<br><font size='3'>Nome do Arquivo:<font size='+1'> $nomedoarquivo</font><br><br>";
	
	echo "<meta http-equiv='refresh' content='0;URL=../php/LerArquivoSP.php?arq=$nomedoarquivo&DtRec=$DataRec&Remetente=$remetente_atual'>";
			
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