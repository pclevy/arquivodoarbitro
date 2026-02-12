<?php
/* Alterado em 2026/02/12, 19:43 */

ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);
	error_reporting(E_ALL);
?>

<?php
	$file = "../config/conexao_ca.cfg";
	$fh = fopen($file, 'r');
	$conteudo = explode("*", fread($fh, filesize($file)));
	$strconexao = trim($conteudo[0]);
	$codificacao = trim($conteudo[1]);
	fclose($fh);

	$conexao = pg_connect($strconexao) or die("erro na conexão");
	$sql = pg_query($conexao,"SELECT reg, sobrenome, nome, municipio AS clube FROM cadastro ORDER BY nome"); 
	$resultado = pg_num_rows($sql);
	$i=0;
	
	echo "<script language='JavaScript' type='text/javascript'>";
	echo "var listaJogadores = [];";
	while ($i<$resultado) {
		$reg = trim(pg_result($sql,$i,'reg'));
		$prenome = trim(pg_result($sql,$i,'nome'));
		$sobrenome = trim(pg_result($sql,$i,'sobrenome'));
		$nome=trim($prenome . ' ' . $sobrenome);
		$clube = trim(pg_result($sql,$i,'clube'));

		echo "listaJogadores.push({reg:" . json_encode($reg) . ", nome:" . json_encode($nome) . ", clube:" . json_encode($clube) . "});";
		$i++;
	}
	echo "console.log('lista jogadores', listaJogadores);";
	echo "</script>";

	echo "
	<script language='JavaScript' type='text/javascript'>
		window.addEventListener('load', function() {
			listaJogadoresMostrada = listaJogadores;
			var selectElement = document.getElementById('enxadrista_list');
			listaJogadoresMostrada.forEach(function(jogador, index) {
				var option = document.createElement('option');
				option.value = index;
				option.text = jogador.nome;
				selectElement.appendChild(option);
			});
		});
	</script>
	";
?>

