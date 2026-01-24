<?php

	$limit=set_time_limit (1*3600);

	$context = stream_context_create(array('http' => array('header'=>'Connection: close')));

	$campos = array("","","","","","","","","");
	$camposvazios = array("","","","","","","","","");

	$forma_busca = "S";   //Sequencial
	//$forma_busca = "L";   //Lista
 
	//$fp = fopen('RatCBX_Geral_48879x00001.csv','w');
	$fp = fopen('RatCBX_Geral_48879x30001.csv','w');
	
	if ($forma_busca == "S")
		{
			//$fpS = fopen('Lista_IdCBX_Geral_48879x00001.txt','r');
			$fpS = fopen('Lista_IdCBX_Geral_48879x30001.txt','r');
			$ic1=0;
			//echo "passou n:1<br>";
			
			while(!feof($fpS))
				{
					$id_consult[$ic1] = trim(fgets($fpS));
					$id_consultN[$ic1] = explode("\t",$id_consult[$ic1]);
					$ic1++;
				}
			fclose($fpS);
			$i = 0;
			//echo '<br>' . $ic1 . '<br>';
			
			echo '<br>' . 'Início: ' . date('Y-m-d H:i:s') . '<br>';
			
			while($i < $ic1)
				{
					//$id_proc = $id_consult[$i];
					$id_proc = $id_consultN[$i][0];
					
					$content = file_get_contents("http://www.cbx.org.br/Jogadores.aspx?nm=&no=$id_proc&fi=&uf=");

					$pos11 = stripos($content, ">".$id_proc."<") + 1;
					if ($pos11<2)
						{
							$camposvazios[0] = $id_proc;
							fputcsv($fp, $camposvazios, ';');
							$i++;
							continue;
						}

					le_dados($content,$pos11,$campos);
					
			    $content2 = file_get_contents("http://cbx.org.br/DetalhesJogador.aspx?no=$id_proc");
			    $posY1 = stripos($content2,">Blitz<");
					if ($posY1>=1)
					{
						$AnoAnterior=1;
						$TRTR='</tr><tr>';
						$posTR=$posY1+12;
						while ($TRTR=='</tr><tr>')
						{
							$posMesAno = stripos($content2,'<td align="center">',$posTR);
							$MesAno = substr($content2, $posMesAno+19, 8);
							$Ano = substr($MesAno, 4, 4) * 1;
							if($Ano>$AnoAnterior)
							{
								$posY2 = stripos($content2,'td><td align="center">',$posMesAno+(19+8));
								$rat_cbxC = substr($content2, $posY2+22, 4);
								$posY3 = stripos($content2,'td><td align="center">',$posY2+1);
								$rat_cbxR = substr($content2, $posY3+22, 4);
								$posY4 = stripos($content2,'td><td align="center">',$posY3+1);
								$rat_cbxB = substr($content2, $posY4+22, 4);
								echo "$AnoAnterior - $MesAno - $Ano <br>";
								$AnoAnterior=$Ano;
							}
							else
							{
								$posY4 = $posY4+(19+8+22+22+22);
							}
							$posTR = stripos($content2,'</tr>',$posY4+22);							
							$TRTR = substr($content2, $posTR, 9);
							
							//echo "$AnoAnterior - $MesAno - $Ano - $TRTR<br>";
						}
						
					}
					else
					{
						$rat_cbxC = 0;
						$rat_cbxR = 0;
						$rat_cbxB = 0;
					}
		    //echo "RAT_CBX_C: " . $rat_cbxC . " - " . "RAT_CBX_R: " . $rat_cbxR . " - " . "RAT_CBX_B: " . $rat_cbxB . "<br>\n";
					$campos[6] = $rat_cbxC;
					$campos[7] = $rat_cbxR;
					$campos[8] = $rat_cbxB;
					
				  fputcsv($fp, $campos, ';');
					//fwrite($fp, $campos[0]);

					//echo "<br>\n\n";
					
					$i++;
					//echo "$i<br>";
					
					//sleep(3);
					
				}
				echo 'Fim: ' . date('Y-m-d H:i:s') . '<br>';
		}
		
	else
		{
			
			$fpM = fopen('id_cbx_max.txt','r');
			$id_cbx_max = trim(fgets($fpM));
			fclose($fpM);
			$qt_falhas = 0;
	  
			//$fpN = fopen('id_cbx_maxN.txt','w');
			$i = $id_cbx_max + 1;
			while($qt_falhas < 5)
				{
					$id_proc = $i;
					echo "ID=" . $id_proc . "*<br>";
					$content = file_get_contents("http://www.cbx.org.br/Jogadores.aspx?nm=&no=$id_proc&fi=&uf=");

					$pos11 = stripos($content, ">".$id_proc."<") + 1;
					if ($pos11<2)
						{
							$qt_falhas++;
							$camposvazios[0] = $id_proc;
							fputcsv($fp, $camposvazios, ';');
							$i++;
							continue;
						}

					le_dados($content,$pos11,$campos);
					
					fputcsv($fp, $campos, ';');

					echo "<br>\n\n";
					$qt_falhas = 0;
					
					$i++;
				}
			$fpN = fopen('id_cbx_maxN.txt','w');
			fwrite($fpN, $campos[0]);
			fclose($fpN);

	 }
 
	fclose($fp);
	
 /*
	$fileoldname = "id_cbx_max_old.txt";
 unlink($fileoldname);
	rename("id_cbx_max.txt", "id_cbx_max_old.txt");
	rename("id_cbx_maxN.txt", "id_cbx_max.txt");
	*/
	
	echo "<br>";
	exit;
	
