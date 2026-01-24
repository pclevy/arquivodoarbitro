<?php
	$context = stream_context_create(array('http' => array('header'=>'Connection: close')));
	
	// chamada aos índices de págs: 
	// http://www.cbx.org.br/Jogadores.aspx?nm=&no=&fi=&uf=RJ   //p/UF
	// "javascript:__doPostBack('grdMain$ctl64$ctl01','')"						//p/UF
	
	$campos = array("","","","","","");
	$camposvazios = array("","","","","","","");
	
	$OpcaoUF[0]="AC";$OpcaoUF[1]="AL";$OpcaoUF[2]="AM";$OpcaoUF[3]="AP";$OpcaoUF[4]="AV";$OpcaoUF[5]="BA";$OpcaoUF[6]="CE";$OpcaoUF[7]="DF";
	$OpcaoUF[8]="ES";$OpcaoUF[9]="GO";$OpcaoUF[10]="MA";$OpcaoUF[11]="MG";$OpcaoUF[12]="MS";$OpcaoUF[13]="MT";$OpcaoUF[14]="PA";$OpcaoUF[15]="PB";
	$OpcaoUF[16]="PE";$OpcaoUF[17]="PI";$OpcaoUF[18]="PR";$OpcaoUF[19]="RJ";$OpcaoUF[20]="RN";$OpcaoUF[21]="RO";$OpcaoUF[22]="RR";$OpcaoUF[23]="RS";
	$OpcaoUF[24]="SC";$OpcaoUF[25]="SE";$OpcaoUF[26]="SP";$OpcaoUF[27]="TO";
	
	echo $OpcaoUF[17];exit;
	
	//for($u=0;$u<28;++$u)
	for($u=19;$u<20;++$u)				// só RJ p/testes
	{
		$arquivo='testcbxUF.csv';
		
		$fp = fopen($arquivo,'a');
		
		$i = 0;
		while($i < 60)
		{
			//echo 'ID=' . $i . '<br>';
			$content = file_get_contents("http://www.cbx.org.br/Jogadores.aspx?nm=&no=$i&fi=&uf=");
			
			//echo ' **->' . strlen($content) . '<-** ';
			//echo ' **->' . $content . '<-** ';
			
			$pos11 = stripos($content, ">".$i."<") + 1;              //>2</a></td><td>
			if ($pos11<2)
			{
				$camposvazios[0] = $i;
				$i++;
				continue;			// *** continua o while ***
			}
			
			le_dados($content,$pos11,&$campos);
			
			fputcsv($fp, $campos, ';', '"');
			
			echo "<br>\n\n";
			
			$i++;
		}
		
		echo '<br><br>Fim do Processamento: de ' . $inicial . ' a ' . ($final-1) . '<br>';
		
		fclose($fp);
	}
	
	echo '<br><br>Fim do Processamento<br>';
	
	exit;
