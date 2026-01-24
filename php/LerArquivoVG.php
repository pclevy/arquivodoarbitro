<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Arquivos Vega</title>
	</head>
	<body>
		<h1>Torneios Vega</h1>
		<?php
			
			$parametros = $_SERVER['QUERY_STRING'];			
			$tam = strlen($parametros);
			//echo " -- $parametros --" . $tam;exit;
			
			$ArqTorneio='';$datarec='';$remetente='';$apresentacao='';
			if($tam>0) 
			{
				$ArqTorneio = trim(htmlspecialchars($_GET['arq']));
				$datarec = trim(htmlspecialchars($_GET['dtrec']));
				$remetente = trim(htmlspecialchars($_GET['remetente']));
			//	$torneio_reg = trim(htmlspecialchars($_GET['torneio_reg']));
			//	//$janela = trim(htmlspecialchars($_GET['janela']));
			//	$janela = isset($_GET['janela']) ?$_GET['janela'] :'';
			
				$file = "../TorneiosVG/" . $ArqTorneio;
			//echo " -- $parametros --" . $tam . ' --- ' . $file;exit;
			
			$dataatualizacao = substr($datarec, 8, 2) . '/' . substr($datarec, 5, 2) . '/' . substr($datarec, 0, 4) ;
			//echo " -- " . $ArqTorneio . ' --- ' . $dataatualizacao;exit;
			}
			else
			{
				$file = "../TorneiosVG/Copa Brasil 2019 - Regional Sudeste FBXDV.vegx";
				//$file = "../TorneiosVG/Pensado CXI 2019.vegx";
			}
	

		//$file = "../TorneiosSM/" . $ArqTorneio;
			
			//$link = "..\TorneiosVG\Copa_Brasil_2019-Regional_Sudeste_FBXDV.vegx";
							   
			$link = $file;
			//echo '<br>*' . $link . '*<br>' . $link1;exit;
			//link do arquivo xml
			
			// ----------------------------------------------------------------------
			
			$PairingSyst[1] = 'Sistema Suíço Dubov FIDE';
			$PairingSyst[2] = 'Todos c/ Todos Simples';
			$PairingSyst[4] = 'Sistema Suíço USCF';
			$PairingSyst[5] = 'Todos c/ Todos Duplo';
			$PairingSyst[7] = 'Sistema Suíço Lim';
			$PairingSyst[10] = 'Sistema Suíço Dutch FIDE';
			$PairingSyst[13] = 'Sistema Suíço Ranked Dutch';
			$PairingSyst[14] = 'Sistema Suíço Burstein (BBP)';
			$PairingSyst[15] = 'Sistema Dutch (BBP)';
			
			$Desempates['Buc1'] = 'Buchholz c/Corte Pior Result.';
			$Desempates['BucM'] = 'Buchholz Mediano';
			$Desempates['BucT'] = 'Buchholz Total';
			$Desempates['S-B']  = 'Sonneborn-Berger';
			$Desempates['Cmlt'] = 'Escore Progressivo';
			$Desempates['ARO.'] = 'Média de Rating dos Oponentes';
			$Desempates['Koya'] = 'Koya';
			$Desempates['Mwns'] = "Maior Núm. de Vitórias!!";
			$Desempates['APRO'] = 'Média de Rating Performace dos Oponentes';
			$Desempates['ARO1'] = 'Média de Rating dos Oponentes - Corte \'Pior\'';
			$Desempates['Mgam'] = 'Partidas Jogadas + Bye';
			$Desempates['DirE'] = 'Encontro Direto';
			$Desempates['Arr.'] = 'Sistema Arranz (Vit.:1 /Empate: 0.6 de pretas, 0.4 de brancas/ lost: 0)';
			$Desempates['SScr'] = 'Escore Padrão (\'0\' - \'1/2\' - \'1\')';
			$Desempates['Buc2'] = 'Buchholz c/Corte 2 Piores Result.';
			$Desempates['MBlk'] = "Maior Núm. de Jogos c/Pretas!!";
			$Desempates['Tor.'] = 'Sistema Torino (descarta partidas ñ jogadas)';
			$Desempates['User'] = 'Desempate Particular';

			/*
			$Desempates['Buc1'] = 'Buchholz Cut 1';
			$Desempates['BucM'] = 'Buchholz Median';
			$Desempates['BucT'] = 'Buchholz Total';
			$Desempates['S-B']  = 'Sonneborn-Berger';
			$Desempates['Cmlt'] = 'Cumulative';
			$Desempates['ARO.'] = 'Average Rat. Opp.';
			$Desempates['Koya'] = 'Koya';
			$Desempates['Mwns'] = 'Most Wins';
			$Desempates['APRO'] = 'Av. Perf. Rat. Opp.';
			$Desempates['ARO1'] = 'Average Rat. Opp. Cut 1';
			$Desempates['Mgam'] = 'Most Paired';
			$Desempates['DirE'] = 'Direct Encounter';
			$Desempates['Arr.'] = 'Arranz System';
			$Desempates['SScr'] = 'Standard Score (0-1/2-1)';
			$Desempates['Buc2'] = 'Buchholz Cut 2';
			$Desempates['MBlk'] = 'Most Black';
			$Desempates['Tor.'] = 'Torino System';
			$Desempates['User'] = 'User Tie-Break';
			*/
			
			// ----------------------------------------------------------------------
			
			//$link = '../TorneiosVG/Copa Brasil 2019 - Regional Sudeste FBXDV.vegx';
						
			$doc = new DOMDocument();
			//$doc->load('file.xml');
			$doc->load($link);
			//echo 'Version: ' . $doc->xmlVersion . "\n";   // Like '1.0', you can modify this
			//echo 'Encoding: ' . $doc->xmlEncoding . "\n"; // Like 'ISO-8859-1', readonly
			if($doc->xmlEncoding!='UTF-8') { echo $doc->xmlEncoding . '<br><br>';}
			
			$file = $link;	//"../config/conexao_ca.cfg";
			$fh = fopen($file, 'r');
			$conteudo = fread($fh, filesize($file));
			if($doc->xmlEncoding!=='UTF-8') {$conteudo=utf8_encode($conteudo);}
			//print_r( simplexml_load_string($conteudo) );
			//exit;
			
			//$xml = simplexml_load_file($link);	// -> Tournament;
			$xml = simplexml_load_string($conteudo);	// -> Tournament;
			//carrega o arquivo XML e retornando um Array
			
			// Exibe as informações do XML
			$torneio['Titulo'] = $xml->Name;
			$torneio['FederacaoLocal'] = $xml->HostFederation;
			$torneio['Local'] = $xml->Place;
			$torneio['Periodo']['Inicio'] = $xml->Date['Begin'];
			$torneio['Periodo']['Final'] = $xml->Date['End'];
			
			$ProgramacaoRod = explode(',',$xml->Schedule);					//<Schedule></Schedule>
			for($i=0;$i<count($ProgramacaoRod);$i++) {
				$Rod = $i + 1;
				$ProgRod[$Rod] = explode('$',$ProgramacaoRod[$i]);
				$torneio['Programacao'][$Rod]['horario'] = $ProgRod[$Rod][0];
				$torneio['Programacao'][$Rod]['data'] = $ProgRod[$Rod][1];
			}
			
			$torneio['Arbitros']['Principal'] = $xml->Arbiters['Chief'];
			$torneio['Arbitros']['Auxiliares'] = $xml->Arbiters['Deputy'];
			$torneio['Sistema']['Reflexao'] = $xml->PlaySystem->RateMove;
			$torneio['Sistema']['TipoEmparceiramento'] = $xml->PlaySystem->PairingSystem;
			$torneio['Sistema']['RoundRobinOrdem'] = $xml->PlaySystem->RRorder;						//<RRorder></RRorder>
			$torneio['Sistema']['QtRodadas'] = $xml->PlaySystem->RoundsNumber;
			$torneio['Sistema']['Escores']['Vitoria'] = $xml->PlaySystem->ScoreSystem['Win'];
			$torneio['Sistema']['Escores']['Empate'] = $xml->PlaySystem->ScoreSystem['Draw'];
			$torneio['Sistema']['Escores']['Derrota'] = $xml->PlaySystem->ScoreSystem['Loss'];
			$torneio['Sistema']['Escores']['PAB'] = $xml->PlaySystem->ScoreSystem['PAB'];
			
			$torneio['OrdenacaoInicial'] = $xml->InitialRanking;

			$TieBreakOrd = 0;
			foreach($xml->TieBreakers->TieBreak as $item):
				$TieBreakOrd++;			
				$it=trim(iconv("UTF-8", "ASCII", utf8_decode($item)));
				$torneio['Desempates'][$TieBreakOrd] = $it;
			endforeach;
			
			$torneio['AssignedBoards'] = $xml->AssignedBoards;				//<AssignedBoards>20 18$</AssignedBoards>
			
			$torneio['RodadaCorrente'] = $xml->CurrentRound;
			$torneio['RodadasCompletas'] = $xml->CompletedRounds;
			
			/*
				<ProgramVersion>Vega 8.2.0</ProgramVersion>
				<Parameters>
					<RegistrationClosed>TRUE</RegistrationClosed>
					<UseAdjustedScore>TRUE</UseAdjustedScore>
					<NoTieBreakByeDraw>FALSE</NoTieBreakByeDraw>
					<VesusID>0</VesusID>
					<ServerFolder></ServerFolder>
					<StartBoard>1</StartBoard>
					<AcceleratedRounds>
						<AcceleratedSystem>0</AcceleratedSystem>
						<IsSetAcceleration>FALSE</IsSetAcceleration>
						<NumberAcceleratedRounds>0</NumberAcceleratedRounds>
						<Baku_GA>0</Baku_GA>
						<IdLimit High="0" Low="0"/>
						<DecreasedAcceleration>FALSE</DecreasedAcceleration>
						<PointsGroup Top="0" Middle="0" Bottom="0"/>
					</AcceleratedRounds>
				</Parameters>
			*/

			$jogador=0;
			foreach($xml->Players->Player as $item):
				$jogador++;
				$Jogadores[$jogador]['Nome'] = $item->Name; //utf8_decode($item->Name);
				
				$Jogadores[$jogador]['Federacao'] = $item->Federation;  //utf8_decode($item->Federation);
				$Jogadores[$jogador]['Clube'] = $item->Origin;  //utf8_decode($item->Origin);
				//<Info></Info>
				$Jogadores[$jogador]['Genero'] = $item->Gender;  //utf8_decode($item->Gender);
				//<Title></Title>
				//<ID_FIDE>0</ID_FIDE>
				//<RatingFIDE>0</RatingFIDE>
				//<KcoeffFIDE>0</KcoeffFIDE>

				$Jogadores[$jogador]['Id_Local'] = $item->ID_Nat;  //utf8_decode($item->ID_Nat);
				$Jogadores[$jogador]['Rat_Local'] = $item->RatingNat;  //utf8_decode($item->RatingNat);
				$Jogadores[$jogador]['FatorKloc'] = $item->KcoeffNat;  //utf8_decode($item->KcoeffNat);
				$Jogadores[$jogador]['Status'] = $item->Status;  //utf8_decode($item->Status);
				$Jogadores[$jogador]['StatusHistory'] = $item->StatusHistory;  //utf8_decode($item->StatusHistory);
				
			endforeach;
			
			$QtJogadores = $jogador;
			
			$Rodada=0;
			foreach($xml->Rounds->Pairing as $item):
				$Rodada++;			
				//$Rodada[$Rodada];
				foreach($item->Pair as $tabuleiro):
					//echo 'Tabuleiro: ' . $tabuleiro['Board'] . ' - Brancas: ' . $Jogadores[$tabuleiro['White']*1]['Name'] . ' - Negras: ' . $tabuleiro['Black'] . ' - Resultado: ' . $tabuleiro['Result'] . ' - Tipo: ' . $tabuleiro['Type'];
					$tab = $tabuleiro['Board'] * 1;
					$Rodadas[$Rodada]['QtTab'] = $tab;					
					$Rodadas[$Rodada][$tab]['Brancas'] = $tabuleiro['White'];
					$Rodadas[$Rodada][$tab]['Negras'] = $tabuleiro['Black']*1;
					$Rodadas[$Rodada][$tab]['Resultado'] = $tabuleiro['Result'];
					$Rodadas[$Rodada][$tab]['Tipo'] = $tabuleiro['Type'];
				endforeach;
			endforeach;
			
			// ----------------------------------------------------------------------

			echo " =============================================================================== <br />";
			echo 'Data de Atualização: ' . $dataatualizacao . '<br>';
			echo 'Remetente: ' . $remetente . '<br><br>';
			
			echo 'Título do Torneio: ' . $torneio['Titulo'] . '<br>';
			echo 'Federação: ' . $torneio['FederacaoLocal'] . '<br>';
			echo 'Local: ' . $torneio['Local'] . '<br>';

			echo 'Período: de ' . $torneio['Periodo']['Inicio'] . ' a ' . $torneio['Periodo']['Final'] . '<br>';
			echo 'Local: ' . $torneio['Local'] . '<br>';
			
			echo 'Programacao: <br>';					//<Schedule></Schedule>
			echo '<table><tr><th align=\'center\'>Rodada</th><th align=\'center\'>Data</th><th align=\'center\'>Horário</th></tr>';
			for($i=0;$i<count($torneio['Programacao']);$i++) {
				$Rod = $i + 1;
				$torneio['Programacao'][$Rod]['horario'] = $ProgRod[$Rod][0];
				$torneio['Programacao'][$Rod]['data'] = $ProgRod[$Rod][1];
				echo '<tr><td align=\'center\'>' . $Rod . '</td><td align=\'center\'>' . $torneio['Programacao'][$Rod]['data'] . '</td><td align=\'center\'>' . $torneio['Programacao'][$Rod]['horario'] . '</td></tr>';
			}
			echo '</table>';
			
			echo 'Arbitro Principal: ' . $torneio['Arbitros']['Principal'] . '<br>';
			echo 'Arbitros Auxiliares: ' . $torneio['Arbitros']['Auxiliares'] . '<br>';
			
			echo 'Sistema de Jogo: <br>';
			echo '  - Reflexão: ' . $torneio['Sistema']['Reflexao'] . '<br>';
			//<PairingSystem>10</PairingSystem>
			$ps=$torneio['Sistema']['TipoEmparceiramento'].'';
			echo '  - Emparceiramento: ' . $PairingSyst[$ps] . '<br>';
			if($ps==2 || $ps==5) {
				echo '  - Ordem Round-Robin: ' . $torneio['Sistema']['RoundRobinOrdem'] . '<br>';			//<RRorder></RRorder>
			}
			echo '  - Rodadas: ' . $torneio['Sistema']['QtRodadas'] . '<br>';
			//<ScoreSystem Win="1" Draw="0.5" Loss="0" PAB="0"/>
			echo 'Pontuação: <br>';
			echo '&nbsp; - Vitória: ' . $torneio['Sistema']['Escores']['Vitoria'] . '<br>';
			echo '&nbsp; - Empate: ' . $torneio['Sistema']['Escores']['Empate'] . '<br>';
			echo '&nbsp; - Derrota: '    . $torneio['Sistema']['Escores']['Derrota'] . '<br>';
			echo '&nbsp; - Não jogada: ' . $torneio['Sistema']['Escores']['PAB'] . '<br>';

			echo '<br>';
			echo 'Ordenação Inicial: ' . $torneio['OrdenacaoInicial'] . '<br>';

			echo '<br>';
			echo 'Desempates: <br>';
			for($i=1;$i<=$TieBreakOrd;$i++) {
				echo '&nbsp; ' . $i . ' - ' . $torneio['Desempates'][$i] . ': '
				.$Desempates[$torneio['Desempates'][$i]].'<br />';
			}
			
			echo '<br>';
			echo 'AssignedBoards: ' . $torneio['AssignedBoards'] . '<br>';	//<AssignedBoards>20 18$</AssignedBoards>
			
			echo '<br>';
			echo 'Rodada Corrente: ' . $torneio['RodadaCorrente'] . 'ª<br>';
			echo 'Rodada completadas: ' . $torneio['RodadasCompletas'] . '<br>';
			
			/*
				<ProgramVersion>Vega 8.2.0</ProgramVersion>
				<Parameters>
					<RegistrationClosed>TRUE</RegistrationClosed>
					<UseAdjustedScore>TRUE</UseAdjustedScore>
					<NoTieBreakByeDraw>FALSE</NoTieBreakByeDraw>
					<VesusID>0</VesusID>
					<ServerFolder></ServerFolder>
					<StartBoard>1</StartBoard>
					<AcceleratedRounds>
						<AcceleratedSystem>0</AcceleratedSystem>
						<IsSetAcceleration>FALSE</IsSetAcceleration>
						<NumberAcceleratedRounds>0</NumberAcceleratedRounds>
						<Baku_GA>0</Baku_GA>
						<IdLimit High="0" Low="0"/>
						<DecreasedAcceleration>FALSE</DecreasedAcceleration>
						<PointsGroup Top="0" Middle="0" Bottom="0"/>
					</AcceleratedRounds>
				</Parameters>
			*/

			echo '<br> ----------------------------------------------------------------- <br>';
			$player=0;
			echo 'Jogadores: <br>';
			echo '<table border=1>';
			echo '<tr><th>Ord</th><th>Nome</th><th>Gênero</th><th>UF</th><th>Clube</th><th>ID</th><th>Rating</th><th>K</th><th>Status</th></tr>';
			
			echo '<tr>';
			for($j=1;$j<=$QtJogadores;$j++) {
			//foreach($xml->Players->Player as $item):
				echo '<td>' . $j . '</td>';
				echo '<td>' . $Jogadores[$j]['Nome'] . '</td>';
				echo '<td>' . $Jogadores[$j]['Federacao'] . '</td>';
				echo '<td>' . $Jogadores[$j]['Clube'] . '</td>';
				//<Info></Info>
				echo '<td>' . $Jogadores[$j]['Genero'] . '</td>';
				//<Title></Title>
				//<ID_FIDE>0</ID_FIDE>
				//<RatingFIDE>0</RatingFIDE>
				//<KcoeffFIDE>0</KcoeffFIDE>
				echo '<td>' . $Jogadores[$j]['Id_Local'] . '</td>';
				echo '<td>' . $Jogadores[$j]['Rat_Local'] . '</td>';
				echo '<td>' . $Jogadores[$j]['FatorKloc'] . '</td>';
				echo '<td>' . $Jogadores[$j]['Status'] . '</td>';

				/*
				$Jogadores[$jogador]['StatusHistory'] = utf8_decode($item->StatusHistory);				
				//<StatusHistory>11111111111111111111111111</StatusHistory>
				*/
				echo "</tr>";
			}
			echo '</table>';
			
			for($r=1;$r<=$Rodada;$r++) {
				echo 'Rodada ' . $r . ':<br>';
				echo '<table border=1>';
				echo '<tr><th>Mesa</th><th>Brancas</th><th>Resultado</th><th>Negras</th></tr>';
				for($t=1;$t<=$Rodadas[$r]['QtTab'];$t++) {
					echo '<tr>';
					echo '<td align=\'center\'>' . $t . '</td>';
					switch ($Rodadas[$r][$t]['Tipo']) {
						case 0:
							if($Rodadas[$r][$t]['Resultado']==00) {
								$res = '-' . ' x ' . '-';
							} else if($Rodadas[$r][$t]['Resultado']==10) {
								$res = ' ' . '+' . ' x ' . '-' . ' ';
							} else {
								$res = ' ' . '-' . ' x ' . '+' . ' ';
							}
							echo '<td>'.$Jogadores[$Rodadas[$r][$t]['Brancas']*1]['Nome'] . '</td><td align=\'center\'>' . $res . '</td><td>' . $Jogadores[$Rodadas[$r][$t]['Negras']*1]['Nome'] . '</td>';
							break;
						case 1:
							if($Rodadas[$r][$t]['Resultado']==22) {
								$res = '½' . ' x ' . '½';
							} else {
								$res = ' ' . substr($Rodadas[$r][$t]['Resultado'],0,1) . ' x ' . substr($Rodadas[$r][$t]['Resultado'],1,1) . ' ';
							}
							echo '<td>'.$Jogadores[$Rodadas[$r][$t]['Brancas']*1]['Nome'] . '</td><td align=\'center\'>' . $res . '</td><td>' . $Jogadores[$Rodadas[$r][$t]['Negras']*1]['Nome'] . '</td>';
							break;
						//case 2:		// (½x0)	(0x½)	(0x0) - ?????
						//	echo '<td>'.$Jogadores[$Rodadas[$r][$t]['Brancas']*1]['Nome'] . '</td><td align=\'center\'>' . '1 x 0' . '</td><td>' . 'Bye' . '</td>';
						//	break;
						case 3:
							echo '<td>'.$Jogadores[$Rodadas[$r][$t]['Brancas']*1]['Nome'] . '</td><td align=\'center\'>' . '1 x 0' . '</td><td>' . 'Bye' . '</td>';
							break;
						case 4:
							echo '<td>'.$Jogadores[$Rodadas[$r][$t]['Brancas']*1]['Nome'] . '</td><td align=\'center\'>' . '0 x 0' . '</td><td>' . ' NE ' . '</td>';
							break;
						
						default:
							$res = ' ' . substr($Rodadas[$r][$t]['Resultado'],0,1) . ' ??? ' . substr($Rodadas[$r][$t]['Resultado'],1,1);
							echo '<td>'.$Jogadores[$Rodadas[$r][$t]['Brancas']*1]['Nome'] . '</td><td align=\'center\'>' . $res . '</td><td>' . $Jogadores[$Rodadas[$r][$t]['Negras']*1]['Nome'] . ' $ ' . $Rodadas[$r][$t]['Tipo'] . '</td>';
					}
					echo '</tr>';
				}
				echo '</table>';
			}
			
			echo '<br><br><br>';
			
			// ----------------------------------------------------------------------
			
		?>
	</body>
</html>