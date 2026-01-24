<?php
	/* ***** Arquivo do Árbitro - Leitura de Arquivo SM ***** */
  /* *** 24/10/2014 * 02/02/2013 * 31/01/2013 *** */
	
	echo '<html dir="ltr" lang="pt-BR">';
	echo '<head>';
	echo '<meta charset="windows-1252" />';
	//echo '<link href="https://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:400" rel="stylesheet" type="text/css">';		//Yanone+Kaffeesatz:700,300,400,200
	echo '<script language="javascript">
							DivPrincipalLeft=(document.getElementById("DivPrincipal").style.left).replace("px","");
							DivPrincipalTop=(document.getElementById("DivPrincipal").style.top).replace("px","");
			  </script>';
	
	//echo '<script language="javascript">jogador=new Array();</script>';
	/* *** Identificar Método ***
	switch($_SERVER['REQUEST_METHOD'])
	{
		case 'GET': $the_request = &$_GET; break;
		case 'POST': $the_request = &$_POST; break;
		default:
	}
	*/
	//echo $_SERVER["SERVER_NAME"];
	
	$parametros = $_SERVER['QUERY_STRING'];
	//echo " -- $parametros --";exit;
	
	$tam = strlen($parametros);
	$ArqTorneio='';$datarec='';$remetente='';$apresentacao='';
	if($tam>0) 
	{
		$ArqTorneio = trim(htmlspecialchars($_GET['arq']));
		$datarec = trim(htmlspecialchars($_GET['dtrec']));
		$remetente = trim(htmlspecialchars($_GET['remetente']));
		$torneio_reg = trim(htmlspecialchars($_GET['torneio_reg']));
		//$janela = trim(htmlspecialchars($_GET['janela']));
		$janela = isset($_GET['janela']) ?$_GET['janela'] :'';
	}
	
	//$cookie_params = session_get_cookie_params();
	//echo '***'.$cookie_params['path'].'***';
	//*y*$OrdNomeSobreNomeTorneio = "OrdNomeSobreNome".$torneio_reg;
	//*y*//$OrdNomeSobreNome = $_COOKIE[$OrdNomeSobreNomeTorneio];
	//*y*$OrdNomeSobreNome = $_COOKIE["OrdNomeSobreNome60"];
	//*y*if($OrdNomeSobreNome=="") {$OrdNomeSobreNome="NS";}
	//*y*echo "(1) *$OrdNomeSobreNomeTorneio: *$OrdNomeSobreNome*";//exit;
	//*y*//echo "<script language=javascript>NomeSobrenomeFormato</script>"; //cookie
	//*y*echo "<script language=javascript>OrdNomeSobreNomeTorneio='$OrdNomeSobreNomeTorneio';OrdNomeSobreNome='$OrdNomeSobreNome';alert('(2) *'+OrdNomeSobreNomeTorneio + '* - *' + '$OrdNomeSobreNome' + '*');</script>"; //cookie
	
	// Lendo String de conexão
	$file = "../config/conexao_ca.cfg";
	$fh = fopen($file, 'r');
	$conteudo = explode("*", fread($fh, filesize($file)));
	$strconexao = trim($conteudo[0]);
	$codificacao = trim($conteudo[1]);
	fclose($fh);
	echo "$strconexao<br><br>";
	//Conectando com o DB
	$conexao=pg_connect($strconexao) or die("erro na conexão");
	//Selecionando registros de 'ratdiff'
	$sqlexp="SELECT * FROM ratdiff ORDER BY p";
	$sqlres=pg_query($conexao,$sqlexp) or die("Sem Resultados!");
	$resultado=pg_num_rows($sqlres);
	$i=0;
	while ($i<$resultado)
	{
		$dp[$i] = pg_result($sqlres,$i,'dp');
		$i++;
	}
	//Selecionando registros de 'expectancia'
	$sqlexp="SELECT dif_min,dif_max,perc_sup FROM expectancia ORDER BY dif_min";
	$sqlres=pg_query($conexao,$sqlexp) or die("Sem Resultados!");
	$resultado=pg_num_rows($sqlres);
	$i=0;
	while ($i<$resultado)
	{
		$dif_min  = pg_result($sqlres,$i,'dif_min');
		$dif_max  = pg_result($sqlres,$i,'dif_max');
		$perc_sup = pg_result($sqlres,$i,'perc_sup');
		if($dif_max>735){$dif_max=800;}
		for($j=$dif_min;$j<=$dif_max;$j++)
		{
			$expectancia[$j]=$perc_sup;
		}
		$i++;
	}
	
	if($ArqTorneio=='')
	{
		$ArqTorneio = trim($_POST['arquivo']);
		$remetente = trim($_POST['remetente']);
		$datarec = trim($_POST['datarec']);
		$torneio_reg = trim($_POST['torneio_reg']);
		$janela = trim($_POST['janela']);
	}	
		//$apresentacao = "geral";
		
		$versao_teste = false;
		if($versao_teste == true)
		{
			//$ArqTorneio ="ii_aberto_brasil_cordeiropolis_210290.TUNX" ;$remetente = "AF Paulo C. Levy";$datarec = '2014/09/27';//$apresentacao = "geral";
			$ArqTorneio ="fco22_571560.TUNX" ;$remetente = "AF Paulo C. Levy";$datarec = '2022/01/24';
			//echo "*$ArqTorneio* - *$remetente* - *$datarec*<br>";//exit;
		}
			//$ArqTorneio ="ii_aberto_brasil_cordeiropolis_210290.TUNX" ;$remetente = "AF Paulo C. Levy";$datarec = '2014/09/27';//$apresentacao = "geral";
		
		$remetente	= rawurldecode($remetente);
		
		$Idioma=0;			// 0-Inglês  /  1-Português(BR)
		
		$DescrTipoTorneio[0] = "Suiço Individual";
		$DescrTipoTorneio[1] = "Round-Robin Individual";
		$DescrTipoTorneio[2] = "Round-Robin p/Equipes";
		$DescrTipoTorneio[3] = "Suiço p/Equipes";
		$DescrTipoTorneio[4] = "Não Catalogado. Informe a pclevybr@gmail.com";
		$DescrTipoTorneio[5] = "Não Catalogado. Informe a pclevybr@gmail.com";
		$DescrTipoTorneio[6] = "Não Catalogado. Informe a pclevybr@gmail.com";
		$DescrTipoTorneio[7] = "Não Catalogado. Informe a pclevybr@gmail.com";
		
		$DescrAvaliacaoRating[0] = "Nenhum";
		$DescrAvaliacaoRating[1] = "Nacional";
		$DescrAvaliacaoRating[2] = "Internacional";
		$DescrAvaliacaoRating[3] = "Nacional/Internacional";
		
		$Matriz_Desempates[$Idioma][1][1]='';
		Montar_Matriz_Desempates();
		$Resultados[1]='z';
		Montar_Matriz_Resultados();
		
		$TitulosFIDE['GM'] = 0;
		$TitulosFIDE['IM'] = 0;
		$TitulosFIDE['FM'] = 0;
		$TitulosFIDE['CM'] = 0;
		$TitulosFIDE['WGM'] = 0;
		$TitulosFIDE['WIM'] = 0;
		$TitulosFIDE['WFM'] = 0;
		$TitulosFIDE['WCM'] = 0;
		
		$Federacoes[]=0;

		// Lendo Arquivo ...
		$file = "../TorneiosSM/" . $ArqTorneio;
		//echo " ----$file--- ";//exit;
		
		clearstatcache();
		$TamArqTorneio = filesize($file);				// ***** 2019/12/04 *****
		
		//echo '<br>' . filesize($file) . '<br>';exit;
		
		$fh = fopen($file, 'rb');
		
		$blocoNaoIdent1 = fread($fh, 32);
		//echo "$blocoNaoIdent1";exit;
		$reg_chess_results = LerCampoLong($fh);				// *** 2016/04/17 *** Novo ***
			
		$blocoNaoIdent2 = fread($fh, 72);
		//echo "$blocoNaoIdent2";exit;
	
	/*
		for ($iii=0;$iii<70;$iii++)
		{
			//$blocoNaoIdent_data = LerCampoDat2(substr($blocoNaoIdent2,$iii,4));
			//$blocoNaoIdent_data = LerCampoFloat(substr($blocoNaoIdent2,$iii,4));
				$blocoNaoIdent_dataarray = LerCampoFloat(substr($blocoNaoIdent2,$iii,4));
				//$blocoNaoIdent_data = $blocoNaoIdent_dataarray[1];
				//echo " $iii - $blocoNaoIdent_data -<br>";
				//echo print_r($blocoNaoIdent_dataarray) . '<br>';
	
				//echo 'Vlr: ' . round($blocoNaoIdent_dataarray*1, 2) . '<br>';
			//echo " $iii - $blocoNaoIdent_data -<br>";
		}
		exit;
		*/
		$TituloTorneio = LerCampoStr($fh);//echo "$TituloTorneio <br>";	
		//$TituloTorneio = LerCampoStr($fh);echo mb_detect_encoding($TituloTorneio) . " <br>";
		$SubTituloTorneio = trim(LerCampoStr($fh));
		$LeadTorneio = LerCampoStr($fh);
		$Diretor = LerCampoStr($fh);
		$Organizador = LerCampoStr($fh);
		$LocalTorneio = LerCampoStr($fh);//echo "$LocalTorneio <br>";

		// *** Campo Árbitros variando: verificar como identificar ***
		  //echo  '<br>' . TestarSeqAsc($fh,39) . '<br>';//exit;
		  //$ArbitrosAux = LerCampoStr($fh);
		  $ArbitrosAux = LerCampoStrLonga($fh);
		  //echo ">".$ArbitrosAux."< <br>";
		//

    //$Nulo=fread($fh, 1); // ***** 2022/02/14 *****
		//echo  '<br>' . TestarSeqAsc($fh,8) . '<br>';//exit;
		//$Arqqq = LerCampoStrLonga($fh);echo "<br>Arq: $Arqqq<br>";
		//echo  '<br>' . TestarSeqAsc2($fh,57) . '<br>';//exit;
    //echo "<br>Pointer 1: ".ftell($fh)." <br>";
		$ArqPGNentrada = LerCampoStr($fh);//echo "<br>Arq. PGN Entrada: $ArqPGNentrada <br>";
		$ArqPGNsaida = LerCampoStr($fh);//echo "<br>Arq. PGN Saida: $ArqPGNsaida <br>";
		$TitTornPGN = LerCampoStr($fh);//echo "<br>Tit. Torn. PGN: $TitTornPGN <br>";
		$LocTornPGN = LerCampoStr($fh);//echo "<br>Loc. Torn. PGN: $LocTornPGN <br>";
    //echo "<br>Pointer 2: ".ftell($fh)." <br>";
      //$Nulo=fread($fh, 2); // ***** 2022/02/06 *****
			//echo  '<br>S: ' . TestarSeqAsc2($fh,104) . '<br>';//exit;
		//$ArquivoTUNx = LerCampoStr($fh);echo "<br>Arq. Tunx: $ArquivoTUNx <br>";
    $ArquivoTUNx = LerCampoStrLonga($fh);//echo "<br>Arq. Tunx: $ArquivoTUNx <br>";
    //echo "<br>Pointer 3: ".ftell($fh)." <br>";

		//echo  '<br>' . TestarSeqAsc2($fh,44) . '<br>';//exit;
      //$Nulo=fread($fh, 2); // ***** 2022/02/06 *****
      //echo "Nulo: $Nulo <br>";//exit;
    //$Nulo=fread($fh, 29); // ***** 2022/02/06 *****
		$ParamNIdent01 = LerCampoStr($fh);
		//echo "ParamNIdent01: $ParamNIdent01 <br>";//exit;

		$CatIdades = LerCampoStr($fh);//echo "<br>Categorias de Idade: $CatIdades <br>";//exit;
		$Ritmo = LerCampoStr($fh);//echo "Ritmo: $Ritmo <br>";
		$ArquivoHTMLRod = LerCampoStr($fh);//echo "$ArquivoHTMLRod <br>";//exit;
		$ParamNIdent02 = LerCampoStr($fh);//echo "$ParamNIdent02 <br>";
		$ParamNIdent03 = LerCampoStr($fh);//echo "$ParamNIdent03 <br>";
		$ParamNIdent04 = LerCampoStr($fh);//echo "$ParamNIdent04 <br>";
		$ParamNIdent05 = LerCampoStr($fh);//echo "$ParamNIdent05 <br>";
		$PaisSediante = LerCampoStr($fh);
		$ArbitroPrincipal = LerCampoStr($fh);//echo "<br>Árbitro Princ.: $ArbitroPrincipal <br>";
		$FedOficial = LerCampoStr($fh);//echo "<br>Fed. Oficial: $FedOficial <br>";
		$EMail = LerCampoStr($fh);//echo "<br>EMail: $EMail <br>";
		$HomePage = LerCampoStr($fh);//echo "<br>HomePage: $HomePage <br>";
		$Pais2 = LerCampoStr($fh);//echo "<br>Pais2: $Pais2 <br>";
		
		if($codificacao != "utf8")
		{
			$TituloTorneio = utf8_encode($TituloTorneio);
			$SubTituloTorneio = utf8_encode($SubTituloTorneio);
			$LeadTorneio = utf8_encode($LeadTorneio);
			$Diretor = utf8_encode($Diretor);
			$Organizador = utf8_encode($Organizador);
			$ArbitrosAux = utf8_encode($ArbitrosAux);
			$ArbitroPrincipal = utf8_encode($ArbitroPrincipal);
			
			$LocalTorneio = utf8_encode($LocalTorneio);
			
		}
	    
		//$posPonteiro1 = ftell($fh);		
		$NuloN0z1=fread($fh, 18);
		//echo "$NuloN0z1";exit;

		//$NuloN0z2=fread($fh, 92);
		//echo "Teste: $NuloN0z2 !<br>";
		//$Arbitro = "teste 99"; //LerCampoStr($fh);
		$Arbitro = LerCampoStr($fh);
		$Arbitro = utf8_encode($Arbitro);
		//$posPonteiro2 = ftell($fh);		
		//echo "árbitro adjunto: $Arbitro <br>";
		
		
		/*
		$caracter=fread($fh, 1);
		until (!empty($caracter))
		{
			$caracter=fread($fh, 1);
		}
		$ponteiroTemp=ftell($fh);
		fseek($fh, $ponteiroTemp-1);
		$Arbitro = LerCampoStr($fh);
		//echo "$Arbitro<br>";
	    
		$caracteryy=fread($fh, 18);
		//fseek($fh, ftell($fh)-3);
		$Arbitro = LerCampoStr($fh);
	    */
	
	//*** bloco de ajuste - 2012/12/14 ***** Inicio ******
		$CadChave=chr(149).chr(255).chr(137).chr(68);
		
		//$Nulo=fread($fh, 212);      212-88=124
		//$Nulo=fread($fh, 124);
		//$Nulo=fread($fh, 12); // ******* 2022/02/09 *****
		//echo "? $Nulo ?";exit;

		//echo TestarSeqAsc($fh,256) . '<br>';exit;
		//echo "? $Nulo ?";exit;
		
		$posPonteiro = ftell($fh);
		//echo "<br><br>Pointer: " . $posPonteiro . "<br><br>";

    //$dados4 = fread($fh, 184);
		$dados4 = fread($fh, 208); // ******* 2022/02/09 *****
		//echo TestarSeqAsc($fh,208) . '<br>';
		//$posChave=strpos($dados4, $CadChave);echo "? $dados4 ?";exit;
	
		$posChave=strpos($dados4, $CadChave);
		if ($posChave === false) {$posChave=-1;}
		//echo '<br><br> Erro desconhecido!! Avise a pclevybr@gmail.com --- ' . $dados4 . ' yy- ' . $posChave . '<br><br>';
		
		//$intervAdic=-1;
		
		switch ($posChave)
		{
			case 6:
				$intervAdic=6;
				break;
			case 8:
				$intervAdic=8;
				break;
			case 10:
				$intervAdic=10;
				break;
			case 12:
				$intervAdic=12;
				break;
			case 14:
				$intervAdic=14;
				break;
			case 16:
				$intervAdic=16;
				break;
			case 18:
				$intervAdic=18;
				break;
			case 22:
				$intervAdic=22;
				break;
			case 26:
				//$intervAdic=31;		// *** 31 ou 68 **** 43 ou 80 *** ??? ***
				$intervAdic=26;		// *** ??? ***
				break;
			case 32:
				$intervAdic=32;
				break;
			case 36:
				$intervAdic=36;
				break;
			case 42:
				$intervAdic=42;
				break;
			case 44:
				$intervAdic=44;
				break;
			case 46:
				$intervAdic=46;
				break;
			case 54:
				$intervAdic=54;
				break;
			case 56:
				$intervAdic=56;
				break;
			case 58:
				$intervAdic=58;
				break;
			case 60:
				$intervAdic=60;
				break;
			case 62:
				$intervAdic=62;
				break;
			case 68:
				$intervAdic=68;
				break;
			case 74:
				$intervAdic=74;
				break;
			case 76:
				$intervAdic=76;
				break;
			case 80:
				$intervAdic=80;
				break;
			case 86:
				$intervAdic=86;
				break;
			case 90:
				$intervAdic=90;
				break;
			case 108:
				$intervAdic=108;
				break;
			case 112:
				$intervAdic=112;
				break;
			case 120:
				$intervAdic=120;
				break;
			case 126:
				$intervAdic=126;
				break;
			case 142:
				$intervAdic=142;
				break;
			case 146:
				$intervAdic=146;
				break;
			case 150:
				$intervAdic=150;
				break;
			case 152:
				$intervAdic=152;
				break;
			case 154:
				$intervAdic=154;
				break;
			case 158:
			  $intervAdic=158;
				break;
			case 162:
				$intervAdic=162;
				break;
			case 164:
				$intervAdic=164;
				break;
			case 172:
				$intervAdic=172;
				break;
			case 178:
				$intervAdic=178;
				break;
			case 182:
				$intervAdic=182;
				break;
			case 184:
				$intervAdic=184;
				break;
			case 188:
				$intervAdic=188;
				break;
			case 190:
				$intervAdic=190;
				break;
			case 192:
				$intervAdic=192;
				break;
			case 198:
				$intervAdic=198;
				break;
			case 200:
				$intervAdic=200;
				break;
			default:
				$intervAdic=-1;
				echo '<br><br> Chave não encontrada!! Avise a pclevybr@gmail.com ---  (' . $posChave . ')<br><br>';
				exit;
				break;
		}
		