//-------------------------------------------------------------------------------------------------

	function le_dados($contentL,$pos11L,&$campos)
  {
			//ID
			$pos12 = stripos($contentL, "</td><td>",$pos11L);
			$nc1 = $pos12-$pos11L-4;
			//echo $pos11L . " - " . $pos12 . " - " . $nc1 . " - ";
			$id_cbx = substr($contentL, $pos11L, $nc1);
//			echo "<br>ID_CBX: " . $id_cbx . "<br>\n\n";
			$campos[0] = $id_cbx;

			//NOME
			$pos21 = $pos12 + 9;
			$pos22 = stripos($contentL, '</td><td align="center">',$pos21);
			$nc2 = $pos22-$pos21;
			//echo $pos21 . " - " . $pos22 . " - " . $nc2 . " - ";
			$nome = utf8_decode(substr($contentL, $pos21, $nc2));
//			echo "NOME: " . $nome . "<br>\n\n";
			$campos[1] = $nome;

			//UF
			$pos31 = $pos22 + 24;
			$pos32 = stripos($contentL, '</td><td align="center">',$pos31);
			$nc3 = $pos32-$pos31;
			//echo $pos31 . " - " . $pos32 . " - " . $nc3 . " - ";
			$uf = substr($contentL, $pos31, $nc3);
//			echo "UF: " . $uf . "<br>\n\n";
			$campos[2] = $uf;

			//ID_FIDE
			$pos41 = $pos32 + 24;
			$pos42 = stripos($contentL, '</td><td align="center"',$pos41);
			$nc4 = $pos42-$pos41;
			//echo $pos41 . " - " . $pos42 . " - " . $nc4 . " - ";
			$if_fide = substr($contentL, $pos41, $nc4);
//			echo "ID_FIDE: " . $if_fide . "<br>\n\n";
			$campos[3] = $if_fide;

			//DATA_PAG
			$pos51 = stripos($contentL, '>',$pos42 + 24) + 1;
			$pos52 = stripos($contentL, '</font></td><td align="center"',$pos51);
			$nc5 = $pos52-$pos51;
			//echo $pos51 . " - " . $pos52 . " - " . $nc5 . " - ";
			$data_pag = substr($contentL, $pos51, $nc5);
			if ($data_pag=='<b>Pendente</b>') {$data_pag='Pendente';}
//			echo "DATA_PAG: " . $data_pag . "<br>\n\n";
			$campos[4] = $data_pag;

			//DATA_NASC
			$pos61 = stripos($contentL, '>',$pos52 + 30) + 1;
			$pos62 = stripos($contentL, '</td>',$pos61);
			$nc6 = $pos62-$pos61;
			//echo $pos61 . " - " . $pos62 . " - " . $nc6 . " - ";
			$data_nasc = substr($contentL, $pos61, $nc6);
//			echo "DATA_NASC: " . $data_nasc . "<br>\n\n";
			$campos[5] = $data_nasc;

			//$content2 = file_get_contents("http://cbx.org.br/DetalhesJogador.aspx?no=$id_cbx");
			//$posY1 = stripos($content2,'<td style="width:25%;">Blitz');
			//$posY2 = stripos($content2,'</td><td align="center">',$posY1);
			//$rat_cbxC = substr($content2, $posY2+23, 4);
			//echo "RAT_CBX_C: " . $rat_cbxC . "<br>\n";
			
			
			/*
			//RAT_CBX
			$pos71 = stripos($contentL, '>',$pos62 + 7) + 1;
			$pos72 = stripos($contentL, '</td>',$pos71);
			$nc7 = $pos72-$pos71;
			//echo $pos71 . " - " . $pos72 . " - " . $nc7 . " - ";
			$rat_cbx = substr($contentL, $pos71, $nc7);
			echo "RAT_CBX: " . $rat_cbx . "<br>\n";
			$campos[6] = $rat_cbx;
*/		
  }

?> 