<html>
	<head>
		<title>Consultar Registro e Rating!</title>
		<script LANGUAGE="JavaScript" SRC="../js/jstrim.js"></script>
		
		<script language="JavaScript" type="text/javascript">
		var listaJogadoresMostrada = [];
			
		var specialChars =
			[
				{val:"a",let:"áàãâä"},
				{val:"e",let:"éèêë"},
				{val:"i",let:"íìîï"},
				{val:"o",let:"óòõôö"},
				{val:"u",let:"úùûü"},
				{val:"c",let:"ç"},
				{val:"A",let:"ÁÀÃÂÄ"},
				{val:"E",let:"ÉÈÊË"},
				{val:"I",let:"ÍÌÎÏ"},
				{val:"O",let:"ÓÒÕÔÖ"},
				{val:"U",let:"ÚÙÛÜ"},
				{val:"C",let:"Ç"},
				{val:"",let:"?!()"}
			];
			
			function replaceSpecialChars(str) {
				var regex;
				var returnString = str;

				return returnString;
			};		
			
			function criar_opcoes(id_select9) {
				for (i=0;i<listaJogadoresMostrada.length;i++) {
					try {
						id_select9.add(new Option(listaJogadoresMostrada[i].nome, i), id_select9.options[0]);
					} catch(e) {
						id_select9.add(new Option(listaJogadoresMostrada[i].nome, i), 0);
					}
				}
			}
			
			function pesq_nome(strDigitada)
			{
				strPesq=replaceSpecialChars(trim(strDigitada)).toUpperCase();
				document.getElementById("enxadrista_reg").value='';
			
			if(strPesq === "") {
				listaJogadoresMostrada = listaJogadores;
			} else {
				listaJogadoresMostrada = listaJogadores.filter(function(jogador) {
					return replaceSpecialChars(jogador.nome).toUpperCase().indexOf(strPesq) >= 0;
				});
			}
			
			var lista_loc = document.getElementById("enxadrista_list");
			tamlista = lista_loc.length;

			for(i=0; i<tamlista; i++){
				lista_loc.remove(0);
			}
			
			listaJogadoresMostrada.forEach(function(jogador, index) {
				try {
					lista_loc.add(new Option(jogador.nome, index), null);
				} catch(e) {
					lista_loc.add(new Option(jogador.nome, index));
				}
			});
		}
		
		function Select_Click(elemento,typeClick) {
			indice=elemento.options[elemento.selectedIndex].value;
			if(indice!='') {
				var jogador = listaJogadoresMostrada[indice];
				document.getElementById('enxadrista_reg').value=jogador.reg;
				document.getElementById('enxadrista').value=jogador.nome;
				if(typeClick=='dbl') {
					document.getElementById('SubmitButton').click();
				}
			}
		}			
		</script>
	</head>
	
	<body bgcolor="eeeeff">
		Título: <input name='titulo' id='titulo' type='text' value='' size='6' maxlenght='6'>
		<font size="3"><b>Xadrez UERJ</b></font><br>
		<font size="6">Arquivo do Árbitro</font><br>
		<font size="3"><b>Pesquisa de Enxadristas - por nome</b></font> <font size='2' color='red'>(Dados baseados na Lista de Rating de fevereiro/2022!!</font><br>
		<font size='2' color='red'> <!-- <b>Em construção</b>: os dados aqui apresentados ainda são experimentais, podendo haver imprecisões!!</font> --> <br>
		<br>
		<input name='titulo' id='titulo' type='text' value='' size='6' maxlenght='6'>
		<form name='reg_rat_pesq' action='reg_rat_pesq.php' method='post' autocomplete='off'>
			<div style="width:460;background-color:#EDFAD6;line-height:30px;padding:1;border:1px solid #2266AA;">
				<table width='100%'>
					<tr>
						<td valign='top' colspan='4'>
							Clube: <input name='clube' id='clube' type='text' value='' size='10' maxlenght='10'>
							&nbsp; &nbsp; 
							&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
							Status: 
							<select name='status' id='status'>
								<option value='N'>Filiados</option>
								<option value='S' selected='true'>Todos</option>
							</select>
						</td>
					</tr>
					
					<tr>
						<td valign='top' colspan='4'>Faixa de Rating. &nbsp; De: 
							<input name='rat_min' id='rat_min' type='text' value='0' size='4' maxlenght='8'> 
							&nbsp; A &nbsp; 
							<input name='rat_max' id='rat_max' type='text' value='3000' size='4' maxlenght='8'>
							&nbsp; &nbsp; &nbsp; 
							Ritmo: 
							<select name='ritmo' id='ritmo'>
								<option value='S' selected='true'>Clássico</option>
								<option value='Q'>Rápido</option>
								<option value='B'>Relâmpago</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign='top'>Nome:</td>
						<td colspan='4'>
							<input name='enxadrista' id='enxadrista' type='text' value='' size='49' maxlenght='60' onkeyup='TamNomPesq=trim(this.value).length;pesq_nome(this.value);' />
							<input name='enxadrista_reg' id='enxadrista_reg' type='hidden' value='' size='6' />
							<select name='enxadrista_list' id='enxadrista_list' size='15' style="width:340px" onclick='Select_Click(this,"clk");' ondblclick='Select_Click(this,"dbl");'><br>
								<option style="font-weight:bold" value="">Digite acima parte do nome ou Selecione aqui .........</option>
							</select>
						</td>
					</tr>
					<tr><td>&nbsp;</td><td><input id='SubmitButton' type='submit' onClick="if(enxadrista_reg.value<1 && clube.value=='' && titulo.value=='' && rat_min.value=='' && rat_max.value==''){alert('Clique em um nome da Lista e/ou escolha um outro critério!!');enxadrista_list.focus(); return false;}" name='Enviar' value='Enviar'></td></tr>
				</table>
			</div>
		</form>
	</body>
</html>