//$intervAdic=1;
		
		//echo $intervAdic;exit;
		
		//if ($intervAdic!=0 AND $intervAdic!=6 AND $intervAdic!=34) {echo " ********* $intervAdic ********";exit;}
		//echo " ********* $intervAdic ********"; exit;
		
		//echo '<br><br> Posição da chave: ' . $posChave . '<br><br>';
		fseek($fh, $posPonteiro+$intervAdic+4);
		//$posPonteiro = ftell($fh);
		//echo "<br><br>Pointer: " . $posPonteiro . "<br><br>";
		
		//echo "$intervAdic<br>";
		//for($i=0;$i<500;$i=$i+5)
		//{
		//$Nulo=fread($fh, 5);
		//echo "i=$i - Nulo: ".ord($Nulo[0])." - ".ord($Nulo[1])." - ".ord($Nulo[2])." - ".ord($Nulo[3])." - ".ord($Nulo[4])." - <br>";
		//}
	//*** bloco de ajuste - 2012/12/14 ***** Final ******
		
		
		
	//$dados4yyy = fread($fh, 3);
	$NumRodadas = LerCampoAsc($fh);//	echo "<br>NR: $NumRodadas<br>";//exit;
	
	//$dados4yyy = fread($fh, 1);
  /*
	for($zzz=1; $zzz<80; $zzz++)
   {
		$NumRodadas = LerCampoAsc($fh);
		echo '<br><br> Erro desconhecido!! Avise a pclevybr@gmail.com --- ' . $zzz . ' - RR: ' . $NumRodadas . '<br><br>';
	 }
	//exit;
	*/
		
		if($janela<1 or $janela>(10+$NumRodadas)) {$janela = 1;}
		
		if($NumRodadas<4){$DigFide9=2;} elseif($NumRodadas>3 and $NumRodadas<14){$DigFide9=3;} elseif($NumRodadas>13){$DigFide9=4;}
		$Mult=pow(10,($DigFide9-2));
		
		$Nulo=fread($fh, 10);//echo "<br>Nulo: $Nulo<br>";		// *** verificado até aqui ***
		//echo "<br>t: ".TestarSeqAsc($fh,10)."<br>"; // ***** 2022/02/21 *****
	
		$TipoTorneio = LerCampoAsc($fh);//echo "TipoTorneio: $TipoTorneio <br>";
		
		$Nulo=fread($fh, 1);//echo ord($Nulo) . "<br>";
		$DataDesc = LerCampoDat($fh);//echo "DataDesc: $DataDesc <br>";// Verificar
  
		$Nulo=fread($fh, 2);
		//echo "<br>t: ".TestarSeqAsc($fh,2)."<br>"; // ***** 2022/02/21 ***** Núm. Rodadas??

		$QtJogadores = LerCampoInt($fh); //echo "<br> QtJogadores: $QtJogadores <br><br>";
  
		$AvaliacaoRating = LerCampoAsc($fh);
		
		//$Nulo=fread($fh, 7);
		$Nulo=fread($fh, 1);
		$NumRodadasReal = LerCampoAsc($fh);echo "<br>NumRodadasReal: ".$NumRodadasReal."<br>"; // ***** 2022/02/21 ***** Núm. Rodadas??
		$Nulo=fread($fh, 5);

		$j = 0;
  for($i=1; $i<10; $i++)
   {
				$b = LerCampoAsc($fh);
				If ($b > 0)
					{
						$j = $j + 1;
						$Desempate[$j] = $b;
					}
   }
		$QtDesempates=$j;
		
		$Nulo=fread($fh, 9);
		$QtEquipes = LerCampoInt($fh);

		$TodosTab = LerCampoAsc($fh);		// Equipe ??? Cor:"Igual a todos os tabuleiros"
  If ($TodosTab == 0)
		 {$DescrTodosTab = "Não";}
  Else
		 {$DescrTodosTab = "Sim";}
		
		$Nulo=fread($fh, 3);
		$CorJogoEmCasa = LerCampoAsc($fh);
  If ($CorJogoEmCasa == 0)
		 {$DescrCorJogoEmCasa = "Negras";}
  Else
		 {$DescrCorJogoEmCasa = "Brancas";}
  
		$Nulo=fread($fh, 6);
		$RatingMinimo = LerCampoInt($fh);
  
		$Nulo=fread($fh, 3);
		$PtsEmparc = LerCampoInt($fh);
		switch ($PtsEmparc)
		 {
				Case 0:
					$DescrPtsEmparc = "Pontos do Jogo";
					break;
				Case 1:
					$DescrPtsEmparc = "Emparceiramento (2,1,0)";
					break;
				Case 97:
					$DescrPtsEmparc = "Pontos do Jogo (3,1,0)";
					break;
				default:
			}
			
		$PtsPartEquipe = LerCampoAsc($fh);
		
		$Nulo=fread($fh, 3);
		$DataInicio = LerCampoDat($fh);
		
		$DataFinal = LerCampoDat($fh);
		
		$Nulo=fread($fh, 1);
		$RepetRodadas = LerCampoAsc($fh);
		
		
		if($RepetRodadas<1) {$RepetRodadas=1;}					// ***** 2019/10/22 * 2019/12/04 *****
		$NumRodadas = $NumRodadas * $RepetRodadas; // ***** 2022/02/14 *****
		
		$Nulo=fread($fh, 1);
		$DataCorte = LerCampoDat($fh);
		
		$Nulo=fread($fh, 2);
		$PontosBye = LerCampoAsc($fh);
		switch ($PontosBye)
		{
			Case 0:
				$VlrPontosBye = 0;
				break;
			Case 1:
				$VlrPontosBye = 0.5;
				break;
			Case 2:
				$VlrPontosBye = 1;
				break;
			default:
		}
		
		$Nulo=fread($fh, 3);
		$ClassAutom = LerCampoAsc($fh);
		If ($ClassAutom == 0)
		{$DescrClassAutom = "Não";}
		Else
		{$DescrClassAutom = "Sim";}
		
		$Nulo=fread($fh, 558);
		
		/*
		$Nulo=fread($fh, 516);
		$Nulo0=fread($fh, 16);
		$Nulo1a=fread($fh, 1);
		$Nulo1b=fread($fh, 1);
		$Nulo2a=fread($fh, 1);
		$Nulo2b=fread($fh, 1);
		$Nulo3a=fread($fh, 1);
		$Nulo3b=fread($fh, 1);
		$Nulo4=fread($fh, 14);
		$Nulo5a=fread($fh, 1);
		$Nulo5b=fread($fh, 1);
		$Nulo6a=fread($fh, 1);
		$Nulo6b=fread($fh, 1);
		$Nulo7a=fread($fh, 1);
		$Nulo7b=fread($fh, 1);
		
		echo $Nulo0.'<br>';
		echo '1 - '.$Nulo1a.' - '.$Nulo1b.'-'.ord($Nulo1b).'<br>';
		echo '2 - '.$Nulo2a.' - '.$Nulo2b.'-'.ord($Nulo2b).'<br>';
		echo '3 - '.$Nulo3a.' - '.$Nulo3b.'-'.ord($Nulo3b).'<br>';
		echo $Nulo4.'<br>';
		echo '5 - '.$Nulo5a.' - '.$Nulo5b.'-'.ord($Nulo5b).'<br>';
		echo '6 - '.$Nulo6a.' - '.$Nulo6b.'-'.ord($Nulo6b).'<br>';
		echo '7 - '.$Nulo7a.' - '.$Nulo7b.'-'.ord($Nulo7b).'<br>';
		echo $Nulo8.'<br>';
		*/
		
		//echo '<br>Tell: '.ftell($fh).'<br>';
		
		for($icrit=1;$icrit<=5;$icrit++)
		 {
				$Corte[$icrit] = LerCampoAsc($fh);
				$QtPior[$icrit] = $Corte[$icrit] % 16;
				$QtMelhor[$icrit] = (int)($Corte[$icrit] / 16);
				$PNJ_Adic[$icrit] = LerCampoAsc($fh);
				
				$Algo[$icrit] = $PNJ_Adic[$icrit] % 16;
				$Adic[$icrit] = $Algo[$icrit] % 4;
				
				If ($Adic[$icrit] == 1)
					{$ExpAdic[$icrit] = "Não";}
				Else
					{$ExpAdic[$icrit] = "Sim";}
				$ExpAdic1C[$icrit]=substr($ExpAdic[$icrit],0,1);
				
				$pJogEqRet[$icrit] = ((int)($PNJ_Adic[$icrit]/16)) % 4;
				$pEquipBye[$icrit] = (int)($PNJ_Adic[$icrit]/16)/4;
				
				
				if($pEquipBye[$icrit] == 1)
					{$EquipBye_Param[$icrit] = 'S';}
				else
					{$EquipBye_Param[$icrit] = 'N';}
				
				$PNJ[$icrit] = (int) ($Algo[$icrit] / 4);
				if($PNJ[$icrit] == 1)
					{$PNJ_Param[$icrit] = 'N';}
				else
					{$PNJ_Param[$icrit] = 'S';}
				
				switch ($PNJ[$icrit])
				{
					Case 0:
						$ExpPNJ[$icrit] = "Calcular com 0,5 ponto (pontos modificados)";
						break;
					Case 1:
						$ExpPNJ[$icrit] = "Calcular com pontos reais";
						break;
					Case 2:
						$ExpPNJ[$icrit] = "Calcular como empate contra si mesmo";
						break;
					Case 3:
						$ExpPNJ[$icrit] = "Calcular como partida contra um jogador virtual";
						break;
					default:
						$ExpPNJ[$icrit] = " ";
				}
				
				$Nulo=fread($fh, 17);
				//echo '<br>Nulo17*' . $Nulo . '*<br>';
				//echo '<br>17*' . TestarSeqAsc($fh, 17) . '*<br>';
		 }
		//echo 'Tell: '.ftell($fh).'<br>';
		$Nulo=fread($fh, 130);
		$TipoRating = LerCampoAsc($fh);
		switch ($TipoRating)
		{
			Case 1:
				$DescrTipoRating = "Rating Nacional";
				break;
			Case 2:
				$DescrTipoRating = "Rating Internacional";
				break;
			Case 3:
				$DescrTipoRating = "Rating Int. depois Rating Nac.";
				break;
			Case 4:
				$DescrTipoRating = "Elo Máximo (Nac,Int)";
				break;
			Case 5:
				$DescrTipoRating = "Rating Nacional (somente)";
				break;
			Case 6:
				$DescrTipoRating = "Rating Internacional (somente)";
				break;
			default:
				$DescrTipoRating = $TipoRating . " ???? ";
		}
		
		$Nulo=fread($fh, 60);
		//echo '*' . TestarSeqAsc($fh, 60) . '*<br>';
		
		$RevisarOrdTab = LerCampoAsc($fh);
		If ($RevisarOrdTab == 0)
		{$DescrRevisarOrdTab = "Não";}
		else
		{$DescrRevisarOrdTab = "Sim";}
		
		$Nulo=fread($fh, 4949);
		
	/*
		$dadoatual=$Nulo;$qtbb=4949;
		//echo " **** $dadoatual ****";exit;
		//echo TestarSeqAsc($fh,$qtbb) . '<br>';exit;
		//echo TestarSeqAsc2($fh,$qtbb) . '<br>';exit;
		for ($iii=0;$iii<$qtbb-2;$iii++)
		{
			//$blocoNaoIdent_data = LerCampoLong2(substr($dadoatual,$iii,4));
			//$blocoNaoIdent_data = LerCampoDat2(substr($dadoatual,$iii,4));
			$blocoNaoIdent_data = LerCampoFloat(substr($dadoatual,$iii,4));
			echo " $iii - $blocoNaoIdent_data -<br>";
			//echo print_r($blocoNaoIdent_data) . '<br>';
		}
		exit;
	*/
	//echo "---" . $NumRodadas . "---";
		if($NumRodadas>0) {
			for($i=1;$i<=$NumRodadas;$i++) {
				$HorarioRodada[$i] = LerCampoStr($fh);
				$HorarioRodada[$i] = str_replace(":","h",$HorarioRodada[$i]);
				$Nulo=fread($fh, 30);
				
				$DataRodada[$i] = LerCampoDat($fh);				//4
				$DataRodada[$i] = str_replace("-","/",$DataRodada[$i]);
				
				$Nulo=fread($fh, 2);
				//$TabulRodada[$i] = LerCampoAsc($fh);				//2
				//$Nulo=fread($fh, 69);
				$TabulRodada[$i] = LerCampoInt($fh);				//2
				
				//$Nulo=fread($fh, 68);
				$Nulo=fread($fh, 10);
				/*
				for($zz=1;$zz<=4;$zz++)
				{
					$Nulo=fread($fh, 1);
					if(ord($Nulo)>0){echo $i.'-'.$zz.'-'.ord($Nulo).'-'.$Nulo.'<br>';}
				}
				*/
				$DataRodada2[$i] = LerCampoDat($fh);				//4
				//echo $i.'-'.$DataTest.'<br>';
				$Nulo=fread($fh, 54);
				
				//echo "Rodada $i: $HorarioRodada[$i]  -  $DataRodada[$i] - $TabulRodada[$i]<br>";
			}
		}
		else {
			$JogIniChave = chr(165).chr(255).chr(137).chr(68);
			$posPonteiroAtual = ftell($fh);
			$RestoArquivo=fread($fh,$TamArqTorneio-$posPonteiroAtual);
			$PosChaveJ = strpos($RestoArquivo, $JogIniChave);
			fseek($fh, $posPonteiroAtual + $PosChaveJ);
			//$posPonteiroAtual2 = ftell($fh);
			//echo 'Pa: ' . $posPonteiroAtual ." - P2: ". $posPonteiroAtual2 ." - D: ". ($posPonteiroAtual - $posPonteiroAtual2) ." - T: ". $TamArqTorneio ." - PC: ". $PosChaveJ . "*<br><br>";

			//$Nulo=fread($fh, 15400);
			//echo TestarSeqAsc($fh,912) . '***<br>';exit;
			
		}

				//$Nulo=fread($fh, 1);			//$JogIniChave = chr(165).chr(255).chr(137).chr(68)
				//echo "Nulo: $Nulo";
				//for($zz=1;$zz<=4;$zz++)
				//	{
				//		$Nulo=fread($fh, 1);
				//		if(ord($Nulo)>0){echo $zz.'-'.ord($Nulo).'-'.$Nulo.'<br>';}
				//	}
				
				$SomaElo=0;

				$Nulo=fread($fh, 4);			//$JogIniChave = chr(165).chr(255).chr(137).chr(68)
				//echo "Nulo: $Nulo";

		  $z=1;
			while ($z<=$QtJogadores)
			{
				$RankInicial[$z]=$z;		// *** talvez seja eliminado ***
				
				//$SobreNomeJogador = trim(LerCampoStr($fh));
				//$PreNomeJogador = trim(LerCampoStr($fh));
				//$NomeJogador[$z] = RetirarNulo(trim($PreNomeJogador . ' ' . $SobreNomeJogador));
				////$NomeJogador[$z]=stripslashes(htmlspecialchars($NomeJogador[$z], ENT_QUOTES));
				//$NomeJogador[$z]=addslashes($NomeJogador[$z]);
				$SobreNomeJogador[$z] = addslashes(RetirarNulo(trim(LerCampoStr($fh))));
				$PreNomeJogador[$z] = addslashes(RetirarNulo(trim(LerCampoStr($fh))));
				//echo 	$PreNomeJogador[$z];
				//echo $PreNomeJogador[$z] . " " . $SobreNomeJogador[$z] . " - " . $RankInicial[$z] . "<br>";
				//echo "	$PreNomeJogador[".$z."]   $SobreNomeJogador[".$z."];";

        		if($codificacao != "utf8")
        		{
        			$PreNomeJogador[$z] = utf8_encode($PreNomeJogador[$z]);
        			$SobreNomeJogador[$z] = utf8_encode($SobreNomeJogador[$z]);
        		}
				
				//*y*//if($_COOKIE["OrdNomeSobreNome"]=='NS')
				//*y*if($OrdNomeSobreNome=='NS')
				//*y*{$NomeJogador[$z] = $PreNomeJogador[$z] . ' ' . $SobreNomeJogador[$z];}
				//*y*else
				//*y*//{$NomeJogador[$z] = $SobreNomeJogador[$z] . ', ' . $PreNomeJogador[$z];}
				//*y*{$NomeJogador[$z] = $SobreNomeJogador[$z] . ' ' . $PreNomeJogador[$z];}
				
				$NomeJogador[$z] = $PreNomeJogador[$z] . ' ' . $SobreNomeJogador[$z];
				//echo "	<br>nomes: $PreNomeJogador[$z] $SobreNomeJogador[$z]  ---  ";
				//echo "	Nome: $NomeJogador[$z] <br>";
				
				/*
				echo "<script language='javascript' type='text/javascript'>";
				echo "	SobreNomeJogador[".$z."] = '".$SobreNomeJogador[$z]."';";
				echo "	PreNomeJogador[".$z."] = '".$PreNomeJogador[$z]."';";
				echo "	NomeJogador[".$z."] = PreNomeJogador[".$z."] + ' ' + SobreNomeJogador[".$z."];";
				echo "</script>";
				*/
				
				$TitAcademico[$z] = LerCampoStr($fh) . '&nbsp;';
				$NomeCurto[$z] = LerCampoStr($fh);
				
				$TitFIDE[$z] = trim(LerCampoStr($fh));
				$Tit=RetirarNulo($TitFIDE[$z]);
				
				if(!isset($TitulosFIDE[$Tit])) {$TitulosFIDE[$Tit]=0;}		// ***** 2019/10/28 *****
				if($Tit!='') {$TitulosFIDE[$Tit]=$TitulosFIDE[$Tit]+1;}
				$TitFIDE[$z] = $Tit . '&nbsp;';
				
				$ID_Nacional[$z] = trim(LerCampoStr($fh));
				if($ID_Nacional[$z]=='') {$ID_Nacional[$z] = '&nbsp;';}
				
				//$BlocoD1[$z] = "?" . ord(fread($fh,1)) . ord(fread($fh,1)) . ord(fread($fh,1)) . ord(fread($fh,1)) . ord(fread($fh,1)) . ord(fread($fh,1))."??";
				//$BlocoD1[$z] = '?' . ord(fread($fh,6)) . '?';
				$BlocoD1[$z] = '?' . TestarSeqAsc2($fh,6) . '?';
				
				//$Clube[$z] = LerCampoStr($fh) . '&nbsp;';
				$Clube[$z] = utf8_encode(trim(LerCampoStr($fh)));
				$Clube[$z] = htmlspecialchars($Clube[$z], ENT_QUOTES); // Clube/Cidade com aspa ********************
				
				$PaisJogador[$z] = trim(LerCampoStr($fh));
				$Federacao=RetirarNulo($PaisJogador[$z]);
				if(isset($Federacoes[$Federacao])) {$Federacoes[$Federacao]=+1;} else {$Federacoes[$Federacao]=1;}

				$Categoria[$z] = trim(LerCampoStr($fh));
				if($Categoria[$z]=='') {$Categoria[$z] = '&nbsp;';}
				$Grupo[$z] = RetirarNulo(trim(LerCampoStr($fh)));
				//if($Grupo[$z]=='') {$Grupo[$z] = '&nbsp;';}
				if($Grupo[$z]=='') {$Grupo[$z] = '?';}
				//$MatrizGrupos[$Grupo[$z]] =	$MatrizGrupos[$Grupo[$z]] . $z . '||';
				if(isset($MatrizGrupos[$Grupo[$z]])) {$MatrizGrupos[$Grupo[$z]]=$MatrizGrupos[$Grupo[$z]] . $z . '||';} else {$MatrizGrupos[$Grupo[$z]]= $z . '||';}
				
				//$BlocoD2[$z] = '?' . ord(fread($fh,8)) . '?';
				$BlocoD2[$z] = '?' . TestarSeqAsc2($fh,8) . '?';
				
				//$BlocoD2[$z] = '?';
				//for($zzz=1;$zzz<=8;$zzz++) {$BlocoD2[$z]=$BlocoD2[$z] . ord(fread($fh,1));}
				//$BlocoD2[$z]=$BlocoD2[$z]. '?';
				
				$Fonte[$z] = trim(LerCampoStr($fh));
				if($Fonte[$z]=='') {$Fonte[$z] = '&nbsp;';}
				
				//$BlocoD3[$z] = "?" . fread($fh,30);
				$BlocoD3[$z] = "?" . TestarSeqAsc2($fh,30);
				
				//$BlocoD3[$z] = '?';
				//for($zzz=1;$zzz<=30;$zzz++) {$BlocoD3[$z]=$BlocoD3[$z] . ord(fread($fh,1));}
				//$BlocoD3[$z]=$BlocoD3[$z]. '?';
				
				$CodSexo = LerCampoAsc($fh);
				switch ($CodSexo)
					{
						Case 0:
							$Sexo[$z] = "M";	// Masculino
							break;
						Case 1:
							$Sexo[$z] = "F";	// Feminino
							break;
						Case 2:
							$Sexo[$z] = "C";	// Computador
							break;
						default:
							$Sexo[$z] = " ";
					}
				
				$BlocoD4[$z] = "?" . fread($fh,1) . '?';
				$RatFIDE[$z] = LerCampoInt($fh);
				$RatNacional[$z] = LerCampoInt($fh);
				
				//$BlocoD4b[$z]= '?' . ord(fread($fh, 1)) . ord(fread($fh, 1)) . '?';
				$BlocoD4b[$z]= '?' . TestarSeqAsc2($fh, 2) . '?';
				
				$DataNasc[$z] = LerCampoDat($fh);
				
				//$Nulo=fread($fh, 2);
				$NumeroClube[$z] = LerCampoAsc($fh);
				
				//$BlocoD5[$z] = '?' . ord(fread($fh, 1)) . ord(fread($fh, 1)) . ord(fread($fh, 1)) . '?';
				$BlocoD5[$z] = '?' . TestarSeqAsc2($fh, 3) . '?';
				
				//$BlocoD6[$z] = LerCampoAsc($fh) . "/" . LerCampoAsc($fh);
				//$BlocoD6[$z]= '?' . ord(fread($fh, 1)) . ord(fread($fh, 1)) . '?';
				$OrdInicJog[$z] = LerCampoInt($fh);
				
				$IdFIDE[$z] = LerCampoLong($fh);
				
				//$Nulo=fread($fh, 1);
				$Equipe[$z] = LerCampoAsc($fh);
				//$Nulo=fread($fh, 1);
				$BlocoD6b[$z]= '?' . ord(fread($fh, 1)) . '?';
				$TabulEquip[$z] = LerCampoAsc($fh);//echo "	NumTab: $Equipe[$z] / $TabulEquip[$z] <br>";
				
				//$Nulo=fread($fh, 1);
				$BlocoD6c[$z]= '?' . ord(fread($fh, 1)) . '?';
				$DM[$z] = LerCampoAsc($fh);
				//if($DM[$z]<>'7'){$DM[$z]='0';}
				
				//$BlocoD7[$z] = '?' . fread($fh,13) . '?';
				$BlocoD7[$z] = '?' . TestarSeqAsc2($fh,13) . '?';
				
				$Status[$z] = ord(fread($fh,1));
        
				// $BlocoD7b[$z] = '?' . fread($fh,3) . '?';
				$BlocoD7b[$z] = '?' . TestarSeqAsc2($fh,3) . '?';
				
				$PtsAdic[$z] = LerCampoInt($fh)/2;
				
				//$BlocoD8[$z] = "?" . fread($fh,2);
				$BlocoD8[$z] = "?" . TestarSeqAsc2($fh,2);
				
				$BNoPair[$z] = LerCampoInt($fh);
				$SNo[$z] = LerCampoInt($fh);
				$FatorK[$z] = LerCampoInt($fh);
				
				$BlocoD9[$z] = "?" . TestarSeqAsc2($fh,50) . "?";
				//$BlocoD9[$z] = "?" . fread($fh,50) . "?";
			
			 /*
			 $dadoatual=$BlocoD9[$z];$qtbb=50;
			 //echo " **** $dadoatual ****";exit;
			 //echo TestarSeqAsc($fh,$qtbb) . '<br>';exit;
			 //echo TestarSeqAsc2($fh,$qtbb) . '<br>';exit;
			 for ($iii=0;$iii<$qtbb-2;$iii++)
			 {
			 //$blocoNaoIdent_data = LerCampoLong2(substr($dadoatual,$iii,4));
			 //$blocoNaoIdent_data = LerCampoDat2(substr($dadoatual,$iii,4));
			 $blocoNaoIdent_data = LerCampoFloat(substr($dadoatual,$iii,4));
			 echo " $iii - $blocoNaoIdent_data -<br>";
			 //echo print_r($blocoNaoIdent_data) . '<br>';
			}
			//		exit;
			*/				
				if($RatFIDE[$z]>0)
				 {$SomaElo=$SomaElo+$RatFIDE[$z];}
				elseif($RatNacional[$z]>0)
				 {$SomaElo=$SomaElo+$RatNacional[$z];}
				else
				 {$SomaElo=$SomaElo+$RatingMinimo;}
				
				//echo $PreNomeJogador[$z] . " " . $SobreNomeJogador[$z] . " - " . $RankInicial[$z] . " - " .  $OrdInicJog[$z] . " - " . $BNoPair[$z] . " - " . $SNo[$z] . "<br>";

				$z++;
			}
			
      //echo "Testeeeeeeeeeeee<br><br>";
			/*
			echo "<script language='javascript' type='text/javascript'>";
			echo " jogador9=new Array();";			
			echo "alert('Teste 111: ');";
			echo "</script>";
      */
			echo "<script language='javascript' type='text/javascript'>";
			echo " jogador=new Array();";
			
			//echo "alert();";

				for ($z=1;$z<=$QtJogadores;$z++)
				//for ($z=1;$z<=412;$z++)
				 {
				  $z1=$z-1;
				  echo "jogador[".$z1."]=new Object();";
					echo "jogador[".$z1."].ord_ini='".$z."';";
					echo "jogador[".$z1."].TitFIDE='".RetirarNulo($TitFIDE[$z])."';";
					echo "jogador[".$z1."].Genero='".RetirarNulo($Sexo[$z])."';";
					echo "jogador[".$z1."].Categoria='".RetirarNulo($Categoria[$z])."';";
					
					echo "jogador[".$z1."].SobreNomeJogador='".RetirarNulo($SobreNomeJogador[$z])."';";
					echo "jogador[".$z1."].PreNomeJogador='".RetirarNulo($PreNomeJogador[$z])."';";
					echo "jogador[".$z1."].NomeJogador='".RetirarNulo($NomeJogador[$z])."';";
					
					//echo "alert('jogador2: ' + jogador[".$z1."].NomeJogador);"; //************ */
					//echo 'alert("Teste");';
					//exit;alert
					
					echo "jogador[".$z1."].DataNasc='".RetirarNulo($DataNasc[$z])."';";
					echo "jogador[".$z1."].IdNAC='".RetirarNulo($ID_Nacional[$z])."';";
					echo "jogador[".$z1."].IdFIDE='".RetirarNulo($IdFIDE[$z])."';";
					echo "jogador[".$z1."].PaisJogador='".RetirarNulo($PaisJogador[$z])."';";
					echo "jogador[".$z1."].RatFIDE=".$RatFIDE[$z].";";
					echo "jogador[".$z1."].FatorK=".$FatorK[$z].";";
					echo "jogador[".$z1."].RatNAC=".$RatNacional[$z].";";
					if(strlen($Clube[$z])<1) {$Clube[$z]="&nbsp;";}
					echo "jogador[".$z1."].Clube='".RetirarNulo($Clube[$z])."';";
					echo "jogador[".$z1."].Grupo='".RetirarNulo($Grupo[$z])."';";
					
					//echo "document.getElementById('test43').innerHTML=jogador[".$z1."].NomeJogador;if(MaiorNome<document.getElementById('test43').offsetWidth){MaiorNome=document.getElementById('test43').offsetWidth};";
					
					}
					//$z1=66;
					//echo "alert('jogador1: ".$z1.": ' + jogador[".$z1."].NomeJogador);"; //************ */
			
			echo "</script>";
			
			//echo "B: $BlocoD1[6] - $BlocoD1[7]";//exit;
			
			$EloMedio=round($SomaElo/$QtJogadores, 0);
			
			//echo '<br><br>' . $EloMedio . '<br><br>';			
			//echo '<br>MatrizGrupos[MG]: ' . $MatrizGrupos['MG'] . '<br><br>';
			
			$zg=0;
			foreach ($MatrizGrupos as $i => $value)
			{
				$GrupoDescr[$zg] = $i;
				$zg++;
			}
			$NumGrupos=$zg;
			//echo '<br>'.$NumGrupos.'<br>';
			
				// ***** Resultados *********************************************************
				// ----- Emparceiramentos e Resultados --------------------------------------

				$Nulo=fread($fh, 4);
				//echo "<br>";echo ftell($fh);echo "<br>";
				//echo "$NumRodadas";exit;
			
				for ($e=1;$e<=$QtEquipes;$e++)
				 {$PontosEquipe[$e]=0;$QtJogadoresEquipe[$e]=0;}
				
				for ($j=1;$j<=$QtJogadores;$j++)
				{
					$PontosJogador[$j]=0;
					$JogClassif[$j]=0;
					$performace[$j]=0;
					//$ratvar[$j][1]=0;$ratvar[$j][2]=0;$ratvar[$j][3]=0;$ratvar[$j][4]=0;$ratvar[$j][5]=0;$ratvar[$j][6]=0;
					$ratvart[$j]=0;
					$media_rat[$j][0]=0;$media_rat[$j][1]=0;$media_rat[$j][2]=0;
					$JogNegras[$j]=0;
					$NumVitJog[$j]=0;
					$PtsDesemp[0][$j]=$j;
					
					//for($i=1;$i<=5;$i++){$PontosKoya[$i][$j]=0;$PontosBH[$i][$j]=0;$PontosSB[$i][$j]=0;)
					
					for ($jc=1;$jc<=6;$jc++)
					{
						$PtsDesemp[$jc][$j]=0;
					}
				}
				
				$ptini=1;  $p=0;
				$VitBt=0;$VitNt=0;$Empatest=0;$Aust=0;$Tott=0;
				
				//test43
				//echo '<div id="test43" style="visibility:visible;display:inline-block;padding:0px;border:3px solid #FF0000;">Teste</div>';
				echo '<div id="test43" style="display:none;height:0px;padding:0px;">.</div>';
				echo "<script language='javascript' type='text/javascript'>";
				//echo "MaiorNome=0;";
				echo "OrdNomeSobreNome='NbS';";
				//echo "document.getElementById('test43').innerHTML='kkkkk';";

				echo "</script>";

				//echo "Núm.Rodadas: ".$NumRodadas."<br>"; // ***** 2022/02/14 *****
				for ($r=1;$r<=$NumRodadas;$r++)
				 {
						//echo "Rodada: ".$r."<br>"; // ***** 2022/02/14 *****
						//$fp1=ftell($fh);
						
						$MesaTabul = 0;
						$VitBr[$r]=0;$VitNr[$r]=0;$Empatesr[$r]=0;$Ausr[$r]=0;$Totr[$r]=0;
						for ($p=$ptini;$p<=($ptini + 21 * $TabulRodada[$r] - 1);$p=$p+21)						
							{
						  
								//$CodJogA = 0; $CodJogB = 0;
								$MesaTabul = $MesaTabul + 1;
								
								//echo "<br>";echo ftell($fh);echo "<br>";
								//$Nulo=fread($fh, 5);
								$CodJogA = LerCampoInt($fh)*1;
								//echo $CodJogA." - ".$NomeJogador[$CodJogA]."<br>"; // ***** 2022/02/14 *****
								//echo "$CodJogA";exit;
								//$JogA = $NomeJogador[$CodJogA] . " (" . $CodJogA . ")";
								$JogA = $NomeJogador[$CodJogA];
								
								$CodJogB = LerCampoInt($fh);
								//echo $CodJogB." - ".$NomeJogador[$CodJogB]."<br>"; // ***** 2022/02/14 *****

								if($CodJogB>65000)
								{
									$CodJogB=$CodJogB-65536;
									$JogB = 'Bye';	//.($CodJogB);
									//echo "$JogB".substr($JogB,0,3);
									//exit;
								}
								else
								{
									//$JogB = $NomeJogador[$CodJogB] . " (" . $CodJogB . ")";
									$JogB = $NomeJogador[$CodJogB];
								}
								
								//test43
								echo "<script language='javascript' type='text/javascript'>";
								//echo "document.getElementById('test43').innerHTML='".$JogA."';if(MaiorNome<document.getElementById('test43').offsetWidth){MaiorNome=document.getElementById('test43').offsetWidth;}";
								//echo "alert(MaiorNome);";
								echo "</script>";
								//echo "<script language='javascript' type='text/javascript'>alert('Final: ' + MaiorNome);</script>";
								
								$CodRes = LerCampoInt($fh);
								//$JogB = $NomeJogador($CodJogB) & " (" & $CodJogB & ")"
         // **************************************************
         if($CodJogB==-2 & $CodRes>0) {$CodRes = $CodRes + 12;}
         // **************************************************
								//echo "$JogA -  *$CodRes-$Resultados[$CodRes]*  -  $JogB<br>";
								$RodMesaResult[$r][$MesaTabul][1]=$JogA;
								$RodMesaResult[$r][$MesaTabul][2]=$PontosJogador[$CodJogA];
								$RodMesaResult[$r][$MesaTabul][3]=$Resultados[$CodRes];
								if($CodJogB<1)
								 {$RodMesaResult[$r][$MesaTabul][4]=0;}
								else
								 {$RodMesaResult[$r][$MesaTabul][4]=$PontosJogador[$CodJogB];}
								$RodMesaResult[$r][$MesaTabul][5]=$JogB;
								
								$RodMesaResult[$r][$MesaTabul][6]=$RatFIDE[$CodJogA];
								$RodMesaResult[$r][$MesaTabul][7]=$TitFIDE[$CodJogA];
								$RodMesaResult[$r][$MesaTabul][8]=$PaisJogador[$CodJogA];
								
								if($CodJogB<1) {
									$RodMesaResult[$r][$MesaTabul][9]=0;
									$RodMesaResult[$r][$MesaTabul][10]="";
									$RodMesaResult[$r][$MesaTabul][11]="";
								}
								else {
									$RodMesaResult[$r][$MesaTabul][9]=$RatFIDE[$CodJogB];
									$RodMesaResult[$r][$MesaTabul][10]=$TitFIDE[$CodJogB];
									$RodMesaResult[$r][$MesaTabul][11]=$PaisJogador[$CodJogB];
								}
								
								// ***** ATENÇÃO: mudar a estrutura ***** 2016/04/22 ********
								$RodMesaResult[$r][$MesaTabul][12]=$CodJogA;
								$RodMesaResult[$r][$MesaTabul][13]=$CodJogB;
								// *********************************************************
								
								//echo $RodMesaResult[$r][$MesaTabul][1];echo " ";
								//echo $RodMesaResult[$r][$MesaTabul][2];echo " ";
								//echo $RodMesaResult[$r][$MesaTabul][3];echo "<br>";
								
								//echo "<br><br> Status do Jogador B: $Status[$CodJogB] <br><br>";
								switch ($CodRes)
									{
										Case 0:
											$PontosJogA = 0; $PontosJogB = 0;
											break;
											
										Case 1:
											//echo "<br><br> Status do Jogador A: $Status[$CodJogA] $NomeJogador[$CodJogA] - $r &nbsp; - &nbsp; Status do Jogador B: $Status[$CodJogB] $NomeJogador[$CodJogB] - $r<br><br>";
											$ResulJogA = 1; $ResulJogB = 0;
											
											if(!isset($Status[$CodJogA])) {$Status[$CodJogA]=0;}	// ***** 2019/10/28 *****
											if(!isset($Status[$CodJogB])) {$Status[$CodJogB]=0;}	// ***** 2019/10/28 *****
											
											if($Status[$CodJogA]==0 && $Status[$CodJogB]==0)
												{
													$PontosJogA = 1; $PontosJogB = 0;
													$VitBr[$r]=$VitBr[$r]+1;
													$NumVitJog[$CodJogA]=$NumVitJog[$CodJogA]+1;
													
													if(!isset($media_rat[$CodJogA][0])) {$media_rat[$CodJogA][0]=0;}	// ***** 2019/10/28 *****
													if(!isset($RatFIDE[$CodJogB])) {$RatFIDE[$CodJogB]=0;}						// ***** 2019/10/28 *****
													$media_rat[$CodJogA][0] = $media_rat[$CodJogA][0] + $RatFIDE[$CodJogB];	//*** soma de rating dos adversários ***
													$media_rat[$CodJogA][1] = $media_rat[$CodJogA][1] + 1;									//*** qt partidas jogadas ***
													$media_rat[$CodJogA][2] = $media_rat[$CodJogA][2] + $PontosJogA;				//*** total de pts nestas partidas ***
													
													if(!isset($media_rat[$CodJogB][0])) {$media_rat[$CodJogB][0]=0;}	// ***** 2019/10/28 *****
													if(!isset($RatFIDE[$CodJogA])) {$RatFIDE[$CodJogA]=0;}						// ***** 2019/10/28 *****
													$media_rat[$CodJogB][0] = $media_rat[$CodJogB][0] + $RatFIDE[$CodJogA];
													
													if(!isset($media_rat[$CodJogB][1])) {$media_rat[$CodJogB][1]=0;}	// ***** 2019/10/28 *****
													$media_rat[$CodJogB][1] = $media_rat[$CodJogB][1] + 1;
													
													if(!isset($media_rat[$CodJogB][2])) {$media_rat[$CodJogB][2]=0;}	// ***** 2019/10/28 *****
													$media_rat[$CodJogB][2] = $media_rat[$CodJogB][2] + $PontosJogB;
													
													$diffrat = ($RatFIDE[$CodJogA]-$RatFIDE[$CodJogB]);
													if($diffrat>400){$diffrat=400;}elseif($diffrat<-400){$diffrat=-400;};
													$ratvar[$CodJogA][$r] = $diffrat > 0 ? ((100-$expectancia[abs($diffrat)])*$FatorK[$CodJogA])/100:(($expectancia[abs($diffrat)])*$FatorK[$CodJogA])/100;
													
													if(!isset($FatorK[$CodJogB])) {$FatorK[$CodJogB]=0;}	// ***** 2019/10/28 *****
													$ratvar[$CodJogB][$r] = $diffrat < 0 ? -(($expectancia[abs($diffrat)])*$FatorK[$CodJogB])/100:-((100-$expectancia[abs($diffrat)])*$FatorK[$CodJogB])/100;
													$ratvart[$CodJogA] = $ratvart[$CodJogA] + $ratvar[$CodJogA][$r];
													
													if(!isset($ratvart[$CodJogB])) {$ratvart[$CodJogB]=0;}				// ***** 2019/10/28 *****
													if(!isset($ratvar[$CodJogB][$r])) {$ratvar[$CodJogB][$r]=0;}	// ***** 2019/10/28 *****
													$ratvart[$CodJogB] = $ratvart[$CodJogB] + $ratvar[$CodJogB][$r];
											 }
											else
												{
													$PontosJogA = 0; $PontosJogB = 0;
											 }
											break;
										Case 2:
											$ResulJogA = '½'; $ResulJogB = '½';
											if($Status[$CodJogA]==0 && $Status[$CodJogB]==0)
												{
													$PontosJogA = 0.5; $PontosJogB = 0.5;
													$Empatesr[$r]=$Empatesr[$r]+1;
													
													$media_rat[$CodJogA][0] = $media_rat[$CodJogA][0] + $RatFIDE[$CodJogB];
													$media_rat[$CodJogA][1] = $media_rat[$CodJogA][1] + 1;
													$media_rat[$CodJogA][2] = $media_rat[$CodJogA][2] + $PontosJogA;
													$media_rat[$CodJogB][0] = $media_rat[$CodJogB][0] + $RatFIDE[$CodJogA];
													$media_rat[$CodJogB][1] = $media_rat[$CodJogB][1] + 1;
													$media_rat[$CodJogB][2] = $media_rat[$CodJogB][2] + $PontosJogB;
													
													$diffrat = ($RatFIDE[$CodJogA]-$RatFIDE[$CodJogB]);
													if($diffrat>400){$diffrat=400;}elseif($diffrat<-400){$diffrat=-400;};
													$ratvar[$CodJogA][$r] = $diffrat > 0 ? ((50-$expectancia[abs($diffrat)])*$FatorK[$CodJogA])/100:-((50-$expectancia[abs($diffrat)])*$FatorK[$CodJogA])/100;
													$ratvar[$CodJogB][$r] = $diffrat < 0 ? ((50-$expectancia[abs($diffrat)])*$FatorK[$CodJogB])/100:-((50-$expectancia[abs($diffrat)])*$FatorK[$CodJogB])/100;
													$ratvart[$CodJogA] = $ratvart[$CodJogA] + $ratvar[$CodJogA][$r];
													$ratvart[$CodJogB] = $ratvart[$CodJogB] + $ratvar[$CodJogB][$r];
													//if($CodJogA==9 and $CodJogB==3) {echo "$CodJogA $CodJogB $r $diffrat - **" . $ratvar[$CodJogA][$r] . "** - *" . $ratvar[$CodJogB][$r] ."* - *" . $expectancia[abs($diffrat)] . "* ";exit;}
													
												}
											else
												{
													$PontosJogA = 0; $PontosJogB = 0;
											 }
											break;
										Case 3:
											$ResulJogA = 0; $ResulJogB = 1;
											if($Status[$CodJogA]==0 && $Status[$CodJogB]==0)
												{
													$PontosJogA = 0; $PontosJogB = 1;
													$VitNr[$r]=$VitNr[$r]+1;
													$NumVitJog[$CodJogB]=$NumVitJog[$CodJogB]+1;
													
													$media_rat[$CodJogA][0] = $media_rat[$CodJogA][0] + $RatFIDE[$CodJogB];
													$media_rat[$CodJogA][1] = $media_rat[$CodJogA][1] + 1;
													$media_rat[$CodJogA][2] = $media_rat[$CodJogA][2] + $PontosJogA;
													$media_rat[$CodJogB][0] = $media_rat[$CodJogB][0] + $RatFIDE[$CodJogA];
													$media_rat[$CodJogB][1] = $media_rat[$CodJogB][1] + 1;
													$media_rat[$CodJogB][2] = $media_rat[$CodJogB][2] + $PontosJogB;
													
													$diffrat = ($RatFIDE[$CodJogA]-$RatFIDE[$CodJogB]);
													if($diffrat>400){$diffrat=400;}elseif($diffrat<-400){$diffrat=-400;};
													$ratvar[$CodJogA][$r] = $diffrat > 0 ? -(($expectancia[abs($diffrat)])*$FatorK[$CodJogA])/100:-((100-$expectancia[abs($diffrat)])*$FatorK[$CodJogA])/100;
													$ratvar[$CodJogB][$r] = $diffrat < 0 ? ((100-$expectancia[abs($diffrat)])*$FatorK[$CodJogB])/100:(($expectancia[abs($diffrat)])*$FatorK[$CodJogB])/100;
													$ratvart[$CodJogA] = $ratvart[$CodJogA] + $ratvar[$CodJogA][$r];
													$ratvart[$CodJogB] = $ratvart[$CodJogB] + $ratvar[$CodJogB][$r];
													
												}
											else
												{
													$PontosJogA = 0; $PontosJogB = 0;
											 }
											break;
											
										Case 4:
											$ResulJogA = 1; $ResulJogB = 0;
											if($Status[$CodJogA]==0 && $Status[$CodJogB]==0)
												{
													$PontosJogA = 1; $PontosJogB = 0;
													$Ausr[$r]=$Ausr[$r]+1;
													$NumVitJog[$CodJogA]=$NumVitJog[$CodJogA]+1;
												}
											else
												{
													$PontosJogA = 0; $PontosJogB = 0;
											 }
											break;
										Case 5:
											$ResulJogA = 0; $ResulJogB = 1;
											if($Status[$CodJogA]==0 && $Status[$CodJogB]==0)
												{
													$PontosJogA = 0; $PontosJogB = 1;
													$Ausr[$r]=$Ausr[$r]+1;
													$NumVitJog[$CodJogB]=$NumVitJog[$CodJogB]+1;
												}
											else
												{
													$PontosJogA = 0; $PontosJogB = 0;
											 }
											break;
										Case 6:
											$ResulJogA = 0; $ResulJogB = 0;
											$PontosJogA = 0; $PontosJogB = 0;
											$Ausr[$r]=$Ausr[$r]+1;
											break;
											
										Case 7:
											$ResulJogA = 0; $ResulJogB = 0;
											$PontosJogA = 0; $PontosJogB = 0;
											break;
										Case 8:
											$ResulJogA = 0; $ResulJogB = 0;
											$PontosJogA = 0; $PontosJogB = 0;
											echo "Código 8 encontrado!!";exit;
											break;
											
										Case 9:
											$ResulJogA = 1; $ResulJogB = 0;
											$PontosJogA = 1; $PontosJogB = 0;
											break;
										Case 10:
											$ResulJogA = 0; $ResulJogB = 0;
											$PontosJogA = 0; $PontosJogB = 0;
											break;
										Case 11:
											$ResulJogA = 0; $ResulJogB = 0.5;
											$PontosJogA = 0; $PontosJogB = 0.5;
											break;
										Case 12:
											$ResulJogA = '½'; $ResulJogB = '½';
											$PontosJogA = 0.5; $PontosJogB = 0;
											break;
											
										Case 13:
											$ResulJogA = 1; $ResulJogB = 0;
											$PontosJogA = 1; $PontosJogB = 0;
											break;
										Case 14:
											$ResulJogA = '½'; $ResulJogB = 0;
											$PontosJogA = 0.5; $PontosJogB = 0;
											break;
										Case 15:
											$ResulJogA = 0; $ResulJogB = 0;
											$PontosJogA = 0; $PontosJogB = 0;
											break;
											
										Case 21:
											$ResulJogA = 1; $ResulJogB = 0;
											$PontosJogA = 1; $PontosJogB = 0;
											break;
											
										default:
											$ResulJogA = 0; $ResulJogB = 0;
											$PontosJogA = 0; $PontosJogB = 0;
											echo "Código Desconhecido encontrado: $CodRes - Rodada: $r - Tabuleiro: $MesaTabul - JogadorA: $CodJogA - JogadorB: $CodJogB!!";exit;	// verificar caso de "Double Round-Robin".
									}
								$PontosJogador[$CodJogA] = $PontosJogador[$CodJogA] + $PontosJogA;
								if($CodJogB>0) {$PontosJogador[$CodJogB] = $PontosJogador[$CodJogB] + $PontosJogB;}
        
								$CrossTable[$CodJogA][$r][1]=$CodJogB;
								$CrossTable[$CodJogA][$r][2]='b';
								if($PontosJogA==0.5)
									{$CrossTable[$CodJogA][$r][3]='½';}
								else
									{
										//$CrossTable[$CodJogA][$r][3]=$PontosJogA;
										if($CodRes==4)
											{$CrossTable[$CodJogA][$r][3]='+';}
										elseif($CodRes==5)
											{$CrossTable[$CodJogA][$r][3]='-';}
										else
											//{$CrossTable[$CodJogA][$r][3]=$PontosJogA;}
											{$CrossTable[$CodJogA][$r][3]=$ResulJogA;}
									}
								
								if($CodJogB>0)
								 {
										$CrossTable[$CodJogB][$r][1]=$CodJogA;
										$CrossTable[$CodJogB][$r][2]='n';
										
											//if($Status[$CodJogA]==0 && $Status[$CodJogB]==0)
											//if($Status[$CodJogA]==0 && $CodJogB>0 && ($CodRes==1 || $CodRes==2 || $CodRes==3 || $CodRes==4 || $CodRes==5))
											if($CodRes==1 || $CodRes==2 || $CodRes==3)
												{
													$JogNegras[$CodJogB]=$JogNegras[$CodJogB]+1;
												}
										
										if($PontosJogB==0.5)
											{$CrossTable[$CodJogB][$r][3]='½';}
										else
											{
												//$CrossTable[$CodJogB][$r][3]=$PontosJogB;
												if($CodRes==4)
													{$CrossTable[$CodJogB][$r][3]='-';}
												elseif($CodRes==5)
													{$CrossTable[$CodJogB][$r][3]='+';}
												else
													//{$CrossTable[$CodJogB][$r][3]=$PontosJogB;}
													{$CrossTable[$CodJogB][$r][3]=$ResulJogB;}
											}
								 }
								else
								 {
										$CrossTable[$CodJogA][$r][1]='-';
										$CrossTable[$CodJogA][$r][2]='-';
									}
								
								/*																					
									bb = ""
									If JogB = "Bye" Then CodRes = CodRes + 12 ': MsgBox (JogA & " - " & CodRes & " - " & Resultados(CodRes))
									If Text2_BlNaoIdent = "N" Or Text2_BlNaoIdent = "T" Then Text2.Text = Text2.Text & JogA & Resultados(CodRes) & JogB & vbCrLf
								*/
								
								if($CodJogA>0)
									{$PtsDesemp[1][$CodJogA] = $PontosJogador[$CodJogA];}
								if($CodJogB>0)
									{$PtsDesemp[1][$CodJogB] = $PontosJogador[$CodJogB];}
								
				    $Nulo=fread($fh, 15);
								
							}	// mesas
	
						$ptini=$p;
						
						$VitBt=$VitBt+$VitBr[$r];
						$Empatest=$Empatest+$Empatesr[$r];
						$VitNt=$VitNt+$VitNr[$r];
						$Aust=$Aust+$Ausr[$r];
						$Totr[$r]=$VitBr[$r]+$Empatesr[$r]+$VitNr[$r]+$Ausr[$r];
						$Tott=$Tott+$Totr[$r];
						
						//$fp2=ftell($fh);
						//if($r<$NumRodadas) {$PulaRodDupla=fread($fh, 63);}
						//echo "<br><br>$fp1 - $fp2<br><br>";exit;
						
					}	// rodadas
					
				 for ($j=1;$j<=$QtJogadores;$j++)
				 {
					//if($media_rat[$j][1]<1)
					if($media_rat[$j][0]<1 OR $media_rat[$j][1]<1)
					{$mr=0;$p=0;$performace[$j]=0;}
					else
					{
						$mr = round($media_rat[$j][0] / $media_rat[$j][1]);
						$p = round($media_rat[$j][2] * 100 / $media_rat[$j][1]);
						$performace[$j] = $mr + $dp[$p];
					}
				 }	
				
				 $Nulo=fread($fh, 4);
				 
				 // ----- Equipes e Resultados --- 28/09/2014 -----------------------------------
				 for($e=1;$e<=$QtEquipes;$e++)
				 {
						$NomeEquipe[$e] = LerCampoStr($fh);
						$NomeEquipeR[$e] = LerCampoStr($fh);
						$Capitao[$e] = LerCampoStr($fh);
						$qq1 = TestarSeqAsc($fh,40);
						$NId1[$e] = LerCampoInt($fh);
						$qq2 = TestarSeqAsc($fh,6);
						$NId2[$e] = LerCampoInt($fh);
						$qq3 = TestarSeqAsc($fh,50);
						
						/*
						echo "$NomeEquipe[$e]<br>";
						echo "$NomeEquipeR[$e]<br>";
						echo "$Capitao[$e]<br>";
						//echo "$qq1<br>";
						//echo "$NId1[$e]<br>";
						//echo "$qq2<br>";
						echo "$NId2[$e]<br>";		// *** Número inicial ***
						//echo "$qq3<br>";
						echo "<hr>";
						*/
					}
				
				 //exit; 
				 
			   $ij=0;$EquipeAnt='';
			   //echo $QtJogadores . '<br>';
			   for ($j=1;$j<=$QtJogadores;$j++)
			   {
				
				 if(!isset($PontosEquipe[$Equipe[$j]])) {$PontosEquipe[$Equipe[$j]]=0;}						// ***** 2019/10/28 *****
				 $PontosEquipe[$Equipe[$j]]=$PontosEquipe[$Equipe[$j]] + $PontosJogador[$j];
				
				 if(!isset($QtJogadoresEquipe[$Equipe[$j]])) {$QtJogadoresEquipe[$Equipe[$j]]=0;}	// ***** 2019/10/28 *****
				 $QtJogadoresEquipe[$Equipe[$j]]=$QtJogadoresEquipe[$Equipe[$j]]+1;
				
				 $JogadoresEquipe[$Equipe[$j]][$QtJogadoresEquipe[$Equipe[$j]]]=$j;
				 if($QtJogadoresEquipe[$Equipe[$j]]<=4)
				 {
					if(!isset($SomaEquipeRatFIDE4[$Equipe[$j]])) {$SomaEquipeRatFIDE4[$Equipe[$j]]=0;}	// ***** 2019/10/28 *****
					$SomaEquipeRatFIDE4[$Equipe[$j]]=$SomaEquipeRatFIDE4[$Equipe[$j]]+$RatFIDE[$j];
					
					if(!isset($SomaEquipeRatNac4[$Equipe[$j]])) {$SomaEquipeRatNac4[$Equipe[$j]]=0;}	// ***** 2019/10/28 *****
					$SomaEquipeRatNac4[$Equipe[$j]]=$SomaEquipeRatNac4[$Equipe[$j]]+$RatNacional[$j];
				 }
					
				 if(!isset($SomaEquipeRatFIDEt[$Equipe[$j]])) {$SomaEquipeRatFIDEt[$Equipe[$j]]=0;}	// ***** 2019/10/28 *****
				 $SomaEquipeRatFIDEt[$Equipe[$j]]=$SomaEquipeRatFIDEt[$Equipe[$j]]+$RatFIDE[$j];
				
				 if(!isset($SomaEquipeRatNact[$Equipe[$j]])) {$SomaEquipeRatNact[$Equipe[$j]]=0;}	// ***** 2019/10/28 *****
				 $SomaEquipeRatNact[$Equipe[$j]]=$SomaEquipeRatNact[$Equipe[$j]]+$RatNacional[$j];
			 }
			 
			 if($versao_teste == true)
				{
					//echo "<br>Quantidade de Jogadores: $QtJogadores &nbsp; &nbsp; - &nbsp; &nbsp; Quantidade de Equipes: $QtEquipes<hr>";
					//for ($i=1;$i<=$QtEquipes;$i++)
					//{
						//echo "Equipe: $i - $NomeEquipe[$i] - ($NomeEquipeR[$i]) - Capitão: $Capitao[$i] - Ordem Alfabética da Equipe: $NId2[$i]<br>";
						for ($j=1;$j<=$QtJogadoresEquipe[$i];$j++)
						{
							echo $JogadoresEquipe[$i][$j] . ' - ' . $j . ' - ' . $NomeJogador[$JogadoresEquipe[$i][$j]] . ' - RatFIDE: ' . $RatFIDE[$JogadoresEquipe[$i][$j]] . ' - RatLocal: ' . $RatNacional[$JogadoresEquipe[$i][$j]] . '<br>';
							//echo "<hr>";
						}
						//echo '<br>(Ref. 4 primeiros) - Média Rating Local: ' . round($SomaEquipeRatNac4[$i]/4) . ' &nbsp; - &nbsp; Média Rating FIDE: ' . round($SomaEquipeRatFIDE[$i]/4);
						//echo '<br>(Ref. todos jogad) - Média Rating Local: ' . round($SomaEquipeRatNac4[$i]/$QtJogadoresEquipe[$i]) . ' &nbsp; - &nbsp; Média Rating FIDE: ' . round($SomaEquipeRatFIDE[$i]/$QtJogadoresEquipe[$i]) . '<br>';
						//echo "<hr>";
					//}
					//exit;
				}
			// ----- FIM Equipes e Resultados --------------------------------------
			 
			//echo "<br><br><br> ... : " . ftell($fh) . "  --  " . $file . '= ' . filesize($file) . " bytes";exit;
			 
			 fclose($fh);
		
