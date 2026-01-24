<?php
	//phpinfp();
	echo "
		<html>
			<head>
				<title>Arquivo do Árbitro - Recepção de Arquivos de Torneios - Swiss Perfect</title>
			</head>
			<body bgcolor='beige'>
				<center>
					<font size='5'><b>Arquivo do Árbitro</b></font><br>
					<font size='3'>Repositório de Torneios</font><br>
					<font size='3'><b>Em Construção: Arquivos Swiss Perfect (\"*.ini\" \"*.trn\" \"*.sco\")</b></font><br>
					<br>
					<font size='3'><b><u>Recepção de Arquivos \"Swiss Perfect\"</u></b></font><br><br><br>
     
					<form name='nomform' enctype='multipart/form-data' action='upload_torneioSP.php' method='POST'>
											
						Remetente: <input name='remetente' type='text' value='' size='50'> &nbsp; &nbsp; &nbsp;
						Email: <input name='email' type='text' value='' size='50'>
						<!input name='senha' type='hidden' value=''><br>
						<br>
						Indique os arquivos: (O \"Swiss Perfect 98\" produz 3 arquivos, com extensões \".ini\", \".trn\" e \".sco\")<br>
						<!input type='hidden' name='userfilename' id='userfilename'><!br>
						<input type='hidden' name='prog' id='prog' value='SP'><br>
						<!input name='uploaded' type='file' id='uploaded' size='60' onChange='javascript:userfilename.value=uploaded.value'><!br><!br>

						<input name='userfile[]' type='file' id='uploaded' size='60'><br><br>
						<input name='userfile[]' type='file' id='uploaded' size='60'><br><br>
						<input name='userfile[]' type='file' id='uploaded' size='60'><br><br>

						<input type='submit' value='Enviar'>
					</form>
				</center>
			</body>
		</html>
	";
?>