//-------------------------------------------------------------------------------------------------
	
	function le_dados($contentL,$pos11L,&$campos)
  {
		//ID
		//$pos12 = stripos($contentL, "</td><td>",$pos11L);
		$pos12 = stripos($contentL, "</a></td><td>",$pos11L);
		$nc1 = $pos12-$pos11L;
		//echo $pos11L . ' - ' . $pos12 . ' - ' . $nc1 . ' - ';
		$id_cbx = substr($contentL, $pos11L, $nc1);
//		echo 'ID_CBX: ' . $id_cbx . "<br>\n\n";
		//$campos[0] = "\"".$id_cbx."\"";
		$campos[0] = $id_cbx;
		
		//NOME
		//$pos21 = $pos12 + 9;
		$pos21 = $pos12 + 13;
		$pos22 = stripos($contentL, '</td><td align="center">',$pos21);
		$nc2 = $pos22-$pos21;
		//echo $pos21 . ' - ' . $pos22 . ' - ' . $nc2 . ' - ';
		$nome = utf8_decode(substr($contentL, $pos21, $nc2));
		echo 'NOME: ' . $nome . "<br>\n\n";
		$campos[1] = $nome;
		
		//UF
		$pos31 = $pos22 + 24;
		$pos32 = stripos($contentL, '</td><td align="center">',$pos31);
		$nc3 = $pos32-$pos31;
		//echo $pos31 . ' - ' . $pos32 . ' - ' . $nc3 . ' - ';
		$uf = utf8_decode(substr($contentL, $pos31, $nc3));
//		echo 'UF: ' . $uf . "<br>\n\n";
		$campos[2] = $uf;
		
		//ID_FIDE
		$pos41 = $pos32 + 24;
		$pos42 = stripos($contentL, '</td><td align="center"',$pos41);
		$nc4 = $pos42-$pos41;
		//echo $pos41 . ' - ' . $pos42 . ' - ' . $nc4 . ' - ';
		$if_fide = substr($contentL, $pos41, $nc4);
//		echo 'ID_FIDE: ' . $if_fide . "<br>\n\n";
		$campos[3] = $if_fide;
		
		//DATA_PAG
		$pos51 = stripos($contentL, '>',$pos42 + 24) + 1;
		$pos52 = stripos($contentL, '</font></td><td align="center"',$pos51);
		$nc5 = $pos52-$pos51;
		//echo $pos51 . ' - ' . $pos52 . ' - ' . $nc5 . ' - ';
		$data_pag = substr($contentL, $pos51, $nc5);
		if ($data_pag=='<b>Pendente</b>') {$data_pag='Pendente';}
//		echo 'DATA_PAG: ' . $data_pag . "<br>\n\n";
		$campos[4] = $data_pag;
		
		//DATA_NASC
		$pos61 = stripos($contentL, '>',$pos52 + 30) + 1;
		//$pos62 = stripos($contentL, '</td><td',$pos61);
		$pos62 = stripos($contentL, '</td>',$pos61);
		$nc6 = $pos62-$pos61;
		//echo $pos61 . ' - ' . $pos62 . ' - ' . $nc6 . ' - ';
		$data_nasc = substr($contentL, $pos61, $nc6);
		//echo 'DATA_NASC: ' . $data_nasc . ' *** ' . strlen($data_nasc) . '<br>\n\n';
//		echo 'DATA_NASC: ' . $data_nasc . "<br>\n\n";
		$campos[5] = $data_nasc;
		
		//RAT_CBX
		/*
		$pos71 = stripos($contentL, '>',$pos62 + 7) + 1;
		$pos72 = stripos($contentL, '</td>',$pos71);
		$nc7 = $pos72-$pos71;
		//echo $pos71 . ' - ' . $pos72 . ' - ' . $nc7 . ' - ';
		$rat_cbx = substr($contentL, $pos71, $nc7);
		//echo 'RAT_CBX: ' . $rat_cbx . '<br>\n';
		$campos[6] = $rat_cbx;
		*/
  }

	$OpcaoUF[0]="AC";$OpcaoUF[1]="AL";$OpcaoUF[2]="AM";$OpcaoUF[3]="AP";$OpcaoUF[4]="AV";$OpcaoUF[5]="BA";$OpcaoUF[6]="CE";$OpcaoUF[7]="DF";
	$OpcaoUF[8]="ES";$OpcaoUF[9]="GO";$OpcaoUF[10]="MA";$OpcaoUF[11]="MG";$OpcaoUF[12]="MS";$OpcaoUF[13]="MT";$OpcaoUF[14]="PA";$OpcaoUF[15]="PB";
	$OpcaoUF[16]="PE";$OpcaoUF[17]="PI";$OpcaoUF[18]="PR";$OpcaoUF[19]="RJ";$OpcaoUF[20]="RN";$OpcaoUF[21]="RO";$OpcaoUF[22]="RR";$OpcaoUF[23]="RS";
	$OpcaoUF[24]="SC";$OpcaoUF[25]="SE";$OpcaoUF[26]="SP";$OpcaoUF[27]="TO";

	?>