//-----------------------------------------------------------------------------  
												
  //Publicando HTML
		/* ***bloco de definição de idioma/charset precisa estar nos primeiros 1024bytes - transferido para início do arquivo ***
		 echo '<html dir="ltr" lang="pt-BR">';
		 echo '<head>';
		 echo '<meta charset="UTF-8" />';
		*/
    echo '<link rel="stylesheet" type="text/css" href="../css/flags.css"/>';
    echo '<style type="text/css">
						body
							{margin:0; padding:0 1px 0 2px;}
						table, td, th
							{border-color: #000; border-style: solid;}
						table
							{border-width: 0px 0px 1px 1px;}
						td
							{
								margin: 0;
								border-width: 1px 1px 0px 0px;
								border-color:#000;
								border-spacing:0;
								border-collapse:collapse;
							}
						th
							{margin: 0; border-width: 1px 1px 0px 0px;}
						div
							{font-family:arial narrow,Helvetica,sans-serif;font-size:14;}
						
						table
							.borderless
								{border-width: 0px 0px 0px 0px;padding:0;border-spacing:0;}
						td
							.borderlessR
								{border-width: 0px 0px 0px 0px;text-align:right;}
							.borderlessC
								{border-width: 0px 0px 0px 0px;text-align:center;}
							.borderlessL
								{border-width: 0px 0px 0px 0px;text-align:left;}
							.column_center
								{width:100%;text-align:center;color:#ff0000;}
					
							.fb-share-button{
								transform: scale(2,1.5);
								-ms-transform: scale(2,1.5);
								-webkit-transform: scale(2,1.5);
								-o-transform: scale(2,1.5);
								-moz-transform: scale(2,1.5);
								transform-origin: top left;
								-ms-transform-origin: top left;
								-webkit-transform-origin: top left;
								-moz-transform-origin: top left;
								-webkit-transform-origin: top left;
								top:-3;
							}
					
					</style>';
				
					echo "<script language='javascript' type='text/javascript'>PontosJogador=new Array();</script>";
					for ($jpt=1;$jpt<=$QtJogadores;$jpt++)
					{
						echo "<script language='javascript' type='text/javascript'>
										PontosJogador['".$jpt."']='".$PontosJogador[$jpt]."';
									</script>";
					}
					
					echo "<script language='javascript' type='text/javascript'>
									NumRodadas=" . $NumRodadas . ";

									var coluna=new Array (0,0,0,0,0,0,0,0,0);

									function Ordenar_Tab(n,GrupoRef)
									{
										grp=GrupoRef;
										/* alert('<br>*** col: ' + n + ' - grp: ' + grp + ' - Descr: ' + GrupoDescr[grp] + ' ***<br>'); */
										/* alert(GrupoDescr[1]); */

										switch(n)
										{
											case 1:
												if(coluna[n]==0)
												{
													jogador.sort(function(a, b){return a.ord_ini-b.ord_ini});
													coluna[n]=1;
												}
												else
												{
													jogador.sort(function(a, b){return b.ord_ini-a.ord_ini});
													coluna[n]=0;
												}																
												break;
											
											case 2:
												if(coluna[n]==0)
													{
														jogador.sort(function(a, b){var nameA=a.TitFIDE.toLowerCase(), nameB=b.TitFIDE.toLowerCase()
															if (nameA < nameB)
																return -1
															if (nameA > nameB)
																return 1
															return 0 });
														coluna[n]=1;
													}
												else
													{
														jogador.sort(function(a, b){var nameA=a.TitFIDE.toLowerCase(), nameB=b.TitFIDE.toLowerCase()
															if (nameA > nameB)
																return -1
															if (nameA < nameB)
																return 1
															return 0 });
														coluna[n]=0;
													}
												break;
											
											case 3:
												if(coluna[n]==0)
													{
														jogador.sort(function(a, b){var nameA=a.NomeJogador.toLowerCase(), nameB=b.NomeJogador.toLowerCase()
															if (nameA < nameB)
																return -1
															if (nameA > nameB)
																return 1
															return 0 });
														coluna[n]=1;
													}
												else
													{
														jogador.sort(function(a, b){var nameA=a.NomeJogador.toLowerCase(), nameB=b.NomeJogador.toLowerCase()
															if (nameA > nameB)
																return -1
															if (nameA < nameB)
																return 1
															return 0 });
														coluna[n]=0;
													}
												break;
											
											case 4:
												if(coluna[n]==0)
													{
														jogador.sort(function(a, b){var nameA=a.PaisJogador.toLowerCase(), nameB=b.PaisJogador.toLowerCase()
															if (nameA < nameB)
																return -1
															if (nameA > nameB)
																return 1
															return 0 });
														coluna[n]=1;
													}
												else
													{
														jogador.sort(function(a, b){var nameA=a.PaisJogador.toLowerCase(), nameB=b.PaisJogador.toLowerCase()
															if (nameA > nameB)
																return -1
															if (nameA < nameB)
																return 1
															return 0 });
														coluna[n]=0;
													}
												break;
											
											case 5:
												if(coluna[n]==0)
													{
														jogador.sort(function(a, b){return b.RatFIDE-a.RatFIDE});
														coluna[n]=1;
													}
												else
													{
														jogador.sort(function(a, b){return a.RatFIDE-b.RatFIDE});
														coluna[n]=0;
													}																
												break;
											
											case 6:
												if(coluna[n]==0)
													{
														jogador.sort(function(a, b){return b.RatNAC-a.RatNAC});
														coluna[n]=1;
													}
												else
													{
														jogador.sort(function(a, b){return a.RatNAC-b.RatNAC});
														coluna[n]=0;
													}																
												break;
											
											case 7:
												if(coluna[n]==0)
													{
													jogador.sort(function(a, b){var nameA=a.Clube.toLowerCase(), nameB=b.Clube.toLowerCase()
															if (nameA < nameB)
																return -1
															if (nameA > nameB)
																return 1
															return 0 });
														coluna[n]=1;
													}
												else
													{
													jogador.sort(function(a, b){var nameA=a.Clube.toLowerCase(), nameB=b.Clube.toLowerCase()
															if (nameA > nameB)
																return -1
															if (nameA < nameB)
																return 1
															return 0 });
														coluna[n]=0;
													}
												break;
											
											case 8:
												/* alert('n: ' + n); */
												if(coluna[n]==0)
													{
														/* alert('jjj 2'); */
														jogador.sort(function(a, b){
															RatFIDEa='0000'+a.RatFIDE;RatFIDEa=RatFIDEa.substr(RatFIDEa.length-4,4);
														 RatNACa ='0000'+a.RatNAC; RatNACa=RatNACa.substr(RatNACa.length-4,4);
															RatFNa=RatFIDEa + RatNACa;
														 RatFIDEb='0000'+b.RatFIDE;RatFIDEb=RatFIDEb.substr(RatFIDEb.length-4,4);
														 RatNACb='0000'+b.RatNAC;RatNACb=RatNACb.substr(RatNACb.length-4,4);
															RatFNb=RatFIDEb + RatNACb;
														 RatFN=RatFNb-RatFNa;
														 /*RatFN=b-a;*/
														 return RatFN
														});
														coluna[n]=1;
													}
												else
													{
														/* alert('jjj 3'); */
														jogador.sort(function(a, b){
															RatFIDEa='0000'+a.RatFIDE;RatFIDEa=RatFIDEa.substr(RatFIDEa.length-4,4);
														 RatNACa ='0000'+a.RatNAC; RatNACa=RatNACa.substr(RatNACa.length-4,4);
															RatFNa=RatFIDEa + RatNACa;
														 RatFIDEb='0000'+b.RatFIDE;RatFIDEb=RatFIDEb.substr(RatFIDEb.length-4,4);
														 RatNACb='0000'+b.RatNAC;RatNACb=RatNACb.substr(RatNACb.length-4,4);
															RatFNb=RatFIDEb + RatNACb;
														 RatFN=RatFNa-RatFNb;
														 return RatFN
														});
														coluna[n]=0;
													}																
												break;

												default:
												/* code to be executed if n is different from case 1 and 2; */
										}
										document.getElementById(\"tab_jogadores\").innerHTML=ImprimirJogadores('');
										document.getElementById(\"Grupo_Jogadores\").innerHTML=ImprimirJogadores(grp);
									}
								</script>";
				
								echo "<script language='javascript' type='text/javascript'>
							  // ***** 2022/02/26*****
								function MostrarJanela(Janela,CtrJanela)
								{
									switch(Janela)
									{
										case \"divDetalhes\":
											msgMostrar=\"Mostrar Detalhes\";
											msgOcultar=\"Ocultar Detalhes\";
											break;
										case \"divEstatisticas\":
											msgMostrar=\"Mostrar Estatisticas\";
											msgOcultar=\"Ocultar Estatisticas\";
											break;
										case \"divHorarios\":
											msgMostrar=\"Mostrar Horarios\";
											msgOcultar=\"Ocultar Horarios\";
											break;
										case \"divCrossTabI\":
											msgMostrar=\"Mostrar Quadro Sinóptico p/RI\";
											msgOcultar=\"Ocultar Quadro Sinóptico p/RI\";
											break;
										case \"divCrossTab\":
											msgMostrar=\"Mostrar Quadro Sinóptico p/pts\";
											msgOcultar=\"Ocultar Quadro Sinóptico p/pts\";
											break;
										case \"divTieBreak\":
											msgMostrar=\"Mostrar Critérios\";
											msgOcultar=\"Ocultar Critérios\";
											break;
										case \"divListaClassif\":
											msgMostrar=\"Mostrar Classificação\";
											msgOcultar=\"Ocultar Classificação\";
											break;
											
										case \"divListaJogadores\":
											msgMostrar=\"Mostrar Jogadores\";
											msgOcultar=\"Ocultar Jogadores\";
											break;
											
										default:
									}
									objeto=document.getElementById(Janela);
									objetoCtr=document.getElementById(CtrJanela);
									mostrarJanela=objeto.style.visibility;
									//alert(Janela +' - '+mostrarJanela);

									FecharJanelas();
									
									if(Janela=='divListaJogadores')
										{MostrarJogadores();}
									else
									{
										if(mostrarJanela=='hidden')
										{
											objeto.style.visibility='visible';objeto.style.height='auto';
											
											objetoCtr.innerHTML='<a href=\"#\"><span onclick=\"MostrarJanela(objeto.id,objetoCtr.id);\">' + msgOcultar + '</span></a>&nbsp;';
											if(Janela=='divListaClassif')
											{
											//document.getElementById('tab_classif').style.visibility='visible';document.getElementById('tab_classif').style.height='auto';
												tab_classif.style.visibility='visible';
												tab_classif.style.height='480px';
												tab_classif.style.overflow='auto';
												CriarLinkVisaoAtual(2);
											}
											else
											{
												objeto.style.height='480px'; //'auto';
												objeto.style.overflow='auto';
												
												if(Janela=='divCrossTab')
												{
													CriarLinkVisaoAtual(3);
												}
												else if(Janela=='divCrossTabI')
												{
													CriarLinkVisaoAtual(4);
												}
												
												//else if(Janela=='divMostrarGrupoJogadores') {CriarLinkVisaoAtual(5);}
												
												else if(Janela=='divEstatisticas')
												{
													CriarLinkVisaoAtual(6);
												}
												else if(Janela=='divHorarios')
												{
													CriarLinkVisaoAtual(7);
												}
												else if(Janela=='divTieBreak')
												{
													CriarLinkVisaoAtual(8);
												}
												else if(Janela=='divDetalhes')
												{
													CriarLinkVisaoAtual(9);
												}
											}
										}
										else
										{
											objeto.style.visibility='hidden'; objeto.style.height='0px';
											objetoCtr.innerHTML='<a href=\"#\"><span onclick=\"MostrarJanela(objeto.id,objetoCtr.id);\">' + msgMostrar + '</span></a>&nbsp;';
										}
									}
								}
							</script>";
								
								echo "<script language='javascript' type='text/javascript'>
								var torneio_reg_Atual=$torneio_reg;
								var TitCol=new Array ('Nr','Tit','Nome','FED','RtFIDE','RtNAC','Clube');
								
								function TamFonte(coef)
								{
								 tf=resumot.style.fontSize.substr(0,resumot.style.fontSize.length-2)*1;
								 tf=tf+coef;
								 if(tf<12){tf=12}
								 if(tf>24){tf=24}
								 tf=tf+\"px\";
								 return tf;
								}
							  function Alerta()
								{
								 alert('alerta!');
								}
								
								function ImprimirJogadores(GrupoRef)
								{
									Grupo=GrupoRef;
									
									Conteudo='<table id=table_jog width=100% cellspacing=0 border=1>';
									
									Conteudo=Conteudo+'<tr><th><a href=\"#\"><span onclick=\"Ordenar_Tab(1,Grupo);\">Nr</span></a></th>';
									//Conteudo=Conteudo+'<th width=62px><a href=\"#\"><span onclick=\"Ordenar_Tab(5,Grupo);\">FED</span></a></th>';
									Conteudo=Conteudo+'<th><center><a href=\"#\"><span onclick=\"Ordenar_Tab(5,Grupo);\">FED</span></a></th>';
									Conteudo=Conteudo+'<th><a href=\"#\"><span onclick=\"Ordenar_Tab(2,Grupo);\">Tit</span></a></th>';
									Conteudo=Conteudo+'<th><center><!a href=\"#\"><!span onclick=\"Ordenar_Tab(2,Grupo);\">Cat<!/span><!/a></th>';
									Conteudo=Conteudo+'<th><center><!a href=\"#\"><!span onclick=\"Ordenar_Tab(2,Grupo);\">Gen<!/span><!/a></th>';
									Conteudo=Conteudo+'<th align=left><a href=\"#\"><span onclick=\"Ordenar_Tab(3,Grupo);\">Nome</span></a>';
									Conteudo=Conteudo+' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <span style=\"font-size:14;font-family:Arial Narrow;\" onclick=\"ObjdivMostrarConfig.click();\">(Nome <a href=\"#\"><img src=\"../imagens/seta_trocar.png\"></a> Sobrenome)</span></th>';
									Conteudo=Conteudo+'<th><a href=\"#\"><span onclick=\"Ordenar_Tab(4,Grupo);\">IdFIDE</span></a></th>';
									Conteudo=Conteudo+'<th><a href=\"#\"><span onclick=\"Ordenar_Tab(6,Grupo);\">RatFIDE</span></a></th>';
									Conteudo=Conteudo+'<th><a href=\"#\"><span onclick=\"\">IdNAC</span></a></th>';
									Conteudo=Conteudo+'<th><a href=\"#\"><span onclick=\"Ordenar_Tab(7,Grupo);\">RatNac</span></a></th>';
									Conteudo=Conteudo+'<th><a href=\"#\"><span onclick=\"Ordenar_Tab(8,Grupo);\">Clube/Cidade</span></a></th></tr>';
									
									for (var z=0;z<jogador.length;z++)
									{
										saltar=false;
										if(Grupo!=''){if(jogador[z].Grupo!=Grupo){saltar=true;}}
										
										if(saltar!=true)
										{
											if(z % 2 == 1)
												{Conteudo=Conteudo+'<tr bgcolor=\"#e0e0e0\">';}
											else
												{Conteudo=Conteudo+'<tr bgcolor=\"#ffffff\">';}
												
											Conteudo=Conteudo+'<td>'+jogador[z].ord_ini+'</td>';
											
											//Conteudo=Conteudo+'<td>'+jogador[z].PaisJogador; //+'<!/td>';
											Conteudo=Conteudo+'<td><center>';
											//flag
											Conteudo=Conteudo+'<div class=\"flag\" style=\"position:relative;float:center;\">';
											Conteudo=Conteudo+'<div title='+jogador[z].PaisJogador+' class=\"tn_' + jogador[z].PaisJogador + '\"></div>';
											Conteudo=Conteudo+'</div>';
											Conteudo=Conteudo+'</td>';
											
											Conteudo=Conteudo+'<td>'+jogador[z].TitFIDE+'</td>';
											Conteudo=Conteudo+'<td><center>'+jogador[z].Categoria+'</td>';
											Conteudo=Conteudo+'<td><center>'+jogador[z].Genero+'</td>';
											
											idjog='jog'+z;
										  //	Conteudo=Conteudo+\"<td><a href='#'><span style='color:blue;' onClick='pegarPosClick(event);mudarFichaJogador(\"+z+\");'>\" + jogador[z].NomeJogador + \"</span></a></td>\";
                      //	Conteudo=Conteudo+' <td><a href=\"#\"><span id='idjog' style='color:blue;' onClick='pegarPosClick(event);mudarFichaJogador(\"+z+\");'>\" + jogador[z].NomeJogador + \"</span></a></td>\";
											Conteudo=Conteudo+\"<td><a href='#'><span id=\"+idjog+\" style='color:blue;' onClick='pegarPosClick(event);mudarFichaJogador(\"+z+\");'>\" + jogador[z].NomeJogador + \"</span></a></td>\";
											
											if(jogador[z].IdFIDE>0)
											{Conteudo=Conteudo+'<td>'+'<a href=\"http://ratings.fide.com/card.phtml?event='+jogador[z].IdFIDE+'\" target=\"_blank\">'+jogador[z].IdFIDE+'</a>'+'</td>';}
											else
											{Conteudo=Conteudo+'<td>&nbsp;</td>';}
											Conteudo=Conteudo+'<td>'+jogador[z].RatFIDE+'</td>';
											
											if(jogador[z].IdNAC>0)
											{Conteudo=Conteudo+'<td>'+'<a href=\"http://www.cbx.org.br/DetalhesJogador.aspx?no='+jogador[z].IdNAC+'\" target=\"_blank\">'+jogador[z].IdNAC+'</a>'+'</td>';}
											else
											{Conteudo=Conteudo+'<td>&nbsp;</td>';}
											Conteudo=Conteudo+'<td>'+jogador[z].RatNAC+'</td>';
											
											Conteudo=Conteudo+'<td style=\"font-size:14px;\">'+jogador[z].Clube+'</td>';															
											Conteudo=Conteudo+'</tr>';
										}
									}
									
									Conteudo=Conteudo+'</table>';
									//alert('Grupo: ' + Grupo);	
									return Conteudo;
								}
								
								function ImprimirClassif(GrupoRef)
								{
									Grupo=GrupoRef;
									
									Conteudo='<table id=table_classif width=100% cellspacing=0 border=1>';
									
									//Conteudo=Conteudo+'<tr><th colspan=5>&nbsp;</th><th colspan=5><center>Desempates</th></tr>';
									Conteudo=Conteudo+'<tr><th colspan=5><span style=\"font-size:14;font-family:Arial Narrow;\" onclick=\"ObjdivMostrarConfig.click();\">(Nome <a href=\"#\"><img src=\"../imagens/seta_trocar.png\"></a> Sobrenome)</span></th>';
									Conteudo=Conteudo+'<th colspan=5><center>Desempates</th></tr>';
									//alert(' vvvv ');
									Conteudo=Conteudo+'<tr><th>Col</th>';
									Conteudo=Conteudo+'<th>FED</th>';
									Conteudo=Conteudo+'<th>Tit</th>';
									Conteudo=Conteudo+'<th align=left>Nome</th>';
									Conteudo=Conteudo+'<th>Pontos</th>';
									Conteudo=Conteudo+'<th>Crit.1</th>';
									Conteudo=Conteudo+'<th>Crit.2</th>';
									Conteudo=Conteudo+'<th>Crit.3</th>';
									Conteudo=Conteudo+'<th>Crit.4</th>';
									Conteudo=Conteudo+'<th>Crit.5</th></tr>';
									
									//alert('jogadorClassif.length: '+jogadorClassif.length);
									
									//Col  Tit  Nome   FED   Pontos  Crit.1 Crit.2 Crit.3 Crit.4 Crit.5 
									for (var y=1;y<jogadorClassif.length;y++)
									{
										saltar=false;
										//if(Grupo!=''){if(jogadorClassif[z].Grupo!=Grupo){saltar=true;}}
										
										if(saltar!=true)
										{
											if(y % 2 == 1)
												{Conteudo=Conteudo+'<tr bgcolor=\"#e0e0e0\">';}
											else
												{Conteudo=Conteudo+'<tr bgcolor=\"#ffffff\">';}
											
											z=JogClassif2[y];
												
											Conteudo=Conteudo+'<td>'+jogadorClassif[z].ord_classif+'</td>';
											
											//Conteudo=Conteudo+'<td>'+jogadorClassif[z].PaisJogador; //+'<!/td>';
											Conteudo=Conteudo+'<td><center>';
											//flag
											Conteudo=Conteudo+'<div class=\"flag\" style=\"position:relative;float:center;\">';
											Conteudo=Conteudo+'<div title='+jogadorClassif[z].PaisJogador+' class=\"tn_' + jogadorClassif[z].PaisJogador + '\"></div>';
											Conteudo=Conteudo+'</div>';
											Conteudo=Conteudo+'</td>';
											
											Conteudo=Conteudo+'<td>'+jogadorClassif[z].TitFIDE+'</td>';
											
										//Conteudo=Conteudo+\"<td><a href='#'><span style='color:blue;' onClick='pegarPosClick(event);mudarFichaJogador(\"+z+\");'>\" + jogadorClassif[z].NomeJogador + \"</span></a>\" + \" (\"+z+\")\"+\"</td>\";
											Conteudo=Conteudo+\"<td><a href='#'><span style='color:blue;' onClick='pegarPosClick(event);mudarFichaJogador(\"+z+\");'>\" + jogador[z-1].NomeJogador + \"</span></a>\" + \" (\"+z+\")\"+\"</td>\";
											
											Conteudo=Conteudo+'<td>'+jogadorClassif[z].PontosJogador+'</td>';
											
											Conteudo=Conteudo+'<td>'+jogadorClassif[z].Desempate1+'</td>';
											Conteudo=Conteudo+'<td>'+jogadorClassif[z].Desempate2+'</td>';
											Conteudo=Conteudo+'<td>'+jogadorClassif[z].Desempate3+'</td>';
											Conteudo=Conteudo+'<td>'+jogadorClassif[z].Desempate4+'</td>';
											Conteudo=Conteudo+'<td>'+jogadorClassif[z].Desempate5+'</td>';
											
											Conteudo=Conteudo+'</tr>';
										}
									}
									
									Conteudo=Conteudo+'</table>';
									//alert(Conteudo);
									//alert('Grupozzz: ' + Grupo);	
									return Conteudo;
								}
								
								function ImprimirRodadas() //pauta
								{
									Conteudo='';
									//Tab  FED  Tit  Rat  Nome  Pts  Res  Pts  Nome  Rat  Tit  FED
									for(r=1;r<=NumRodadas;r++)
									{
										idname='divEmparceiramento'+r;
										if(!document.getElementById(idname))
										{
											Conteudo=Conteudo+'<div id='+idname+' name='+idname+' style=\"position:relative;float:left;font-family:Arial Narrow,Helvetica,sans-serif;visibility:hidden;width:100%;height:0px;padding:0px;border:0px solid #2266AA;\">';
											alert('Conteudo1: ' + Conteudo); // ***** 2022/02/26 *** Pista *****
										}
										else
										{
											visibilityAtual=document.getElementById(idname).style.visibility;
											if(visibilityAtual=='visible')
											{Conteudo=Conteudo+'<div id='+idname+' name='+idname+' style=\"position:relative;float:left;font-family:Arial Narrow,Helvetica,sans-serif;visibility:visible;width:100%;height:auto;padding:0px;border:0px solid #2266AA;\">';}
											else
											{Conteudo=Conteudo+'<div id='+idname+' name='+idname+' style=\"position:relative;float:left;font-family:Arial Narrow,Helvetica,sans-serif;visibility:hidden;width:100%;height:0px;padding:0px;border:0px solid #2266AA;\">';}
										}
										
										Conteudo=Conteudo+'<b>'+r+'ª Rodada: </b>';
										Conteudo=Conteudo+DataRodada[r]+' às '+HorarioRodada[r]+'<br>';
										Conteudo=Conteudo+'<table cellspacing=0 width=\"100%\" border=1 style=\"font-family:Arial Narrow,Helvetica,sans-serif;font-size:14;\">';
										
										Conteudo=Conteudo+'<tr>';
										Conteudo=Conteudo+'<th>Tab</th>';
										Conteudo=Conteudo+'<th><center>FED</th>';
										Conteudo=Conteudo+'<th>Tit</th>';
										Conteudo=Conteudo+'<th>Rat</th>';
										Conteudo=Conteudo+'<th align=left>Nome</th>';
										Conteudo=Conteudo+'<th>Pts</th>';
										Conteudo=Conteudo+'<th>Res</th>';
										Conteudo=Conteudo+'<th>Pts</th>';
										Conteudo=Conteudo+'<th align=left>Nome</th>';
										Conteudo=Conteudo+'<th>Rat</th>';
										Conteudo=Conteudo+'<th>Tit</th>';
										Conteudo=Conteudo+'<th><center>FED</th>';
										Conteudo=Conteudo+'</tr>';
										
										//for (m=1;m<=TabulRodada[r];m++)
										for (m=1;m<=3;m++)
										{
											
											if(m % 2 == 0)
												{Conteudo=Conteudo+' <tr bgcolor=\"#e0e0e0\">';}
											else
												{Conteudo=Conteudo+' <tr bgcolor=\"#ffffff\">';}
											Conteudo=Conteudo+'<td>'+m+'</td>';
										
											Conteudo=Conteudo+'<td><center>';
											//Conteudo=Conteudo+'<span style=\"font-family:Consolas,Helvetica,sans-serif;font-size:15px;\">'+RodadaMesa[r][m].PaisJogadorA+'</span>';
											//flag
											Conteudo=Conteudo+' <div class=\"flag\" style=\"position:relative;float:center;\">';
											Conteudo=Conteudo+' <div title='+RodadaMesa[r][m].PaisJogadorA+' class=\"tn_'+RodadaMesa[r][m].PaisJogadorA+'\"></div>';
											Conteudo=Conteudo+' </div>';
											Conteudo=Conteudo+'</td>';
											
											Conteudo=Conteudo+'<td>';
											Conteudo=Conteudo+'<span style=\"font-family:Consolas,Helvetica,sans-serif;font-size:15px;\">'+RodadaMesa[r][m].TitJogadorA+'</span>';
											Conteudo=Conteudo+'</td>';
											Conteudo=Conteudo+'<td>'+RodadaMesa[r][m].RatJogadorA+'</td>';
											Conteudo=Conteudo+'<td><a href=\"#\"><span style=\"color:blue;\" onClick=\"pegarPosClick(event);mudarFichaJogador('+(RodadaMesa[r][m].NumIniJogadorA-1)+');\">';
											
											//Conteudo=Conteudo+RodadaMesa[r][m].NomeJogadorA;
											Conteudo=Conteudo+jogador[RodadaMesa[r][m].NumIniJogadorA-1].NomeJogador;
											Conteudo=Conteudo+'</span></a> ('+RodadaMesa[r][m].NumIniJogadorA+')';
											Conteudo=Conteudo+'</td>';
											
											Conteudo=Conteudo+'<td>';
											Conteudo=Conteudo+RodadaMesa[r][m].PontosJogadorA;
											Conteudo=Conteudo+'</td>';
											Conteudo=Conteudo+'<td><center>';
											Conteudo=Conteudo+RodadaMesa[r][m].ResPartida;
											Conteudo=Conteudo+'</center></td>';
											Conteudo=Conteudo+'<td>';
											Conteudo=Conteudo+RodadaMesa[r][m].PontosJogadorB;
											Conteudo=Conteudo+'</td>';
											
											if (RodadaMesa[r][m].NomeJogadorB!='Bye')
											{	
												Conteudo=Conteudo+'<td><a href=\"#\"><span style=\"color:blue;\" onClick=\"pegarPosClick(event);mudarFichaJogador('+(RodadaMesa[r][m].NumIniJogadorB-1)+');\">';
												
												//Conteudo=Conteudo+RodadaMesa[r][m].NomeJogadorB;
												Conteudo=Conteudo+jogador[RodadaMesa[r][m].NumIniJogadorB-1].NomeJogador;
												Conteudo=Conteudo+'</span></a> ('+RodadaMesa[r][m].NumIniJogadorB+')';
												Conteudo=Conteudo+'</td>';
												
												Conteudo=Conteudo+'<td>';
												Conteudo=Conteudo+RodadaMesa[r][m].RatJogadorB;
												Conteudo=Conteudo+'</td>';
												Conteudo=Conteudo+'<td>';
												Conteudo=Conteudo+'<span style=\"font-family:Consolas,Helvetica,sans-serif;font-size:15px;\">'+RodadaMesa[r][m].TitJogadorB+'</span>';
												Conteudo=Conteudo+'</td>';
												Conteudo=Conteudo+'<td><center>';
												//Conteudo=Conteudo+'<span style=\"font-family:Consolas,Helvetica,sans-serif;font-size:15px;\">'+RodadaMesa[r][m].PaisJogadorB+'</span>';
												//flag
												Conteudo=Conteudo+' <div class=\"flag\" style=\"position:relative;float:center;\">';
												Conteudo=Conteudo+' <div title='+RodadaMesa[r][m].PaisJogadorB+' class=\"tn_'+RodadaMesa[r][m].PaisJogadorB+'\"></div>';
												Conteudo=Conteudo+' </div>';
												Conteudo=Conteudo+'</td>';
												//alert('Conteudo-2.1: ' + Conteudo); // ***** 2022/02/26 *** Pista *****
											}
											else
											{												
												//alert('Conteudo-2.2: ' + Conteudo); // ***** 2022/02/26 *** Pista *****
												if (RodadaMesa[r][m].NomeJogadorB==-1)
												{Conteudo=Conteudo+'<td>Bye</td>';}
												else
												{
													if (RodadaMesa[r][m].ResPartida=='½x-')
													{Conteudo=Conteudo+'<td>Bye Ausente</td>';}
													else
													{Conteudo=Conteudo+'<td>Não emparceirado</td>';}
												}
												Conteudo=Conteudo+'<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>';
										  }
											
										 //alert('Conteudo-3.1: ' + Conteudo); // ***** 2022/02/26 *** Pista *****
										 Conteudo=Conteudo+'</tr>';
										 alert(r + m); // ***** 2022/02/26 *** Pista *****
										 alert('Conteudo-3.2: ' + Conteudo); // ***** 2022/02/26 *** Pista *****
									  }
									 
										alert('Conteudo-4.0: '); // ***** 2022/02/26 *** Pista *****
										alert('Conteudo-4.1: ' + Conteudo); // ***** 2022/02/26 *** Pista *****
									  Conteudo=Conteudo+'</table>';
									  alert('Conteudo-4.2: ' + Conteudo); // ***** 2022/02/26 *** Pista *****
									  Conteudo=Conteudo+'</div>';
									  alert('Conteudo-4.3: ' + Conteudo); // ***** 2022/02/26 *** Pista *****
								  }
								  //alert(Conteudo);
								  //alert('Grupoyyy: ' + Grupo);	
									alert('Conteudo-5.0: ' + Conteudo); // ***** 2022/02/26 *** Pista *****
								  return Conteudo;
							  }
         
								function ImprimirQuadroSinoptico(classif)
								{
									//Grupo=GrupoRef;
									//alert(' vvvv ');
									
									if(classif<1 || classif>2)
									{
										if(classif_atual>=1 && classif_atual<=2)
										{classif=classif_atual;}
										else
										{classif=1;classif_atual=classif;}
									}
									else
									{classif_atual=classif;}
									
									if(classif==1)
									{Conteudo='<b>Quadro Sinóptico: (p/Ranking Inicial - RI)</b> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ';}
									else
									{Conteudo='<b>Quadro Sinóptico: (p/Pontuação)</b> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ';}
									
									Conteudo=Conteudo+'<span style=\"font-size:14;font-family:Arial Narrow;\" onclick=\"ObjdivMostrarConfig.click();\"><b>(Nome <a href=\"#\"><img src=\"../imagens/seta_trocar.png\"></a> Sobrenome)</b></span>';

									Conteudo=Conteudo+'<table id=\"table_QSinopt\" cellspacing=0 width=\"100%\" border=1 style=\"font-family:Arial Narrow,Helvetica,sans-serif;font-size:14;\">';
									
									//Conteudo=Conteudo+'<tr><th colspan=5><span style=\"font-size:14;font-family:Arial Narrow;\" onclick=\"ObjdivMostrarConfig.click();\">(Nome <a href=\"#\"><img src=\"../imagens/seta_trocar.png\"></a> Sobrenome)</span></th>';									
									
									if(classif==1)
									{Conteudo=Conteudo+'<tr><th>RI</th>';}
									else
									{Conteudo=Conteudo+'<tr><th>Cl</th>';}
									
									Conteudo=Conteudo+'<th><center>Fed</th>';
									Conteudo=Conteudo+'<th>Tit</th>';
									Conteudo=Conteudo+'<th>Nome</th>';
									Conteudo=Conteudo+'<th>Rat</th>';
									
									for (r=1;r<=NumRodadas;r++)
									{
										Conteudo=Conteudo+'<th align=\"center\">' + r + 'ªrod</td>';
									}
									Conteudo=Conteudo+'<th>Pts</th></tr>';
								
									//alert('jogadorClassif.length: '+jogadorClassif.length);
									
									//RI  Fed  Tit  Nome  Rat  r1ªrod  r2ªrod  ...  r8ªrod  r9ªrod  ...  Pontos 
									
									//alert(QS_jogadorClassif.length);
									for (var y=1;y<QS_jogadorClassif.length;y++)
									{
										saltar=false;
										//if(Grupo!=''){if(jogadorClassif[z].Grupo!=Grupo){saltar=true;}}
										
										if(saltar!=true)
										{
											if(y % 2 == 1)
												{Conteudo=Conteudo+'<tr bgcolor=\"#e0e0e0\">';}
											else
												{Conteudo=Conteudo+'<tr bgcolor=\"#ffffff\">';}
											
											if(classif==1)
											{
												z=y;
												Conteudo=Conteudo+'<td>'+QS_jogadorClassif[z].ord_classif+'</td>';
											}
											else
											{
												Conteudo=Conteudo+'<td>'+QS_jogadorClassif[y].ord_classif+'</td>';
												z=OrdQuadroSinoptPont[y];
											}
											
											//Conteudo=Conteudo+'<td>'+QS_jogadorClassif[z].ord_classif+'</td>';
											
											//Conteudo=Conteudo+'<td width=63px>'+QS_jogadorClassif[z].PaisJogador; //+'<!/td>';
											Conteudo=Conteudo+'<td><center>';
											//flag
											Conteudo=Conteudo+'<div class=\"flag\" style=\"position:relative;float:center;\">';
											Conteudo=Conteudo+'<div title='+QS_jogadorClassif[z].PaisJogador+' class=\"tn_' + QS_jogadorClassif[z].PaisJogador + '\"></div>';
											Conteudo=Conteudo+'</div>';
											Conteudo=Conteudo+'</td>';
											
											Conteudo=Conteudo+'<td>'+QS_jogadorClassif[z].TitFIDE+'</td>';
											
										//Conteudo=Conteudo+\"<td><a href='#'><span style='color:blue;' onClick='pegarPosClick(event);mudarFichaJogador(\"+z+\");'>\" + QS_jogadorClassif[z].NomeJogador + \"</span></a>\";
											Conteudo=Conteudo+\"<td><a href='#'><span style='color:blue;' onClick='pegarPosClick(event);mudarFichaJogador(\"+z+\");'>\" + jogador[z-1].NomeJogador + \"</span></a>\";
											if(classif==2){Conteudo=Conteudo+\" (\"+z+\")\";}
											Conteudo=Conteudo+\"</td>\";
											
											Conteudo=Conteudo+'<td>'+QS_jogadorClassif[z].RatFIDE+'</td>';
											
											for (r=1;r<=NumRodadas;r++)
											{
												Conteudo=Conteudo+'<td align=\"center\">'+QS_jogadorClassif[z].RodRes[r]+'</td>';
											}
											Conteudo=Conteudo+'<td>'+QS_jogadorClassif[z].PontosJogador+'</td>';
											
											Conteudo=Conteudo+'</tr>';
										}
									}
									
									Conteudo=Conteudo+'</table>';
									//alert(Conteudo);
									return Conteudo;
								}
         
						function pegarPosClick(event)
						{
							PosClickX = event.clientX;
							PosClickY = event.clientY;
						}
				 
						function mudarFichaJogador(numjogador)
						{
							fichaJogador=document.getElementById('ficha_jogador');
							if(fichaJogador.style.visibility=='visible' && NumFichaJogador==numjogador)
							{fichaJogador.style.visibility='hidden';}
							else
							{
								NumFichaJogador=numjogador;					// NumFichaJogador é Global !
								fichaJogador.style.left=1;
								fichaJogador.style.top=1;
								fichaJogador.style.height='auto';
								fichaJogador.style.border='1px';
								
								fichaJogador.innerHTML=ImprimirFichaJogador(NumFichaJogador);
							
								fichaJogadorLeft=PosClickX-DivPrincipalLeft;
								fichaJogadorWidth=fichaJogador.offsetWidth;
								fichaJogadorParentWidth=fichaJogador.offsetParent.offsetWidth;
								if((fichaJogadorLeft + fichaJogadorWidth) > fichaJogadorParentWidth)
								{fichaJogador.style.left=fichaJogadorParentWidth-fichaJogadorWidth;}
								else
								{fichaJogador.style.left=fichaJogadorLeft;}
								
								fichaJogadorTop=PosClickY-DivPrincipalTop;
								fichaJogadorHeight=fichaJogador.offsetHeight;
								fichaJogadorParentHeight=fichaJogador.offsetParent.offsetHeight;							
								if((PosClickY-4 + fichaJogadorHeight) > fichaJogadorParentHeight)
								{fichaJogador.style.top=fichaJogadorParentHeight-fichaJogadorHeight;}
								else
								{fichaJogador.style.top=fichaJogadorTop;}
								
								fichaJogador.style.visibility='visible';
							}
						}
						
						function ImprimirFichaJogador(njogador)
						{
							//object
							Conteudo='<table onClick=fichaJogador.style.visibility=\"hidden\"; width=100% cellspacing=1 border=1>';
							//Conteudo=Conteudo+'<tr><td>&nbsp;</td><td colspan=2>Publicar - Imprimir</td><td width=10> X </td></tr>';
							
							Conteudo=Conteudo+'<tr><td>';
							
							Conteudo=Conteudo+'Nome:'+jogador[njogador].NomeJogador+'<br>';
							
							Conteudo=Conteudo+'TitFIDE: '+jogador[njogador].TitFIDE+'<br>';
							Conteudo=Conteudo+'Nasc: '+jogador[njogador].DataNasc+'<br>';
							Conteudo=Conteudo+'Clube: '+jogador[njogador].Clube+'<br>';
							Conteudo=Conteudo+'País: '+jogador[njogador].PaisJogador+'<br>';
							Conteudo=Conteudo+'</td>';
							
							Conteudo=Conteudo+'<td>';
							Conteudo=Conteudo+'RatNAC: '+jogador[njogador].RatNAC+'<br>';							
							//Conteudo=Conteudo+'IdNAC: '+jogador[njogador].IdNAC+'<br>';
											if(jogador[njogador].IdNAC>0)
											{Conteudo=Conteudo+'IdNAC: '+'<a href=\"http://www.cbx.org.br/DetalhesJogador.aspx?no='+jogador[njogador].IdNAC+'\">'+jogador[njogador].IdNAC+'</a>'+'<br>'}
											else
											{Conteudo=Conteudo+'IdNAC:&nbsp;<br>';}
											
							//Conteudo=Conteudo+'IdFIDE: '+'<a href=\"http://ratings.fide.com/card.phtml?event='+jogador[njogador].IdFIDE+'\">'+jogador[njogador].IdFIDE+'</a>'+'<br>';
											if(jogador[njogador].IdFIDE>0)
											{Conteudo=Conteudo+'IdFIDE: '+'<a href=\"http://ratings.fide.com/card.phtml?event='+jogador[njogador].IdFIDE+'\">'+jogador[njogador].IdFIDE+'</a>'+'<br>'}
											else
											{Conteudo=Conteudo+'IdFIDE:&nbsp;<br>';}
							Conteudo=Conteudo+'Ranking Inicial: '+(njogador+1)+'<br>';
							Conteudo=Conteudo+'Classif: ' + JogClassif[njogador+1] +'<br>';
							Conteudo=Conteudo+'</td>';
							
							Conteudo=Conteudo+'<td colspan=2>';
							Conteudo=Conteudo+'RatFIDE: '+jogador[njogador].RatFIDE+'<br>';
							Conteudo=Conteudo+'Fator K: '+jogador[njogador].FatorK+'<br>';
							if(performace[njogador+1]>0)
							{
							 Conteudo=Conteudo+'Performace: ' + performace[njogador+1] +'<br>';
							 Conteudo=Conteudo+'Var. Rating: ' + ratvart[njogador+1] +'<br>';
							}
							else
							{
							 Conteudo=Conteudo+'Performace: - <br>';
							 Conteudo=Conteudo+'Var. Rating: - <br>';
							}
							Conteudo=Conteudo+'Pontos: ' + PontosJogador[njogador+1] +'<br>';
											
							
							Conteudo=Conteudo+'</td></tr>';
							Conteudo=Conteudo+'</table>';
							
							//rodadas
							Conteudo=Conteudo+'<table width=100% cellspacing=1 border=1>';
							Conteudo=Conteudo+'<tr>';
							Conteudo=Conteudo+'<th>Rd</th><th>Ad</th><th>Tit</th><th>Nome</th>';
							Conteudo=Conteudo+'<th>RatF</th><th>RatN</th><th>Fed</th>';
							Conteudo=Conteudo+'<th>Pts</th><th>Res</th><th>K</th><th>±rat</th>';
							Conteudo=Conteudo+'<th>RatPv</th>';	// Rating Previsto
							Conteudo=Conteudo+'</tr>';
							
							
							rating_previsto=jogador[njogador].RatFIDE;
							for(r=1;r<=NumRodadas;r++)
							{
								Conteudo=Conteudo+'<tr>';
								Conteudo=Conteudo+'<td>'+r+'</td>';
								//alert('*'+CrossTable[njogador+1][r][1]+'*')
								if(CrossTable[njogador+1][r][1]=='-')
								{
									
									titL=' - ';advL=' - ';
									switch(CrossTable[njogador+1][r][3])
									{
										case '1':
											nomeL='bye';
											break;
										case '½':
											nomeL='bye ausente';
											break;
										case '0':
											nomeL='ñ/emparc.';
											break;
									}
									
									ratfL=' - ';ratnL=' - ';fedL=' - ';ptsL=' - ';
									resL='&nbsp;'+CrossTable[njogador+1][r][3];
									fatkL=' - ';
									Conteudo=Conteudo+'<td>'+titL+'</td>'+'<td>'+advL+'</td>'+'<td>'+nomeL+'</td>'+'<td>'+ratfL+'</td>';
									Conteudo=Conteudo+'<td>'+ratnL+'</td>'+'<td>'+fedL+'</td>'+'<td>'+ptsL+'</td>'+'<td>'+'&nbsp;'+resL+'</td>';
									Conteudo=Conteudo+'<td>'+fatkL+'</td>';
									
								}	
								else
								{
									
									if(CrossTable[njogador+1][r][1]<1)
									{
										Conteudo=Conteudo+'<td>-</td>'+'<td>-</td>'+'<td>-</td>'+'<td>-</td>'+'<td>-</td>'+'<td>-</td>'+'<td>-</td>'+'<td>-</td>'+'<td>-</td>';
									}
									else
									{
									
									jogL=CrossTable[njogador+1][r][1]-1;
									
									Conteudo=Conteudo+'<td>'+CrossTable[njogador+1][r][1]+'</td>';
									
									Conteudo=Conteudo+'<td>'+jogador[jogL].TitFIDE+'</td>';
									
									Conteudo=Conteudo+'<td><a href=\"#\"><span style=\"color:blue;\" onClick=\'mudarFichaJogador('+jogL+');\'>'+jogador[jogL].NomeJogador+'</span></a></td>';
									
									Conteudo=Conteudo+'<td>'+jogador[jogL].RatFIDE+'</td>';
									Conteudo=Conteudo+'<td>'+jogador[jogL].RatNAC+'</td>';
									Conteudo=Conteudo+'<td>'+jogador[jogL].PaisJogador+'</td>';
									Conteudo=Conteudo+'<td>'+PontosJogador[CrossTable[njogador+1][r][1]]+'</td>';
									
									Conteudo=Conteudo+'<td>'+CrossTable[njogador+1][r][2]+CrossTable[njogador+1][r][3]+'</td>';
									
									Conteudo=Conteudo+'<td>'+jogador[jogL].FatorK+'</td>';
									}
									
							  }
								
								Conteudo=Conteudo+'<td>'+ratvar[njogador+1][r]+'</td>';
								
								rating_previsto = rating_previsto + parseFloat(ratvar[njogador+1][r]);
								Conteudo=Conteudo+'<td>'+rating_previsto.toFixed(1)+'</td>';		// *** Rating Previsto ***
								
								Conteudo=Conteudo+'</tr>';
							}
							
							Conteudo=Conteudo+'</table>';
							
							return Conteudo;
						}
         
           function divOrdenar()
            {
													mostraSelOrd=divSelOrdem.style.visibility;
				         if(mostraSelOrd=='hidden')
														{divSelOrdem.style.visibility='visible';divSelOrdem.style.height=220;}
													else
														{divSelOrdem.style.visibility='hidden';divSelOrdem.style.height=0;}
												}
         
						function MostrarDetalhes()
						{
							alert('Grupo222: ' + Grupo);	
							mostrarDetalhes=divDetalhes.style.visibility;
							if(mostrarDetalhes=='hidden')
								{divDetalhes.style.visibility='visible';divDetalhes.style.height='auto';divDetalhes.style.padding='1px';divDetalhes.style.border='1px';divMostrarDetalhes.innerHTML='<a href=\"#\"><span onclick=\"MostrarDetalhes();\">Ocultar Detalhes</span></a>&nbsp;';}
							else
								{divDetalhes.style.visibility='hidden';divDetalhes.style.height='0px';divDetalhes.style.padding='0px';divDetalhes.style.border='0px';divMostrarDetalhes.innerHTML='<a href=\"#\"><span onclick=\"MostrarDetalhes();\">Mostrar Detalhes</span></a>&nbsp;';}
						}

						function MostrarEstat()
						{
							alert('Grupo333: ' + Grupo);	
							mostrarEstat=divEstatisticas.style.visibility;
							if(mostrarEstat=='hidden')
								{divEstatisticas.style.visibility='visible';divEstatisticas.style.height='auto';divMostrarEstat.innerHTML='<a href=\"#\"><span onclick=\"MostrarEstat();\">Ocultar Estatisticas</span></a>&nbsp;';}
							else
								{divEstatisticas.style.visibility='hidden'; divEstatisticas.style.height='0px';divMostrarEstat.innerHTML='<a href=\"#\"><span onclick=\"MostrarEstat();\">Mostrar Estatisticas</span></a>&nbsp;';}
						}
						
					function MostrarHorarios()
					{
						mostrarHorarios=divHorarios.style.visibility;
						if(mostrarHorarios=='hidden')
							{divHorarios.style.visibility='visible';divHorarios.style.height='auto';divMostrarHorarios.innerHTML='&nbsp; &nbsp; <a href=\"#\"><span onclick=\"MostrarHorarios();\">Ocultar Horarios</span></a>&nbsp;';}
						else
							{divHorarios.style.visibility='hidden';divHorarios.style.height='0px';divMostrarHorarios.innerHTML='&nbsp; &nbsp; <a href=\"#\"><span onclick=\"MostrarHorarios();\">Mostrar Horarios</span></a>&nbsp;';}
					}
					
          function MostrarCrossTableI()
          {
						mostrarCrossTableI=divCrossTabI.style.visibility;
				    if(mostrarCrossTableI=='hidden')
							{divCrossTabI.style.visibility='visible';divCrossTabI.style.height='auto';divCrossTabI.style.border=1;divMostrarCrossTableI.innerHTML='&nbsp; &nbsp; <a href=\"#\"><span onclick=\"MostrarCrossTableI();\">Ocultar Quadro Sinóptico p/RI</span></a>&nbsp;';}
						else
							{divCrossTabI.style.visibility='hidden'; divCrossTabI.style.height=0;divCrossTabI.style.border=0;divMostrarCrossTableI.innerHTML='&nbsp; &nbsp; <a href=\"#\"><span onclick=\"MostrarCrossTableI();\">Mostrar Quadro Sinóptico p/RI</span></a>&nbsp;';}
					}
					
          function MostrarCrossTable()
          {
						mostrarCrossTable=divCrossTab.style.visibility;
				    if(mostrarCrossTable=='hidden')
							{divCrossTab.style.visibility='visible';divCrossTab.style.height='auto';divCrossTab.style.width='auto';divCrossTabI.style.border=1;divMostrarCrossTable.innerHTML='&nbsp; &nbsp; <a href=\"#\"><span onclick=\"MostrarCrossTable();\">Ocultar Quadro Sinóptico p/pts</span></a>&nbsp;';}
						else
							{divCrossTab.style.visibility='hidden'; divCrossTab.style.height='0px';divCrossTabI.style.border=0;divMostrarCrossTable.innerHTML='&nbsp; &nbsp; <a href=\"#\"><span onclick=\"MostrarCrossTable();\">Mostrar Quadro Sinóptico p/pts</span></a>&nbsp;';}
					}
					
           function MostrarEmparceiramentos(rd)
            {
							
							//id = 'divEmparceiramentos';
							obj=document.getElementById('divEmparceiramentos');
							//alert(obj.id);
							
							idr = 'rd'+rd;
							objetor=document.getElementById(idr);
							
							id = 'divEmparceiramento'+rd;

							alert(rd + ' - ' + idr + ' - ' + id);

							objeto=document.getElementById(id);
							CorFundoRodada=objetor.style.background;
							CorFundoRodada=CorFundoRodada.substr(0,18);
							//alert(CorFundoRodada);
						  //if(CorFundoRodada=='rgb(187, 187, 187) none repeat scroll 0% 0%') {CorFundoRodada='#bbbbbb';}
							if(CorFundoRodada=='rgb(187, 187, 187)') {CorFundoRodada='#bbbbbb';}
							
							FecharJanelas();
							
							if(CorFundoRodada=='#bbbbbb')
								{
									obj.style.visibility='hidden';obj.style.height='0px';obj.style.padding=0;
									objeto.style.visibility='hidden'; objeto.style.height='0px';objeto.style.padding=0;
									/*objeto.style.position='relative';objeto.style.top='0px';*/
									objetor.style.background='none';objetor.style.height='0px';objetor.style.padding='0px';
								}
							else
								{
									obj.style.visibility='visible';
									obj.style.height='480px';obj.style.overflow='auto';
									objeto.style.visibility='visible';objeto.style.height='auto';
									/*objeto.style.position='absolute';objeto.style.top='130px';*/
									objetor.style.background='#bbbbbb';
									
									CriarLinkVisaoAtual(10+rd);
								}
						}
											
           function MostrarTodosEmparceiramentos(acao)
            {
							/*alert(acao);*/
							FecharJanelas();
							
							obj=document.getElementById('divEmparceiramentos');
							if(acao==1)
								{obj.style.visibility='visible';obj.style.height='480px';obj.style.overflow='auto';}
							else
								{obj.style.visibility='hidden';obj.style.height='0px';}
							
							for(i_rd=1;i_rd<=NumRodadas;i_rd++)
								{
									id = 'divEmparceiramento'+i_rd;
									objeto=document.getElementById(id);
									mostrarEmparceiramento=objeto.style.visibility;
									if(acao==1)
										{objeto.style.visibility='visible';objeto.style.height='auto';}
									else
										{objeto.style.visibility='hidden'; objeto.style.height='0px';}
								}
							if(acao==1)
								{
									divMostrarTodos.innerHTML='&nbsp;&nbsp; <a href=\"#\"><span onclick=\"MostrarTodosEmparceiramentos(0);\">Ocultar todos</span></a>&nbsp;';
									CriarLinkVisaoAtual(10);
								}
							else
								{divMostrarTodos.innerHTML='&nbsp;&nbsp; <a href=\"#\"><span onclick=\"MostrarTodosEmparceiramentos(1);\">Mostrar todos</span></a>&nbsp;';}
						}			
						
						function CriarLinkVisaoAtual(janelaL)
						{
							//alert(janelaL);
							JanelaVista=janelaL;
							linkcont=document.getElementById('linkpubl').innerHTML;
							//posparini1=linkcont.indexOf('?torneio_reg');
							posparini1=linkcont.indexOf('&amp;torneio_reg');
							
							if(posparini1>0)
							{
								posparfim1=linkcont.indexOf('\">',posparini1);
								linkcont1=linkcont.substr(0, (posparfim1+2));
								linkcont2=linkcont.substr((posparfim1+2), (linkcont.length-posparfim1+2));
								
								linkparam=linkcont.substr(posparini1, (posparfim1-posparini1));
								
								//linkcont3=linkcont1.replace(linkparam, '?torneio_reg=36' + '&amp;janela=' + janelaL);
								//linkcont4=linkcont2.replace(linkparam, '?torneio_reg=36' + '&amp;janela=' + janelaL);
								//linkcont3=linkcont1.replace(linkparam, '?torneio_reg=' + torneio_reg_Atual + '&amp;janela=' + janelaL);
								//linkcont4=linkcont2.replace(linkparam, '?torneio_reg=' + torneio_reg_Atual + '&amp;janela=' + janelaL);
								linkcont3=linkcont1.replace(linkparam, '&torneio_reg=' + torneio_reg_Atual + '&amp;janela=' + janelaL);
								linkcont4=linkcont2.replace(linkparam, '&torneio_reg=' + torneio_reg_Atual + '&amp;janela=' + janelaL);
								
								//document.getElementById('linktest').value=linkcont3 + linkcont4;
								document.getElementById('linkpubl').innerHTML=linkcont3 + linkcont4;
							}
						}
						
						function FecharLinkVisaoAtual()
						{
							JanelaVista=0;
							linkcont=document.getElementById('linkpubl').innerHTML;
							posparini1=linkcont.indexOf('&amp;janela');
				
							if(posparini1>0)
							{
								posparfim1=linkcont.indexOf('\">',posparini1);
								linkpar1=linkcont.substr(posparini1, (posparfim1-posparini1));
								
								linkcont2=linkcont.replace(linkpar1, '');
								linkcont2=linkcont2.replace(linkpar1, '');
								
								//document.getElementById('linktest').value=linkcont2;
								document.getElementById('linkpubl').innerHTML=linkcont2;
							}
						}
						
						function MostrarJogadores()
						{
							mostrarListaJogadores=divListaJogadores.style.visibility;
				      
							if(mostrarListaJogadores=='hidden')
							{
								divListaJogadores.style.visibility='visible';divListaJogadores.style.height='auto';
								
								tab_jogadores.style.height='480px'; //'auto';
								tab_jogadores.style.overflow='auto';
								tab_jogadores.style.visibility='visible';
								divMostrarListaJogadores.innerHTML='<a href=\"#\"><span onclick=\"MostrarJogadores();\">Ocultar Jogadores</span></a>&nbsp;';
								CriarLinkVisaoAtual(1);
							}
							else
							{
								divListaJogadores.style.visibility='hidden';divListaJogadores.style.height='0px';tab_jogadores.style.height='0px';tab_jogadores.style.visibility='hidden';divMostrarListaJogadores.innerHTML='<a href=\"#\"><span onclick=\"MostrarJogadores();\">Mostrar Jogadores</span></a>&nbsp;';
							}
							//alert('Grupo11111: ' + Grupo);	
						}
						
						function MostrarGrupoJogadoresN(grp,grpDescr)
						{
							/* alert('grp: ' + grp); */
							obj=document.getElementById('Grupo_Jogadores');
							id = 'divGrupoJogadores';
							objeto=document.getElementById(id);
							idg = 'gp' + grp;
							objetog = document.getElementById(idg);
							CorFundoGrupo=objetog.style.background;
							if(CorFundoGrupo=='none repeat scroll 0% 0% rgb(187, 187, 187)') {CorFundoGrupo='#bbbbbb';}
							FecharJanelas();
							document.getElementById('spanGrupoDescr').innerHTML=grpDescr;
							document.getElementById('Grupo_Jogadores').innerHTML=ImprimirJogadores(grpDescr);
							divGrupoJogadores.style.visibility='visible';divGrupoJogadores.style.height='auto';Grupo_Jogadores.style.height='auto';Grupo_Jogadores.style.visibility='visible';
							if(CorFundoGrupo=='#bbbbbb')
							{
								obj.style.visibility='hidden';obj.style.height=0;
								objeto.style.visibility='hidden'; objeto.style.height=0;
								objetog.style.background='none';
							}
							else
							{
								obj.style.visibility='visible';
								obj.style.height='480px';obj.style.overflow='auto';
								objeto.style.visibility='visible';objeto.style.height='auto';
								objetog.style.background='#bbbbbb';
							}
						}
						
						function FecharJanelas()
						{
							
							FecharLinkVisaoAtual();
							
							ficha_jogador.style.visibility='hidden';ficha_jogador.style.height='0px';ficha_jogador.style.border='0px';
							divDetalhes.style.visibility='hidden';divDetalhes.style.height='0px';divDetalhes.style.border='0px';divDetalhes.style.padding='0px';divMostrarDetalhes.innerHTML='<a href=\"#\"><span onclick=\"MostrarJanela(divDetalhes.id,divMostrarDetalhes.id);\">Mostrar Detalhes</span></a>&nbsp;';
							divEstatisticas.style.visibility='hidden';divEstatisticas.style.height='0px';divEstatisticas.style.border='0px';divEstatisticas.style.padding='0px';divMostrarEstat.innerHTML='<a href=\"#\"><span onclick=\"MostrarJanela(divEstatisticas.id,divMostrarEstat.id);\">Mostrar Estatísticas</span></a>&nbsp;';
							divHorarios.style.visibility='hidden';divHorarios.style.height='0px';divHorarios.style.border='0px';divHorarios.style.padding='0px';divMostrarHorarios.innerHTML='<a href=\"#\"><span onclick=\"MostrarJanela(divHorarios.id,divMostrarHorarios.id);\">Mostrar Horarios</span></a>&nbsp;';
							divCrossTabI.style.visibility='hidden';divCrossTabI.style.height='0px';divCrossTabI.style.border=0;divMostrarCrossTableI.innerHTML='<a href=\"#\"><span onclick=\"MostrarJanela(divCrossTabI.id,divMostrarCrossTableI.id);\">Mostrar Quadro Sinóptico p/RI</span></a>&nbsp;';
							divCrossTab.style.visibility='hidden';divCrossTab.style.height='0px';divCrossTab.style.border=0;divMostrarCrossTable.innerHTML='<a href=\"#\"><span onclick=\"MostrarJanela(divCrossTab.id,divMostrarCrossTable.id);\">Mostrar Quadro Sinóptico p/pts</span></a>&nbsp;';
							divTieBreak.style.visibility='hidden';divTieBreak.style.height='0px';divTieBreak.style.border='0px';divMostrarCriterios.innerHTML='<a href=\"#\"><span onclick=\"MostrarJanela(divTieBreak.id,divMostrarCriterios.id);\">Mostrar Critérios</span></a>&nbsp;';
							divListaClassif.style.visibility='hidden';divListaClassif.style.height='0px'; divMostrarClassif.innerHTML='<a href=\"#\"><span onclick=\"MostrarJanela(divListaClassif.id,divMostrarClassif.id);\">Mostrar Classificação</span></a>&nbsp;';
							
							divListaJogadores.style.visibility='hidden';divListaJogadores.style.height='0px';tab_jogadores.style.height='0px';tab_jogadores.style.visibility='hidden';divMostrarListaJogadores.innerHTML='<a href=\"#\"><span onclick=\"MostrarJanela(divListaJogadores.id,divMostrarListaJogadores.id);\">Mostrar Jogadores</span></a>&nbsp;';
							divGrupoJogadores.style.visibility='hidden';divGrupoJogadores.style.height='0px';Grupo_Jogadores.style.height='0px';Grupo_Jogadores.style.visibility='hidden';
							
							//divMostrarGrupoJogadores.innerHTML='<a href=\"#\"><span onclick=\"MostrarJanela(divGrupoJogadores.id,divMostrarGrupoJogadores.id);\">Mostrar Grupos</span></a>&nbsp;';
							
							divSelOrdem.style.visibility='hidden';divSelOrdem.style.height=0;
							divEmparceiramentos.style.visibility='hidden';divEmparceiramentos.style.height='0px';
							divMostrarTodos.innerHTML='&nbsp;&nbsp; <a href=\"#\"><span onclick=\"MostrarTodosEmparceiramentos(1);\">Mostrar todos</span></a>&nbsp;';
							
							for (var i=1;i<=NumRodadas;i++)
							//for (var i=1;i<=1;i++)
							{
								var id = 'divEmparceiramento'+i;
								objeto_rod=document.getElementById(id);
								alert('ID: ' + id + ' - objetoID: ' + document.getElementById(id).name); // ***** 2022/02/26 *** Melhor pista!!! *****
								objeto_rod.style.height='0px';
								objeto_rod.style.visibility='hidden';
								objeto_rod.style.position='relative';
								objeto_rod.style.padding='0px';
								objeto_rod.style.border='0px';
								
								var idr = 'rd'+i;
								objeto_r=document.getElementById(idr);
								//alert(idr);
								objeto_r.style.background='none';
							}
							
							for (var i=0;i<NumGrupos;i++)
							{
								var idg = 'gp'+i;
							  objeto_g=document.getElementById(idg);
								objeto_g.style.background='none';
							}
						  
						}
						
						function MostrarJanelaY(Janela,CtrJanela)
            {
							switch(Janela)
							{
								case \"divDetalhes\":
									msgMostrar=\"Mostrar Detalhes\";
									msgOcultar=\"Ocultar Detalhes\";
									break;
								case \"divEstatisticas\":
									msgMostrar=\"Mostrar Estatisticas\";
									msgOcultar=\"Ocultar Estatisticas\";
									break;
								case \"divHorarios\":
									msgMostrar=\"Mostrar Horarios\";
									msgOcultar=\"Ocultar Horarios\";
									break;
								case \"divCrossTabI\":
									msgMostrar=\"Mostrar Quadro Sinóptico p/RI\";
									msgOcultar=\"Ocultar Quadro Sinóptico p/RI\";
									break;
								case \"divCrossTab\":
									msgMostrar=\"Mostrar Quadro Sinóptico p/pts\";
									msgOcultar=\"Ocultar Quadro Sinóptico p/pts\";
									break;
								case \"divTieBreak\":
									msgMostrar=\"Mostrar Critérios\";
									msgOcultar=\"Ocultar Critérios\";
									break;
								case \"divListaClassif\":
									msgMostrar=\"Mostrar Classificação\";
									msgOcultar=\"Ocultar Classificação\";
									break;
									
								case \"divListaJogadores\":
									msgMostrar=\"Mostrar Jogadores\";
									msgOcultar=\"Ocultar Jogadores\";
									break;
									
								default:
							}
							objeto=document.getElementById(Janela);
							objetoCtr=document.getElementById(CtrJanela);
							mostrarJanela=objeto.style.visibility;
							//alert(Janela +' - '+mostrarJanela);
							FecharJanelas();
							
							if(Janela=='divListaJogadores')
								{MostrarJogadores();}
							else
							{
								if(mostrarJanela=='hidden')
								{
									objeto.style.visibility='visible';objeto.style.height='auto';
									
									objetoCtr.innerHTML='<a href=\"#\"><span onclick=\"MostrarJanela(objeto.id,objetoCtr.id);\">' + msgOcultar + '</span></a>&nbsp;';
									if(Janela=='divListaClassif')
									{
									//document.getElementById('tab_classif').style.visibility='visible';document.getElementById('tab_classif').style.height='auto';
										tab_classif.style.visibility='visible';
										tab_classif.style.height='480px';
										tab_classif.style.overflow='auto';
										CriarLinkVisaoAtual(2);
									}
									else
									{
										objeto.style.height='480px'; //'auto';
										objeto.style.overflow='auto';
										
										if(Janela=='divCrossTab')
										{
											CriarLinkVisaoAtual(3);
										}
										else if(Janela=='divCrossTabI')
										{
											CriarLinkVisaoAtual(4);
										}
										
										//else if(Janela=='divMostrarGrupoJogadores') {CriarLinkVisaoAtual(5);}
										
										else if(Janela=='divEstatisticas')
										{
											CriarLinkVisaoAtual(6);
										}
										else if(Janela=='divHorarios')
										{
											CriarLinkVisaoAtual(7);
										}
										else if(Janela=='divTieBreak')
										{
											CriarLinkVisaoAtual(8);
										}
										else if(Janela=='divDetalhes')
										{
											CriarLinkVisaoAtual(9);
										}
									}
								}
								else
								{
									objeto.style.visibility='hidden'; objeto.style.height='0px';
									objetoCtr.innerHTML='<a href=\"#\"><span onclick=\"MostrarJanela(objeto.id,objetoCtr.id);\">' + msgMostrar + '</span></a>&nbsp;';
								}
							}
					  }
          
					function funcExibirCriterio(desempate,criterio,descricao,TieBreakParam)
					{
						/* alert(TieBreakParam); */
						ExibirCriterio.innerHTML= '&nbsp;(' + desempate + ') <b>' + criterio + ' - ' + descricao + '</b>&nbsp;' + TieBreakParam + '</b>&nbsp;';
						//ExibirCriterio.style.visibility='visible';
						ExibirCriterio.style.display='inline-block';
					}
										
					function OcultarExibirCriterio()
					{
						/*alert('kkkkk');*/
						ExibirCriterio.innerHTML= '&nbsp';
						//ExibirCriterio.style.visibility='hidden';
						ExibirCriterio.style.display='none';
					}

					function funcExibirGrupo(DescrGrupo)
					{
						ExibirGrupo.innerHTML= '&nbsp;Grupo <b>\"' + DescrGrupo + '\"</b>&nbsp;';
						ExibirGrupo.style.visibility='visible';ExibirGrupo.style.height='20px';
					}
					
					function OcultarExibirGrupo()
					{
						ExibirGrupo.style.visibility='hidden';
					}

//-------------------------------------------------------------------------------					

					function Config(OrdNomeSobreNome)
					{
						switch(OrdNomeSobreNome)
						{
							case 'SbN':
								//OrdNomeSobreNome='SbN';
								for(i=0;i<jogador.length;i++)
								{
									jogador[i].NomeJogador=jogador[i].SobreNomeJogador+' '+jogador[i].PreNomeJogador;
									//jogadorClassif[i+1].NomeJogador=jogadorClassif[i+1].SobreNomeJogador+' '+jogadorClassif[i+1].PreNomeJogador;
									//QS_jogadorClassif[i+1].NomeJogador=QS_jogadorClassif[i+1].SobreNomeJogador+' '+QS_jogadorClassif[i+1].PreNomeJogador;
								}
								break;
							case 'SvN':		// a implementar
								//OrdNomeSobreNome='S,N';
								for(i=0;i<jogador.length;i++)
								{
									jogador[i].NomeJogador=jogador[i].SobreNomeJogador+', '+jogador[i].PreNomeJogador;
									//jogadorClassif[i+1].NomeJogador=jogadorClassif[i+1].SobreNomeJogador+', '+jogadorClassif[i+1].PreNomeJogador;
									//QS_jogadorClassif[i+1].NomeJogador=QS_jogadorClassif[i+1].SobreNomeJogador+', '+QS_jogadorClassif[i+1].PreNomeJogador;
								}
								break;
							case 'NvS':		// a implementar
								//OrdNomeSobreNome='N,S';
								for(i=0;i<jogador.length;i++)
								{
									jogador[i].NomeJogador=jogador[i].PreNomeJogador+', '+jogador[i].SobreNomeJogador;
									//jogadorClassif[i+1].NomeJogador=jogadorClassif[i+1].PreNomeJogador+', '+jogadorClassif[i+1].SobreNomeJogador;
									//QS_jogadorClassif[i+1].NomeJogador=QS_jogadorClassif[i+1].PreNomeJogador+', '+QS_jogadorClassif[i+1].SobreNomeJogador;
								}
								break;
							case 'NbS':
								//OrdNomeSobreNome='NbS';
								for(i=0;i<jogador.length;i++)
								{
									jogador[i].NomeJogador=jogador[i].PreNomeJogador+' '+jogador[i].SobreNomeJogador;
									//jogadorClassif[i+1].NomeJogador=jogadorClassif[i+1].PreNomeJogador+' '+jogadorClassif[i+1].SobreNomeJogador;
									//QS_jogadorClassif[i+1].NomeJogador=QS_jogadorClassif[i+1].PreNomeJogador+' '+QS_jogadorClassif[i+1].SobreNomeJogador;
								}
								break;
							default:
								//OrdNomeSobreNome='NbS';
								for(i=0;i<jogador.length;i++)
								{
									jogador[i].NomeJogador=jogador[i].PreNomeJogador+' '+jogador[i].SobreNomeJogador;
									//jogadorClassif[i+1].NomeJogador=jogadorClassif[i+1].PreNomeJogador+' '+jogadorClassif[i+1].SobreNomeJogador;
									//QS_jogadorClassif[i+1].NomeJogador=QS_jogadorClassif[i+1].PreNomeJogador+' '+QS_jogadorClassif[i+1].SobreNomeJogador;
								}
								break;
						}
						
				// ***** Reimpressão das Vistas **********************************************************************
						if (typeof NumFichaJogador !== 'undefined')						// *** (!==) not equal value or not equal type ***
						{document.getElementById('ficha_jogador').innerHTML=ImprimirFichaJogador(NumFichaJogador);}
						
						document.getElementById('tab_jogadores').innerHTML=ImprimirJogadores('');						
						document.getElementById('tab_classif').innerHTML=ImprimirClassif('');
						document.getElementById('Grupo_Jogadores'). innerHTML=ImprimirJogadores('');	//(grp);
						if(classif_atual==1)
						{
							document.getElementById('quadro_sinoptico_ini').innerHTML=ImprimirQuadroSinoptico('1');
							document.getElementById('quadro_sinoptico_pts').innerHTML=ImprimirQuadroSinoptico('2');
						}
						else
						{
							document.getElementById('quadro_sinoptico_pts').innerHTML=ImprimirQuadroSinoptico('2');
							document.getElementById('quadro_sinoptico_ini').innerHTML=ImprimirQuadroSinoptico('1');
						}
						document.getElementById('tab_rodadas').innerHTML=ImprimirRodadas();
				// *******************************************************************************************************
						
					}
					
//-------------------------------------------------------------------------------					
				</script>";

    echo '</head>';

		echo '<body>';
		
		
		echo '<div title="Compartilhar1" id="fb-root"></div>
					<script>(function(d, s, id) {
						var js, fjs = d.getElementsByTagName(s)[0];
						if (d.getElementById(id)) return;
						js = d.createElement(s); js.id = id;
						js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6";
						fjs.parentNode.insertBefore(js, fjs);
					 }(document, "script", "facebook-jssdk"));
					</script>
				 ';
		
    
    echo ' <div id="divSelOrdem" name="divSelOrdem" style="visibility:hidden;position:absolute;z-index:1;left:658px;top:63px;width:170px;height:0px;padding:1px;background:#ddffdd;border:1px solid #ff66AA;">';
		echo '  <b>Recurso em Construção</b>!<hr>';
		echo "  <script language='javascript' type='text/javascript'>
							for(ii=0;ii<7;ii++) {document.write(TitCol[ii]+'<br>');}
							function FecharConfig(object)
							{object.offsetParent.style.visibility='hidden';}
						</script>";
    echo '  <br> (RatFIDE+RatNAC) &nbsp;<b><a href="#"><span onclick="Ordenar_Tab(8,Grupo);'.'">Ordenar</span></a></b>';
    echo ' </div>';
		
    echo ' <div id="divConfig" name="divConfig" style="visibility:hidden;position:absolute;float:right;z-index:1;right:10px;top:10px;width:auto;height:auto;padding:6px;background-color:#FAFFFA;border:1px solid #ff66AA;">';
		echo '  <b>Configurar posição de "Nome" e "Sobrenome":</b><br>';
		echo '<span style="line-height:7px;"><br></span>';
		echo '  &nbsp; &nbsp; &nbsp; <span style="line-height:15px;padding:2;background-color:#EEFFEE;border:1px solid;" onclick="Config(\'NbS\');"><a href="#">Nome Sobrenome</a></span>';
		echo '  &nbsp; &nbsp; <span style="line-height:15px;padding:2;background-color:#EEFFEE;border:1px solid;" onclick="Config(\'SvN\');"><a href="#">Sobrenome, Nome</a></span><br>';
		echo '<span style="line-height:9px;"><br></span>';
		echo '  &nbsp; &nbsp; &nbsp; <span style="line-height:15px;padding:2;background-color:#EEFFEE;border:1px solid;" onclick="Config(\'SbN\');"><a href="#">Sobrenome Nome</a></span><!br>';
		echo '  &nbsp; &nbsp; <span style="line-height:15px;padding:2;background-color:#EEFFEE;border:1px solid;" onclick="Config(\'NvS\');"><a href="#">Nome, Sobrenome</a></span><br>';
		echo '<span style="line-height:9px;"><br></span>';
//		echo '  &nbsp; &nbsp; <span style="float:right;line-height:15px;padding:2;background-color:#EFFFEF;border:1px solid #FF0000;" onclick="alert(document.getElementById(\"divConfig\").style.visibility);"> &nbsp; <a href="#">Cancelar</a> &nbsp; </span><br>';
		echo '  &nbsp; &nbsp; <span style="float:right;line-height:15px;padding:2;background-color:#EFFFEF;border:1px solid #FF0000;" onclick="FecharConfig(this);"> &nbsp; <a href="#">Fechar</a> &nbsp; </span><br>';
    echo ' </div>';
		
		echo ' <div style="position:relative;padding:1px;height:16px;border:1px solid #2266AA;">';
    
		echo "  <div id='titulo' name='titulo' style='position:relative;float:left;font-family:\"Arial Narrow\";'><b>$TituloTorneio</b> &nbsp; &nbsp; &nbsp; </div>";
		if($_SERVER["SERVER_NAME"]=='localhost')
		{  //php/index.php?page=
		//echo "  <div id='linkpubl' name='linkpubl' style='position:relative;float:left;width:auto;font-family:\"Yanone Kaffeesatz\",sans-serif;font-weight:400;'> &nbsp;z<a href='http://localhost/arquivodoarbitro/php/index.php?page=torneio.php&torneio_reg=$torneio_reg'>arquivodoarbitro.esfinge.org/php/index.php?page=torneio.php&torneio_reg=$torneio_reg</a></div>";
			echo "  <div id='linkpubl' name='linkpubl' style='position:relative;float:left;width:auto;font-family:\"Arial Narrow\",sans-serif;font-weight:800;'> « <a href='http://localhost/arquivodoarbitro/php/index.php?page=torneio.php&torneio_reg=$torneio_reg'>
			<span title='http://localhost/arquivodoarbitro/php/index.php?page=torneio.php&torneio_reg=$torneio_reg'>Clique aqui para Publicação Direta</span></a> »</div>";
		}
		else
		{
		//echo "  <div id='linkpubl' name='linkpubl' style='position:relative;float:left;width:auto;font-family:\"Yanone Kaffeesatz\",sans-serif;font-weight:400;'> &nbsp;<a href='http://www.arquivodoarbitro.esfinge.org/php/index.php?page=torneio.php&torneio_reg=$torneio_reg'>arquivodoarbitro.esfinge.org/php/index.php?page=torneio.php&torneio_reg=$torneio_reg</a></div>";
			echo "  <div id='linkpubl' name='linkpubl' style='position:relative;float:left;width:auto;font-family:\"Arial Narrow\",sans-serif;font-weight:800;'> « <a href='http://www.arquivodoarbitro.esfinge.org/php/index.php?page=torneio.php&torneio_reg=$torneio_reg'>
			<span title='http://arquivodoarbitro.esfinge.org/php/index.php?page=torneio.php&torneio_reg=$torneio_reg'>Clique aqui para Publicação Direta</span></a> »</div>";
		}
		
	//echo ' &nbsp; <div class="fb-share-button" data-href="http://www.arquivodoarbitro.esfinge.org/php/index.php?page=torneio.php&amp;torneio_reg=' . $torneio_reg . '&amp;janela=' . $janela  . '" data-layout="icon" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fwww.arquivodoarbitro.esfinge.org%2Fphp%2Findex.php%3Fpage%3Dtorneio.php%26torneio_reg%3D' . $torneio_reg . '%26janela%3D' . $janela  . '&amp;src=sdkpreparse"></a></div>';
		//echo ' &nbsp; <div class="fb-share-button" data-href="http://www.arquivodoarbitro.esfinge.org/php/index.php?page=torneio.php&amp;torneio_reg=' . $torneio_reg . '&amp;janela=' . $janela  . '" data-layout="icon" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fwww.arquivodoarbitro.esfinge.org%2Fphp%2Findex.php%3Fpage%3Dtorneio.php%26torneio_reg%3D' . $torneio_reg . '%26janela%3D' . $janela  . '&amp;src=sdkpreparse"></a></div>';
		echo ' &nbsp; ';
		for($jb=1;$jb<=$NumRodadas;$jb++)
			{echo '<div id=janbt' . $jb  . '  style="display:none;" class="fb-share-button" data-href="http://www.arquivodoarbitro.esfinge.org/php/index.php?page=torneio.php&amp;torneio_reg=' . $torneio_reg . '&amp;janela=' . $jb  . '" data-layout="icon" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fwww.arquivodoarbitro.esfinge.org%2Fphp%2Findex.php%3Fpage%3Dtorneio.php%26torneio_reg%3D' . $torneio_reg . '%26janela%3D' . $jb  . '&amp;src=sdkpreparse"></a></div>';}
	//echo '<div style="display:none;" class="fb-share-button" data-href="http://www.arquivodoarbitro.esfinge.org/php/index.php?page=torneio.php&amp;torneio_reg=' . $torneio_reg . '&amp;janela=' . $janela2 . '" data-layout="icon" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fwww.arquivodoarbitro.esfinge.org%2Fphp%2Findex.php%3Fpage%3Dtorneio.php%26torneio_reg%3D' . $torneio_reg . '%26janela%3D' . $janela2 . '&amp;src=sdkpreparse"></a></div>';
		echo '<script>janbt="janbt"+'.$janela.';document.getElementById(janbt).style.display="inline-block";</script>';
		
    echo ' </div>';
    
		echo ' <div style="position:relative;padding:1px;height:22px;border:1px solid #2266AA;">';
		echo '  <div id="divMostrarDetalhes" name="divMostrarDetalhes" title="Mostrar Detalhes" style="visibility:visible;position:relative;float:left;width:auto;border:1px solid #2266AA;">';
    echo '   <a href="#"><span id="JanDetClick" onclick="MostrarJanela(divDetalhes.id,divMostrarDetalhes.id);">Mostrar Detalhes</span></a>&nbsp;';
    echo '  </div>';
		echo '  <div id="divMostrarCriterios" name="divMostrarCriterios" title="Mostrar Critérios" style="visibility:visible;position:relative;float:left;left:1px;width:auto;border:1px solid #2266AA;">';
    echo '   <a href="#"><span id="JanCritClick" onclick="MostrarJanela(divTieBreak.id,divMostrarCriterios.id);">Mostrar Critérios</span></a>&nbsp;';
    echo '  </div>';
		echo '  <div id="divMostrarHorarios" name="divMostrarHorarios" title="Mostrar Horários" style="visibility:visible;position:relative;float:left;left:2px;width:auto;border:1px solid #2266AA;">';
    echo ' <a href="#"><span id="JanHorClick" onclick="MostrarJanela(divHorarios.id,divMostrarHorarios.id);">Mostrar Horários</span></a>&nbsp;';
    echo '  </div>';
	/*
	echo '  <div id="divMostrarEstat" name="divMostrarEstat" style="visibility:visible;position:relative;float:left;left:2px;width:auto;border:1px solid #2266AA;">';
    echo '   <a href="#"><span id="JanEstatClick" onclick="MostrarJanela(divEstatisticas.id,divMostrarEstat.id);">Mostrar Estatísticas</span></a>'&nbsp;;
    echo '  </div>';
	*/
		$data_ult=substr($datarec,8,2).'/'.substr($datarec,5,2).'/'.substr($datarec,0,4);
		echo '  <div id="divAtualizacao" name="divAtualizacao" title="Última atualização e Remetente" style="visibility:visible;position:relative;float:left;left:3px;width:auto;border:1px solid #2266AA;">';
    echo "   Última Atualização:<a href='#'><span onClick=''></span></a> $data_ult &nbsp; &nbsp;";
    echo "   Remetente: $remetente";
    echo '  </div>';
		echo "  <div style='position:relative;float:right;border:1px solid #2266AA';>(Swiss Manager)</div>";
    echo ' </div>';
		
		echo ' <div style="position:relative;height:22px;padding:1px;border:1px solid #2266AA;">';
		
		echo '  <div id="divMostrarListaJogadores" name="divMostrarListaJogadores" title="Mostrar Jogadores" style="visibility:visible;position:relative;float:left;width:auto;border:1px solid #2266AA;">';
    echo '   <a href="#"><span id="JanJogClick" onclick="MostrarJanela(divListaJogadores.id,divMostrarListaJogadores.id);">Mostrar Jogadores</span></a>&nbsp;';
    echo '  </div>';
		
		echo '  <div id="divMostrarGrupoJogadores" name="divMostrarGrupoJogadores" style="visibility:visible;position:relative;float:left;left:1px;border:1px solid #2266AA;">';
    //$NumGrupos=4;$GrupoDescr[0]='M';$GrupoDescr[1]='A';$GrupoDescr[2]='B';$GrupoDescr[3]='C';
		echo '   Grupos:';
		for ($g=0;$g<$NumGrupos;$g++)
		{
			$gd="\"".$GrupoDescr[$g]."\"";
			$grpDescr=$gd; //$GrupoDescr[$g];
			echo   "&nbsp;<a href=\"#\"><span id='gp".$g."' onmouseout='OcultarExibirGrupo();' onmouseover='funcExibirGrupo($gd);' onclick='MostrarGrupoJogadoresN($g,$grpDescr);'>g" . ($g+1) . "</span></a>";
		}
		echo '&nbsp;';
    echo '  </div>';
		
		echo "<script language='javascript' type='text/javascript'>";				
		echo " GrupoDescr=new Array();";
		for ($g=0;$g<$NumGrupos;$g++)
			{ echo "GrupoDescr[".$g."]='".$GrupoDescr[$g]."';"; }
		echo "NumGrupos=".$NumGrupos.";";
		if($NumGrupos<2) {echo 'document.getElementById("divMostrarGrupoJogadores").style.display="none"';}
		echo '</script>';
		//echo '<br>DD: '.$GrupoDescr[1].'*<br><br>';
		
		// echo '  <div id="divMostrarEstat" name="divMostrarEstat" style="visibility:visible;position:relative;float:left;left:2px;width:auto;border:1px solid #2266AA;">';
    // echo '   <a href="#"><span id="JanEstatClick" onclick="MostrarJanela(divEstatisticas.id,divMostrarEstat.id);">Mostrar Estatísticas</span></a>'&nbsp;;
    // echo '  </div>';
	
	echo '  <div id="divMostrarEstat" name="divMostrarEstat" style="visibility:visible;position:relative;float:left;left:2px;width:auto;border:1px solid #2266AA;">';
  echo '   <a href="#"><span id="JanEstatClick" onclick="MostrarJanela(divEstatisticas.id,divMostrarEstat.id);">Mostrar Estatísticas</span></a>';
  echo '  </div>';
	
	echo '  <div id="divMostrarClassif" name="divMostrarClassif" style="visibility:visible;position:relative;float:left;left:3px;width:auto;border:1px solid #2266AA;">';
    echo '   <a href="#"><span id="JanClassifClick" onclick="MostrarJanela(divListaClassif.id,divMostrarClassif.id);">Mostrar Classificação</span></a>&nbsp;';
    echo '  </div>';
		echo '  <div id="divMostrarCrossTableI" name="divMostrarCrossTableI" style="visibility:visible;position:relative;float:left;left:4px;width:auto;border:1px solid #2266AA;">';
    echo '   <a href="#"><span id="JanCrossIClick" onclick="MostrarJanela(divCrossTabI.id,divMostrarCrossTableI.id);">Mostrar Quadro Sinóptico p/RI</span></a>&nbsp;';
    echo '  </div>';
		echo '  <div id="divMostrarCrossTable" name="divMostrarCrossTable" style="visibility:visible;position:relative;float:left;left:5px;width:auto;border:1px solid #2266AA;">';
    echo '   <a href="#"><span id="JanCrossFClick" onclick="MostrarJanela(divCrossTab.id,divMostrarCrossTable.id);">Mostrar Quadro Sinóptico p/pts</span></a>';
    echo '  </div>';
    
		echo "<div id='divMostrarConfig' name='divMostrarConfig' title='Configurar ordem de Nome e Sobrenome' onClick='document.getElementById(\"divConfig\").style.visibility=\"visible\";' style='position:absolute;float:right;width:auto;right:1px;'>";
		//echo " <img src='../imagens/settings.png'>";
		echo " <img src='../imagens/settings.png'>";
		echo " <script language='javascript' type='text/javascript'>";
		echo "  ObjdivMostrarConfig=new Object();";
		echo "  ObjdivMostrarConfig=document.getElementById('divMostrarConfig');";
		//echo "  alert(ObjdivMostrarConfig.title);";
		//echo "  ObjdivMostrarConfig.click();";
		echo ' </script>';
    echo '</div>';
		
    /*
		echo '  <div class="flag"  style="position:relative;float:left;">';
    echo '  <div class="tn_BRA"></div>';
    echo '  </div>';
		*/
		
    echo ' </div>';
		
		
		
		echo ' <div style="position:relative;height:22px;padding:1px;border:1px solid #2266AA;">';
		
		echo '  <div id="divMostrarEmparceiramentos" name="divMostrarEmparceiramentos" style="position:relative;visibility:visible;float:left;border:1px solid #2266AA;">';
    echo '   Emparceiramentos/Resultados:';
		for ($r=1;$r<=$NumRodadas;$r++)
		{
			echo '&nbsp;<a href="#"><span id="rd'.$r.'" onclick="MostrarEmparceiramentos('.$r.');">'.$r.'ªrd</span></a> ';
		}
		echo '&nbsp; ';
    echo '  </div>';
		
		
		
		
		echo '  <div id="divMostrarTodos" name="divMostrarTodos" style="position:relative;visibility:visible;float:left;border:1px solid #2266AA;">';
		echo '			&nbsp;&nbsp; <a href="#"><span id="JanTodasRodClick" onclick="MostrarTodosEmparceiramentos(1);">Mostrar todos</span></a> ';
    echo '  </div>';
		
		echo '  <div id="divMostrarPGN" name="divMostrarPGN" style="visibility:visible;position:relative;padding-left:2;padding-right:2;float:right;right:1px;border:1px solid #2266AA;">';
		$ArqTorneioPGN = "../TorneiosSM/" . substr($ArqTorneio,0,strlen($ArqTorneio)-5) . '.pgn';
		if (!file_exists($ArqTorneioPGN))
		{echo "<s>Baixar PGN</s>";}
		else
		{echo "<a href=$ArqTorneioPGN>Baixar PGN</a>";}
    echo '</div>';
		
		echo '  <div id="divEnviarPGN" name="divEnviarPGN" style="visibility:visible;position:relative;padding-left:2;padding-right:2;float:right;right:3px;border:1px solid #2266AA;">';
		$ArqTorneioPGN = substr($ArqTorneio,0,strlen($ArqTorneio)-5) . '.pgn';
		echo "   <a href='af_receberarquivo_SM_PGN.php?nome=$ArqTorneio&pgn=$ArqTorneioPGN'>Enviar PGN</a> ";
    echo '  </div>';
		
    echo ' </div>';
		
		echo ' <div id="divDetalhes" name="divDetalhes" style="visibility:hidden;position:relative;height:0px;padding:0px;border:0px solid #2266AA;">';
		if($RepetRodadas==2)
		 {
			$RoundRobinDuplo='(Duplo)';
		 }
		if(!isset($RoundRobinDuplo)) {$RoundRobinDuplo='';}					// ***** 2019/10/28 *****
		
		echo "  <b>SubTítulo do Torneio</b>: $SubTituloTorneio<br>
			<b>Observações</b>: $LeadTorneio<br>
			<b>Organização</b>: $Organizador<br>
			<b>Diretor</b>: $Diretor<br>
			<b>Árbitro Principal</b>: $ArbitroPrincipal<br>
			<b>Árbitros</b>: $Arbitro<br>
			<b>Árbitros Auxiliares</b>: $ArbitrosAux<br>
			<b>Local</b>: $LocalTorneio<br>
			<b>Período</b>: $DataInicio a $DataFinal<br>
			<b>Número de Rodadas</b>: $NumRodadas<br>
			<b>Tipo do Torneio</b>: $TipoTorneio - $DescrTipoTorneio[$TipoTorneio] $RoundRobinDuplo<br>
			<b>Ritmo</b>: $Ritmo<br>
			<b>Categorias de Idade</b>: $CatIdades<br>
			<b>Quant. de Jogadores</b>: $QtJogadores<br>
			<b>Tipo Rating</b>: $TipoRating - $DescrTipoRating<br>
			<b>Avaliação de Rating</b>: $AvaliacaoRating - $DescrAvaliacaoRating[$AvaliacaoRating]<br>
			<b>Elo Médio</b>: $EloMedio<br>
			<b>Correio Eletrônico</b>: $EMail<br>
			<b>Página do Evento na Internet</b>: <a href='$HomePage'>$HomePage</a><br>
			<br>
			";
    echo ' </div>';
		
		// Estatísticas
		ksort($Federacoes);
		//echo'<font size=1><br></font>';
		echo '<div id="divEstatisticas" name="divEstatisticas" style="position:relative;visibility:hidden;height:0px;padding:0px;border:1px solid #2266AA;">';
    echo ' <table cellspacing=0 width=100%>';
    echo ' <tr valign=top><td width=25%><b>Estatística de Federações</b>: &nbsp;<br>';
    echo ' <table cellspacing=0 border=1>';
		echo "  <tr><th>N.FED</th><th>FED</th><th>QtJog</th></tr>";
		$nf=0;
		foreach ($Federacoes as $i => $value)
		{
			$nf++;
			if($nf % 2 == 0)
			{echo ' <tr bgcolor="#e0e0e0">';}
			else
			{echo ' <tr bgcolor="#ffffff">';}
			echo " <td>$nf</td><td>$i</td><td align=right>$Federacoes[$i]</td></tr>";
		}
		echo ' </table>';
		echo ' </td>';
		echo ' <td width=21%><b>Estatística de Títulos</b>: &nbsp;<br>';
		echo ' <table cellspacing=0 border=1>';
		echo "  <tr><th>Tit</th><th>QtTit</th></tr>";
		$nt=0;
		foreach ($TitulosFIDE as $i => $value)
		{
			if($TitulosFIDE[$i]>0)
			{
				$nt++;
				if($nt % 2 == 0)
					{echo ' <tr bgcolor="#e0e0e0">';}
				else
					{echo ' <tr bgcolor="#ffffff">';}
				//echo ' <tr>';
				echo "<td>$i</td><td align=right>$TitulosFIDE[$i]</td></tr>";
			}
		}
    echo ' </table>';
    echo ' </td>';
    echo ' <td width=54%><b>Estatística de Jogos</b>: (Em Construção!)<br>';
    echo ' <table cellspacing=0 border=1>';
				echo "  <tr><th>Rodada</th><th>Vit.Brancas</th><th>Empates</th><th>Vit.Negras</th><th>Ausências</th><th>Total</th></tr>";
				$nt=0;
				for ($r=1;$r<=$NumRodadas;$r++)
					{
								$nt++;
								if($nt % 2 == 0)
									{echo '<tr bgcolor="#e0e0e0">';}
								else
									{echo '<tr bgcolor="#ffffff">';}
						
						echo "<td>$r</td><td>$VitBr[$r]</td><td>$Empatesr[$r]</td><td>$VitNr[$r]</td><td>$Ausr[$r]</td><td>$Totr[$r]</td>";
					}
								$nt++;
								if($nt % 2 == 0)
									{echo '<tr bgcolor="#e0e0e0">';}
								else
									{echo '<tr bgcolor="#ffffff">';}
				
				echo "<td>Totais</td><td>$VitBt</td><td>$Empatest</td><td>$VitNt</td><td>$Aust</td><td>$Tott</td>";
    echo ' </table>';
    echo ' </td></tr>';
    echo ' </table>';
		echo '</div>';
				
				// Horários
				echo '<div id="divHorarios" name="divHorarios" style="position:relative;visibility:hidden;height:0px;border:1px solid #2266AA;">';
				for($i=1;$i<=$NumRodadas;$i++)
					{
						echo "$i"."ª Rodada: $DataRodada[$i] - Horário: $HorarioRodada[$i] - Tabuleiros: $TabulRodada[$i]<br>";
					}
				echo '</div>';
				
//-------------------------------------------------------------------------------------------------------
// em pauta
		// Rodadas - Emparceiramentos e Resultados
		echo '<div id="divEmparceiramentos" name="divEmparceiramentos" style="position:absolute;width:842px;overflow:hidden;visibility:hidden;height:0px;border:1px solid #2266AA;">';
			
			//<span style=\"font-size:14;font-family:Arial Narrow;\" onclick=\"ObjdivMostrarConfig.click();\">(Nome <a href=\"#\"><img src=\"../imagens/seta_trocar.png\"></a> Sobrenome)</span></th>';
			echo '<b>Emparceiramentos e Resultados</b>: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ';
			echo '<span style="font-size:14;font-family:Arial Narrow;" onclick="ObjdivMostrarConfig.click();"><b>(Nome <a href="#"><img src="../imagens/seta_trocar.png"></a> Sobrenome)</b></span>';
			echo "<div id='tab_rodadas' name='tab_rodadas' style='width:800px;position:relative;overflow:hidden;visibility:visible;height:auto;border:1px solid #2266AA;'>";
			echo 'Tabela das Rodadas';
			echo '</div>';
		echo '</div>';
		//echo '<script language="javascript" type="text/javascript">';
		//	echo 'document.getElementById("tab_rodadas").style.width=document.getElementById("tab_rodadas").offsetWidth;';
		//echo '</script>';
		
		echo "<script language='javascript' type='text/javascript'>";
			echo " RodadaMesa=new Array();DataRodada=new Array();HorarioRodada=new Array();TabulRodada=new Array();";
			for($r=1;$r<=$NumRodadas;$r++)
			{
				echo "DataRodada[".$r."]='".$DataRodada[$r]."';";
				echo "HorarioRodada[".$r."]='".RetirarNulo($HorarioRodada[$r])."';";
				echo "TabulRodada[".$r."]='".$TabulRodada[$r]."';";
					echo "RodadaMesa[".$r."]=new Object();";
				for ($m=1;$m<=$TabulRodada[$r];$m++)
				{
					echo "RodadaMesa[".$r."][".$m."]=new Object();";
					echo "RodadaMesa[".$r."][".$m."].PaisJogadorA='".RetirarNulo($RodMesaResult[$r][$m][8])."';";
					echo "RodadaMesa[".$r."][".$m."].TitJogadorA='".$RodMesaResult[$r][$m][7]."';";
					echo "RodadaMesa[".$r."][".$m."].RatJogadorA='".$RodMesaResult[$r][$m][6]."';";
					
					echo "RodadaMesa[".$r."][".$m."].NomeJogadorA='".$RodMesaResult[$r][$m][1]."';";
					echo "RodadaMesa[".$r."][".$m."].NumIniJogadorA='".$RodMesaResult[$r][$m][12]."';";
					
					echo "RodadaMesa[".$r."][".$m."].PontosJogadorA='".$RodMesaResult[$r][$m][2]."';";
					echo "RodadaMesa[".$r."][".$m."].ResPartida='".$RodMesaResult[$r][$m][3]."';";
					echo "RodadaMesa[".$r."][".$m."].PontosJogadorB='".$RodMesaResult[$r][$m][4]."';";
					echo "RodadaMesa[".$r."][".$m."].NomeJogadorB='".$RodMesaResult[$r][$m][5]."';";
					echo "RodadaMesa[".$r."][".$m."].NumIniJogadorB='".$RodMesaResult[$r][$m][13]."';";
					echo "RodadaMesa[".$r."][".$m."].RatJogadorB='".$RodMesaResult[$r][$m][9]."';";
					echo "RodadaMesa[".$r."][".$m."].TitJogadorB='".$RodMesaResult[$r][$m][10]."';";
					echo "RodadaMesa[".$r."][".$m."].PaisJogadorB='".RetirarNulo($RodMesaResult[$r][$m][11])."';";
				}
			}
			echo 'document.getElementById("tab_rodadas").innerHTML=ImprimirRodadas();';
		echo "</script>";
// ------------------------------------------------------------------------													
				
				
				if($apresentacao=='geral')
				{
					echo " <b>SubTítulo</b>: $SubTituloTorneio<br>
												Lead: $LeadTorneio<br>
												Diretor: $Diretor<br>
												Organizador: $Organizador<br>
												Local: $LocalTorneio<br>
												Árbitros Auxiliares: $ArbitrosAux<br>
												Arquivo PGN de Entrada: $ArqPGNentrada<br>
												Arquivo PGN de Saída: $ArqPGNsaida<br>
												Tít. Torneio p/PGN: $TitTornPGN<br>
												Local-Torneio PGN: $LocTornPGN<br>
												Arquivo do Torneio: $ArquivoTUNx<br>
												ParamNIdent01: $ParamNIdent01<br>
												Categorias de Idade: $CatIdades<br>
												Ritmo: $Ritmo<br>
												ArquivoHTMLRod: $ArquivoHTMLRod<br>
												ParamNIdent02: $ParamNIdent02<br>
												ParamNIdent03: $ParamNIdent03<br>
												ParamNIdent04: $ParamNIdent04<br>
												ParamNIdent05: $ParamNIdent05<br>
												País Sediante: $PaisSediante<br>
												Árbitro Principal: $ArbitroPrincipal<br>
												Federação Oficial: $FedOficial<br>
												Email: $EMail<br>
												HomePage: $HomePage<br>
												País2(?): $Pais2<br>
												
												Número de Rodadas: $NumRodadas<br>
												Tipo do Torneio: $TipoTorneio - $DescrTipoTorneio[$TipoTorneio]<br>
												Data Não Identificada: $DataDesc<br>
												Quant. de Jogadores: $QtJogadores<br>
												
												Avaliacao de Rating: $AvaliacaoRating - $DescrAvaliacaoRating[$AvaliacaoRating]<br>
												";

					echo '<br>Critérios de Desempate: <br>';
					//for($j=1; $j<=5; $j++)
					for($j=1; $j<=$QtDesempates; $j++)
						{
							echo ' &nbsp; &nbsp; ' . $j . ' - ' . $Desempate[$j] . '-' . $Matriz_Desempates[$Idioma][$Desempate[$j]][1] . '<br>';
						}
					echo '<br>';
					
					echo "Igual a Todos os Tabuleiros? $DescrTodosTab<br>";		// Equipe ????
					
					if($QtEquipes>0)
						{echo "Quant. de Equipes: $QtEquipes<br>";}
					
					echo "Cor p/ Jogo em Casa: $DescrCorJogoEmCasa<br>";
					
					echo "Rating Mínimo: $RatingMinimo<br>";
					
					echo "Emparceiramento como ... : $PtsEmparc - $DescrPtsEmparc<br>";
					
					echo "Pontos de Partida. Para a Equipe: $PtsPartEquipe<br>";
					
					echo "Data de Início: $DataInicio<br>";
					
					echo "Data de Término: $DataFinal<br>";
					
					echo "Repetição de Rodadas: $RepetRodadas &nbsp; (ex. Round-Robin Duplo)<br>";
					
					echo "Data de Corte: $DataCorte<br>";
					
					echo "Pontos para o Jogador Bye: $PontosBye - $VlrPontosBye<br>";
					
					echo "Ordem da Classificação Inicial Automaticamente? $ClassAutom - $DescrClassAutom<br>";
				}
					
					echo '<div id="divTieBreak" name="divTieBreak" style="position:relative;height:0px;padding:0px;font-family:Arial Narrow,Helvetica,sans-serif;font-size:16px;visibility:hidden;border:0px solid #2266AA;">';
					echo '<b>Critérios de Desempate</b>:<br>';
					if($QtDesempates>0)
					 {
							for($NrDesempate=1;$NrDesempate<=$QtDesempates;$NrDesempate++)
								{
									$TieBreakParam[$NrDesempate] = "($QtPior[$NrDesempate],$QtMelhor[$NrDesempate],$ExpAdic1C[$NrDesempate],$PNJ_Param[$NrDesempate],$pJogEqRet[$NrDesempate],$EquipBye_Param[$NrDesempate])";
									//echo $TieBreakParam[$NrDesempate].'<br>';
									echo '<span style="line-height:50%;"><br></span>';
									
									//if(!isset($Desempate[$NrDesempate])) {$Desempate[$NrDesempate]=0;}			// ***** 2019/10/28 *****
									if(!isset($Matriz_Desempates[$Idioma][$Desempate[$NrDesempate]][1])) {$Matriz_Desempates[$Idioma][$Desempate[$NrDesempate]][1]='';}			// ***** 2019/10/28 *****
									//echo " ********* $NrDesempate * $Desempate[$NrDesempate] *  *****";
									echo "&nbsp; &nbsp; <b>$NrDesempate</b>° Critério: &nbsp; <b>$Desempate[$NrDesempate]</b>-<b>" . $Matriz_Desempates[$Idioma][$Desempate[$NrDesempate]][1] . '</b>';
									
									if(!isset($Matriz_Desempates[$Idioma][$Desempate[$NrDesempate]][2])) {$Matriz_Desempates[$Idioma][$Desempate[$NrDesempate]][2]='';}			// ***** 2019/10/28 *****
									if(strtoupper($Matriz_Desempates[$Idioma][$Desempate[$NrDesempate]][2])!='N')
										{echo ' '.$TieBreakParam[$NrDesempate];}
									echo '<br>';
									//if(($Desempate[$NrDesempate]>1 && $Desempate[$NrDesempate]<5) || ($Desempate[$NrDesempate]>15 && $Desempate[$NrDesempate]<17) || ($Desempate[$NrDesempate]==22) || ($Desempate[$NrDesempate]>27 && $Desempate[$NrDesempate]<32) || ($Desempate[$NrDesempate]==34) || ($Desempate[$NrDesempate]==37))
									//if($Desempate[$NrDesempate]==44 || $Desempate[$NrDesempate]==52 || $Desempate[$NrDesempate]==54 || $Desempate[$NrDesempate]==55 || $Desempate[$NrDesempate]==60)
									if(strtoupper($Matriz_Desempates[$Idioma][$Desempate[$NrDesempate]][2])=='S')
										{
											if($Matriz_Desempates[$Idioma][$Desempate[$NrDesempate]][2]=='s') {echo "&nbsp; &nbsp; &nbsp; &nbsp; - <i>***Critério em implementação***</i><br>";}
											if($Corte[$NrDesempate]==0)
												{echo "&nbsp; &nbsp; &nbsp; - Sem Cortes<br>";}
											else
												{echo "&nbsp; &nbsp; &nbsp; - Corte:  ->  Melhor(es)=$QtMelhor[$NrDesempate] / Pior(es)=$QtPior[$NrDesempate]<br>";}
											echo "&nbsp; &nbsp; &nbsp; - Partidas não jogadas (wo, bye,...): *$ExpPNJ[$NrDesempate]* &nbsp; &nbsp; - &nbsp;  Adicionar pontos próprios: $ExpAdic[$NrDesempate]<br>";
											//echo "($NrDesempate,$Corte[$NrDesempate],$QtMelhor[$NrDesempate],$QtPior[$NrDesempate],$PNJ_Adic[$NrDesempate],*$PNJ[$NrDesempate]*,$Adic[$NrDesempate],$ExpPNJ[$NrDesempate],$ExpAdic[$NrDesempate])<br>";
											//echo "($QtPior[$NrDesempate],$QtMelhor[$NrDesempate],$ExpAdic1C[$NrDesempate],$PNJ_Param[$NrDesempate],$pJogEqRet[$NrDesempate],$EquipBye_Param[$NrDesempate])<br>";
										}
									else
										{
											//if(($Desempate[$NrDesempate]==1) OR ($Desempate[$NrDesempate]==11) OR ($Desempate[$NrDesempate]==12) OR ($Desempate[$NrDesempate]==45) OR ($Desempate[$NrDesempate]==53))
											if(strtoupper($Matriz_Desempates[$Idioma][$Desempate[$NrDesempate]][2])=='N')
												{
													if($Matriz_Desempates[$Idioma][$Desempate[$NrDesempate]][2]=='n') {echo "&nbsp; &nbsp; &nbsp; &nbsp; - <i>***Critério em implementação***</i><br>";}
													echo "&nbsp; &nbsp; &nbsp; &nbsp; - parâmetros não aplicáveis<br>";
												}
											else
												{
													echo "&nbsp; &nbsp; &nbsp; &nbsp; - parâmetros em construção<br>";
												}
										}
								}
						}					
					echo '</div>';
					
				if($apresentacao=='geral')
				{
					echo "Revisar Ordem dos Tabuleiros: $DescrRevisarOrdTab<br>";
					
					echo "Classificar/Exibir Rating: $DescrTipoRating<br>";
					
					echo "<br>";

					for($i=1;$i<=$NumRodadas;$i++)
						{
							echo "$iª Rodada: $DataRodada[$i] - Horário: $HorarioRodada[$i] - Tabuleiros: $TabulRodada[$i]<br>";
						}
					echo "<br>";
				
				for($i=1;$i<=$NumRodadas;$i++)
					{
						echo "$i"."ª DataExtra: $DataRodada2[$i]<br>";
					}
				}
    // ==========================================================================
    // Jogadores

				//echo "<br><br>$QtJogadores<br><br>";exit;
				
				//$z=67;			// ttttttttttttttttt
				//echo "<br><br>NomeJogador='".$NomeJogador[$z]."' - ".$QtJogadores."<br><br>";
				//echo "<br><br>NomeJogador='".RetirarNulo($NomeJogador[$z])."'<br><br>";
				//exit;
				
				//echo '<div id="test43" style="visibility:hidden;display:inline-block;border:1px solid #2266AA;">.</div>';
				
				//echo "<script language='javascript' type='text/javascript'>";
				//echo "alert('Teste 222: ');";
				//echo "</script>";
	
			
				echo "<script language='javascript' type='text/javascript'>";
				echo "MaiorNome=0;";
				//echo " jogador=new Array();";

				//for ($z=1;$z<=$QtJogadores;$z++)
				for ($z=1;$z<=6;$z++)
				 {
				  $z1=$z-1;
				  //echo "jogador[".$z1."]=new Object();";
					echo "jogador[".$z1."].ord_ini='".$z."';";
					echo "jogador[".$z1."].TitFIDE='".RetirarNulo($TitFIDE[$z])."';";
					echo "jogador[".$z1."].Genero='".RetirarNulo($Sexo[$z])."';";
					
					echo "jogador[".$z1."].SobreNomeJogador='".RetirarNulo($SobreNomeJogador[$z])."';";
					echo "jogador[".$z1."].PreNomeJogador='".RetirarNulo($PreNomeJogador[$z])."';";
					echo "jogador[".$z1."].NomeJogador='".RetirarNulo($NomeJogador[$z])."';";
					
					//echo 'alert(jogador['.$z1.'].NomeJogador);'; //************ */
					//echo 'alert("Testando ...");'; //************ */
					
					echo "jogador[".$z1."].DataNasc='".RetirarNulo($DataNasc[$z])."';";
					echo "jogador[".$z1."].IdNAC='".RetirarNulo($ID_Nacional[$z])."';";
					echo "jogador[".$z1."].IdFIDE='".RetirarNulo($IdFIDE[$z])."';";
					echo "jogador[".$z1."].PaisJogador='".RetirarNulo($PaisJogador[$z])."';";
					echo "jogador[".$z1."].RatFIDE=".$RatFIDE[$z].";";
					echo "jogador[".$z1."].FatorK=".$FatorK[$z].";";
					echo "jogador[".$z1."].RatNAC=".$RatNacional[$z].";";
					if(strlen($Clube[$z])<1) {$Clube[$z]="&nbsp;";}
					echo "jogador[".$z1."].Clube='".RetirarNulo($Clube[$z])."';";
					echo "jogador[".$z1."].Grupo='".RetirarNulo($Grupo[$z])."';";
					
					echo "document.getElementById('test43').innerHTML=jogador[".$z1."].NomeJogador;if(MaiorNome<document.getElementById('test43').offsetWidth){MaiorNome=document.getElementById('test43').offsetWidth};";
					//echo "document.getElementById('test43').innerHTML='yyyyy';";

					}

					echo "document.getElementById('test43').innerHTML = 'New text!';"; // **********************************

    //echo " document.write('hhhh');";
    //echo "<br> -- document.write(jogador[3].NomeJogador); -- <br>";
		echo '</script>';
		
		//divListaJogadores tab_jogadores divMostrarListaJogadores
		echo '<div id="divListaJogadores" name="divListaJogadores" style="position:absolute;width:99%;overflow:hidden;visibility:hidden;height:0px;border:1px solid #2266AA;">';
    echo ' <b>Dados dos Jogadores</b>: (Ordenação: ';
    echo '  Clique nos Títulos das colunas ou em <b><a href="#"><span onclick="divOrdenar();">Ordenação Composta</span></a></b>)<br>';
		echo " <div id='tab_jogadores' name='tab_jogadores' style='width:800px;position:relative;overflow:hidden;visibility:hidden;height:0px;'>";
		echo "  Tabela de Jogadores";
		echo ' </div>';
		echo '</div>';
		echo '<script language="javascript" type="text/javascript">';
		echo 'document.getElementById("tab_jogadores").innerHTML=ImprimirJogadores("");';
		echo 'document.getElementById("tab_jogadores").style.width=document.getElementById("table_jog").offsetWidth;';
		
		echo 'document.getElementById("jog5").click();';			// *** Simula Click ***
		
		echo '</script>';
		
		//divListaClassif tab_classif divMostrarListaClassif
		echo '<div id="divListaClassif" name="divListaClassif" style="position:absolute;width:99%;overflow:hidden;visibility:hidden;height:0px;border:1px solid #2266AA;">';
    echo ' <b>Lista de Classificação</b>: ';
		echo " <div id='tab_classif' name='tab_classif' style='width:800px;position:relative;overflow:hidden;visibility:hidden;height:0px;'>";
		echo '  Tabela de Classificação';
		echo ' </div>';
		echo '</div>';
		echo '<script language="javascript" type="text/javascript">';
		//echo 'document.getElementById("tab_classif").innerHTML=ImprimirClassif("");';
		echo 'document.getElementById("tab_classif").style.width=document.getElementById("table_Classif").offsetWidth;';
		echo '</script>';
		
		// Ficha do Jogador
		echo "<div id='ficha_jogador' name='ficha_jogador' style='z-index:1;position:absolute;float:left;height:0px;padding:0px;visibility:hidden;background-color:#FAFFFA;border:0px solid #2266AA;'>";
		echo "Ficha de Jogador";
		echo '</div>';		
		
		//divGrupoJogadores Grupo_Jogadores divMostrarGrupoJogadores
		echo '<div id="divGrupoJogadores" name="divGrupoJogadores" style="position:relative;visibility:hidden;height:0px;border:1px solid #2266AA;">';
		echo ' <b>Grupo de Jogadores</b>: '; echo '<b><span id="spanGrupoDescr"></span></b> &nbsp; &nbsp; ';
		echo '  (Ordenação: Clique nos Títulos das colunas ou em <b><a href="#"><span onclick="divOrdenar();">Ordenação Composta</span></a></b>)<br>';
		echo " <div id='Grupo_Jogadores' name='Grupo_Jogadores' style='visibility:hidden;height:0;'>";
		echo "  Jogadores do Grupo X<br>";
		echo ' </div>';
		echo '</div>';
		echo '<script language="javascript" type="text/javascript">';
		echo 'document.getElementById("Grupo_Jogadores").innerHTML=ImprimirJogadores("");';
		echo '</script>';
		
		echo '<div id="ExibirGrupo" name="ExibirGrupo" style="visibility:hidden;position:absolute;left:300px;top:34px;height:0px;width:auto;background:#ddffdd;color:#0000ff;padding:1;border:2px solid #000;">';
		echo 'GrupoX';
		echo '</div>';
		//echo "<br>***<br>";
		
    if($apresentacao=="geral")
					{
						echo "<br><hr>";
						echo "<b>Dados Gerais dos Jogadores</b>:<br>";
						echo "<table cellspacing=0 width='1650' border=1>";
						echo "<tr><th>Nr</th><th>TF</th><th>Nome</th><th>FED</th><th>RatFIDE</th><th>RatNac</th><th>Clube</th><th>TitA</th><th>Apelido</th><th>IdNac</th><th>?1?</th><th>Cat</th><th>Grp</th><th>?2?</th><th>Font</th><th>?3?</th><th>Sex</th><th>?4?</th><th>?4b?</th><th>DataNasc</th><th>NrClub</th><th>?5?</th><th>OrdInic</th><th>Id_FIDE</th><th>Equipe</th><th>?6b?</th><th>TbEq</th><th>?6c?</th><th>DM</th><th>?7?</th><th>Status</th><th>?7b?</th><th>PA</th><th>?8?</th><th>BNo</th><th>SNo</th><th>FtK</th><th>?9?</th></tr>";
						
						//$ordem_apres='inicial';
						$ordem_apres='RatFIDE';
						//$ordem_apres='RatNAC';
						
						switch ($ordem_apres)
							{
								case 'inicial':
									echo 'Ordem Inicial';
									for ($z=1;$z<=$QtJogadores;$z++)
										{
											$ordFIDE[$z]=$z;
										}
									break;
								case 'RatFIDE':
									echo 'Ordem FIDE';
									arsort($RatFIDE);
									$i_ord=0;
									foreach ($RatFIDE as $chave => $valor)
										{
											if($chave>0)
												{
													$i_ord++;
													$ordFIDE[$i_ord]=$chave;
												}
										}
									break;
								case 'RatNAC':
									echo 'Ordem NAC';
									arsort($RatNacional);
									$i_ord=0;
									foreach ($RatNacional as $chave => $valor)
										{
											if($chave>0)
												{
													$i_ord++;
													$ordFIDE[$i_ord]=$chave;
												}
										}
									break;
								default:
									echo 'sem ordem';
							}
						
						for ($z=1;$z<=$QtJogadores;$z++)
							{
								echo "<tr>";
								$ord=$ordFIDE[$z];
								//echo "<td>$z</td>";
								echo "<td>$ord</td>";
								echo "<td>$TitFIDE[$ord]</td>";
								echo "<td>$NomeJogador[$ord]</td>";
								echo "<td>$PaisJogador[$ord]</td>";
								echo "<td>$RatFIDE[$ord]</td>";
								echo "<td>$RatNacional[$ord]</td>";
								if(strlen($Clube[$ord])<1)
									{echo "<td>&nbsp;</td>";}
								else
									{echo "<td>$Clube[$ord]</td>";}
									
								echo "<td>$TitAcademico[$ord]</td>";
								echo "<td>$NomeCurto[$ord]</td>";
								//echo "<td>$TitFIDE[$ord]</td>";
								echo "<td>$ID_Nacional[$ord]</td>";
								echo "<td>$BlocoD1[$ord]</td>";
								//echo "<td>$Clube[$ord]</td>";
								//echo "<td>$PaisJogador[$ord]</td>";
								echo "<td>$Categoria[$ord]</td>";
								
								//echo "<td>$Grupo[$ord]</td>";
								if($Grupo[$ord]=='')
									echo "<td>&nbsp;</td>";
								else
									echo "<td>$Grupo[$ord]</td>";
								
								echo "<td>$BlocoD2[$ord]</td>";
								echo "<td>$Fonte[$ord]</td>";
								echo "<td>$BlocoD3[$ord]</td>";
								echo "<td>$Sexo[$ord]</td>";
								
								echo "<td>$BlocoD4[$ord]</td>";
								echo "<td>$BlocoD4b[$ord]</td>";
								
								//echo "<td>$RatFIDE[$ord]</td>";
								//echo "<td>$RatNacional[$ord]</td>";
								echo "<td>$DataNasc[$ord]</td>";
								echo "<td>$NumeroClube[$ord]</td>";
								
								echo "<td>$BlocoD5[$ord]</td>";
								echo "<td>$OrdInicJog[$ord]</td>";
								
								echo "<td>$IdFIDE[$ord]</td>";
								echo "<td>$Equipe[$ord]</td>";
								echo "<td>$BlocoD6b[$ord]</td>";
								echo "<td>$TabulEquip[$ord]</td>";
								echo "<td>$BlocoD6c[$ord]</td>";
								echo "<td>$DM[$ord]</td>";
									
								echo "<td>$BlocoD7[$ord]</td>";
								
								echo "<td>$Status[$ord]</td>";
								
								echo "<td>$BlocoD7b[$ord]</td>";
								
								echo "<td>$PtsAdic[$ord]</td>";
									
								echo "<td>$BlocoD8[$ord]</td>";
								
								echo "<td>$BNoPair[$ord]</td>";
								echo "<td>$SNo[$ord]</td>";
								echo "<td>$FatorK[$ord]</td>";
								
								echo "<td>$BlocoD9[$ord]</td>";
								
								echo "</tr>";
								
							}
							
						echo "</table><br>";
						
						echo '<hr>';
						
						// ***** Resultados *********************************************************
						
								echo "<b>Emparceiramentos e Resultados:</b><br>";
								
								for ($r=1;$r<=$NumRodadas;$r++)
									{
										echo "<b>" . $r . "ª Rodada: </b><br>";
										echo "<table cellspacing=0 border=1>";
										echo "<tr><td><b>Mesa</td><td><b>Nome</td><td><b>Pts</td><td><b><center>Resultado</center></td><td><b>Pts</td><td><b> &nbsp; Nome</td></tr>";
										
										for ($m=1;$m<=$TabulRodada[$r];$m++)
											{
												echo "<tr><td>$m</td><td>";
												echo $RodMesaResult[$r][$m][1];
												echo "</td><td>";
												echo $RodMesaResult[$r][$m][2];
												echo "</td><td><center>";
												echo $RodMesaResult[$r][$m][3];
												echo "</center></td><td>";
												echo $RodMesaResult[$r][$m][4];
												echo "</td><td>";
												echo $RodMesaResult[$r][$m][5];
												echo "</td></tr>";
											}
										
										echo "</table>";
									}
								
					}
				
    //echo '<div id="ExibirCriterio" name="ExibirCriterio" style="position:relative;display:none;height:auto;background:#ddffdd;color:#0000ff;padding:1;border:2px solid #000;">';
		//echo 'Descr Desempate';
    //echo '</div>';
				//echo '<div id="divClassif" name="divClassif" style="font-family:Arial Narrow,Helvetica,sans-serif;font-size:16;visibility:visible;left:5;padding:1;height:auto;width:824;border:1px solid #2266AA;">';
//				echo '<div id="divClassif" name="divClassif" style="position:relative;font-family:Arial Narrow,Helvetica,sans-serif;font-size:16px;visibility:hidden;padding:0px;height:0px;border:1px solid #2266AA;">';
											// ***** Classificação (com parte de Critérios de Desempate)*****
											if(count($PontosJogador)>0)
												{
													//echo '<b style="line-height:147%;">Classificação</b>: (<i>Em Construção: Critérios de Desempate</i>) <!br>';
													//echo '<div id="ExibirCriterio" name="ExibirCriterio" style="position:relative;display:none;height:auto;background:#ddffdd;color:#0000ff;padding:0px;border:2px solid #000;">Descr Desempate</div>';
													//echo '<table cellspacing=0 border=1>';
													//echo '<tr><td colspan=5>&nbsp;</td><td colspan='.$QtDesempates.'><center><b>Desempates</b></center></td></tr>';
													//echo '<tr><td><b>Cod</td><td><b>Tit</td><td><b>Nome</td><td><center><b>Fed</td><td><b>Pontos</td>';
													for($ic=1;$ic<=$QtDesempates;$ic++)
														{
															//echo "<td><b><a href=\"#\"><span onmouseout='OcultarExibirCriterio();' onmouseover='funcExibirCriterio(\"".$ic."\",\"".$Desempate[$ic]."\",\"".$Matriz_Desempates[$Idioma][$Desempate[$ic]][1]."\",\"".$TieBreakParam[$ic]."\");'>Crit.".$ic."</span></a></td>";

															if(!isset($Matriz_Desempates[$Idioma][$Desempate[$ic]][2])) {$Matriz_Desempates[$Idioma][$Desempate[$ic]][2]='';}		// ***** 2019/10/28 *****
															if(strtoupper($Matriz_Desempates[$Idioma][$Desempate[$ic]][2])=='N')
																{$TB_Param='';}
															else
																{$TB_Param=$TieBreakParam[$ic];}
													//		echo "<td><b><a href=\"#\"><span onmouseout='OcultarExibirCriterio();' onmouseover='funcExibirCriterio(\"".$ic."\",\"".$Desempate[$ic]."\",\"".$Matriz_Desempates[$Idioma][$Desempate[$ic]][1]."\",\"".$TB_Param."\");'>Crit.".$ic."</span></a></td>";
														}
													
													//{echo '<td><b>Desempate '.$ic.'&nbsp;'.$Matriz_Desempates[$Idioma][$ic][1].'</td>';}
													//echo "</tr>";
													
													// ***********************************************************************
															/*
																$PtsDesemp[2][6]=23;
																$PtsDesemp[2][2]=22;
																$PtsDesemp[2][12]=21.5;
																$PtsDesemp[2][1]=21;
																$PtsDesemp[2][5]=20.5;
																$PtsDesemp[2][10]=22;
															*/
													// ***********************************************************************
													
													/*
														if($Desempate[1]==1)
															{
																for($itb=1;$itb<=$QtJogadores;$itb++)
																	{$PtsDesemp[2][$itb] = $PontosJogador[$itb];}
															}
													*/
													//echo "<br><br>".count($PtsDesemp)."<br><br>";exit;
													
													CalcularDesempates();
													
													/*
													array_multisort($PtsDesemp[1], SORT_NUMERIC, SORT_DESC,
																					$PtsDesemp[2], SORT_NUMERIC, SORT_DESC,
																					$PtsDesemp[3], SORT_NUMERIC, SORT_DESC,
																					$PtsDesemp[4], SORT_NUMERIC, SORT_DESC,
																					$PtsDesemp[5], SORT_NUMERIC, SORT_DESC,
																					$PtsDesemp[6], SORT_NUMERIC, SORT_DESC,
																					$PtsDesemp[0]
																					);
													*/
													
													array_multisort($PtsDesemp[1],SORT_DESC,SORT_NUMERIC,
																					$PtsDesemp[2],SORT_DESC,SORT_NUMERIC,
																					$PtsDesemp[3],SORT_DESC,SORT_NUMERIC,
																					$PtsDesemp[4],SORT_DESC,SORT_NUMERIC,
																					$PtsDesemp[5],SORT_DESC,SORT_NUMERIC,
																					$PtsDesemp[6],SORT_DESC,SORT_NUMERIC,
																					$PtsDesemp[0]
																					);
													
													for($itb=$QtJogadores;$itb>0;$itb--)
														{ for($ic=0;$ic<=6;$ic++) {$PtsDesemp[$ic][$itb]=$PtsDesemp[$ic][$itb-1];} }
													for($ic=0;$ic<=6;$ic++) {unset($PtsDesemp[$ic][0]);}
													
													// *************** Inserir aqui formatação de Critério "Fide9 - progressive score" *********
													for($itb=1;$itb<=$QtJogadores;$itb++)
														{
															//for($ic=2;$ic<=6;$ic++)
															for($ic=2;$ic<=$QtDesempates+1;$ic++)
																{
																	$Matriz_Crit[$ic-1][$itb]=$PtsDesemp[$ic][$itb];
																	
																	/*
																	if($Desempate[$ic-1]==9)
																		{
																			$ValCrit=(String)($Matriz_Crit[$ic-1][$itb]);
																			//$ValCritTemp=substr($ValCrit,3,5);
																			$ValCritTemp='';
																			for($ivc=0;$ivc<$NumRodadas-1;$ivc++)
																			 {
																					$ValCritRod=substr($ValCrit,$ivc*$DigFide9,$DigFide9);
																					$ValCritRod1=(Int)$ValCritRod % 10;
																					//$ValCritRod1=175 % 10;
																					if($ValCritRod1==5)
																						{$ValCritRod=substr($ValCritRod,-$DigFide9,-1) . '½';}
																						//{$ValCritRod=substr($ValCritRod,-$DigFide9,-1) . '.5';}
																					else
																						{$ValCritRod=substr($ValCritRod,-$DigFide9,-1) . ' ';}
																						//{$ValCritRod=substr($ValCritRod,-$DigFide9,-1) . '.0';}
																					
																					$ValCritTemp=$ValCritTemp . $ValCritRod . ' ';
																				}
																			$Matriz_Crit[$ic-1][$itb]=trim($ValCritTemp);
																		}
																	*/
																	switch ($Desempate[$ic-1])
																		{
																			
																			case 8:
																			case 11:
																			case 25:
																				$ValCrit=(String)($Matriz_Crit[$ic-1][$itb]);
																				$TamValCrit=strlen($ValCrit);
																				$ValCrit1=(float)($ValCrit)*10 % 10;
																				
																				if($ValCrit1==5)
																					{
																						if($Matriz_Crit[$ic-1][$itb]<1)
																							{$ValCrit='½';}
																						else
																							{$ValCrit=substr($ValCrit,0,$TamValCrit-2) . '½';}
																						}
																					
																				$Matriz_Crit[$ic-1][$itb]=trim($ValCrit);
																				break;
																			
																			case 9:
																				$ValCrit=(String)($Matriz_Crit[$ic-1][$itb]);
																				//$ValCritTemp=substr($ValCrit,3,5);
																				$ValCritTemp='';
																				for($ivc=0;$ivc<$NumRodadas-1;$ivc++)
																					{
																						$ValCritRod=(String)((float)(substr($ValCrit,$ivc*$DigFide9,$DigFide9)));
																						$ValCritRod1=(Int)$ValCritRod % 10;
																						//$ValCritRod1=175 % 10;
																						
																						if($ValCritRod1==5)
																							{
																								//{$ValCritRod=substr($ValCritRod,-$DigFide9,-1) . '.5';}
																								if($Matriz_Crit[$ic-1][$itb]<1)
																									{$ValCritRod='½';}
																								else
																									{$ValCritRod=substr($ValCritRod,-$DigFide9,-1) . '½';}
																							}
																						else
																							{
																								//{$ValCritRod=substr($ValCritRod,-$DigFide9,-1) . '.0';}
																							 $ValCritRod=substr($ValCritRod,-$DigFide9,-1) . ' ';
																							 if(strlen(trim($ValCritRod))<1) {$ValCritRod='0';};
																							}
																						
																						$ValCritTemp=$ValCritTemp . $ValCritRod . ' ';
																					}
																				$Matriz_Crit[$ic-1][$itb]=trim($ValCritTemp);
																				break;
																			
																			/*
																			case 25:
																				$ValCrit=(String)($Matriz_Crit[$ic-1][$itb]);
																				$TamValCrit=strlen($ValCrit);
																				$ValCrit1=(float)($ValCrit)*10 % 10;
																				if($ValCrit1==5)
																					{$ValCrit=substr($ValCrit,0,$TamValCrit-2) . '½';}
																				$Matriz_Crit[$ic-1][$itb]=trim($ValCrit);
																				break;
																			*/
																		}
																	
																		
																}
														}
													
													
// ------------------------------------------------------------------------													
			echo "<script language='javascript' type='text/javascript'>";
			echo " jogadorClassif=new Array();JogClassif2=new Array();";
			$Colocacao=0;
			for($itb=1;$itb<=$QtJogadores;$itb++)
				{
					$Colocacao++;
					$nj=$PtsDesemp[0][$itb];
					$JogClassif[$nj]=$Colocacao;
				  echo "JogClassif2[".$Colocacao."]=".$nj.";";
				  
					echo "jogadorClassif[".$nj."]=new Object();";
					echo "jogadorClassif[".$nj."].ord_classif='".$Colocacao."';";
					echo "jogadorClassif[".$nj."].TitFIDE='".$TitFIDE[$nj]."';";
					echo "jogadorClassif[".$nj."].SobreNomeJogador='".$SobreNomeJogador[$nj]."';";
					echo "jogadorClassif[".$nj."].PreNomeJogador='".$PreNomeJogador[$nj]."';";
					echo "jogadorClassif[".$nj."].NomeJogador='".$NomeJogador[$nj]."';";
					echo "jogadorClassif[".$nj."].PaisJogador='".RetirarNulo($PaisJogador[$nj])."';";
					echo "jogadorClassif[".$nj."].PontosJogador='".$PontosJogador[$nj]."';";
					for($ic=1;$ic<=$QtDesempates+1;$ic++)
						{
							if($Desempate[$ic]!=9)
								{
									echo "jogadorClassif[".$nj."].Desempate".$ic."='".$Matriz_Crit[$ic][$nj]."';";
								}
							else
								{
									echo "jogadorClassif[".$nj."].Desempate".$ic."='".$Matriz_Crit[$ic][$nj]."';";
								}
						}
				}
			echo 'document.getElementById("tab_classif").innerHTML=ImprimirClassif("");';
			
			echo "</script>";

// ------------------------------------------------------------------------													
			
			echo '<div id="divCrossTab" name="divCrossTab" style="position:relative;font-family:Arial Narrow,Helvetica,sans-serif;font-size:16;visibility:hidden;height:0px;padding:0px;border:0px solid #2266AA;">';
					// ----- Cross Table / p/Pontuação --------------------------------------
				echo "<div id='quadro_sinoptico_pts' style=';border:1px solid #2266AA;'>";
				echo " Quadro p/pts";
				echo "</div>";
			echo '</div>';
			
			echo '<div id="divCrossTabI" name="divCrossTabI" style="position:relative;font-family:Arial Narrow,Helvetica,sans-serif;font-size:16;visibility:hidden;height:0px;padding:0px;border:0px solid #2266AA;">';
			// ----- Cross Table / Ranking Inicial --------------------------------------				
				echo "<div id='quadro_sinoptico_ini' style=';border:1px solid #2266AA;'>";
				echo " Quadro Inicial";
				echo "</div>";
			echo '</div>';
			
			echo "<script language='javascript' type='text/javascript'>";
			echo "</script>";
			echo "<script language='javascript' type='text/javascript'>";
			echo "OrdQuadroSinoptInic=new Array();OrdQuadroSinoptPont=new Array();";
			echo "QS_jogadorClassif=new Array();QS_JogClassif=new Array();";
			      
						//$OrdQSin=$RankInicial;
						for($i_ord=1;$i_ord<=$QtJogadores;$i_ord++)
						{
							$OrdQuadroSinoptInic[$i_ord]=$i_ord;
							echo "OrdQuadroSinoptInic[".$i_ord."]='".$i_ord."';";
						}
			
						
						$OrdQSin=$JogClassif;
						asort($OrdQSin);
						$i_ord=0;
						foreach ($OrdQSin as $chave => $valor)
						{
							if($chave>0)
							{
								$i_ord++;
								$OrdQuadroSinoptPont[$i_ord]=$chave;
								echo "OrdQuadroSinoptPont[".$i_ord."]='".$OrdQuadroSinoptPont[$i_ord]."';";
							}
						}
							
			$Colocacao=0;
			
			for($itb=1;$itb<=$QtJogadores;$itb++)
				{
					$Colocacao++;
					
					$nji=$OrdQuadroSinoptInic[$itb];		// Ranking Inicial
					$njp=$OrdQuadroSinoptPont[$itb];		// Ranking Pontuação
					
				  echo "QS_JogClassif[".$Colocacao."]='".$nji."';";
				  

					echo "QS_jogadorClassif[".$nji."]=new Object();";
					echo "QS_jogadorClassif[".$nji."].ord_classif='".$Colocacao."';";
					echo "QS_jogadorClassif[".$nji."].TitFIDE='".$TitFIDE[$nji]."';";
					
					echo "QS_jogadorClassif[".$nji."].SobreNomeJogador='".$SobreNomeJogador[$nji]."';";
					echo "QS_jogadorClassif[".$nji."].PreNomeJogador='".$PreNomeJogador[$nji]."';";
					echo "QS_jogadorClassif[".$nji."].NomeJogador='".$NomeJogador[$nji]."';";
					
					echo "QS_jogadorClassif[".$nji."].PaisJogador='".RetirarNulo($PaisJogador[$nji])."';";
					
					switch($TipoRating)
					{
						case '1':
							$RatPrincipal = $RatNacional[$nji];
							break;
						case '2':
							$RatPrincipal = $RatFIDE[$nji];
							break;
						case '3':
							if($RatFIDE[$nji]<1)
								{$RatPrincipal = $RatNacional[$nji];}
							else
								{$RatPrincipal = $RatFIDE[$nji];}
							break;
						case '4':
							echo ' <td>'.max($RatFIDE[$chave],$RatNacional[$chave]).'</td>';
							$RatPrincipal = max($RatFIDE[$nji],$RatNacional[$nji]);
							break;
						case '5':
							$RatPrincipal = $RatNacional[$nji];
							break;
						case '6':
							$RatPrincipal = $RatFIDE[$nji];
							break;
						default:
							$RatPrincipal = $RatNacional[$nji];
							break;
					}
				echo "QS_jogadorClassif[".$nji."].RatFIDE='".$RatPrincipal."';";
					
					echo "QS_jogadorClassif[".$nji."].RodRes=new Object();";
					for ($r=1;$r<=$NumRodadas;$r++)
					{
						echo "QS_jogadorClassif[".$nji."].RodRes[".$r."]='".$CrossTable[$nji][$r][1].$CrossTable[$nji][$r][2].$CrossTable[$nji][$r][3]."';";
					}
					echo "QS_jogadorClassif[".$nji."].PontosJogador='".$PontosJogador[$nji]."';";
					
				}
			
			echo 'document.getElementById("quadro_sinoptico_ini").innerHTML=ImprimirQuadroSinoptico("1");';
			echo 'document.getElementById("quadro_sinoptico_pts").innerHTML=ImprimirQuadroSinoptico("2");';
			
			echo "</script>";
	
		}
					
					echo "<script language='javascript' type='text/javascript'>JogClassif=new Array();performace=new Array();ratvart=new Array();</script>";
					for ($jpt=1;$jpt<=$QtJogadores;$jpt++)
					{
						echo "<script language='javascript' type='text/javascript'>
										JogClassif['".$jpt."']='".$JogClassif[$jpt]."';
										performace['".$jpt."']='".$performace[$jpt]."';
										ratvart['".$jpt."']='".$ratvart[$jpt]."';
									</script>";
					}					
					
									
									
									echo "<script language='javascript' type='text/javascript'>
													CrossTable=new Array();
													ratvar=new Array();
												</script>";
									for ($jpt=1;$jpt<=$QtJogadores;$jpt++)
									{
										echo "<script language='javascript' type='text/javascript'>
														CrossTable['".$jpt."']=new Array();
														ratvar['".$jpt."']=new Array();
													</script>";
										for ($rpt=1;$rpt<=$NumRodadas;$rpt++)
										{
											if(!isset($ratvar[$jpt][$rpt])) {$ratvar[$jpt][$rpt]='';}		// ***** 2019/10/28 *****
											echo "<script language='javascript' type='text/javascript'>
															CrossTable['".$jpt."']['".$rpt."']=new Array();
															ratvar['".$jpt."']['".$rpt."']='".$ratvar[$jpt][$rpt]."';
														</script>";
											for ($ppt=1;$ppt<=3;$ppt++)
											{
												echo "<script language='javascript' type='text/javascript'>
																CrossTable['".$jpt."']['".$rpt."']['".$ppt."']='".$CrossTable[$jpt][$rpt][$ppt]."';
															</script>";
											}
										}
									}
					
// -------------------------------------------------------------------------------------------

    if($apresentacao=="geral")
					{
								// ***** Equipes e Resultados *********************************************************
								if($QtEquipes>0)
									{
										echo "<b>Equipes:</b><br>";
										echo "<table cellspacing=0 border=1>";
										echo "<tr><td><b>NrEq</td><td><b>Nome</td><td><b>Nome Curto</td><td><b>Capitão</td><td><b>NId1</td><td><b>NId2</td><td><b>Pts</td></tr>";
										for($e=1;$e<=$QtEquipes;$e++)
											{
												echo '<tr>';
												echo "<td>$e</td>";
												echo "<td>$NomeEquipe[$e]</td>";
												echo "<td>$NomeEquipeR[$e]</td>";
												echo "<td>$Capitao[$e]&nbsp;</td>";
												echo "<td>$NId1[$e]</td>";
												echo "<td>$NId2[$e]</td>";
												echo "<td>$PontosEquipe[$e]</td>";
												echo '</tr>';
											}
										echo '</table>';
									}
								
								// ----- Cross Table / Ranking Inicial --------------------------------------
								echo '<br><b>Quadro Sinóptico:</b><br>';
								echo '<table cellspacing=0 border=1>';
								echo '<tr><td><b>Cod</td><td><b>Nome</td>';
								for ($r=1;$r<=$NumRodadas;$r++)
									{
										echo '<td><b>'.$r.'ª Rodada</td>';
									}
								echo '<td><b>Pts</td>';
								for ($j=1;$j<=$QtJogadores;$j++)
									{
										echo '<tr>';
										echo '<td>'.$j.'</td>';
										echo '<td>'.$NomeJogador[$j].'</td>';
										for ($r=1;$r<=$NumRodadas;$r++)
											{
												echo '<td>';
													echo '<table cellspacing=0 width="100%"><tr>';
														echo '<td width="35%" style="text-align:right;">'.$CrossTable[$j][$r][1].'</td>';
														echo '<td style="text-align:center;">'.$CrossTable[$j][$r][2].'</td>';
														echo '<td width="35%">'.$CrossTable[$j][$r][3].'</td>';
													echo '</tr></table>';
												echo '</td>';
											}
										echo '<td>'.$PontosJogador[$j].'</td>';
										echo '</tr>';
									}
								echo '</table>';
								
								// ----- Cross Table / p/Pontuação --------------------------------------
								echo '<br><b>Quadro Sinóptico: (p/Pontuação)</b><br>';
								echo '<table cellspacing=0 border=1>';
								echo '<tr><td><b>Cod</td><td><b>Nome</td>';
								for ($r=1;$r<=$NumRodadas;$r++)
									{
										echo '<td><b>'.$r.'ª Rodada</td>';
									}
								echo '<td><b>Pts</td>';
								
								arsort($PontosJogador);
								foreach ($PontosJogador as $chave => $valor)
									{
										if($chave>0)
											{
												echo '<tr>';
												echo '<td>'.$chave.'</td>';
												echo '<td>'.$NomeJogador[$chave].'</td>';
												for ($r=1;$r<=$NumRodadas;$r++)
													{
														echo '<td>';
															echo '<table cellspacing=0 width="100%"><tr>';
																echo '<td width="35%" style="text-align:right;">'.$CrossTable[$chave][$r][1].'</td>';
																echo '<td style="text-align:center;">'.$CrossTable[$chave][$r][2].'</td>';
																echo '<td width="35%">'.$CrossTable[$chave][$r][3].'</td>';
															echo '</tr></table>';
														echo '</td>';
													}
												echo '<td>'.$PontosJogador[$chave].'</td>';
												echo '</tr>';
											}
									}
								echo '</table>';
					
					}
					
    echo "</body></html>";
		
		switch($janela)
		{
			case 1:
				$abrir_janela='JanJogClick';
				break;
			case 2:
				$abrir_janela='JanClassifClick';
				break;
			case 3:
				$abrir_janela='JanCrossFClick';
				break;
			case 4:
				$abrir_janela='JanCrossIClick';
				break;
//		case 5:
//			$abrir_janela='JanCrossIClick';
//			break;
			case 6:
				$abrir_janela='JanEstatClick';
				break;
			case 7:
				$abrir_janela='JanHorClick';
				break;
			case 8:
				$abrir_janela='JanCritClick';
				break;
			case 9:
				$abrir_janela='JanDetClick';
				break;
			case 10:
				$abrir_janela='JanTodasRodClick';
				break;
			default:
				$abrir_janela='rd'.($janela-10);
				break;
		}				
		echo "<script language='javascript'>
		       document.getElementById('$abrir_janela').click();			// *** Simula Click ***
					</script>";
		
		exit;
		
	// -----------------------------------------------------------------------------	
	
	function RetirarNulo($texto)
	{
		$tam=strlen($texto);
		$novotexto='';
		for ($i=0;$i<$tam;$i++)
		{
			$carac = substr($texto,$i,1);
			$cod = ord($carac);
			if ($cod>0)
				{$novotexto = $novotexto . $carac;}
		}
		return $novotexto;
	}

	function LerCampoStr($PointerFile)
	{
		$TamString=ord(fread($PointerFile, 1)) * 2;
		if ($TamString>0)
			{$VrlString = fread($PointerFile, $TamString);}
		else
			{$VrlString = " ";}
		$Nulo=fread($PointerFile, 1);
		$VrlString=str_replace(chr(0), "", $VrlString);
		return $VrlString;
	}
	
	function LerCampoStrLonga($PointerFile)
	{
		$VlrInt = strrev(fread($PointerFile, 2));
		$arr = unpack("n", $VlrInt);				// ***** 2019/10/26 *****
		$value = $arr[1];
		$TamString=$value * 2;
		//echo "<br> Vlr: $value - $TamString <br>";//exit;
		if ($TamString>0)
			{
				$VrlString = fread($PointerFile, $TamString);
				$VrlString=str_replace(chr(0), "", $VrlString);
			}
		else
			{$VrlString = " ";}
//		$Nulo=fread($PointerFile, 1); // ***** 2022/02/14 *****
		return $VrlString;
	}

	
  function LerCampoAsc($PointerFile)
			{
				$VrlAsc = ord(fread($PointerFile, 1));
				//$Nulo=fread($PointerFile, 1);
				return $VrlAsc;
			}

  function LerCampoDat($PointerFile)
			{
				$VrlDate = strrev(fread($PointerFile, 4));
				$arr = unpack('n2ints', $VrlDate);
				$value = $arr['ints1'] * (256 * 256) + intval($arr['ints2']);	// - 1;
				$Dia = substr($value,strlen($value)-2,2);
				$Mes = substr($value,strlen($value)-4,2);
				$Ano = substr($value,0,4);
				$Data = Date('d-m-Y',mktime(0,0,0,$Mes,$Dia,$Ano));
				//$Nulo=fread($PointerFile, 1);
				return $Data;
			}

  function LerCampoDat2($strdate)
			{
				$VrlDate = strrev($strdate);
				$arr = unpack('n2ints', $VrlDate);
				$value = $arr['ints1'] * (256 * 256) + intval($arr['ints2']);	// - 1;
				$Dia = substr($value,strlen($value)-2,2);
				$Mes = substr($value,strlen($value)-4,2);
				$Ano = substr($value,0,4);
				$Data = Date('d-m-Y',mktime(0,0,0,$Mes,$Dia,$Ano));
				//$Nulo=fread($PointerFile, 1);
				return $Data;
			}

  function LerCampoLong($PointerFile)
			{
			 $VrlLong = fread($PointerFile, 4);
				//$arr = unpack('Lints4', $VrlLong);
				$arr = unpack('Lints1', $VrlLong);
				//$value = $arr['ints1'] * (256 * 256 * 256) + $arr['ints2'] * (256 * 256) + $arr['ints3'] * (256) + intval($arr['ints4']);
				$value = $arr['ints1'];
				return $value;
			}

  function LerCampoLong2($VrlLong)
			{
			 //$VrlLong = fread($PointerFile, 4);
				//$arr = unpack('Lints4', $VrlLong);
				$arr = unpack('Lints1', $VrlLong);
				//$value = $arr['ints1'] * (256 * 256 * 256) + $arr['ints2'] * (256 * 256) + $arr['ints3'] * (256) + intval($arr['ints4']);
				$value = $arr['ints1'];
				return $value;
			}
  
	function LerCampoInt($PointerFile)
			{
				/*
				$ar1 = pack("n", 298);
				$ar2 = unpack("n", $ar1);
				$value1 = $ar2[0] + $ar2[1] + $ar2[2];
				echo $ar2[0] . "-" . $ar2[1] . "-" . $ar2[2] . "-" . $value1;exit;
				*/
				
				//$nnn = fread($PointerFile, 2);
				$VlrInt = strrev(fread($PointerFile, 2));
				//$VlrInt = fread($PointerFile, 2);
//				$VlrInt = pack("n", 298);
				$arr = unpack("n", $VlrInt);				// ***** 2019/10/26 *****
				//if(isset($arr[0])==false) {$arr[0]=0;}
				
				//$yarr[0]=ord($VlrInt[0]);
				//$yarr[1]=ord($VlrInt[1]);
				/*
				$value = $arr[0] + $arr[1];
				*/
				$value = $arr[1];
				//echo $yarr[0] . "-" . $yarr[1] . "-" . $arr[1] . "-" . $value;exit;
				return $value;				
			}

  function LerCampoFloat($strfloat)
			{
				$arr = unpack('f', $strfloat);
				//$value = $arr['ints1'] * (256 * 256 * 256) + $arr['ints2'] * (256 * 256) + $arr['ints3'] * (256) + intval($arr['ints4']);
				//$value = $arr['ints1'];
				
				return $arr[1];	//$value;
			}

  function TestarSeqAsc($PointerFile,$QtBytes)
			{
				$VrlAsc = "";
    for ($i=1;$i<=$QtBytes;$i++)
				 {
				  $cod = ord(fread($PointerFile, 1));
				  if ($cod>-1)
					  {$VrlAsc = $VrlAsc . '/' . $cod;}
					}
				return $VrlAsc;
			}
  
  function TestarSeqAsc2($PointerFile,$QtBytes)
			{
				$VrlAsc = "";
    for ($i=1;$i<=$QtBytes;$i++)
				 {
				  $byte = fread($PointerFile, 1);
				  $cod = ord($byte);
				  if ($cod>0)
					  {$VrlAsc = $VrlAsc . '/' . $byte;}
					}
				return $VrlAsc;
			}
		
  function Montar_Matriz_Desempates()
		 {
			 global $Matriz_Desempates,$Idioma;
    $Matriz_Desempates[0][0][1] = " "; $Matriz_Desempates[0][0][2] = "N"; $Matriz_Desempates[0][0][3] = "N"; $Matriz_Desempates[0][0][4] = "N"; $Matriz_Desempates[0][0][5] = "N";
    $Matriz_Desempates[0][1][1] = "Pts.  points (game-points)";                                                                    $Matriz_Desempates[0][ 1][2] = "N"; $Matriz_Desempates[0][1][3] = "N"; $Matriz_Desempates[0][1][4] = "N"; $Matriz_Desempates[0][1][5] = "N";
    $Matriz_Desempates[0][2][1] = "Buchholz Tie-Breaks (all Results)";                                                             $Matriz_Desempates[0][ 2][2] = " "; $Matriz_Desempates[0][2][3] = " "; $Matriz_Desempates[0][2][4] = " "; $Matriz_Desempates[0][2][5] = " ";
    $Matriz_Desempates[0][3][1] = "Buchholzwertung (1 Streichresultat)";                                                           $Matriz_Desempates[0][ 3][2] = " "; $Matriz_Desempates[0][3][3] = " "; $Matriz_Desempates[0][3][4] = " "; $Matriz_Desempates[0][3][5] = " ";
    $Matriz_Desempates[0][4][1] = "Buchholz Tie-Breaks (without two results=middle Tie-Breaks)";                                   $Matriz_Desempates[0][ 4][2] = " "; $Matriz_Desempates[0][4][3] = " "; $Matriz_Desempates[0][4][4] = " "; $Matriz_Desempates[0][4][5] = " ";
    $Matriz_Desempates[0][5][1] = "Manually input in field rankcorr. in player-dialog";                                            $Matriz_Desempates[0][ 5][2] = "N"; $Matriz_Desempates[0][5][3] = " "; $Matriz_Desempates[0][5][4] = " "; $Matriz_Desempates[0][5][5] = " ";
    $Matriz_Desempates[0][6][1] = "Manually input in field rankcorr. in team-dialog";                                              $Matriz_Desempates[0][ 6][2] = " "; $Matriz_Desempates[0][6][3] = "N"; $Matriz_Desempates[0][6][4] = "N"; $Matriz_Desempates[0][6][5] = "N";
    $Matriz_Desempates[0][7][1] = "Sonneborn-Berger-Tie-Break (with real points)";                                                 $Matriz_Desempates[0][ 7][2] = "n"; $Matriz_Desempates[0][7][3] = "N"; $Matriz_Desempates[0][7][4] = "N"; $Matriz_Desempates[0][7][5] = "N";
    $Matriz_Desempates[0][8][1] = "Fide Tie-Break (*Progressive Score*)";                                                          $Matriz_Desempates[0][ 8][2] = "N"; $Matriz_Desempates[0][8][3] = " "; $Matriz_Desempates[0][8][4] = " "; $Matriz_Desempates[0][8][5] = " ";
    $Matriz_Desempates[0][9][1] = "Fide Tie-Break (*Progressive Score*) (fine)";                                                   $Matriz_Desempates[0][ 9][2] = "N"; $Matriz_Desempates[0][9][3] = " "; $Matriz_Desempates[0][9][4] = " "; $Matriz_Desempates[0][9][5] = " ";
    $Matriz_Desempates[0][10][1] = "rating average of the opponents";                                                              $Matriz_Desempates[0][10][2] = " "; $Matriz_Desempates[0][10][3] = " "; $Matriz_Desempates[0][10][4] = " "; $Matriz_Desempates[0][10][5] = " ";
    $Matriz_Desempates[0][11][1] = "The results of the players in the same point group";                                           $Matriz_Desempates[0][11][2] = "N"; $Matriz_Desempates[0][11][3] = "N"; $Matriz_Desempates[0][11][4] = " "; $Matriz_Desempates[0][11][5] = " ";
    $Matriz_Desempates[0][12][1] = "The greater number of victories";                                                              $Matriz_Desempates[0][12][2] = "N"; $Matriz_Desempates[0][12][3] = "N"; $Matriz_Desempates[0][12][4] = " "; $Matriz_Desempates[0][12][5] = " ";
    $Matriz_Desempates[0][13][1] = "Matchpoints (2 for wins, 1 for Draws, 0 for Losses)";                                          $Matriz_Desempates[0][13][2] = "n"; $Matriz_Desempates[0][13][3] = "N"; $Matriz_Desempates[0][13][4] = "N"; $Matriz_Desempates[0][13][5] = "N";
    $Matriz_Desempates[0][14][1] = "The results of the teams in then same point group according to Matchpoints";                   $Matriz_Desempates[0][14][2] = "n"; $Matriz_Desempates[0][14][3] = "N"; $Matriz_Desempates[0][14][4] = "N"; $Matriz_Desempates[0][14][5] = "N";
    $Matriz_Desempates[0][15][1] = "Board Tie-Breaks of the whole tournament";                                                     $Matriz_Desempates[0][15][2] = "n"; $Matriz_Desempates[0][15][3] = "N"; $Matriz_Desempates[0][15][4] = "N"; $Matriz_Desempates[0][15][5] = "N";
    $Matriz_Desempates[0][16][1] = "Buchholz Tie-Breaks (sum of team-points of the opponents and own points)";                     $Matriz_Desempates[0][16][2] = " "; $Matriz_Desempates[0][16][3] = " "; $Matriz_Desempates[0][16][4] = " "; $Matriz_Desempates[0][16][5] = "N";
    $Matriz_Desempates[0][17][1] = "Buchholz Tie-Breaks (with the real points)";                                                   $Matriz_Desempates[0][17][2] = " "; $Matriz_Desempates[0][17][3] = " "; $Matriz_Desempates[0][17][4] = " "; $Matriz_Desempates[0][17][5] = " ";
    $Matriz_Desempates[0][18][1] = "Carasaxa Tie-Breaks";                                                                          $Matriz_Desempates[0][18][2] = " "; $Matriz_Desempates[0][18][3] = " "; $Matriz_Desempates[0][18][4] = " "; $Matriz_Desempates[0][18][5] = " ";
    $Matriz_Desempates[0][19][1] = "Sonneborn-Berger Tie-Break (with modified points, analogous to Buchholz Tie-Break)";           $Matriz_Desempates[0][19][2] = " "; $Matriz_Desempates[0][19][3] = " "; $Matriz_Desempates[0][19][4] = " "; $Matriz_Desempates[0][19][5] = " ";
    $Matriz_Desempates[0][20][1] = "rating average of the opponents (without one result)";                                         $Matriz_Desempates[0][20][2] = " "; $Matriz_Desempates[0][20][3] = " "; $Matriz_Desempates[0][20][4] = " "; $Matriz_Desempates[0][20][5] = " ";
    $Matriz_Desempates[0][21][1] = "rating performance";                                                                           $Matriz_Desempates[0][21][2] = " "; $Matriz_Desempates[0][21][3] = " "; $Matriz_Desempates[0][21][4] = " "; $Matriz_Desempates[0][21][5] = " ";
    $Matriz_Desempates[0][22][1] = "Buchholz Tie-Breaks (sum of team-points of the opponents)";                                    $Matriz_Desempates[0][22][2] = " "; $Matriz_Desempates[0][22][3] = " "; $Matriz_Desempates[0][22][4] = " "; $Matriz_Desempates[0][22][5] = "N";
    $Matriz_Desempates[0][23][1] = "Sum of the ratings of the opponents (whithout one result)";                                    $Matriz_Desempates[0][23][2] = "n"; $Matriz_Desempates[0][23][3] = " "; $Matriz_Desempates[0][23][4] = " "; $Matriz_Desempates[0][23][5] = " ";
    $Matriz_Desempates[0][24][1] = "The BSV-Board-Tie-Break";                                                                      $Matriz_Desempates[0][24][2] = " "; $Matriz_Desempates[0][24][3] = "N"; $Matriz_Desempates[0][24][4] = "N"; $Matriz_Desempates[0][24][5] = "N";
    $Matriz_Desempates[0][25][1] = "Sum of Buchholz-Tie-Breaks (all Results)";                                                     $Matriz_Desempates[0][25][2] = "N"; $Matriz_Desempates[0][25][3] = " "; $Matriz_Desempates[0][25][4] = " "; $Matriz_Desempates[0][25][5] = " ";
    $Matriz_Desempates[0][26][1] = "For imported tournaments (Tie-break 1)";                                                       $Matriz_Desempates[0][26][2] = " "; $Matriz_Desempates[0][26][3] = " "; $Matriz_Desempates[0][26][4] = " "; $Matriz_Desempates[0][26][5] = " ";
    $Matriz_Desempates[0][27][1] = "For imported tournaments (Tie-break 2)";                                                       $Matriz_Desempates[0][27][2] = " "; $Matriz_Desempates[0][27][3] = " "; $Matriz_Desempates[0][27][4] = " "; $Matriz_Desempates[0][27][5] = " ";
    $Matriz_Desempates[0][28][1] = "Buchholz-Tie-Breaks (all Results (special))";                                                  $Matriz_Desempates[0][28][2] = " "; $Matriz_Desempates[0][28][3] = " "; $Matriz_Desempates[0][28][4] = " "; $Matriz_Desempates[0][28][5] = " ";
    $Matriz_Desempates[0][29][1] = "Buchholz-Tie-Breaks (without two results=middle Tie-Breaks (special))";                        $Matriz_Desempates[0][29][2] = " "; $Matriz_Desempates[0][29][3] = " "; $Matriz_Desempates[0][29][4] = " "; $Matriz_Desempates[0][29][5] = " ";
    $Matriz_Desempates[0][30][1] = "Buchholz-Tie-Breaks (all Results with real points)";                                           $Matriz_Desempates[0][30][2] = " "; $Matriz_Desempates[0][30][3] = " "; $Matriz_Desempates[0][30][4] = " "; $Matriz_Desempates[0][30][5] = " ";
    $Matriz_Desempates[0][31][1] = "Buchholz-Tie-Breaks (without two results with real points)";                                   $Matriz_Desempates[0][31][2] = " "; $Matriz_Desempates[0][31][3] = " "; $Matriz_Desempates[0][31][4] = " "; $Matriz_Desempates[0][31][5] = " ";
    $Matriz_Desempates[0][32][1] = "For imported tournaments (Tie-break 1)";                                                       $Matriz_Desempates[0][32][2] = " "; $Matriz_Desempates[0][32][3] = " "; $Matriz_Desempates[0][32][4] = " "; $Matriz_Desempates[0][32][5] = " ";
    $Matriz_Desempates[0][33][1] = "Fide Tie-Break (no points for dropped players)";                                               $Matriz_Desempates[0][33][2] = " "; $Matriz_Desempates[0][33][3] = " "; $Matriz_Desempates[0][33][4] = " "; $Matriz_Desempates[0][33][5] = " ";
    $Matriz_Desempates[0][34][1] = "Buchholz-Tie-Breaks (without two results=middle Tie-Breaks)";                                  $Matriz_Desempates[0][34][2] = " "; $Matriz_Desempates[0][34][3] = " "; $Matriz_Desempates[0][34][4] = " "; $Matriz_Desempates[0][34][5] = "N";
    $Matriz_Desempates[0][35][1] = "FIDE-Sonneborn-Berger-Tie-Break";                                                              $Matriz_Desempates[0][35][2] = " "; $Matriz_Desempates[0][35][3] = "N"; $Matriz_Desempates[0][35][4] = "N"; $Matriz_Desempates[0][35][5] = "N";
    $Matriz_Desempates[0][36][1] = "rating average of the opponents (variabel with parameter)";                                    $Matriz_Desempates[0][36][2] = "s"; $Matriz_Desempates[0][36][3] = " "; $Matriz_Desempates[0][36][4] = " "; $Matriz_Desempates[0][36][5] = " ";
    $Matriz_Desempates[0][37][1] = "Buchholz Tie-Breaks (variabel with parameter)";                                                $Matriz_Desempates[0][37][2] = "s"; $Matriz_Desempates[0][37][3] = " "; $Matriz_Desempates[0][37][4] = " "; $Matriz_Desempates[0][37][5] = "S";
    $Matriz_Desempates[0][38][1] = "Points (game-points) + 1 point for each won match.";                                           $Matriz_Desempates[0][38][2] = " "; $Matriz_Desempates[0][38][3] = "N"; $Matriz_Desempates[0][38][4] = "N"; $Matriz_Desempates[0][38][5] = "N";
    $Matriz_Desempates[0][39][1] = "points (3 for wins, 2 for Draws, 1 for Losses, 0 for Losses forfeit)";                         $Matriz_Desempates[0][39][2] = " "; $Matriz_Desempates[0][39][3] = "N"; $Matriz_Desempates[0][39][4] = "N"; $Matriz_Desempates[0][39][5] = " ";
    $Matriz_Desempates[0][40][1] = "Matchpoints (3 for wins, 1 for Draws, 0 for Losses)";                                          $Matriz_Desempates[0][40][2] = " "; $Matriz_Desempates[0][40][3] = "N"; $Matriz_Desempates[0][40][4] = "N"; $Matriz_Desempates[0][40][5] = "N";
    $Matriz_Desempates[0][41][1] = "The better result (½ or 1) against the rating-strongest player";                               $Matriz_Desempates[0][41][2] = " "; $Matriz_Desempates[0][41][3] = " "; $Matriz_Desempates[0][41][4] = " "; $Matriz_Desempates[0][41][5] = " ";
    $Matriz_Desempates[0][42][1] = "Points (Game-points + Qualifying-points)";                                                     $Matriz_Desempates[0][42][2] = "n"; $Matriz_Desempates[0][42][3] = "N"; $Matriz_Desempates[0][42][4] = "N"; $Matriz_Desempates[0][42][5] = "N";
    $Matriz_Desempates[0][43][1] = "Play-off Points";                                                                              $Matriz_Desempates[0][43][2] = "n"; $Matriz_Desempates[0][43][3] = " "; $Matriz_Desempates[0][43][4] = " "; $Matriz_Desempates[0][43][5] = " ";
    $Matriz_Desempates[0][44][1] = "Matchpoints (variabel)";                                                                       $Matriz_Desempates[0][44][2] = "s"; $Matriz_Desempates[0][44][3] = "S"; $Matriz_Desempates[0][44][4] = "S"; $Matriz_Desempates[0][44][5] = "S";
    $Matriz_Desempates[0][45][1] = "Koya  Koya-System (Points against player with >= 50% of the points)";                          $Matriz_Desempates[0][45][2] = "N"; $Matriz_Desempates[0][45][3] = " "; $Matriz_Desempates[0][45][4] = " "; $Matriz_Desempates[0][45][5] = " ";
    $Matriz_Desempates[0][46][1] = "Points (game-points) + Matchpoints (3 for wins, 1 for Draws, 0 for Losses)";                   $Matriz_Desempates[0][46][2] = " "; $Matriz_Desempates[0][46][3] = "N"; $Matriz_Desempates[0][46][4] = "N"; $Matriz_Desempates[0][46][5] = "N";
    $Matriz_Desempates[0][47][1] = "Points (variabel)";                                                                            $Matriz_Desempates[0][47][2] = " "; $Matriz_Desempates[0][47][3] = "S"; $Matriz_Desempates[0][47][4] = "S"; $Matriz_Desempates[0][47][5] = "S";
    $Matriz_Desempates[0][48][1] = "Sum of Matchpoints (variabel)";                                                                $Matriz_Desempates[0][48][2] = " "; $Matriz_Desempates[0][48][3] = " "; $Matriz_Desempates[0][48][4] = " "; $Matriz_Desempates[0][48][5] = "S";
    $Matriz_Desempates[0][49][1] = "Olympiad Matchpoints (2,1,0) (without lowest result)";                                         $Matriz_Desempates[0][49][2] = "n"; $Matriz_Desempates[0][49][3] = " "; $Matriz_Desempates[0][49][4] = " "; $Matriz_Desempates[0][49][5] = " ";
    $Matriz_Desempates[0][50][1] = "Olympiad-Sonneborn-Berger-Tie-Break";                                                          $Matriz_Desempates[0][50][2] = "n"; $Matriz_Desempates[0][50][3] = " "; $Matriz_Desempates[0][50][4] = " "; $Matriz_Desempates[0][50][5] = " ";
    $Matriz_Desempates[0][51][1] = "For internal tests";                                                                           $Matriz_Desempates[0][51][2] = " "; $Matriz_Desempates[0][51][3] = " "; $Matriz_Desempates[0][51][4] = " "; $Matriz_Desempates[0][51][5] = " ";
    $Matriz_Desempates[0][52][1] = "Sonneborn-Berger-Tie-Break(variabel) ";                                                        $Matriz_Desempates[0][52][2] = "s"; $Matriz_Desempates[0][52][3] = " "; $Matriz_Desempates[0][52][4] = " "; $Matriz_Desempates[0][52][5] = " ";
    $Matriz_Desempates[0][53][1] = "Most black";                                                                                   $Matriz_Desempates[0][53][2] = "N"; $Matriz_Desempates[0][53][3] = " "; $Matriz_Desempates[0][53][4] = " "; $Matriz_Desempates[0][53][5] = " ";
    $Matriz_Desempates[0][54][1] = "Recursive Ratingperformance";                                                                  $Matriz_Desempates[0][54][2] = "s"; $Matriz_Desempates[0][54][3] = "S"; $Matriz_Desempates[0][54][4] = " "; $Matriz_Desempates[0][54][5] = " ";
    $Matriz_Desempates[0][55][1] = "Average Recursive Performance of Opponents";                                                   $Matriz_Desempates[0][55][2] = "s"; $Matriz_Desempates[0][55][3] = "S"; $Matriz_Desempates[0][55][4] = " "; $Matriz_Desempates[0][55][5] = " ";
    $Matriz_Desempates[0][56][1] = "Olympiad Khanty Mansysk Matchpoints (2,1,0) (without lowest result)";                          $Matriz_Desempates[0][56][2] = " "; $Matriz_Desempates[0][56][3] = " "; $Matriz_Desempates[0][56][4] = " "; $Matriz_Desempates[0][56][5] = "N";
    $Matriz_Desempates[0][57][1] = "Olympiad Khanty Mansysk-Sonneborn-Berger-Tie-Break";                                           $Matriz_Desempates[0][57][2] = " "; $Matriz_Desempates[0][57][3] = " "; $Matriz_Desempates[0][57][4] = " "; $Matriz_Desempates[0][57][5] = "N";
    $Matriz_Desempates[0][58][1] = "Rtg Sum (without lowest rtg) or Progressive Score (especially for the Youth WCC 2011)";        $Matriz_Desempates[0][58][2] = "n"; $Matriz_Desempates[0][58][3] = " "; $Matriz_Desempates[0][58][4] = " "; $Matriz_Desempates[0][58][5] = " ";
    $Matriz_Desempates[0][59][1] = "Rating Performance without two results (EM 2011) especially for the Single-EM 2011 in France"; $Matriz_Desempates[0][59][2] = "n"; $Matriz_Desempates[0][59][3] = " "; $Matriz_Desempates[0][59][4] = " "; $Matriz_Desempates[0][59][5] = " ";
    $Matriz_Desempates[0][60][1] = "Performance (variable with parameter)";                                                        $Matriz_Desempates[0][60][2] = "s"; $Matriz_Desempates[0][60][3] = "S"; $Matriz_Desempates[0][60][4] = " "; $Matriz_Desempates[0][60][5] = " ";
    $Matriz_Desempates[0][61][1] = "Arranz System (Win:1 / Draw:0.6 black, 0.4 white / Lost:0)";                                   $Matriz_Desempates[0][61][2] = "n"; $Matriz_Desempates[0][61][3] = " "; $Matriz_Desempates[0][61][4] = " "; $Matriz_Desempates[0][61][5] = " ";
				
    $Matriz_Desempates[1][0][1] = " "; $Matriz_Desempates[1][0][2] = "N"; $Matriz_Desempates[1][0][3] = "N"; $Matriz_Desempates[1][0][4] = "N"; $Matriz_Desempates[1][0][5] = "N";
    $Matriz_Desempates[1][1][1] = "Pts.  points (game-points)";                                                                    $Matriz_Desempates[1][ 1][2] = "N"; $Matriz_Desempates[1][1][3] = "N"; $Matriz_Desempates[1][1][4] = "N"; $Matriz_Desempates[1][1][5] = "N";
    $Matriz_Desempates[1][2][1] = "Buchholz Tie-Breaks (all Results)";                                                             $Matriz_Desempates[1][ 2][2] = " "; $Matriz_Desempates[1][2][3] = " "; $Matriz_Desempates[1][2][4] = " "; $Matriz_Desempates[1][2][5] = " ";
    $Matriz_Desempates[1][3][1] = "Buchholzwertung (1 Streichresultat)";                                                           $Matriz_Desempates[1][ 3][2] = " "; $Matriz_Desempates[1][3][3] = " "; $Matriz_Desempates[1][3][4] = " "; $Matriz_Desempates[1][3][5] = " ";
    $Matriz_Desempates[1][4][1] = "Buchholz Tie-Breaks (without two results=middle Tie-Breaks)";                                   $Matriz_Desempates[1][ 4][2] = " "; $Matriz_Desempates[1][4][3] = " "; $Matriz_Desempates[1][4][4] = " "; $Matriz_Desempates[1][4][5] = " ";
    $Matriz_Desempates[1][5][1] = "Manually input in field rankcorr. in player-dialog";                                            $Matriz_Desempates[1][ 5][2] = "N"; $Matriz_Desempates[1][5][3] = " "; $Matriz_Desempates[1][5][4] = " "; $Matriz_Desempates[1][5][5] = " ";
    $Matriz_Desempates[1][6][1] = "Manually input in field rankcorr. in team-dialog";                                              $Matriz_Desempates[1][ 6][2] = " "; $Matriz_Desempates[1][6][3] = "N"; $Matriz_Desempates[1][6][4] = "N"; $Matriz_Desempates[1][6][5] = "N";
    $Matriz_Desempates[1][7][1] = "Sonneborn-Berger-Tie-Break (with real points)";                                                 $Matriz_Desempates[1][ 7][2] = "n"; $Matriz_Desempates[1][7][3] = "N"; $Matriz_Desempates[1][7][4] = "N"; $Matriz_Desempates[1][7][5] = "N";
    $Matriz_Desempates[1][8][1] = "Escore Acumulado";                                                                              $Matriz_Desempates[1][ 8][2] = "N"; $Matriz_Desempates[1][8][3] = " "; $Matriz_Desempates[1][8][4] = " "; $Matriz_Desempates[1][8][5] = " ";
    $Matriz_Desempates[1][9][1] = "Escore Acumulado (Refinado)";                                                                   $Matriz_Desempates[1][ 9][2] = "N"; $Matriz_Desempates[1][9][3] = " "; $Matriz_Desempates[1][9][4] = " "; $Matriz_Desempates[1][9][5] = " ";
    $Matriz_Desempates[1][10][1] = "rating average of the opponents";                                                              $Matriz_Desempates[1][10][2] = " "; $Matriz_Desempates[1][10][3] = " "; $Matriz_Desempates[1][10][4] = " "; $Matriz_Desempates[1][10][5] = " ";
    $Matriz_Desempates[1][11][1] = "Confronto Direto";                                                                             $Matriz_Desempates[1][11][2] = "N"; $Matriz_Desempates[1][11][3] = "N"; $Matriz_Desempates[1][11][4] = " "; $Matriz_Desempates[1][11][5] = " ";
    $Matriz_Desempates[1][12][1] = "Maior número de vitórias";                                                                     $Matriz_Desempates[1][12][2] = "N"; $Matriz_Desempates[1][12][3] = "N"; $Matriz_Desempates[1][12][4] = " "; $Matriz_Desempates[1][12][5] = " ";
    $Matriz_Desempates[1][13][1] = "Matchpoints (2 for wins, 1 for Draws, 0 for Losses)";                                          $Matriz_Desempates[1][13][2] = "n"; $Matriz_Desempates[1][13][3] = "N"; $Matriz_Desempates[1][13][4] = "N"; $Matriz_Desempates[1][13][5] = "N";
    $Matriz_Desempates[1][14][1] = "The results of the teams in then same point group according to Matchpoints";                   $Matriz_Desempates[1][14][2] = "n"; $Matriz_Desempates[1][14][3] = "N"; $Matriz_Desempates[1][14][4] = "N"; $Matriz_Desempates[1][14][5] = "N";
    $Matriz_Desempates[1][15][1] = "Board Tie-Breaks of the whole tournament";                                                     $Matriz_Desempates[1][15][2] = "n"; $Matriz_Desempates[1][15][3] = "N"; $Matriz_Desempates[1][15][4] = "N"; $Matriz_Desempates[1][15][5] = "N";
    $Matriz_Desempates[1][16][1] = "Buchholz Tie-Breaks (sum of team-points of the opponents and own points)";                     $Matriz_Desempates[1][16][2] = " "; $Matriz_Desempates[1][16][3] = " "; $Matriz_Desempates[1][16][4] = " "; $Matriz_Desempates[1][16][5] = "N";
    $Matriz_Desempates[1][17][1] = "Buchholz Tie-Breaks (with the real points)";                                                   $Matriz_Desempates[1][17][2] = " "; $Matriz_Desempates[1][17][3] = " "; $Matriz_Desempates[1][17][4] = " "; $Matriz_Desempates[1][17][5] = " ";
    $Matriz_Desempates[1][18][1] = "Carasaxa Tie-Breaks";                                                                          $Matriz_Desempates[1][18][2] = " "; $Matriz_Desempates[1][18][3] = " "; $Matriz_Desempates[1][18][4] = " "; $Matriz_Desempates[1][18][5] = " ";
    $Matriz_Desempates[1][19][1] = "Sonneborn-Berger Tie-Break (with modified points, analogous to Buchholz Tie-Break)";           $Matriz_Desempates[1][19][2] = " "; $Matriz_Desempates[1][19][3] = " "; $Matriz_Desempates[1][19][4] = " "; $Matriz_Desempates[1][19][5] = " ";
    $Matriz_Desempates[1][20][1] = "rating average of the opponents (without one result)";                                         $Matriz_Desempates[1][20][2] = " "; $Matriz_Desempates[1][20][3] = " "; $Matriz_Desempates[1][20][4] = " "; $Matriz_Desempates[1][20][5] = " ";
    $Matriz_Desempates[1][21][1] = "rating performance";                                                                           $Matriz_Desempates[1][21][2] = " "; $Matriz_Desempates[1][21][3] = " "; $Matriz_Desempates[1][21][4] = " "; $Matriz_Desempates[1][21][5] = " ";
    $Matriz_Desempates[1][22][1] = "Buchholz Tie-Breaks (sum of team-points of the opponents)";                                    $Matriz_Desempates[1][22][2] = " "; $Matriz_Desempates[1][22][3] = " "; $Matriz_Desempates[1][22][4] = " "; $Matriz_Desempates[1][22][5] = "N";
    $Matriz_Desempates[1][23][1] = "Sum of the ratings of the opponents (whithout one result)";                                    $Matriz_Desempates[1][23][2] = "n"; $Matriz_Desempates[1][23][3] = " "; $Matriz_Desempates[1][23][4] = " "; $Matriz_Desempates[1][23][5] = " ";
    $Matriz_Desempates[1][24][1] = "The BSV-Board-Tie-Break";                                                                      $Matriz_Desempates[1][24][2] = " "; $Matriz_Desempates[1][24][3] = "N"; $Matriz_Desempates[1][24][4] = "N"; $Matriz_Desempates[1][24][5] = "N";
    $Matriz_Desempates[1][25][1] = "Sum of Buchholz-Tie-Breaks (all Results)";                                                     $Matriz_Desempates[1][25][2] = "N"; $Matriz_Desempates[1][25][3] = " "; $Matriz_Desempates[1][25][4] = " "; $Matriz_Desempates[1][25][5] = " ";
    $Matriz_Desempates[1][26][1] = "For imported tournaments (Tie-break 1)";                                                       $Matriz_Desempates[1][26][2] = " "; $Matriz_Desempates[1][26][3] = " "; $Matriz_Desempates[1][26][4] = " "; $Matriz_Desempates[1][26][5] = " ";
    $Matriz_Desempates[1][27][1] = "For imported tournaments (Tie-break 2)";                                                       $Matriz_Desempates[1][27][2] = " "; $Matriz_Desempates[1][27][3] = " "; $Matriz_Desempates[1][27][4] = " "; $Matriz_Desempates[1][27][5] = " ";
    $Matriz_Desempates[1][28][1] = "Buchholz-Tie-Breaks (all Results (special))";                                                  $Matriz_Desempates[1][28][2] = " "; $Matriz_Desempates[1][28][3] = " "; $Matriz_Desempates[1][28][4] = " "; $Matriz_Desempates[1][28][5] = " ";
    $Matriz_Desempates[1][29][1] = "Buchholz-Tie-Breaks (without two results=middle Tie-Breaks (special))";                        $Matriz_Desempates[1][29][2] = " "; $Matriz_Desempates[1][29][3] = " "; $Matriz_Desempates[1][29][4] = " "; $Matriz_Desempates[1][29][5] = " ";
    $Matriz_Desempates[1][30][1] = "Buchholz-Tie-Breaks (all Results with real points)";                                           $Matriz_Desempates[1][30][2] = " "; $Matriz_Desempates[1][30][3] = " "; $Matriz_Desempates[1][30][4] = " "; $Matriz_Desempates[1][30][5] = " ";
    $Matriz_Desempates[1][31][1] = "Buchholz-Tie-Breaks (without two results with real points)";                                   $Matriz_Desempates[1][31][2] = " "; $Matriz_Desempates[1][31][3] = " "; $Matriz_Desempates[1][31][4] = " "; $Matriz_Desempates[1][31][5] = " ";
    $Matriz_Desempates[1][32][1] = "For imported tournaments (Tie-break 1)";                                                       $Matriz_Desempates[1][32][2] = " "; $Matriz_Desempates[1][32][3] = " "; $Matriz_Desempates[1][32][4] = " "; $Matriz_Desempates[1][32][5] = " ";
    $Matriz_Desempates[1][33][1] = "Fide Tie-Break (no points for dropped players)";                                               $Matriz_Desempates[1][33][2] = " "; $Matriz_Desempates[1][33][3] = " "; $Matriz_Desempates[1][33][4] = " "; $Matriz_Desempates[1][33][5] = " ";
    $Matriz_Desempates[1][34][1] = "Buchholz-Tie-Breaks (without two results=middle Tie-Breaks)";                                  $Matriz_Desempates[1][34][2] = " "; $Matriz_Desempates[1][34][3] = " "; $Matriz_Desempates[1][34][4] = " "; $Matriz_Desempates[1][34][5] = "N";
    $Matriz_Desempates[1][35][1] = "FIDE-Sonneborn-Berger-Tie-Break";                                                              $Matriz_Desempates[1][35][2] = " "; $Matriz_Desempates[1][35][3] = "N"; $Matriz_Desempates[1][35][4] = "N"; $Matriz_Desempates[1][35][5] = "N";
    $Matriz_Desempates[1][36][1] = "rating average of the opponents (variabel with parameter)";                                    $Matriz_Desempates[1][36][2] = "s"; $Matriz_Desempates[1][36][3] = " "; $Matriz_Desempates[1][36][4] = " "; $Matriz_Desempates[1][36][5] = " ";
    $Matriz_Desempates[1][37][1] = "Buchholz (variável com parâmetros)";                                                           $Matriz_Desempates[1][37][2] = "s"; $Matriz_Desempates[1][37][3] = " "; $Matriz_Desempates[1][37][4] = " "; $Matriz_Desempates[1][37][5] = "S";
    $Matriz_Desempates[1][38][1] = "Points (game-points) + 1 point for each won match.";                                           $Matriz_Desempates[1][38][2] = " "; $Matriz_Desempates[1][38][3] = "N"; $Matriz_Desempates[1][38][4] = "N"; $Matriz_Desempates[1][38][5] = "N";
    $Matriz_Desempates[1][39][1] = "points (3 for wins, 2 for Draws, 1 for Losses, 0 for Losses forfeit)";                         $Matriz_Desempates[1][39][2] = " "; $Matriz_Desempates[1][39][3] = "N"; $Matriz_Desempates[1][39][4] = "N"; $Matriz_Desempates[1][39][5] = " ";
    $Matriz_Desempates[1][40][1] = "Matchpoints (3 for wins, 1 for Draws, 0 for Losses)";                                          $Matriz_Desempates[1][40][2] = " "; $Matriz_Desempates[1][40][3] = "N"; $Matriz_Desempates[1][40][4] = "N"; $Matriz_Desempates[1][40][5] = "N";
    $Matriz_Desempates[1][41][1] = "The better result (½ or 1) against the rating-strongest player";                               $Matriz_Desempates[1][41][2] = " "; $Matriz_Desempates[1][41][3] = " "; $Matriz_Desempates[1][41][4] = " "; $Matriz_Desempates[1][41][5] = " ";
    $Matriz_Desempates[1][42][1] = "Points (Game-points + Qualifying-points)";                                                     $Matriz_Desempates[1][42][2] = "n"; $Matriz_Desempates[1][42][3] = "N"; $Matriz_Desempates[1][42][4] = "N"; $Matriz_Desempates[1][42][5] = "N";
    $Matriz_Desempates[1][43][1] = "Play-off Points";                                                                              $Matriz_Desempates[1][43][2] = "n"; $Matriz_Desempates[1][43][3] = " "; $Matriz_Desempates[1][43][4] = " "; $Matriz_Desempates[1][43][5] = " ";
    $Matriz_Desempates[1][44][1] = "Matchpoints (variabel)";                                                                       $Matriz_Desempates[1][44][2] = "s"; $Matriz_Desempates[1][44][3] = "S"; $Matriz_Desempates[1][44][4] = "S"; $Matriz_Desempates[1][44][5] = "S";
    $Matriz_Desempates[1][45][1] = "Koya  Koya-System (Points against player with >= 50% of the points)";                          $Matriz_Desempates[1][45][2] = "N"; $Matriz_Desempates[1][45][3] = " "; $Matriz_Desempates[1][45][4] = " "; $Matriz_Desempates[1][45][5] = " ";
    $Matriz_Desempates[1][46][1] = "Points (game-points) + Matchpoints (3 for wins, 1 for Draws, 0 for Losses)";                   $Matriz_Desempates[1][46][2] = " "; $Matriz_Desempates[1][46][3] = "N"; $Matriz_Desempates[1][46][4] = "N"; $Matriz_Desempates[1][46][5] = "N";
    $Matriz_Desempates[1][47][1] = "Points (variabel)";                                                                            $Matriz_Desempates[1][47][2] = " "; $Matriz_Desempates[1][47][3] = "S"; $Matriz_Desempates[1][47][4] = "S"; $Matriz_Desempates[1][47][5] = "S";
    $Matriz_Desempates[1][48][1] = "Sum of Matchpoints (variabel)";                                                                $Matriz_Desempates[1][48][2] = " "; $Matriz_Desempates[1][48][3] = " "; $Matriz_Desempates[1][48][4] = " "; $Matriz_Desempates[1][48][5] = "S";
    $Matriz_Desempates[1][49][1] = "Olympiad Matchpoints (2,1,0) (without lowest result)";                                         $Matriz_Desempates[1][49][2] = "n"; $Matriz_Desempates[1][49][3] = " "; $Matriz_Desempates[1][49][4] = " "; $Matriz_Desempates[1][49][5] = " ";
    $Matriz_Desempates[1][50][1] = "Olympiad-Sonneborn-Berger-Tie-Break";                                                          $Matriz_Desempates[1][50][2] = "n"; $Matriz_Desempates[1][50][3] = " "; $Matriz_Desempates[1][50][4] = " "; $Matriz_Desempates[1][50][5] = " ";
    $Matriz_Desempates[1][51][1] = "For internal tests";                                                                           $Matriz_Desempates[1][51][2] = " "; $Matriz_Desempates[1][51][3] = " "; $Matriz_Desempates[1][51][4] = " "; $Matriz_Desempates[1][51][5] = " ";
    $Matriz_Desempates[1][52][1] = "Sonneborn-Berger (variável)";                                                                  $Matriz_Desempates[1][52][2] = "s"; $Matriz_Desempates[1][52][3] = " "; $Matriz_Desempates[1][52][4] = " "; $Matriz_Desempates[1][52][5] = " ";
    $Matriz_Desempates[1][53][1] = "Most black";                                                                                   $Matriz_Desempates[1][53][2] = "N"; $Matriz_Desempates[1][53][3] = " "; $Matriz_Desempates[1][53][4] = " "; $Matriz_Desempates[1][53][5] = " ";
    $Matriz_Desempates[1][54][1] = "Recursive Ratingperformance";                                                                  $Matriz_Desempates[1][54][2] = "s"; $Matriz_Desempates[1][54][3] = "S"; $Matriz_Desempates[1][54][4] = " "; $Matriz_Desempates[1][54][5] = " ";
    $Matriz_Desempates[1][55][1] = "Average Recursive Performance of Opponents";                                                   $Matriz_Desempates[1][55][2] = "s"; $Matriz_Desempates[1][55][3] = "S"; $Matriz_Desempates[1][55][4] = " "; $Matriz_Desempates[1][55][5] = " ";
    $Matriz_Desempates[1][56][1] = "Olympiad Khanty Mansysk Matchpoints (2,1,0) (without lowest result)";                          $Matriz_Desempates[1][56][2] = " "; $Matriz_Desempates[1][56][3] = " "; $Matriz_Desempates[1][56][4] = " "; $Matriz_Desempates[1][56][5] = "N";
    $Matriz_Desempates[1][57][1] = "Olympiad Khanty Mansysk-Sonneborn-Berger-Tie-Break";                                           $Matriz_Desempates[1][57][2] = " "; $Matriz_Desempates[1][57][3] = " "; $Matriz_Desempates[1][57][4] = " "; $Matriz_Desempates[1][57][5] = "N";
    $Matriz_Desempates[1][58][1] = "Rtg Sum (without lowest rtg) or Progressive Score (especially for the Youth WCC 2011)";        $Matriz_Desempates[1][58][2] = "n"; $Matriz_Desempates[1][58][3] = " "; $Matriz_Desempates[1][58][4] = " "; $Matriz_Desempates[1][58][5] = " ";
    $Matriz_Desempates[1][59][1] = "Rating Performance without two results (EM 2011) especially for the Single-EM 2011 in France"; $Matriz_Desempates[1][59][2] = "n"; $Matriz_Desempates[1][59][3] = " "; $Matriz_Desempates[1][59][4] = " "; $Matriz_Desempates[1][59][5] = " ";
    $Matriz_Desempates[1][60][1] = "Performance (variable with parameter)";                                                        $Matriz_Desempates[1][60][2] = "s"; $Matriz_Desempates[1][60][3] = "S"; $Matriz_Desempates[1][60][4] = " "; $Matriz_Desempates[1][60][5] = " ";
    $Matriz_Desempates[1][61][1] = "Arranz System (Win:1 / Draw:0.6 black, 0.4 white / Lost:0)";                                   $Matriz_Desempates[1][61][2] = "n"; $Matriz_Desempates[1][61][3] = " "; $Matriz_Desempates[1][61][4] = " "; $Matriz_Desempates[1][61][5] = " ";
			}
		
  function Montar_Matriz_Resultados()
		 {
			 global $Resultados;
        $Resultados[0] = " x ";
        $Resultados[1] = "1x0";
        $Resultados[2] = "½x½";
        $Resultados[3] = "0x1";
        $Resultados[4] = "+x-";
        $Resultados[5] = "-x+";
        $Resultados[6] = "-x-";
        $Resultados[7] = "AxA";
        $Resultados[8] = "?x?";
        $Resultados[9] = "1x-";
        $Resultados[10] = "0x0";
        $Resultados[11] = "0x½";
        $Resultados[12] = "½x0";
								
        $Resultados[13] = "1x-";  // *** Levy ***
        $Resultados[14] = "½x-";  // *** Levy ***
        $Resultados[15] = "-x-";  // *** Levy ***

        //$Resultados[13] = " 1   13";  // *** Levy ***
        //$Resultados[14] = " ½   14";  // *** Levy ***
        //$Resultados[15] = " 0   15";  // *** Levy ***

        //$Resultados[21] = " 1 x 0 ";  // *** Continental ***
								
			}
		
  function CalcularDesempates()
			{
				//global $PtsDesemp,$Desempate,$DigFide9,$Mult,$QtJogadores,$NomeJogador,$Status,$NumRodadas,$PontosJogador,$QtDesempates,$JogNegras,$NumVitJog,$CrossTable,$PontosBH25,$PontosSB,$PontosKoya;
				global $PtsDesemp,$Desempate,$Corte,$QtPior,$QtMelhor,$PNJ,$ExpPNJ,$DigFide9,$Mult,$QtJogadores,$DM,$NomeJogador,$Status,$NumRodadas,$PontosJogador,$QtDesempates,$JogNegras,$NumVitJog,$CrossTable;
				
				for($i=1;$i<=$QtDesempates;$i++)
					{
						switch ($Desempate[$i])
							{
								case 1:
									for($itb=1;$itb<=$QtJogadores;$itb++)
										{$PtsDesemp[$i+1][$itb] = $PontosJogador[$itb];}
									break;
								
								case 5:
									for($itb=1;$itb<=$QtJogadores;$itb++)
										{$PtsDesemp[$i+1][$itb] = $DM[$itb];}
									break;
								
								case 8:		// 8 - Fide Tye-Break - (Progressive Score / Cumulative Score)
									for($j=1;$j<=$QtJogadores;$j++){$PontosJogRod[$j]=0;$PontosProgrScore[$j]=0;}
									for($j=1;$j<=$QtJogadores;$j++)
										{
											for($r=1;$r<=$NumRodadas;$r++)
												{
													switch(''.$CrossTable[$j][$r][3].'')
														{
															case '1':
																//if($Status[$j]=='0' && $Status[$CrossTable[$j][$r][1]]=='0')
																$PontosJogRod[$j] = $PontosJogRod[$j] + 1;
																$PontosProgrScore[$j] = $PontosProgrScore[$j] + $PontosJogRod[$j];
																//$yy = 'Jogador: '.$j.'Rodada: '.$r.' - '.$PontosProgrScore[$j].' - '.$CrossTable[$j][$r][3];
																//if($j==10)
																//	{echo "<script language='javascript' type='text/javascript'>alert('".$yy."');</script>";}
																break;
															case '½':
																$PontosJogRod[$j] = $PontosJogRod[$j] + 0.5;
																$PontosProgrScore[$j] = $PontosProgrScore[$j] + $PontosJogRod[$j];
																	
																break;
															case '+':
																$PontosJogRod[$j] = $PontosJogRod[$j] + 1;
																$PontosProgrScore[$j] = $PontosProgrScore[$j] + $PontosJogRod[$j];
																break;
															case '0':														
																//$yy = 'Jogador: '.$j.'Rodada: '.$r.' - '.$PontosProgrScore[$j].' - '.$CrossTable[$j][$r][3];
																//if($j==6)
																//	{echo "<script language='javascript' type='text/javascript'>alert('".$yy."');</script>";}
																$PontosJogRod[$j] = $PontosJogRod[$j];
																$PontosProgrScore[$j] = $PontosProgrScore[$j] + $PontosJogRod[$j];
																break;
															case '-':
																$PontosJogRod[$j] = $PontosJogRod[$j];
																$PontosProgrScore[$j] = $PontosProgrScore[$j] + $PontosJogRod[$j];
																break;
															default:
																break;
														}
												}
											for($jj=1;$jj<=$QtJogadores;$jj++)
												{
													if($PtsDesemp[0][$jj]==$j)
														{$PtsDesemp[$i+1][$jj] = $PontosProgrScore[$j];}
												}
										}
									break;
								
								case 9:		// 9 - Fide Tye-Break fine - (Progressive Score / Cumulative Score - fine)
									for($j=1;$j<=$QtJogadores;$j++){$PontosJogRod[$j]=0;$PontosProgrScore[$j]=0;}

									for($j=1;$j<=$QtJogadores;$j++)
										{
											for($r=1;$r<=$NumRodadas;$r++)
												{
													switch(''.$CrossTable[$j][$r][3].'')
														{
															case '1':
																//if($Status[$j]=='0' && $Status[$CrossTable[$j][$r][1]]=='0')
																$PontosJogRod[$j] = $PontosJogRod[$j] + 1;
																$PontosProgrScore[$j] = $PontosProgrScore[$j] + $PontosJogRod[$j];
																//$yy = 'Jogador: '.$j.'Rodada: '.$r.' - '.$PontosProgrScore[$j].' - '.$CrossTable[$j][$r][3];
																//if($j==10)
																//	{echo "<script language='javascript' type='text/javascript'>alert('".$yy."');</script>";}
																break;
															case '½':
																$PontosJogRod[$j] = $PontosJogRod[$j] + 0.5;
																$PontosProgrScore[$j] = $PontosProgrScore[$j] + $PontosJogRod[$j];
																	
																break;
															case '+':
																//$PontosJogRod[$j] = $PontosJogRod[$j] + 1;
																//$PontosProgrScore[$j] = $PontosProgrScore[$j] + $PontosJogRod[$j];
																break;
															case '0':														
																//$yy = 'Jogador: '.$j.'Rodada: '.$r.' - '.$PontosProgrScore[$j].' - '.$CrossTable[$j][$r][3];
																//if($j==6)
																//	{echo "<script language='javascript' type='text/javascript'>alert('".$yy."');</script>";}
																$PontosJogRod[$j] = $PontosJogRod[$j];
																$PontosProgrScore[$j] = $PontosProgrScore[$j] + $PontosJogRod[$j];
																break;
															case '-':
																$PontosJogRod[$j] = $PontosJogRod[$j];
																$PontosProgrScore[$j] = $PontosProgrScore[$j] + $PontosJogRod[$j];
																break;
															default:
																break;
														}
												}
										}
										
									for($j=1;$j<=$QtJogadores;$j++){$PontosJogRod[$j]=0;}
									for($j=1;$j<=$QtJogadores;$j++)
										{
											$strFide9[$j]='';
											for($r=1;$r<$NumRodadas;$r++)		// **** NumRodadas-1 ****
												{
													switch(''.$CrossTable[$j][$r][3].'')
														{
															case '1':
																//if($Status[$j]=='0' && $Status[$CrossTable[$j][$r][1]]=='0')
																$PontosJogRod[$j] = $PontosJogRod[$j] + 1;
																$PontosProgrScore[$j] = $PontosProgrScore[$j] - $PontosJogRod[$j];
																//$yy = 'Jogador: '.$j.'Rodada: '.$r.' - '.$PontosProgrScore[$j].' - '.$CrossTable[$j][$r][3];
																//if($j==10)
																//	{echo "<script language='javascript' type='text/javascript'>alert('".$yy."');</script>";}
																break;
															case '½':
																$PontosJogRod[$j] = $PontosJogRod[$j] + 0.5;
																$PontosProgrScore[$j] = $PontosProgrScore[$j] - $PontosJogRod[$j];
																	
																break;
															case '+':
																//$PontosJogRod[$j] = $PontosJogRod[$j] + 1;
																//$PontosProgrScore[$j] = $PontosProgrScore[$j] + $PontosJogRod[$j];
																break;
															case '0':														
																//$yy = 'Jogador: '.$j.'Rodada: '.$r.' - '.$PontosProgrScore[$j].' - '.$CrossTable[$j][$r][3];
																//if($j==6)
																//	{echo "<script language='javascript' type='text/javascript'>alert('".$yy."');</script>";}
																$PontosJogRod[$j] = $PontosJogRod[$j];
																$PontosProgrScore[$j] = $PontosProgrScore[$j] - $PontosJogRod[$j];
																break;
															case '-':
																$PontosJogRod[$j] = $PontosJogRod[$j];
																$PontosProgrScore[$j] = $PontosProgrScore[$j] - $PontosJogRod[$j];
																break;
															default:
																break;
														}
													
											  //if($NumRodadas<4){$DigFide9=2;} elseif($NumRodadas>3 and $NumRodadas<14){$DigFide9=3;} elseif($NumRodadas>13){$DigFide9=4;}
													//$Mult=pow(10,($DigFide9-2));
													//$strFide9[$j]=$strFide9[$j] . substr('000'.(string)($PontosProgrScore[$j]*$Mult),-1*$DigFide9) . ' ';
													$strFide9[$j]=$strFide9[$j] . substr('000'.(string)($PontosProgrScore[$j]*$Mult),-1*$DigFide9);
														
												}
											//trim($strFide9[$j]);
											for($jj=1;$jj<=$QtJogadores;$jj++)
												{
													if($PtsDesemp[0][$jj]==$j)
														{$PtsDesemp[$i+1][$jj] = $strFide9[$j];}
												}
										}
									
									break;
									
								case 11:
									CalcularConfrontoDireto($i+1);
									break;
								
								case 12:
									for($itb=1;$itb<=$QtJogadores;$itb++)
										{
											for($ii=1;$ii<=$QtJogadores;$ii++)
												{
													if($PtsDesemp[0][$ii]==$itb)
														{$PtsDesemp[$i+1][$ii] = $NumVitJog[$itb];}
												}
										}
									break;
								
								// --------------------------------------------------------
								case 25:		// 25-Sum of Buchholz-Tie-Breaks (all Results)
									
									for($j=1;$j<=$QtJogadores;$j++)
										{
											for($r=1;$r<=$NumRodadas;$r++)
												{
													//if($Status[$j]=='0' && $Status[$CrossTable[$j][$r][1]]=='0')
													//	{
															$adver=$CrossTable[$j][$r][1];
															for($r1=1;$r1<=$NumRodadas;$r1++)
																{
																	$adver1=$CrossTable[$adver][$r1][1];
																	for($r2=1;$r2<=$NumRodadas;$r2++)
																		{
																			switch(''.$CrossTable[$adver1][$r2][3].'')
																				{
																					case '1':
																						//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																						if(''.$CrossTable[$adver1][$r2][1].''=='-')
																							{$PontosBH25[$j] = $PontosBH25[$j] + 0.5;}
																						else
																							{$PontosBH25[$j] = $PontosBH25[$j] + 1;}
																						break;
																					case "½":
																						//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																							{$PontosBH25[$j] = $PontosBH25[$j] + 0.5;}
																						break;
																					case "+":
																						//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																							{$PontosBH25[$j] = $PontosBH25[$j] + 0.5;}
																							//{$PontosBH25[$j] = $PontosBH25[$j] + 1;}
																						break;
																					case "-":
																						//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																							{$PontosBH25[$j] = $PontosBH25[$j] + 0.5;}
																							//{$PontosBH25[$j] = $PontosBH25[$j] + 0;}
																						break;
																					case "0":														
																						if(''.$CrossTable[$adver1][$r2][1].''=='-')
																							{$PontosBH25[$j] = $PontosBH25[$j] + 0.5;}
																						else
																							{$PontosBH25[$j] = $PontosBH25[$j] + 0;}
																						break;
																					default:
																						break;
																				}
																		}
																}
													// }
												}
											for($jj=1;$jj<=$QtJogadores;$jj++)
												{
													if($PtsDesemp[0][$jj]==$j)
														{$PtsDesemp[$i+1][$jj] = $PontosBH25[$j];}
												}
										}
									break;
								
// ***************************************************************								
								case 37:		// 37-Buchholz Tie-Breaks (variabel with parameter)
									//$Matriz_Desempates[$Idioma][$Desempate[]][2], $Corte[], $QtPior[], $QtMelhor[], $Adic[], $PNJ_Adic[], $ExpAdic[], $ExpPNJ[]
									
									//break;   									// ********* ********* ********* *********
									for($j=1;$j<=$QtJogadores;$j++)
										{
											$PontosBH[$i][$j] = 0;
											
											//********************************************
											//echo "<script language='javascript' type='text/javascript'>alert('***Corte: ".$i.'-'.$Corte[$i]."');</script>";
											if($Corte[$i]>0)
												{
													unset($lista_cortes_M);unset($lista_cortes_P);
													/* ordena resultados dos adversários e marca os cortes */
													for($r=1;$r<=$NumRodadas;$r++)
														{
															$PontosAdver[0][$r-1] = $r;
															if(''.$CrossTable[$j][$r][1].''=='-')
																{
																	$PontosAdver[1][$r-1] = 0;
																	$PontosAdver[2][$r-1] = $NumRodadas/2;
																}
															else
																{
																	$PontosAdver[1][$r-1] = $CrossTable[$j][$r][1];
																	if($Status[''.$CrossTable[$j][$r][1].'']=='1')
																		{$PontosAdver[2][$r-1] = 0;}
																	else
																		{
																			/*
																				switch(''.$CrossTable[$j][$r][3].'')
																					{
																						case '1':
																							$PontosAdver[2][$r-1] = $PontosJogador[$CrossTable[$j][$r][1]];
																							break;
																						case '½':
																							//$PontosAdver[2][$r-1] = $PontosJogador[$CrossTable[$j][$r][1]]/2;
																							$PontosAdver[2][$r-1] = $PontosJogador[$CrossTable[$j][$r][1]];
																							break;
																						default:
																							$PontosAdver[2][$r-1] = 0;
																							break;
																					}
																			*/
																			$PontosAdver[2][$r-1] = $PontosJogador[$CrossTable[$j][$r][1]];
																		}
																}
														}
													
													/*
														for($zz=0;$zz<$NumRodadas;$zz++)
															{echo $j . ' * ' . $PontosAdver[0][$zz].' - '.$PontosAdver[1][$zz].' - '.$PontosAdver[2][$zz].'<br>';}
														echo '<br>';
													*/
													array_multisort($PontosAdver[2],SORT_DESC,SORT_NUMERIC,
																													$PontosAdver[1],SORT_DESC,SORT_NUMERIC,
																													$PontosAdver[0]
																												);
											  //echo "<script language='javascript' type='text/javascript'>alert('QtMelhor: ".$QtMelhor[$i]. ' - QtPior: '.$QtPior[$i]."');</script>";													
													
														//for($zz=0;$zz<$NumRodadas;$zz++)
														//	{echo $j . ' * ' . $PontosAdver[0][$zz].' - '.$PontosAdver[1][$zz].' - '.$PontosAdver[2][$zz].'<br>';}
														//echo '<br>';													
													
													
													for($jz=0;$jz<$QtMelhor[$i];$jz++)
														{$lista_cortes_M[$jz] = $PontosAdver[0][$jz];}
															//echo "<script language='javascript' type='text/javascript'>alert('Rodas: ".$NumRodadas-$QtPior."');</script>";
													for($jz=$NumRodadas;$jz>$NumRodadas-$QtPior[$i];$jz--)
														{
															//echo "<script language='javascript' type='text/javascript'>alert('$jz: ".$jz."');</script>";
															$lista_cortes_P[$NumRodadas-$jz] = $PontosAdver[0][$jz-1];
														}
												}
											else
												{unset($lista_cortes_M);unset($lista_cortes_P);}
											
											//$QtCortesM=count($lista_cortes_M);		// ***** 2019/10/28 *****
											if(!isset($lista_cortes_M))							// ***** 2019/10/28 *****
												{$QtCortesM=0;}												// ***** 2019/10/28 *****
											else																		// ***** 2019/10/28 *****
												{$QtCortesM=count($lista_cortes_M);}	// ***** 2019/10/28 *****
											
											//$QtCortesP=count($lista_cortes_P);		// ***** 2019/10/28 *****
											if(!isset($lista_cortes_P))							// ***** 2019/10/28 *****
												{$QtCortesP=0;}												// ***** 2019/10/28 *****
											else																		// ***** 2019/10/28 *****
												{$QtCortesP=count($lista_cortes_P);}	// ***** 2019/10/28 *****
											
											//echo "<script language='javascript' type='text/javascript'>alert('QtCortes: ".$QtCortesM. ' - '.$QtCortesP."');</script>";													
											//********************************************
											
											for($r=1;$r<=$NumRodadas;$r++)
												{
													$rodadaSB=true;
													for($jz=0;$jz<$QtCortesM;$jz++) { if($r==$lista_cortes_M[$jz]) {$rodadaSB=false;} }
													for($jz=0;$jz<$QtCortesP;$jz++) { if($r==$lista_cortes_P[$jz]) {$rodadaSB=false;} }
													if($rodadaSB==true)
														{
															
															//switch(''.$CrossTable[$j][$r][3].'')
															$TestResul=''.$CrossTable[$j][$r][3].'';
															switch($TestResul)
																{
																	case '1':
																		//if($Status[$j]=='0' && $Status[''.$CrossTable[$j][$r][1]].''=='0')
																			{
																				$adver=''.$CrossTable[$j][$r][1].'';
																				if($adver=='-')
																					{$PontosBH[$i][$j] = $PontosBH[$i][$j] + $NumRodadas/2;}
																				else
																					{	
																						for($r1=1;$r1<=$NumRodadas;$r1++)
																							{
																								$adver1=''.$CrossTable[$adver][$r1][1].'';
																								//$test=''.$CrossTable[$adver][$r1][3].'';
																								switch(''.$CrossTable[$adver][$r1][3].'')
																									{
																										case '1':
																											if($adver1=='-')
																												{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																											else
																												{
																											//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																													{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 1;}
																												}
																											break;
																										case "½":
																											if($adver1=='-')
																												{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																											else
																												{
																											//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																														{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																												}
																											break;
																										case "+":
																											//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																												{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																												//{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 1;}
																											break;
																										case "-":
																											//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																												{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																											//	{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0;}
																											break;
																										case "0":														
																											if($adver1=='-')
																												{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																											break;
																										default:
																											break;
																									}																						
																							}
																					}
																			}
																		break;
																		
																	case '½':
																		//if($Status[$j]=='0' && $Status[''.$CrossTable[$j][$r][1]].''=='0')
																			{
																				$adver=''.$CrossTable[$j][$r][1].'';
																				if($adver=='-')
																					{$PontosBH[$i][$j] = $PontosBH[$i][$j] + $NumRodadas/2;}
																				for($r1=1;$r1<=$NumRodadas;$r1++)
																					{
																						$adver1=''.$CrossTable[$adver][$r1][1].'';
																						$test=''.$CrossTable[$adver][$r1][3].'';
																						switch($test)
																							{
																								case '1':
																									if($adver1=='-')
																										{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																									else
																										{
																									//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																										  {$PontosBH[$i][$j] = $PontosBH[$i][$j] + 1;}
																										}
																									break;
																								case "½":
																									if($adver1=='-')
																										{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																									else
																										{
																									//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																										  {$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																										}
																									break;
																								case "+":
//																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																										//{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5 / 2;}
																										//{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 1 / 2;}
																										//{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 1;}
																										{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																									break;
																								case "-":
//																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																									//	//{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5 / 2;}
																									//	{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0 / 2;}
																										{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																									break;
																								case "0":														
																									if($adver1=='-')
																										{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																									break;
																								default:
																									break;
																							}																						
																					}
																			}
																		break;
																		
																	case '+':
																		//if($Status[$j]=='0' && $Status[''.$CrossTable[$j][$r][1]].''=='0')
																			{
																				$adver=''.$CrossTable[$j][$r][1].'';
																				if($adver=='-')
																					{$PontosBH[$i][$j] = $PontosBH[$i][$j] + $NumRodadas/2;}			// **** verificar *****
																				
																				
																				//{echo "<script language='javascript' type='text/javascript'>alert('***Critério: ".$i.' - $PNJ'.$PNJ[$i].' - Calc:'.$ExpPNJ[$PNJ[$i]]."');</script>";}
																				if($i!=3)
																					{
																						
																							for($r1=1;$r1<=$NumRodadas;$r1++)
																								{
																									$adver1=''.$CrossTable[$adver][$r1][1].'';
																									$test=''.$CrossTable[$adver][$r1][3].'';
																									switch($test)
																										{
																											case '1':
																												if($adver1=='-')
																													{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																												else
																													{
																												//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																															{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 1;}
																													}
																												break;
																											case "½":
																												if($adver1=='-')
																													{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																												else
																													{
																												//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																															{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																													}
																												break;
																											case "+":
																												//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																													{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																												break;
																											case "-":
			 																								//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																												//	//{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																												//	{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0;}
																													{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																												break;
																											case "0":														
																												if($adver1=='-')
																													{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																												break;
																											default:
																												break;
																										}																						
																								}
																						
																					}
																				else
																					{
																						// *** alteração necessária para "Virtual Opponent" ***      ++++ ATENÇÃO: repetir para Sonneborn-Berger ++++
																						// *** (+) BH = (pts do adversario até a rodada anterior) + (pts na rodada presente) + (0,5pt por rodada posterior) ***
																						for($r1=1;$r1<=$r-1;$r1++)
																							{
																								$adver1=''.$CrossTable[$adver][$r1][1].'';
																								$test=''.$CrossTable[$adver][$r1][3].'';
																								switch($test)
																									{
																										case '1':
																											if($adver1=='-')
																												{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																											else
																												{
																											//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																														{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 1;}
																												}
																											break;
																										case "½":
																											if($adver1=='-')
																												{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																											else
																												{
																											//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																														{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																												}
																											break;
																										case "+":
																											//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																												{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																											break;
																										case "-":
		 																								//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																											//	//{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																											//	{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0;}
																												{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																											break;
																										case "0":														
																											if($adver1=='-')
																												{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																											break;
																										default:
																											break;
																									}																						
																							}
																						
																						$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0;
																						$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5*($NumRodadas-$r);
																					}
																				
																			}
																		break;
																		
																	case '0':														
																		//if($Status[$j]=='0' && $Status[''.$CrossTable[$j][$r][1]].''=='0')
																			{
																				$adver=''.$CrossTable[$j][$r][1].'';
																				if($adver=='-')
																					{$PontosBH[$i][$j] = $PontosBH[$i][$j] + $NumRodadas/2;}
																				for($r1=1;$r1<=$NumRodadas;$r1++)
																					{
																						if(!isset($CrossTable[$adver][$r1][1])) {$CrossTable[$adver][$r1][1]='';}		// ***** 2019/10/28 *****
																						$adver1=''.$CrossTable[$adver][$r1][1].'';
																						
																						//$test=''.$CrossTable[$adver][$r1][3].'';
																						
																						if(!isset($CrossTable[$adver][$r1][3])) {$CrossTable[$adver][$r1][3]='';}		// ***** 2019/10/28 *****
																						switch(''.$CrossTable[$adver][$r1][3].'')
																							{
																								case '1':
																									if($adver1=='-')
																										{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																									else
																										{
																									//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																										  {$PontosBH[$i][$j] = $PontosBH[$i][$j] + 1;}
																										}
																									break;
																								case "½":
																									if($adver1=='-')
																										{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																									else
																										{
																									//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																										  {$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																										}
																									break;
																								case "+":
																									//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																										{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																										//{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 1;}
																									break;
																								case "-":
																									//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																									//	//{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																									//	{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0;}
																										{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																									break;
																								case "0":														
																									if($adver1=='-')
																										{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																									break;
																								default:
																									break;
																							}																						
																					}
																			}
																		break;
																	
																	
																	
																	case '-':
																		//if($Status[$j]=='0' && $Status[''.$CrossTable[$j][$r][1]].''=='0')
																			{
																				$adver=''.$CrossTable[$j][$r][1].'';
																				for($r1=1;$r1<=$NumRodadas;$r1++)
																					{
																						$adver1=''.$CrossTable[$adver][$r1][1].'';
																						//$test=''.$CrossTable[$adver][$r1][3].'';
																						switch(''.$CrossTable[$adver][$r1][3].'')
																							{
																								case '1':
																									//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																										{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 1;}
																									break;
																								case "½":
																									if($adver1=='-')
																										{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																									else
																										{
																									//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																										  {$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																										}
																									break;
																								case "+":
																									//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																										{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																										//{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 1;}
																									break;
																								case "-":
																									//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																									//	//{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																									//	{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0;}
																										{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																									break;
																								case "0":														
																									if($adver1=='-')
																										{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																									break;
																								default:
																									break;
																							}																						
																					}
																			}
																		break;
																	
																	default:
																		break;
																}
															
														}
												}
											for($jj=1;$jj<=$QtJogadores;$jj++)
												{
													if($PtsDesemp[0][$jj]==$j)
														{$PtsDesemp[$i+1][$jj] = $PontosBH[$i][$j];}
												}
										}
									break;
// ****************************************************								
								
								// -----------------------------------------------------------------------------
								case 45:		// 45-Koya (Pontos contra jogador com >= 50% dos Pontos possíveis)
								
									for($j=1;$j<=$QtJogadores;$j++)
										{
											for($r=1;$r<=$NumRodadas;$r++)
												{
													switch(''.$CrossTable[$j][$r][3].'')
														{
															case '1':
																if($Status[$j]=='0' && $Status[$CrossTable[$j][$r][1]]=='0')
																	{
																		if($PontosJogador[$CrossTable[$j][$r][1]]>=$NumRodadas/2)
																			{$PontosKoya[$i][$j] = $PontosKoya[$i][$j] + 1;}
																	}
																break;
																
															case '½':
																if($Status[$j]=='0' && $Status[$CrossTable[$j][$r][1]]=='0')
																	{
																		if($PontosJogador[$CrossTable[$j][$r][1]]>=$NumRodadas/2)
																			{$PontosKoya[$i][$j] = $PontosKoya[$i][$j] + 0.5;}
																	}
																break;
																
															case '+':
																if($Status[$j]=='0' && $Status[$CrossTable[$j][$r][1]]=='0')
																	{
																		if($PontosJogador[$CrossTable[$j][$r][1]]>=$NumRodadas/2)
																			{$PontosKoya[$i][$j] = $PontosKoya[$i][$j] + 1;}
																	}
																break;
																
															case '0':														
															case '-':
																break;
															default:
																break;
														}
												}
											for($jj=1;$jj<=$QtJogadores;$jj++)
											{
												if($PtsDesemp[0][$jj]==$j)
												{
													if($PontosKoya[$i][$j]<=0) {$PontosKoya[$i][$j]=0;}
													$PtsDesemp[$i+1][$jj] = $PontosKoya[$i][$j];
												}
											}
										}
								
									break;
								
								case 52:		// 52-Sonneborn-Berger (variavel)
									//$Matriz_Desempates[$Idioma][$Desempate[]][2], $Corte[], $QtPior[], $QtMelhor[], $Adic[], $PNJ_Adic[], $ExpAdic[], $ExpPNJ[]
									
									for($j=1;$j<=$QtJogadores;$j++)
										{
											$PontosSB[$i][$j] = 0;
											
											//********************************************
											//echo "<script language='javascript' type='text/javascript'>alert('Corte: ".$i.'-'.$Corte[$i]."');</script>";													
											if($Corte[$i]>0)
												{
													unset($lista_cortes_M);unset($lista_cortes_P);
													/* ordena resultados dos adversários e marca os cortes */
													for($r=1;$r<=$NumRodadas;$r++)
														{
															$PontosAdver[0][$r-1] = $r;
															if(''.$CrossTable[$j][$r][1].''=='-')
																{
																	$PontosAdver[1][$r-1] = 0;
																	$PontosAdver[2][$r-1] = $NumRodadas/2;
																}
															else
																{
																	$PontosAdver[1][$r-1] = $CrossTable[$j][$r][1];
																	if($Status[''.$CrossTable[$j][$r][1].'']=='1')
																		{$PontosAdver[2][$r-1] = 0;}
																	else
																		{
																			switch(''.$CrossTable[$j][$r][3].'')
																				{
																					case '1':
																						$PontosAdver[2][$r-1] = $PontosJogador[$CrossTable[$j][$r][1]];
																						// +++++++++++++++++ Providenciar Pontos Corrigidos +++++++++++++++++
																						break;
																					case '½':
																						$PontosAdver[2][$r-1] = $PontosJogador[$CrossTable[$j][$r][1]]/2;
																						// +++++++++++++++++ Providenciar Pontos Corrigidos +++++++++++++++++
																						break;
																					case '+':
																						$PontosAdver[2][$r-1] = $PontosJogador[$CrossTable[$j][$r][1]];
																						// +++++++++++++++++ Providenciar Pontos Corrigidos +++++++++++++++++
																						break;
																					default:
																						$PontosAdver[2][$r-1] = 0;
																						break;
																				}
																		}
																}
														}
													
													/*
														for($zz=0;$zz<$NumRodadas;$zz++)
															{echo $j . ' * ' . $PontosAdver[0][$zz].' - '.$PontosAdver[1][$zz].' - '.$PontosAdver[2][$zz].'<br>';}
														echo '<br>';
													*/
													array_multisort($PontosAdver[2],SORT_DESC,SORT_NUMERIC,
																													$PontosAdver[1],SORT_DESC,SORT_NUMERIC,
																													$PontosAdver[0]
																												);
											  //echo "<script language='javascript' type='text/javascript'>alert('QtMelhor: ".$QtMelhor[$i]. ' - '.$QtPior[$i]."');</script>";													
													/*
														for($zz=0;$zz<$NumRodadas;$zz++)
															{echo $j . ' * ' . $PontosAdver[0][$zz].' - '.$PontosAdver[1][$zz].' - '.$PontosAdver[2][$zz].'<br>';}
														echo '<br>';													
													*/
													
													for($jz=0;$jz<$QtMelhor[$i];$jz++)
														{$lista_cortes_M[$jz] = $PontosAdver[0][$jz];}
													for($jz=$QtPior[$i];$jz>$NumRodadas-$QtPior[$i];$jz--)
														{$lista_cortes_P[$jz] = $PontosAdver[0][$jz];}
												}
											else
												{unset($lista_cortes_M);unset($lista_cortes_P);}
											
											//$QtCortesM=count($lista_cortes_M);
											//$QtCortesP=count($lista_cortes_P);
											//$QtCortesM=count($lista_cortes_M);		// ***** 2019/10/28 *****
											if(!isset($lista_cortes_M))							// ***** 2019/10/28 *****
												{$QtCortesM=0;}												// ***** 2019/10/28 *****
											else																		// ***** 2019/10/28 *****
												{$QtCortesM=count($lista_cortes_M);}	// ***** 2019/10/28 *****
											
											//$QtCortesP=count($lista_cortes_P);		// ***** 2019/10/28 *****
											if(!isset($lista_cortes_P))							// ***** 2019/10/28 *****
												{$QtCortesP=0;}												// ***** 2019/10/28 *****
											else																		// ***** 2019/10/28 *****
												{$QtCortesP=count($lista_cortes_P);}	// ***** 2019/10/28 *****
											
											//echo "<script language='javascript' type='text/javascript'>alert('QtCortes: ".$QtCortesM. ' - '.$QtCortesP."');</script>";													
											//********************************************
											
											for($r=1;$r<=$NumRodadas;$r++)
												{
													$rodadaSB=true;
													for($jz=0;$jz<$QtCortesM;$jz++) { if($r==$lista_cortes_M[$jz]) {$rodadaSB=false;} }
													for($jz=1;$jz<=$QtCortesP;$jz++) { if($r==$lista_cortes_P[$jz]) {$rodadaSB=false;} }
													if($rodadaSB==true)
														{
															switch(''.$CrossTable[$j][$r][3].'')
																{
																	case '1':
																		if($Status[$j]=='0' && $Status[$CrossTable[$j][$r][1]]=='0')
																			{
																				$adver=$CrossTable[$j][$r][1];
																				for($r1=1;$r1<=$NumRodadas;$r1++)
																					{
																						$adver1=$CrossTable[$adver][$r1][1];
																						//$test=''.$CrossTable[$adver][$r1][3].'';
																						switch(''.$CrossTable[$adver][$r1][3].'')
																							{
																								case '1':
																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																										{$PontosSB[$i][$j] = $PontosSB[$i][$j] + 1;}
																									break;
																								case "½":
																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																										{$PontosSB[$i][$j] = $PontosSB[$i][$j] + 0.5;}
																									break;
																								case "+":
																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																										//{$PontosSB[$i][$j] = $PontosSB[$i][$j] + 0.5;}
																										{$PontosSB[$i][$j] = $PontosSB[$i][$j] + 1;}
																									break;
																								case "-":
																									//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																									//	//{$PontosSB[$i][$j] = $PontosSB[$i][$j] + 0.5;}
																									//	{$PontosSB[$i][$j] = $PontosSB[$i][$j] + 0;}
																									break;
																								case "0":														
																									break;
																								default:
																									break;
																							}																						
																					}
																			}
																		break;
																		
																	case '½':
																		if($Status[$j]=='0' && $Status[$CrossTable[$j][$r][1]]=='0')
																			{
																				$adver=$CrossTable[$j][$r][1];
																				for($r1=1;$r1<=$NumRodadas;$r1++)
																					{
																						$adver1=$CrossTable[$adver][$r1][1];
																						$test=''.$CrossTable[$adver][$r1][3].'';
																						switch($test)
																							{
																								case '1':
																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																										{$PontosSB[$i][$j] = $PontosSB[$i][$j] + 1 / 2;}
																									break;
																								case "½":
																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																										{$PontosSB[$i][$j] = $PontosSB[$i][$j] + 0.5 / 2;}
																									break;
																								case "+":
																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																										//{$PontosSB[$i][$j] = $PontosSB[$i][$j] + 0.5 / 2;}
																										{$PontosSB[$i][$j] = $PontosSB[$i][$j] + 1 / 2;}
																									break;
																								case "-":
																									//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																									//	//{$PontosSB[$i][$j] = $PontosSB[$i][$j] + 0.5 / 2;}
																									//	{$PontosSB[$i][$j] = $PontosSB[$i][$j] + 0 / 2;}
																									break;
																								case "0":														
																									break;
																								default:
																									break;
																							}																						
																					}
																			}
																		break;
																		
																	case '+':
																		if($Status[$j]=='0' && $Status[$CrossTable[$j][$r][1]]=='0')
																			{
																				$adver=$CrossTable[$j][$r][1];
																				for($r1=1;$r1<=$NumRodadas;$r1++)
																					{
																						$adver1=$CrossTable[$adver][$r1][1];
																						$test=''.$CrossTable[$adver][$r1][3].'';
																						switch($test)
																							{
																								case '1':
																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																										{$PontosSB[$i][$j] = $PontosSB[$i][$j] + 1;}
																									break;
																								case "½":
																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																										{$PontosSB[$i][$j] = $PontosSB[$i][$j] + 0.5;}
																									break;
																								case "+":
																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																										{$PontosSB[$i][$j] = $PontosSB[$i][$j] + 0.5;}
																									break;
																								case "-":
																									//if($Status[$adver]=='0' && $Status[$adver1]=='0')
																									//	//{$PontosSB[$i][$j] = $PontosSB[$i][$j] + 0.5;}
																									//	{$PontosSB[$i][$j] = $PontosSB[$i][$j] + 0;}
																									break;
																								case "0":														
																									break;
																								default:
																									break;
																							}																						
																					}
																			}
																		break;
																		
																	case '0':														
																	case '-':
																		break;
																	default:
																		break;
																}
														}
												}
											for($jj=1;$jj<=$QtJogadores;$jj++)
												{
													if($PtsDesemp[0][$jj]==$j)
														{$PtsDesemp[$i+1][$jj] = $PontosSB[$i][$j];}
												}
										}
									break;
								
								case 53:		// 53-Num. part. de Negras
									for($itb=1;$itb<=$QtJogadores;$itb++)
										{
											//$PtsDesemp[$i+1][$itb] = $JogNegras[$itb];
											for($ii=1;$ii<=$QtJogadores;$ii++)
												{
													if($PtsDesemp[0][$ii]==$itb)
														{$PtsDesemp[$i+1][$ii] = $JogNegras[$itb];}
												}
										}
									break;
								
								default:
									break;
							}
					}
			}
		
  function CalcularConfrontoDireto($Crit)
			{
				global $PtsDesemp,$QtJogadores,$PontosJogador,$CrossTable,$NumRodadas;
				
				array_multisort($PtsDesemp[1],SORT_DESC,SORT_NUMERIC,
																				$PtsDesemp[2],SORT_DESC,SORT_NUMERIC,
																				$PtsDesemp[3],SORT_DESC,SORT_NUMERIC,
																				$PtsDesemp[4],SORT_DESC,SORT_NUMERIC,
																				$PtsDesemp[5],SORT_DESC,SORT_NUMERIC,
																				$PtsDesemp[6],SORT_DESC,SORT_NUMERIC,
																				$PtsDesemp[0]
																			);
				// Restabelece a Matriz "$PtsDesemp[crit][Jog]" para "Jog" inicial = 1
				for($itb=$QtJogadores;$itb>0;$itb--)
					{ for($ic=0;$ic<=6;$ic++) {$PtsDesemp[$ic][$itb]=$PtsDesemp[$ic][$itb-1];} }
				for($ic=0;$ic<=6;$ic++) {unset($PtsDesemp[$ic][0]);}
				
				$QtEmpatados=1;
				$nj=$PtsDesemp[0][1];
				$PontosEmpateIni=1000;
				//$PontosEmpateIni=$PontosJogador[$nj];
				$JogadoresEmpatados[1]=$nj;
				//for($itb=0;$itb<$QtJogadores;$itb++)
				for($itb=1;$itb<=$QtJogadores;$itb++)							//Percorre os Jogadores Ordenados por Pontos ($PtsDesemp[0][$itb])
					{
						$nj=$PtsDesemp[0][$itb];
						if($PontosJogador[$nj]<$PontosEmpateIni)
							{
								if($QtEmpatados < $NumRodadas+1)
									{	
										for($x=1;$x<=$QtEmpatados;$x++) {$JogadoresEmpatadosPts[$x]=0;}
										if($QtEmpatados>1)
											{
												
												
												
												//$QtJogosMutuos=($QtEmpatados-1)*($QtEmpatados)/2;
												$QtJogosMutuos=$QtEmpatados-1;
												$JogosMutuos=0;
												//echo "<script language='javascript' type='text/javascript'>alert('QtEmpatados: ".$QtEmpatados."');</script>";
												for($ije=1;$ije<=$QtEmpatados;$ije++)
													{
														//echo "<script language='javascript' type='text/javascript'>alert('ije: ".$ije."');</script>";
														$JogosMutuos=0;
														for($ije2=1;$ije2<=$QtEmpatados;$ije2++)
															{
																
																if($ije2<>$ije)
																	{
																		
																		for($irod=1;$irod<=$NumRodadas;$irod++)
																			{
																				
																				//$yy = 'Rodada: '.$irod.' - '.$JogadoresEmpatados[$ije].' - '.$CrossTable[$JogadoresEmpatados[$ije2]][$irod][1];
																				//echo "<script language='javascript' type='text/javascript'>alert('".$yy."');</script>";
																				
																				if($JogadoresEmpatados[$ije]==''.$CrossTable[$JogadoresEmpatados[$ije2]][$irod][1].'')
																					{
																						$JogosMutuos++;
																						//echo "<script language='javascript' type='text/javascript'>alert('".$JogosMutuos."');</script>";
																						
																						switch (''.$CrossTable[$JogadoresEmpatados[$ije2]][$irod][3].'')
																							{
																								case '1':
																									$JogadoresEmpatadosPts[$ije2]=$JogadoresEmpatadosPts[$ije2]+1;
																									break;
																								case '0':
																									//$JogadoresEmpatadosPts[$ije1]=$JogadoresEmpatadosPts[$ije1]+1;
																									break;
																								case '½':
																									$JogadoresEmpatadosPts[$ije2]=$JogadoresEmpatadosPts[$ije2]+0.5;
																									//$JogadoresEmpatadosPts[$ije]=$JogadoresEmpatadosPts[$ije]+0.5;
																									break;
																								case '+':
																									$JogadoresEmpatadosPts[$ije2]=$JogadoresEmpatadosPts[$ije2]+1;
																									break;
																								default:
																									break;
																							}
																					}
																			}
																		if($JogosMutuos<$QtEmpatados-1)
																			{
																				//$ije2=$QtEmpatados+1;
																				//$ije=$QtEmpatados+1;
																				//$JogosMutuos=0;
																			}
																		
																	}
																
															}
													}
												
												//echo "<script language='javascript' type='text/javascript'>alert('JogosMutuos: ".$JogosMutuos."');</script>";
												if($JogosMutuos==$QtEmpatados-1)
													{
														for($x=1;$x<=$QtEmpatados;$x++)
															{
																//$je=$JogadoresEmpatados[$x]; $jep=$JogadoresEmpatadosPts[$x];
																for($ii=1;$ii<=$QtJogadores;$ii++)
																	{
																		if($PtsDesemp[0][$ii]==$JogadoresEmpatados[$x])
																			{
																				$PtsDesemp[$Crit][$ii]=$JogadoresEmpatadosPts[$x];
																			}
																	}
																//echo $Crit.' - *'.$PtsDesemp[0][$je].'* - '.$je.' - '.$jep.'<br>';
															}
													}
												
												//echo '<br>';
												
												unset($JogadoresEmpatados);
												$QtEmpatados=1;
												$JogadoresEmpatados[1]=$nj;
												
											}
										else
											{
												$JogadoresEmpatados[1]=$nj;
											}
										
										$PontosEmpateIni=$PontosJogador[$nj];
										
										unset($JogadoresEmpatados);
										$QtEmpatados=1;
										$JogadoresEmpatados[1]=$nj;
									}	
							
							}
						else
							{
								$QtEmpatados++;
								$JogadoresEmpatados[$QtEmpatados]=$nj;
							}
					}
				
				array_multisort($PtsDesemp[1],SORT_DESC,SORT_NUMERIC,
																				$PtsDesemp[2],SORT_DESC,SORT_NUMERIC,
																				$PtsDesemp[3],SORT_DESC,SORT_NUMERIC,
																				$PtsDesemp[4],SORT_DESC,SORT_NUMERIC,
																				$PtsDesemp[5],SORT_DESC,SORT_NUMERIC,
																				$PtsDesemp[6],SORT_DESC,SORT_NUMERIC,
																				$PtsDesemp[0]
																			);
				// Restabelece a Matriz "$PtsDesemp[crit][Jog]" para "Jog" inicial = 1
				for($itb=$QtJogadores;$itb>0;$itb--)
					{ for($ic=0;$ic<=6;$ic++) {$PtsDesemp[$ic][$itb]=$PtsDesemp[$ic][$itb-1];} }
				for($ic=0;$ic<=6;$ic++) {unset($PtsDesemp[$ic][0]);}
				
			}
	
?>