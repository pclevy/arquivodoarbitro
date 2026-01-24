<?php
//phpInfo();
//exit;
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
		//echo "<br>hhh<br>";

 $nomedoarquivoSM = $list_par[0];
 $nomedoarquivoPGN = $list_par[1];
 $nome_torneio = $nomedoarquivoSM;
	//$nome_torneio='TorneioTest.xtux';
	//$nome_torneio='r20020704.csv';
	echo "
		<html>
			<head>
				<title>Arquivo do Árbitro - Recepção de Arquivos de Torneios - Swiss Manager - PGN</title>
				<script  language='javascript' type='text/javascript'>
					nome_torneio = '$nome_torneio';
					//alert(nome_torneio);
					function verificar_nome_arq()
					{
						corpo_nome_torneio = nome_torneio.substr(0,nome_torneio.indexOf('.'));  //str.indexOf('world')
						corpo_userfilename = document.getElementById('userfilename').value.substr(0,document.getElementById('userfilename').value.indexOf('.'));
						//alert(corpo_nome_torneio + ' - ' + corpo_userfilename);
						if(corpo_nome_torneio != corpo_userfilename)
						{
							//alert(nome_torneio + ' e ' + corpo_userfilename + ' devem ser iguais!');
							alert(corpo_nome_torneio + ' e ' + corpo_userfilename + ' devem ser iguais!');
							return false;
						}
						else
						{
							return true;
						}
					}
				</script>
				
			</head>
			<body bgcolor='beige'>
				<center>
					<font size='5'><b>Arquivo do Árbitro</b></font><br>
					<font size='3'>Repositório de Torneios</font><br>
					<font size='3'><b>Em Construção: Arquivos Swiss Manager - PGN (\"*.pgn\")</b></font><br>
					<br>
					<font size='3'><b><u>Recepção de Arquivos \"Swiss Manager - PGN\"</u></b></font><br><br><br>
					<form name='nomform' enctype='multipart/form-data' action='upload_torneioSM_PGN.php?nome=$nomedoarquivoSM' method='POST'>
						Remetente: <input name='remetente' type='text' value='' size='50'> &nbsp; &nbsp; &nbsp;
						Email: <input name='email' type='text' value='' size='50'>
						<!input name='senha' type='hidden' value=''><br>
						<br>
						Indique o arquivo:<br>
						<input type='hidden' name='userfilename' id='userfilename'><br>
						<input type='hidden' name='prog' id='prog' value='SM'><br>
						<input name='uploaded' id='uploaded' size='60' type='file' onChange='javascript:userfilename.value=uploaded.value'><br><br>
						<input type='submit' onClick='if(!verificar_nome_arq()) {return false;}' value='Enviar'>
					</form>
				</center>
			</body>
		</html>
	";
	exit;
	
?>