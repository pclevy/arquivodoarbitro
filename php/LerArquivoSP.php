<?php
  /* ***** Ler Arquivos SP98, Swiss Perfect ***** */
  /* *** 2013/02/09 * 2013/01/30 *** */
	
	$parametros = $_SERVER['QUERY_STRING'];
	
	//echo "<br><br> ++++ $parametros[1] ++++<br><br>";
	//if(strlen($parametros)>0) 
		$tam = strlen($parametros);
		$ArqTorneio='';$datarec='';$remetente='';$apresentacao='';
		if($tam>0) 
			{
				//$tam = strlen($parametros);
				$pos1 = stripos($parametros, "=")+1;
				$pos1f = stripos($parametros, "&");
				
				if($pos1f<1)
					{$Tam_pos1f = $tam;}
				else
					{$Tam_pos1f = $pos1f-$pos1;}
				//$ArqTorneio	= substr($parametros,$pos1,$pos1f-$pos1);
				$ArqTorneio	= substr($parametros,$pos1,$Tam_pos1f);
				
				$pos2 = stripos($parametros, "=",$pos1f)+1;
				$pos2f = stripos($parametros, "&",$pos1f+1);
				if($pos2f=='') {$pos2f = $tam;}
				$datarec = substr($parametros,$pos2,$pos2f-$pos2);
				
				$pos3 = stripos($parametros, "=",$pos2f)+1;
				$pos3f = stripos($parametros, "&",$pos2f+1);
				if($pos3f=='') {$pos3f = $tam;}
				$remetente	= rawurldecode(substr($parametros,$pos3,$pos3f-$pos3));
				
				$pos4 = stripos($parametros, "=",$pos3)+1;
				//$pos4f = stripos($parametros, "&",$pos3f);
				//$apresentacao 	= substr($parametros,$pos4,$pos4f-$pos4);
				$apresentacao = substr($parametros,$pos4);
				//$apresentacao = "geral";
				
				//echo "<br><br>*$ArqTorneio* - *$datarec* - *$apresentacao*<br><br>";
			}
			//$apresentacao = "geral";

  if($ArqTorneio=='')
		 {
			$ArqTorneio = trim($_POST['arquivo']);
		  $remetente = trim($_POST['remetente']);
		  $datarec = trim($_POST['datarec']);
			}
		
		$Diretor='"Não informado"';
		$ArbitrosAux='"Não informado"';
		$LocalTorneio='"Não informado"';
		$DataInicio='"Não informado"';
		$DataFinal='"Não informado"';
		$Ritmo='"Não informado"';
		$CatIdades='"Não informado"';
		$EMail='"Não informado"';
		$HomePage='"Não informado"';
		
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
		
		$Matriz_DesempatesSP[$Idioma][1][1]='';
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
		
		$fileINI = "../TorneiosSP/" . $ArqTorneio . ".ini";
		$fileTRN = "../TorneiosSP/" . $ArqTorneio . ".trn";
		$fileSCO = "../TorneiosSP/" . $ArqTorneio . ".sco";
		//echo '<br>%%%'.$fileINI.'%%%<br>';
		//echo "passou 1<br>";
		LerArqINI($fileINI);
		//echo "passou 2<br>";
		//exit;
		
		LerArqTRN($fileTRN);
		LerArqSCO($fileSCO);
		
	//-----------------------------------------------------------------------------  
		//Publicando HTML
    echo "<html><head>";
		 echo '<meta charset="UTF-8">';
    
				echo '<style type="text/css">
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
												{font-family:arial narrow;}
											
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
											
										</style>';
				//echo "<br><br>---".$NumRodadas."---<br><br>";
				echo "<script language='javascript' type='text/javascript'>
											NumRodadas=" . $NumRodadas . ";
										</script>";
				
				echo "<script language='javascript' type='text/javascript'>
											
											var coluna=new Array (0,0,0,0,0,0,0,0,0);
											
											function Ordenar_Tab(n,GrupoRef)
            {
													grp=GrupoRef;
													/* alert('<br>*** ' + n + ' - ' + GrupoRef + '***<br>'); */
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
				
				Conteudo='<table cellspacing=0 border=1>';
				Conteudo=Conteudo+'<tr><th><a href=\"#\"><span onclick=\"Ordenar_Tab(1,Grupo);\">Nr</span></a></th>';
				Conteudo=Conteudo+'<th><a href=\"#\"><span onclick=\"Ordenar_Tab(2,Grupo);\">TF</span></a></th>';
				Conteudo=Conteudo+'<th align=left><a href=\"#\"><span onclick=\"Ordenar_Tab(3,Grupo);\">Nome</span></a></th>';
				Conteudo=Conteudo+'<th><a href=\"#\"><span onclick=\"Ordenar_Tab(4,Grupo);\">FED</span></a></th>';
				Conteudo=Conteudo+'<th><a href=\"#\"><span onclick=\"Ordenar_Tab(5,Grupo);\">RatFIDE</span></a></th>';
				Conteudo=Conteudo+'<th><a href=\"#\"><span onclick=\"Ordenar_Tab(6,Grupo);\">RatNac</span></a></th>';
				Conteudo=Conteudo+'<th><a href=\"#\"><span onclick=\"Ordenar_Tab(7,Grupo);\">Clube</span></a></th></tr>';
				
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
							Conteudo=Conteudo+'<td>'+jogador[z].TitFIDE+'</td>';
							Conteudo=Conteudo+'<td>'+jogador[z].NomeJogador+'</td>';
							Conteudo=Conteudo+'<td>'+jogador[z].PaisJogador+'</td>';
							
							/* Conteudo=Conteudo+'<td>'+jogador[z].RatFIDE+'</td>'; */
							if(jogador[z].RatFIDE=='')
								{Conteudo=Conteudo+'<td>&nbsp;</td>';}
							else
								{Conteudo=Conteudo+'<td>'+jogador[z].RatFIDE+'</td>';}

							/* Conteudo=Conteudo+'<td>'+jogador[z].RatNAC+'</td>'; */
							if(jogador[z].RatNAC=='')
								{Conteudo=Conteudo+'<td>&nbsp;</td>';}
							else
								{Conteudo=Conteudo+'<td>'+jogador[z].RatNAC+'</td>';}
							
							Conteudo=Conteudo+'<td>'+jogador[z].Clube+'</td>';															
							Conteudo=Conteudo+'</tr>';
						}
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
													mostrarDetalhes=divDetalhes.style.visibility;
				         if(mostrarDetalhes=='hidden')
														{divDetalhes.style.visibility='visible';divDetalhes.style.height=165;divMostrarDetalhes.innerHTML='<a href=\"#\"><span onclick=\"MostrarDetalhes();\">Ocultar Detalhes</span></a>';}
													else
														{divDetalhes.style.visibility='hidden';divDetalhes.style.height=0;divMostrarDetalhes.innerHTML='<a href=\"#\"><span onclick=\"MostrarDetalhes();\">Mostrar Detalhes</span></a>';}
												}
         
           function MostrarEstat()
            {
													mostrarEstat=divEstatisticas.style.visibility;
				         if(mostrarEstat=='hidden')
														{divEstatisticas.style.visibility='visible';divEstatisticas.style.height='auto';divMostrarEstat.innerHTML='<a href=\"#\"><span onclick=\"MostrarEstat();\">Ocultar yyy Estatisticas</span></a>';}
													else
														{divEstatisticas.style.visibility='hidden'; divEstatisticas.style.height=0;divMostrarEstat.innerHTML='<a href=\"#\"><span onclick=\"MostrarEstat();\">Mostrar yyy Estatisticas</span></a>';}
												}
         
           function MostrarHorarios()
            {
													mostrarHorarios=divHorarios.style.visibility;
				         if(mostrarHorarios=='hidden')
														{divHorarios.style.visibility='visible';divHorarios.style.height='auto';divMostrarHorarios.innerHTML='&nbsp; &nbsp; <a href=\"#\"><span onclick=\"MostrarHorarios();\">Ocultar Horarios</span></a>';}
													else
														{divHorarios.style.visibility='hidden'; divHorarios.style.height=0;divMostrarHorarios.innerHTML='&nbsp; &nbsp; <a href=\"#\"><span onclick=\"MostrarHorarios();\">Mostrar Horarios</span></a>';}
												}
											
           function MostrarCrossTable()
            {
													mostrarCrossTable=divCrossTab.style.visibility;
				         if(mostrarCrossTable=='hidden')
														{divCrossTab.style.visibility='visible';divCrossTab.style.height='auto';divMostrarCrossTable.innerHTML='&nbsp; &nbsp; <a href=\"#\"><span onclick=\"MostrarCrossTable();\">Ocultar Quadro Sinóptico</span></a>';}
													else
														{divCrossTab.style.visibility='hidden'; divCrossTab.style.height=0;divMostrarCrossTable.innerHTML='&nbsp; &nbsp; <a href=\"#\"><span onclick=\"MostrarCrossTable();\">Mostrar Quadro Sinóptico</span></a>';}
												}
											
           function MostrarEmparceiramentos(rd)
            {
													
													/*id = 'divEmparceiramentos';*/
													obj=document.getElementById('divEmparceiramentos');
													/*alert(obj.id);*/
													
													idr = 'rd'+rd;
													objetor=document.getElementById(idr);
													
													id = 'divEmparceiramento'+rd;
													objeto=document.getElementById(id);
													CorFundoRodada=objetor.style.background;
													if(CorFundoRodada=='none repeat scroll 0% 0% rgb(187, 187, 187)') {CorFundoRodada='#bbbbbb';}
													FecharJanelas();
													if(CorFundoRodada=='#bbbbbb')
														{
															obj.style.visibility='hidden';obj.style.height=0;
															objeto.style.visibility='hidden'; objeto.style.height=0;
															objeto.style.position='relative';objeto.style.top=0;
															objetor.style.background='none';
														}
													else
														{
															obj.style.visibility='visible';obj.style.height='auto';
															objeto.style.visibility='visible';objeto.style.height='auto';
															objeto.style.position='absolute';objeto.style.top=130;
															objetor.style.background='#bbbbbb';
														}
											}
											
           function MostrarTodosEmparceiramentos(acao)
            {
													/*alert(acao);*/
													FecharJanelas();
													
													obj=document.getElementById('divEmparceiramentos');
				         if(acao==1)
														{obj.style.visibility='visible';obj.style.height='auto';}
													else
														{obj.style.visibility='hidden';obj.style.height=0;}
													
													for(i_rd=1;i_rd<=NumRodadas;i_rd++)
														{
															id = 'divEmparceiramento'+i_rd;
															objeto=document.getElementById(id);
															mostrarEmparceiramento=objeto.style.visibility;
															if(acao==1)
																{objeto.style.visibility='visible';objeto.style.height='auto';}
															else
																{objeto.style.visibility='hidden'; objeto.style.height=0;}
														}
													if(acao==1)
														{divMostrarTodos.innerHTML='&nbsp;&nbsp; <a href=\"#\"><span onclick=\"MostrarTodosEmparceiramentos(0);\">Ocultar todos</span></a>';}
													else
														{divMostrarTodos.innerHTML='&nbsp;&nbsp; <a href=\"#\"><span onclick=\"MostrarTodosEmparceiramentos(1);\">Mostrar todos</span></a>';}
											}
											
			function MostrarJogadores()
            {
				mostrarListaJogadores=divListaJogadores.style.visibility;
				if(mostrarListaJogadores=='hidden')
					{divListaJogadores.style.visibility='visible';divListaJogadores.style.height='199';tab_jogadores.style.height='199';tab_jogadores.style.visibility='visible';divMostrarListaJogadores.innerHTML='<a href=\"#\"><span onclick=\"MostrarJogadores();\">Ocultar Jogadores</span></a>';}
				else
					{divListaJogadores.style.visibility='hidden';divListaJogadores.style.height=0;tab_jogadores.style.height=0;tab_jogadores.style.visibility='hidden';divMostrarListaJogadores.innerHTML='<a href=\"#\"><span onclick=\"MostrarJogadores();\">Mostrar Jogadores</span></a>';}
			}
											
           function MostrarGrupoJogadores(grp,grpDescr)
            {
													/* alert('grp: ' + grp); */
													mostrarGrupoJogadores=divGrupoJogadores.style.visibility;
				         if(mostrarGrupoJogadores=='hidden')
														{
															FecharJanelas();
															document.getElementById('spanGrupoDescr').innerHTML=grpDescr;
															document.getElementById('Grupo_Jogadores').innerHTML=ImprimirJogadores(grpDescr);
															/* document.getElementById('Grupo_Jogadores').innerHTML=ImprimirJogadores(''); */
															divGrupoJogadores.style.visibility='visible';divGrupoJogadores.style.height='auto';Grupo_Jogadores.style.height='auto';Grupo_Jogadores.style.visibility='visible'; /*divMostrarGrupoJogadores.innerHTML='<a href=\"#\"><span onclick=\"MostrarGrupoJogadores(grp,grpDescr);\">Ocultar Grupos</span></a>';*/
														}
													else
														{divGrupoJogadores.style.visibility='hidden'; divGrupoJogadores.style.height=0;     Grupo_Jogadores.style.height=0;     Grupo_Jogadores.style.visibility='hidden'; /*divMostrarGrupoJogadores.innerHTML='<a href=\"#\"><span onclick=\"MostrarGrupoJogadores(grp,grpDescr);\">Mostrar Grupos</span></a>';*/}
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
													divGrupoJogadores.style.visibility='visible';divGrupoJogadores.style.height='auto';Grupo_Jogadores.style.height='auto';Grupo_Jogadores.style.visibility='visible'; /*divMostrarGrupoJogadores.innerHTML='<a href=\"#\"><span onclick=\"MostrarGrupoJogadores(grp,grpDescr);\">Ocultar Grupos</span></a>';*/
													if(CorFundoGrupo=='#bbbbbb')
														{
															obj.style.visibility='hidden';obj.style.height=0;
															objeto.style.visibility='hidden'; objeto.style.height=0;
															objetog.style.background='none';
														}
													else
														{
															obj.style.visibility='visible';obj.style.height='auto';
															objeto.style.visibility='visible';objeto.style.height='auto';
															objetog.style.background='#bbbbbb';
														}
												}
											
											function FecharJanelas()
            {
													divDetalhes.style.visibility='hidden';divDetalhes.style.height=0;divMostrarDetalhes.innerHTML='<a href=\"#\"><span onclick=\"MostrarJanela(divDetalhes.id,divMostrarDetalhes.id);\">Mostrar Detalhes</span></a>';
							  /* alert(\"chegou em Fechar\"); */
													divEstatisticas.style.visibility='hidden';divEstatisticas.style.height=0;divMostrarEstat.innerHTML='<a href=\"#\"><span onclick=\"MostrarJanela(divEstatisticas.id,divMostrarEstat.id);\">Mostrar Estatísticas</span></a>';
													divHorarios.style.visibility='hidden';divHorarios.style.height=0;divMostrarHorarios.innerHTML='<a href=\"#\"><span onclick=\"MostrarJanela(divHorarios.id,divMostrarHorarios.id);\">Mostrar Horarios</span></a>';
													divCrossTab.style.visibility='hidden';divCrossTab.style.height=0;divMostrarCrossTable.innerHTML='<a href=\"#\"><span onclick=\"MostrarJanela(divCrossTab.id,divMostrarCrossTable.id);\">Mostrar Quadro Sinóptico</span></a>';
													divTieBreak.style.visibility='hidden';divTieBreak.style.height=0;divMostrarCriterios.innerHTML='<a href=\"#\"><span onclick=\"MostrarJanela(divTieBreak.id,divMostrarCriterios.id);\">Mostrar Critérios</span></a>';
													divClassif.style.visibility='hidden';divClassif.style.height=0; divMostrarClassif.innerHTML='<a href=\"#\"><span onclick=\"MostrarJanela(divClassif.id,divMostrarClassif.id);\">Mostrar Classificação</span></a>';
													
													divListaJogadores.style.visibility='hidden';divListaJogadores.style.height=0;tab_jogadores.style.height=0;tab_jogadores.style.visibility='hidden';divMostrarListaJogadores.innerHTML='<a href=\"#\"><span onclick=\"MostrarJanela(divListaJogadores.id,divMostrarListaJogadores.id);\">Mostrar Jogadores</span></a>';
													divGrupoJogadores.style.visibility='hidden';divGrupoJogadores.style.height=0;Grupo_Jogadores.style.height=0;Grupo_Jogadores.style.visibility='hidden'; /*divMostrarGrupoJogadores.innerHTML='<a href=\"#\"><span onclick=\"MostrarJanela(divGrupoJogadores.id,divMostrarGrupoJogadores.id);\">Mostrar Grupos</span></a>';*/
													
													divSelOrdem.style.visibility='hidden';
													divEmparceiramentos.style.visibility='hidden';divEmparceiramentos.style.height=0;
							  /* alert(\"processando em Fechar 1\"); */
													divMostrarTodos.innerHTML='&nbsp;&nbsp; <a href=\"#\"><span onclick=\"MostrarTodosEmparceiramentos(1);\">Mostrar todos</span></a>';
													
													for (var i=1;i<=NumRodadas;i++)
														{
																var id = 'divEmparceiramento'+i;
																objeto_rod=document.getElementById(id);
																objeto_rod.style.visibility='hidden';
																objeto_rod.style.height=0;
																objeto_rod.style.position='relative';
																objeto_rod.style.top=0;
																
																var idr = 'rd'+i;
																objeto_r=document.getElementById(idr);
																/*alert(objeto_r.id);*/
																objeto_r.style.background='none';
															}
							  /* alert(\"processando em Fechar 2\"); */
													NumGrupos=0;	/* ************************************* */
													for (var i=0;i<NumGrupos;i++)
														{
																var idg = 'gp'+i;
																objeto_g=document.getElementById(idg);
																objeto_g.style.background='none';
														}
							  /* alert(\"processando em Fechar 3\"); */
												}
           
           
											function MostrarJanela(Janela,CtrJanela)
            {
							 /* alert(\"sgsgsgsg \" + Janela); */
													switch(Janela)
														{
															case \"divDetalhes\":
							 /* alert(\"passou 1\"); */
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
															case \"divCrossTab\":
																msgMostrar=\"Mostrar Quadro Sinóptico\";
																msgOcultar=\"Ocultar Quadro Sinóptico\";
																break;
															case \"divTieBreak\":
																msgMostrar=\"Mostrar Critérios\";
																msgOcultar=\"Ocultar Critérios\";
																break;
															case \"divClassif\":
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
													 /* alert(mostrarJanela); */
							  /* alert(\"vai para Fechar\"); */
													FecharJanelas();
							  /* alert(\"voltou de Fechar\"); */
													
													if(Janela=='divListaJogadores')
														{MostrarJogadores();}
													else
														{
															
															if(mostrarJanela=='hidden')
																{
																	objeto.style.visibility='visible';objeto.style.height='auto';
																	objetoCtr.innerHTML='<a href=\"#\"><span onclick=\"MostrarJanela(objeto.id,objetoCtr.id);\">' + msgOcultar + '</span></a>';
																}
															else
																{
																	objeto.style.visibility='hidden'; objeto.style.height=0;
																	objetoCtr.innerHTML='<a href=\"#\"><span onclick=\"MostrarJanela(objeto.id,objetoCtr.id);\">' + msgMostrar + '</span></a>';
																}
														}
												}
         
           function funcExibirCriterio(desempate,criterio,descricao,TieBreakParam)
            {
													/* alert(TieBreakParam); */
													ExibirCriterio.innerHTML= '&nbsp;(' + desempate + ') <b>' + criterio + ' - ' + descricao + '</b>&nbsp;' + TieBreakParam + '</b>&nbsp;';
													ExibirCriterio.style.visibility='visible';ExibirCriterio.style.height=20;
												}
											
           function OcultarExibirCriterio()
            {
													/* alert('kkkkk'); */
													ExibirCriterio.style.visibility='hidden';
												}
												
           function funcExibirGrupo(DescrGrupo)
            {
														ExibirGrupo.innerHTML= '&nbsp;Grupo <b>\"' + DescrGrupo + '\"</b>&nbsp;';
														ExibirGrupo.style.visibility='visible';ExibirGrupo.style.height=20;
												}
											
           function OcultarExibirGrupo()
            {
													ExibirGrupo.style.visibility='hidden';
												}
											
										</script>";
    echo "</head>";

    echo '<body>';
			 
    echo ' <div id="divSelOrdem" name="divSelOrdem" style="visibility:hidden;position:absolute;z-index:1;left:658;top:113;width:170;height:0;padding:1;background:#ddffdd;border:1px solid #2266AA;">';
    echo '  <b>Recurso em Construção</b>!<hr>';
				echo "  <script language='javascript' type='text/javascript'>
												 for(ii=0;ii<7;ii++) {document.write(TitCol[ii]+'<br>');}
													Grup='';
													/* alert(Grup); */
										  </script>";
    //echo '  <br> (RatFIDE+RatNAC) &nbsp;<b><a href="#"><span onclick="alert(Grupo);Ordenar_Tab(8,Grupo);'.'">Ordenar</span></a></b>';
    echo '  <br> (RatFIDE+RatNAC) &nbsp;<b><a href="#"><span onclick="Ordenar_Tab(8,Grup);">Ordenar</span></a></b>';
    echo ' </div>';
    
				//echo " Título: <font size=+1><b>$TituloTorneio</b></font><br>";
				echo ' <div style="position:relative;padding:1;height:22;width:824;padding:1;">';
    echo "  <div style='position:relative;float:left;'>Título: <font size=+1><b>$TituloTorneio</b></font></div>";
    echo "  <div style='position:relative;float:right;'>(Swiss Perfect)</div>";
    echo ' </div>';
    
				echo ' <div style="position:relative;padding:1;height:22;width:824;padding:1;border:1px solid #2266AA;">';
				echo '  <div id="divMostrarDetalhes" name="divMostrarDetalhes" style="visibility:visible;position:relative;float:left;width:120;border:1px solid #2266AA;">';
    echo '   <a href="#"><span onclick="MostrarJanela(divDetalhes.id,divMostrarDetalhes.id);">Mostrar Detalhes</span></a>';
    echo '  </div>';
				echo '  <div id="divMostrarCriterios" name="divMostrarCriterios" style="visibility:visible;position:relative;float:left;left:1;width:110;border:1px solid #2266AA;">';
    echo '   <a href="#"><span onclick="MostrarJanela(divTieBreak.id,divMostrarCriterios.id);">Mostrar Critérios</span></a>';
    echo '  </div>';
		
		//Grupos:
		//echo '  <div id="divMostrarHorarios" name="divMostrarHorarios" style="visibility:visible;position:relative;float:left;left:2;width:110;border:1px solid #2266AA;">';
		echo '  <div id="divMostrarHorarios" name="divMostrarHorarios" style="visibility:visible;position:relative;float:left;left:2;display:none;border:1px solid #2266AA;">';
    echo ' <a href="#"><span onclick="MostrarJanela(divHorarios.id,divMostrarHorarios.id);">Mostrar Horários</span></a>';
    echo '  </div>';
		if($DataRodada[1]!='')
			{
				echo "<script language='javascript' type='text/javascript'>";				
				echo " divMostrarGrupoJogadores.style.display='inline';";
				echo '</script>';
			}
		
		$data_ult=substr($datarec,8,2).'/'.substr($datarec,5,2).'/'.substr($datarec,0,4);
		echo '  <div id="divAtualizacao" name="divAtualizacao" style="visibility:visible;position:relative;float:left;left:3;width:470;border:1px solid #2266AA;">';
    echo "   Última Atualização: $data_ult &nbsp; &nbsp; &nbsp; ";
    echo "   Remetente: $remetente";
    echo '  </div>';
    echo ' </div>';
		
		echo ' <div style="position:relative;height:22;width:824;top:1;padding:1;border:1px solid #2266AA;">';
		echo '  <div id="divMostrarListaJogadores" name="divMostrarListaJogadores" style="visibility:visible;position:relative;float:left;width:120;border:1px solid #2266AA;">';
    echo '   <a href="#"><span onclick="MostrarJanela(divListaJogadores.id,divMostrarListaJogadores.id);">Mostrar Jogadores</span></a>';
    echo '  </div>';
				
		//echo '  <div id="divMostrarGrupoJogadores" name="divMostrarGrupoJogadores" style="visibility:visible;position:relative;float:left;border:1px solid #2266AA;">';
		echo '  <div id="divMostrarGrupoJogadores" name="divMostrarGrupoJogadores" style="visibility:visible;position:relative;float:left;left:1;display:none;border:1px solid #2266AA;">';
    //$NumGrupos=4;$GrupoDescr[0]='M';$GrupoDescr[1]='A';$GrupoDescr[2]='B';$GrupoDescr[3]='C';
		echo '   Grupos:';
		for ($g=0;$g<$NumGrupos;$g++)
			{
				$gd="\"".$GrupoDescr[$g]."\"";
				$grpDescr=$gd; //$GrupoDescr[$g];
				//echo   "&nbsp;<a href=\"#\"><span onmouseout='OcultarExibirGrupo();' onmouseover='funcExibirGrupo($gd);' onclick='MostrarGrupoJogadores($gd);'>Gp" . ($g+1) . "</span></a>";
				echo   "&nbsp;<a href=\"#\"><span id='gp".$g."' onmouseout='OcultarExibirGrupo();' onmouseover='funcExibirGrupo($gd);' onclick='MostrarGrupoJogadoresN($g,$grpDescr);'>Gp" . ($g+1) . "</span></a>";
				}
		echo '&nbsp; ';
    echo '  </div>';
				if($NumGrupos>1)
					{
						echo "<script language='javascript' type='text/javascript'>";				
						echo " divMostrarGrupoJogadores.style.display='inline';";
						echo '</script>';
					}
				
				echo "<script language='javascript' type='text/javascript'>";				
				echo " GrupoDescr=new Array();";
				for ($g=0;$g<$NumGrupos;$g++)
				 { echo "GrupoDescr[".$g."]='".$GrupoDescr[$g]."';"; }
				echo "NumGrupos=".$NumGrupos.";";
				//echo 'alert(GrupoDescr[1]);';
				echo '</script>';
				//echo '<br>DD: '.$GrupoDescr[1].'*<br><br>';
				
				
				echo '  <div id="divMostrarEstat" name="divMostrarEstat" style="visibility:visible;position:relative;float:left;left:2;width:130;border:1px solid #2266AA;">';
    echo '   <a href="#"><span onclick="MostrarJanela(divEstatisticas.id,divMostrarEstat.id);">Mostrar Estatísticas</span></a>';
    echo '  </div>';
				echo '  <div id="divMostrarClassif" name="divMostrarClassif" style="visibility:visible;position:relative;float:left;left:3;width:140;border:1px solid #2266AA;">';
    echo '   <a href="#"><span onclick="MostrarJanela(divClassif.id,divMostrarClassif.id);">Mostrar Classificação</span></a>';
    echo '  </div>';
				echo '  <div id="divMostrarCrossTable" name="divMostrarCrossTable" style="visibility:visible;position:relative;float:left;left:4;width:166;border:1px solid #2266AA;">';
    echo '   <a href="#"><span onclick="MostrarJanela(divCrossTab.id,divMostrarCrossTable.id);">Mostrar Quadro Sinóptico</span></a>';
    echo '  </div>';
    echo ' </div>';
				
				echo ' <div style="position:relative;height:22;padding:1;top:2;width:824;border:1px solid #2266AA;">';
				
				echo '  <div id="divMostrarEmparceiramentos" name="divMostrarEmparceiramentos" style="visibility:visible;position:relative;float:left;border:1px solid #2266AA;">';
    echo '   Emparceiramentos/Resultados:';
													for ($r=1;$r<=$NumRodadas;$r++)
														{
															echo '&nbsp;<a href="#"><span id="rd'.$r.'" onclick="MostrarEmparceiramentos('.$r.');">'.$r.'ªrd</span></a> ';
														}
													echo '&nbsp; ';
    echo '  </div>';
				
				echo '  <div id="divMostrarTodos" name="divMostrarTodos" style="visibility:visible;position:relative;float:left;left:1;border:1px solid #2266AA;">';
				echo '			&nbsp;&nbsp; <a href="#"><span onclick="MostrarTodosEmparceiramentos(1);">Mostrar todos</span></a> ';
    echo '  </div>';
				
    echo ' </div>';
				
    
				echo ' <div id="divDetalhes" name="divDetalhes" style="visibility:hidden;position:absolute;top:110;left:10;width:824;height:0;padding;1;border:1px solid #2266AA;">';
				//echo ' <div id="divDetalhes" name="divDetalhes" style="visibility:visible;position:absolute;top:110;left:10;width:824;height:0;padding;1;border:1px solid #2266AA;">';
												if($RepetRodadas==2) {$RoundRobinDuplo='(Duplo)';}
				
				//if($EquipIndiv==0) {$SistTorneio=' Individual';} else {$SistTorneio=' po Equipes';}
				//$TipoSistTorneio = '('.$TipoTorneio.'-'.$EquipIndiv.')'.$DescrTipoTorneio[$TipoTorneio] . $SistTorneio;
				switch ($TipoTorneio)
					{
						case 0:
							switch ($EquipIndiv)
								{
									case 0:
									 $TipTorn=0;
										break;
									case 1:
									 $TipTorn=3;
										break;
								}
							break;
						case 1:
							switch ($EquipIndiv)
								{
									case 0:
									 $TipTorn=1;
										break;
									case 1:
									 $TipTorn=2;
										break;
								}
							break;
					}
				
				//<b>Tipo do Torneio</b>: $TipoTorneio - $DescrTipoTorneio[$TipoTorneio] $RoundRobinDuplo<br>
				$TipoSistTorneio = '('.$TipoTorneio.'-'.$EquipIndiv.') '.$TipTorn.'-'.$DescrTipoTorneio[$TipoTorneio] . $SistTorneio;
				
				//<b>Página do Evento na Internet</b>: <a href='$HomePage'>$HomePage</a><br>
				if($HomePage=='"Não informado"')	
					{$PaginaWeb = $HomePage;}
				else	
					{$PaginaWeb = '<a href="'.$HomePage.'">'.$HomePage.'</a>';}
				echo "  <b>Organização</b>: $Organizador<br>
											 <b>Diretor</b>: $Diretor<br>
											 <b>Árbitro Principal</b>: $ArbitroPrincipal<br>
											 <b>Árbitros Auxiliares</b>: $ArbitrosAux<br>
											 <b>Local</b>: $LocalTorneio<br>
											 <b>Período</b>: $DataInicio a $DataFinal<br>
												<b>Número de Rodadas</b>: $NumRodadas<br>
												<b>Tipo do Torneio</b>: $TipoSistTorneio $RoundRobinDuplo<br>
												<b>Ritmo</b>: $Ritmo<br>
												<b>Categorias de Idade</b>: $CatIdades<br>
												<b>Quant. de Jogadores</b>: $QtJogadores<br>
											 <b>Elo Médio</b>: $EloMedio<br>
												<b>Correio Eletrônico</b>: $EMail<br>
												<b>Página do Evento na Internet</b>: $PaginaWeb<br>
												
												<b>Tabuleiro Inicial</b>: $TabulInicial<br>
												<b>Multi Games</b>: $MultiGames<br>
												<b>Sub-Escore</b>: $SubEscore<br>
												
												<br>
									";
    echo ' </div>';
				
				ksort($Federacoes);
				//echo'<font size=1><br></font>';
				echo '<div id="divEstatisticas" name="divEstatisticas" style="visibility:hidden;height:0;padding:1;position:absolute;top:110;width:824;padding:2;border:1px solid #2266AA;">';
    echo ' <b>Estatística do Torneio</b>:<br>';
    echo ' <table cellspacing=0 width=100%>';
    echo ' <tr valign=top><td><b>Federações</b>: &nbsp;<br>';
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
						if(trim($i)=='')
							{echo " <td>$nf</td><td>&nbsp;</td><td align=right>$Federacoes[$i]</td></tr>";}
						else
							{echo " <td>$nf</td><td>$i</td><td align=right>$Federacoes[$i]</td></tr>";}
     }
    echo ' </table>';
    
				echo ' </td>';
    echo ' <td><b>Títulos</b>: &nbsp;<br>';
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
    echo ' <td><b>Clubes</b>: &nbsp;<br>';
    echo ' <table cellspacing=0 border=1>';
				echo "  <tr><th>N.Clubes</th><th>Clubes</th><th>QtClubes</th></tr>";
				$nt=0;
				foreach ($Clubes as $i => $value)
				 {
						if($Clubes[$i]>0)
						 {
								$nc++;
								if($nc % 2 == 0)
									{echo ' <tr bgcolor="#e0e0e0">';}
								else
									{echo ' <tr bgcolor="#ffffff">';}
							 //echo ' <tr>';
						  //echo "<td>$i</td><td align=right>$Clubes[$i]</td></tr>";
								if(trim($i)=='')
									{echo " <td>$nc</td><td>&nbsp;</td><td align=right>$Clubes[$i]</td></tr>";}
								else
									{echo " <td>$nc</td><td>$i</td><td align=right>$Clubes[$i]</td></tr>";}
							}
     }
    echo ' </table>';
    
				echo ' </td>';
    echo ' <td><b>Jogos/Resultados</b>:<br>';
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
						//echo "<tr>";
						$Totr[$r] = $VitBr[$r] + $Empatesr[$r] + $VitNr[$r] + $Ausr[$r];
						echo "<td>$r</td><td>$VitBr[$r]</td><td>$Empatesr[$r]</td><td>$VitNr[$r]</td><td>$Ausr[$r]</td><td>$Totr[$r]</td>";
						$VitBt=$VitBt+$VitBr[$r]; $Empatest=$Empatest+$Empatesr[$r]; $VitNt=$VitNt+$VitNr[$r]; $Aust=$Aust+$Ausr[$r]; $Tott=$Tott+$Totr[$r];
					}
								$nt++;
								if($nt % 2 == 0)
									{echo '<tr bgcolor="#e0e0e0">';}
								else
									{echo '<tr bgcolor="#ffffff">';}
				//echo "<tr>";
				echo "<td>Totais</td><td>$VitBt</td><td>$Empatest</td><td>$VitNt</td><td>$Aust</td><td>$Tott</td>";
    echo ' </table>';
    echo ' </td></tr>';
    echo ' </table>';
				echo '</div>';
				
				echo '<div id="divHorarios" name="divHorarios" style="visibility:hidden;position:absolute;top:110;left:10;height:0;width:400;border:1px solid #2266AA;">';
				for($i=1;$i<=$NumRodadas;$i++)
					{
						echo "$i"."ª Rodada: $DataRodada[$i] - Horário: $HorarioRodada[$i] - Tabuleiros: $TabulRodada[$i]<br>";
					}
				echo '</div>';
				
				echo '<div id="divEmparceiramentos" name="divEmparceiramentos" style="visibility:hidden;height:0;width:824;padding:1;border:0px solid #2266AA;">';
				echo "<b>Emparceiramentos e Resultados:</b><br>";
				for($i=1;$i<=$NumRodadas;$i++)
				//for($i=1;$i<=6;$i++)
					{
						echo '<div id="divEmparceiramento'.$i.'" name="divEmparceiramento'.$i.'" style="visibility:hidden;height:0;width:824;padding:1;border:0px solid #2266AA;">';
						//echo 'Rodada '.$i.' - Emparceiramento  --- em Construção ---';
						//echo '<b>Emparceiramentos/Resultados</b>:<br>';
								
								$r=$i;
								//for ($r=1;$r<=$NumRodadas;$r++)
								//	{
										echo "<b>" . $r . "ª Rodada: </b><br>";
										echo "<table cellspacing=0 border=1>";
										
										if($i_ord % 2 == 0)
											{echo ' <tr bgcolor="#e0e0e0">';}
										else
											{echo ' <tr bgcolor="#ffffff">';}
										//echo '<tr>';
										
										echo "<tr><td><b>Mesa</td><td><b>Nome</td><td><b>Pts</td><td><b><center>Resultado</center></td><td><b>Pts</td><td><b> &nbsp; Nome</td></tr>";
										
										for ($m=1;$m<=$TabulRodada[$r];$m++)
											{
												
												if($m % 2 == 0)
													{echo ' <tr bgcolor="#e0e0e0">';}
												else
													{echo ' <tr bgcolor="#ffffff">';}
												//echo '<tr>';
												
												echo "<td>$m</td><td>";
												echo $RodMesaResult[$r][$m][1];
												echo "</td><td>";
												echo $RodMesaResult[$r-1][$m][2];		// *** 2019/11/19 ***
												echo "</td><td><center>";
												echo $RodMesaResult[$r][$m][3];
												echo "</center></td><td>";
												echo $RodMesaResult[$r-1][$m][4];		// *** 2019/11/19 ***
												echo "</td><td>";
												echo $RodMesaResult[$r][$m][5];
												echo "</td></tr>";
											}
										echo "</table>";
								//	}
						echo '</div>';
					}
				echo '</div>';
					
					
					/*
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
					*/
					
				//echo'<font size=1><br></font>';
				echo '<div id="divCrossTab" name="divCrossTab" style="font-family:Arial Narrow,Helvetica,sans-serif;font-size:16;visibility:hidden;position:absolute;top:110;height:0;width:824;padding:1;border:1px solid #2266AA;">';
						// ----- Cross Table / p/Pontuação --------------------------------------
						echo '<b>Quadro Sinóptico: (p/Pontuação)</b><br>';
						
						echo '<table cellspacing=0 width=100% border=1 style="font-family:Arial Narrow,Helvetica,sans-serif;font-size:14;">';
						echo '<tr><td><b>Clas</td><td><b>Tit</td><td><b>Nome</td><td><b>RatF</td><td><b>RatL</td><td><b>RatP</td><td><b>FED</td>';
						for ($r=1;$r<=$NumRodadas;$r++)
							{
								echo '<td align="center"><b>'.$r.'ª Rod</td>';
							}
						echo '<td><b>Pts</td></tr>';
						
						arsort($PontosJogador);
						foreach ($PontosJogador as $chave => $valor)
							{
								if($chave>0 AND $NumInic[$chave])
									{
										$i_ord++;
										
										if($i_ord % 2 == 0)
											{echo ' <tr bgcolor="#e0e0e0">';}
										else
											{echo ' <tr bgcolor="#ffffff">';}
										//echo '<tr>';
										
										echo ' <td>'.$i_ord.'</td>';
										echo ' <td>'.$TitFIDE[$chave].'</td>';
										//echo ' <td>'.$NomeJogador[$chave].' ('.$chave.')</td>';
										echo ' <td>'.$NomeJogador[$chave].' ('.$NumInic[$chave].')</td>';
										
										if($RatFIDE[$chave]=='')
											{echo ' <td>'.'&nbsp;'.'</td>';}
										else
											{echo ' <td>'.$RatFIDE[$chave].'</td>';}
										if($RatNacional[$chave]=='')
											{echo ' <td>'.'&nbsp;'.'</td>';}
										else
											{echo ' <td>'.$RatNacional[$chave].'</td>';}
										if($RatPrinc[$chave]=='')
											{echo ' <td>'.'&nbsp;'.'</td>';}
										else
											{echo ' <td>'.$RatPrinc[$chave].'</td>';}
											
										echo ' <td>'.$PaisJogador[$chave].'</td>';
										for ($r=1;$r<=$NumRodadas;$r++)
											{
												//echo '<td>'.$CrossTable[$chave][$r][1].$CrossTable[$chave][$r][2].$CrossTable[$chave][$r][3].'</td>';
												/*
												echo '<td align="center">
																			<table class="borderless"><tr>'.
																				'<td class="borderlessR">'.$CrossTable[$chave][$r][1].'</td>'.
																				'<td class="borderlessC">'.$CrossTable[$chave][$r][2].'</td>'.
																				'<td class="borderlessL">'.$CrossTable[$chave][$r][3].'</td>'.
																			'</tr></table>'.
																		'</td>';
													*/
												echo '<td align="center">'.$CrossTable[$chave][$r][1].$CrossTable[$chave][$r][2].$CrossTable[$chave][$r][3].'</td>';
											}
										echo '<td>'.$PontosJogador[$chave].'</td>';
										echo '</tr>';
									}
							}
							
						echo '</table>';
					
				echo '</div>';
					
					echo '<div id="divTieBreak" name="divTieBreak" style="font-family:Arial Narrow,Helvetica,sans-serif;font-size:16;visibility:hidden;position:absolute;top:110;height:0;width:824;padding:1;border:1px solid #2266AA;">';
					echo '<b>Critérios de Desempate</b>:<br>';
					if($QtDesempates>0)
					 {
							
							for($NrDesempate=1;$NrDesempate<=$QtDesempates;$NrDesempate++)
								{
									$TieBreakParam[$NrDesempate] = "($QtPior[$NrDesempate],$QtMelhor[$NrDesempate],$ExpAdic1C[$NrDesempate],$PNJ_Param[$NrDesempate],$pJogEqRet[$NrDesempate],$EquipBye_Param[$NrDesempate])";
									//echo $TieBreakParam[$NrDesempate].'<br>';
									echo '<span style="line-height:50%;"><br></span>';
							 
								/*		
									if($Desempate[$NrDesempate]>100)
									{echo "&nbsp; &nbsp; <b>$NrDesempate</b>° Critério: &nbsp; <b>" . $Matriz_DesempatesSP[$Idioma][$Desempate[$NrDesempate]][1] . '</b>';$TieBreakParam[$NrDesempate]='';}
								else
									{echo "&nbsp; &nbsp; <b>$NrDesempate</b>° Critério: &nbsp; <b>$Desempate[$NrDesempate]</b>-<b>" . $Matriz_DesempatesSP[$Idioma][$Desempate[$NrDesempate]][1] . '</b>';}
							 */
								echo "&nbsp; &nbsp; <b>$NrDesempate</b>° Critério: &nbsp; <b>" . $Matriz_DesempatesSP[$Idioma][$Desempate[$NrDesempate]][1] . '</b>';$TieBreakParam[$NrDesempate]='';
									
									if(strtoupper($Matriz_DesempatesSP[$Idioma][$Desempate[$NrDesempate]][2])!='N')
										{echo ' '.$TieBreakParam[$NrDesempate];}
									echo '<br>';
									//if(($Desempate[$NrDesempate]>1 && $Desempate[$NrDesempate]<5) || ($Desempate[$NrDesempate]>15 && $Desempate[$NrDesempate]<17) || ($Desempate[$NrDesempate]==22) || ($Desempate[$NrDesempate]>27 && $Desempate[$NrDesempate]<32) || ($Desempate[$NrDesempate]==34) || ($Desempate[$NrDesempate]==37))
									//if($Desempate[$NrDesempate]==44 || $Desempate[$NrDesempate]==52 || $Desempate[$NrDesempate]==54 || $Desempate[$NrDesempate]==55 || $Desempate[$NrDesempate]==60)
									if(strtoupper($Matriz_DesempatesSP[$Idioma][$Desempate[$NrDesempate]][2])=='S')
										{
											if($Matriz_DesempatesSP[$Idioma][$Desempate[$NrDesempate]][2]=='s') {echo "&nbsp; &nbsp; &nbsp; &nbsp; - <i>***Critério em implementação***</i><br>";}
											if($Corte[$NrDesempate]==0)
												{echo "&nbsp; &nbsp; &nbsp; - Sem Cortes<br>";}
											else
												{echo "&nbsp; &nbsp; &nbsp; - Corte:  ->  Melhor(es)=$CorteBhQtMelhor[$NrDesempate] / Pior(es)=$CorteBhQtPior[$NrDesempate]<br>";}
											//echo "&nbsp; &nbsp; &nbsp; - Partidas não jogadas (wo, bye,...): *$ExpPNJ[$NrDesempate]* &nbsp; &nbsp; - &nbsp;  Adicionar pontos próprios: $ExpAdic[$NrDesempate]<br>";
											
											//$BuchholzAdjust[$i]
											if($BuchholzAdjust[$NrDesempate]==1)
											 {echo "&nbsp; &nbsp; &nbsp; - Partidas não jogadas (wo, bye,...): 0.5 ponto<br>";}
											else
											 {echo "&nbsp; &nbsp; &nbsp; - Partidas não jogadas (wo, bye,...): pontos reais<br>";}
											
											//echo "($NrDesempate,$Corte[$NrDesempate],$QtMelhor[$NrDesempate],$QtPior[$NrDesempate],$PNJ_Adic[$NrDesempate],*$PNJ[$NrDesempate]*,$Adic[$NrDesempate],$ExpPNJ[$NrDesempate],$ExpAdic[$NrDesempate])<br>";
											//echo "($QtPior[$NrDesempate],$QtMelhor[$NrDesempate],$ExpAdic1C[$NrDesempate],$PNJ_Param[$NrDesempate],$pJogEqRet[$NrDesempate],$EquipBye_Param[$NrDesempate])<br>";
										}
									else
										{
											//if(($Desempate[$NrDesempate]==1) OR ($Desempate[$NrDesempate]==11) OR ($Desempate[$NrDesempate]==12) OR ($Desempate[$NrDesempate]==45) OR ($Desempate[$NrDesempate]==53))
											if(strtoupper($Matriz_DesempatesSP[$Idioma][$Desempate[$NrDesempate]][2])=='N')
												{
													if($Matriz_DesempatesSP[$Idioma][$Desempate[$NrDesempate]][2]=='n') {echo "&nbsp; &nbsp; &nbsp; &nbsp; - <i>***Critério em implementação***</i><br>";}
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
					
				
				//echo " document.write(jogador[0].NomeJogador);";
				echo "<script language='javascript' type='text/javascript'>";
				//echo "alert('yyyyyyyyyyyyyyy');";
				echo " jogador=new Array();";
				
				for ($z=1;$z<=$QtJogadores;$z++)
				//for ($z=1;$z<=5;$z++)
				 {
				  $z1=$z-1;
				  echo "jogador[".$z1."]=new Object();";
					 echo "jogador[".$z1."].ord_ini='".$z."';";
					 //echo "jogador[".$z1."].TitFIDE='".RetirarNulo($TitFIDE[$z])."';";
					 echo "jogador[".$z1."].TitFIDE='".$TitFIDE[$z]."';";
					 //echo "jogador[".$z1."].NomeJogador='".$NomeJogador[$z]."';";
					 echo "jogador[".$z1."].NomeJogador='".htmlentities($NomeJogador[$z], ENT_QUOTES)."';";
					 echo "jogador[".$z1."].PaisJogador='".$PaisJogador[$z]."';";
					 echo "jogador[".$z1."].RatFIDE='".$RatFIDE[$z]."';";
					 echo "jogador[".$z1."].RatNAC='".$RatNacional[$z]."';";
						if(strlen($Clube[$z])<1) {$Clube[$z]="&nbsp;";}
					 echo "jogador[".$z1."].Clube='".$Clube[$z]."';";
					 echo "jogador[".$z1."].Grupo='".$Grupo[$z]."';";
					}
					
				//echo " document.write(jogador[1].NomeJogador);";
				echo '</script>';
				
				//divListaJogadores tab_jogadores divMostrarListaJogadores
				echo '<div id="divListaJogadores" name="divListaJogadores" style="visibility:hidden;position:absolute;top:110;height:0;left:10;width:824;border:1px solid #2266AA;">';
    echo ' <b>Dados dos Jogadores</b>: (Ordenação: ';
    echo '  Clique nos Títulos das colunas ou em <b><a href="#"><span onclick="divOrdenar();">Ordenação Composta</span></a></b>)<br>';
				echo " <div id='tab_jogadores' name='tab_jogadores' style='visibility:hiden;heigh:0;'>";
				echo "  Tabela de Jogadores   : $QtJogadores";
				echo ' </div>';
				echo '</div>';
				echo '<script language="javascript" type="text/javascript">';
				echo 'document.getElementById("tab_jogadores").innerHTML=ImprimirJogadores("");';
				echo '</script>';
				
				//divGrupoJogadores Grupo_Jogadores divMostrarGrupoJogadores
				echo '<div id="divGrupoJogadores" name="divGrupoJogadores" style="visibility:hidden;position:absolute;top:110;height:0;left:10;width:824;border:1px solid #2266AA;">';
    echo ' <b>Grupo de Jogadores</b>: '; echo '<b><span id="spanGrupoDescr"></span></b> &nbsp; &nbsp; ';
    echo '  (Ordenação: Clique nos Títulos das colunas ou em <b><a href="#"><span onclick="divOrdenar();">Ordenação Composta</span></a></b>)<br>';
				echo " <div id='Grupo_Jogadores' name='Grupo_Jogadores' style='visibility:hiden;heigh:0;'>";
				echo "  Jogadores do Grupo X<br>";
				echo ' </div>';
				echo '</div>';
				echo '<script language="javascript" type="text/javascript">';
				echo 'document.getElementById("Grupo_Jogadores").innerHTML=ImprimirJogadores("");';
				echo '</script>';

    echo '<div id="ExibirGrupo" name="ExibirGrupo" style="visibility:hidden;position:absolute;left:300;top:34;height:0;width:auto;background:#ddffdd;color:#0000ff;padding:1;border:2px solid #000;">';
				echo 'GrupoX';
    echo '</div>';
				
				
    echo '<div id="ExibirCriterio" name="ExibirCriterio" style="visibility:hidden;position:absolute;left:350;top:109;height:0;background:#ddffdd;color:#0000ff;padding:1;border:2px solid #000;">';
				echo 'Descr Desempate';
    echo '</div>';
				//echo '<div id="divClassif" name="divClassif" style="font-family:Arial Narrow,Helvetica,sans-serif;font-size:16;visibility:visible;left:5;padding:1;height:auto;width:824;border:1px solid #2266AA;">';
				echo '<div id="divClassif" name="divClassif" style="font-family:Arial Narrow,Helvetica,sans-serif;font-size:16;visibility:hidden;position:absolute;top:110;padding:1;height:0;width:824;border:1px solid #2266AA;">';
											// ***** Classificação (com parte de Critérios de Desempate)*****
											if(count($PontosJogador)>0)
												{
													echo '<b>Classificação</b>: (<i>Em Construção: Critérios de Desempate</i>)<br>';
													echo '<table cellspacing=0 border=1>';
													echo '<tr><td colspan=3>&nbsp;</td><td colspan='.$QtDesempates.'><center><b>Desempates</b></center></td></tr>';
													echo '<tr><td><b>Cod</td><td><b>Nome</td><td><b>Pontos</td>';
													for($ic=1;$ic<=$QtDesempates;$ic++)
														{
															//echo "<td><b><a href=\"#\"><span onmouseout='OcultarExibirCriterio();' onmouseover='funcExibirCriterio(\"".$ic."\",\"".$Desempate[$ic]."\",\"".$Matriz_DesempatesSP[$Idioma][$Desempate[$ic]][1]."\",\"".$TieBreakParam[$ic]."\");'>Crit.".$ic."</span></a></td>";
															if(strtoupper($Matriz_DesempatesSP[$Idioma][$Desempate[$ic]][2])=='N')
																{$TB_Param='';}
															else
																{$TB_Param=$TieBreakParam[$ic];}
															echo "<td><b><a href=\"#\"><span onmouseout='OcultarExibirCriterio();' onmouseover='funcExibirCriterio(\"".$ic."\",\"".$Desempate[$ic]."\",\"".$Matriz_DesempatesSP[$Idioma][$Desempate[$ic]][1]."\",\"".$TB_Param."\");'>Crit.".$ic."</span></a></td>";
														}
													
													//{echo '<td><b>Desempate '.$ic.'&nbsp;'.$Matriz_DesempatesSP[$Idioma][$ic][1].'</td>';}
													echo "</tr>";
													
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
													
													array_multisort($PtsDesemp[1], SORT_NUMERIC, SORT_DESC,
																													$PtsDesemp[2], SORT_NUMERIC, SORT_DESC,
																													$PtsDesemp[3], SORT_NUMERIC, SORT_DESC,
																													$PtsDesemp[4], SORT_NUMERIC, SORT_DESC,
																													$PtsDesemp[5], SORT_NUMERIC, SORT_DESC,
																													$PtsDesemp[6], SORT_NUMERIC, SORT_DESC,
																													$PtsDesemp[0]
																												);
													for($itb=$QtJogadores;$itb>0;$itb--)
														{ for($ic=0;$ic<=6;$ic++) {$PtsDesemp[$ic][$itb]=$PtsDesemp[$ic][$itb-1];} }
													for($ic=0;$ic<=6;$ic++) {unset($PtsDesemp[$ic][0]);}
													
													// *************** Inserir aqui formatação de Critério "Fide9 - progressive score" *********
													//echo $QtJogadores . ' - ' . $QtDesempates . '<br>';
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
																	
																	
																	//echo $ic . ' - ' . $Desempate[$ic-1] . '<br>';
																	switch ($Desempate[$ic-1])
																		{
																			
																			//case 8:
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
													
													$Colocacao=0;
													for($itb=1;$itb<=$QtJogadores;$itb++)
														{
															
															$nj=$PtsDesemp[0][$itb];
															if($NumInic[$nj]>0) {
																$Colocacao++;
																echo '<tr>';
																echo  "<td>$Colocacao</td>";
																echo  "<td>$NomeJogador[$nj] ($NumInic[$nj])</td>";
																echo  "<td>$PontosJogador[$nj]</td>";
																
																//echo  '<td>'.$PtsDesemp[2][$itb].'</td>';
																//echo  '<td>'.$PtsDesemp[3][$itb].'</td>';
																//echo  '<td>'.$PtsDesemp[4][$itb].'</td>';
																//echo  '<td>'.$PtsDesemp[5][$itb].'</td>';
																//echo  '<td>'.$PtsDesemp[6][$itb].'</td>';
																
																//for($ic=2;$ic<=$QtDesempates+1;$ic++)
																//	{echo  "<td>".$PtsDesemp[$ic][$itb].'</td>';}
																//echo '</tr>';
																for($ic=1;$ic<=$QtDesempates+1;$ic++)
																	{
																		//echo '<td>';
																		if($Desempate[$ic]!=9)
																			{
																				echo '<td>';
																				echo   $Matriz_Crit[$ic][$itb];
																			}
																		else
																			{
																				//echo   $Matriz_Crit[$ic][$itb];
																				echo '<td align=center>';
																				echo '<table class="borderless" width=100%><tr>';
																				
																				for($ic9=0;$ic9<$NumRodadas-2;$ic9++)
																					{echo '<td class="borderlessL" width=10%>'.substr($Matriz_Crit[$ic][$itb],$ic9*4,3).'&nbsp;</td>';}
																				echo '<td class="borderlessL" width=10%>'.substr($Matriz_Crit[$ic][$itb],$ic9*4,3).'</td>';
																				
																				echo '</tr></table>';
																			}
																		
																		echo '</td>';
																	}
																echo '</tr>';
															}
														}
													
													echo "</table>";
												}
				echo "</div>";
								
				


				
				
				
				//							Título: $TituloTorneio<br>
				
    
			/*	
				echo "  yyyyy<br>
           SubTítulo: $SubTituloTorneio<br>
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
				for($j=1; $j<5; $j++)
					{
						echo ' &nbsp; &nbsp; ' . $j . ' - ' . $Desempate[$j] . '-' . $Matriz_DesempatesSP[$Desempate[$j]][1] . '<br>';
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
				
				echo "Data de Corte: $DataCorte<br>";
				
				echo "Pontos para o Jogador Bye: $PontosBye - $VlrPontosBye<br>";
				
				echo "Ordem da Classificação Inicial Automaticamente? $ClassAutom - $DescrClassAutom<br>";
				
				echo "<br>Critérios de Desempate. Parâmetros:<br>";

				$NrDesempate = 1;
				echo "Desempate: $NrDesempate<br>";
    echo "&nbsp; - Corte $Corte; :  ->  Melhor=$QtMelhor / Pior=$QtPior<br>";
				echo "&nbsp; - Partidas não jogadas (wo, bye,...): $PNJ_Adic/$PNJ/$Adic/$ExpPNJ &nbsp; --> Adicionar pontos próprios: $ExpAdic<br>";
				
				$NrDesempate = 2;
				//echo "Desempate: $NrDesempate<br>";
    //echo "&nbsp; - Corte $Corte; :  ->  Melhor=$QtMelhor / Pior=$QtPior<br>";
				//echo "&nbsp; - Partidas não jogadas (wo, bye,...): $PNJ_Adic/$PNJ/$Adic/$ExpPNJ &nbsp; --> Adicionar pontos próprios: $ExpAdic<br>";
				echo "Desempate: $NrDesempate   -   em construção<br>";
				
				$NrDesempate = 3;
				//echo "Desempate: $NrDesempate<br>";
    //echo "&nbsp; - Corte $Corte; :  ->  Melhor=$QtMelhor / Pior=$QtPior<br>";
				//echo "&nbsp; - Partidas não jogadas (wo, bye,...): $PNJ_Adic/$PNJ/$Adic/$ExpPNJ &nbsp; --> Adicionar pontos próprios: $ExpAdic<br>";
				echo "Desempate: $NrDesempate   -   em construção<br>";
				
				$NrDesempate = 4;
				//echo "Desempate: $NrDesempate<br>";
    //echo "&nbsp; - Corte $Corte; :  ->  Melhor=$QtMelhor / Pior=$QtPior<br>";
				//echo "&nbsp; - Partidas não jogadas (wo, bye,...): $PNJ_Adic/$PNJ/$Adic/$ExpPNJ &nbsp; --> Adicionar pontos próprios: $ExpAdic<br>";
				echo "Desempate: $NrDesempate   -   em construção<br>";
				
				$NrDesempate = 5;
				//echo "Desempate: $NrDesempate<br>";
    //echo "&nbsp; - Corte $Corte; :  ->  Melhor=$QtMelhor / Pior=$QtPior<br>";
				//echo "&nbsp; - Partidas não jogadas (wo, bye,...): $PNJ_Adic/$PNJ/$Adic/$ExpPNJ &nbsp; --> Adicionar pontos próprios: $ExpAdic<br>";
				echo "Desempate: $NrDesempate   -   em construção<br>";
				
				echo "Revisar Ordem dos Tabuleiros: $DescrRevisarOrdTab<br>";
				
				echo "Classificar/Exibir Rating: $DescrTipoRating<br>";
				
				echo '<br>';

		  for($i=1;$i<=$NumRodadas;$i++)
		   {
						echo "$iª Rodada: $DataRodada[$i] - Horário: $HorarioRodada[$i] - Tabuleiros: $TabulRodada[$i]<br>";
		   }
				echo '<br>';

    // ==========================================================================
    // Jogadores
    
    echo "</body></html>";
				
				// ***** Resultados *********************************************************
				echo "<b>Emparceiramentos e Resultados:</b><br>";
				
				for ($r=1;$r<=$NumRodadas;$r++)
				[0][1][0][1] {
						echo "<b>" . $r . "ª Rodada: </b><br>";
						echo "<table border=1>";
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
				
				// ***** Pontuação *********************************************************
				if(count($PontosJogador)>0)
				 {
						echo "<b>Pontuação:</b><br>";
						echo "<table border=1>";
						echo "<tr><td><b>Cod</td><td><b>Nome</td><td><b>Pontos</td></tr>";
						
						arsort($PontosJogador);
						foreach ($PontosJogador as $chave => $valor)
							{
								if($chave>0)
									{
										echo "<tr><td>$chave</td>";
										echo "<td>$NomeJogador[$chave]</td>";
										echo "<td>$PontosJogador[$chave]</td></tr>";
									}
							}
						
						echo "</table>";
				 }
				
				// ***** Equipes e Resultados *********************************************************
				if($QtEquipes>0)
				 {
						echo "<b>Equipes:</b><br>";
						echo "<table border=1>";
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
				echo '<table border=1>';
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
									echo '<table width="100%"><tr>';
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
				echo '<table border=1>';
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
											echo '<table width="100%"><tr>';
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
			*/
			
		 exit;
		
	// -----------------------------------------------------------------------------	
	// ----- Funções ---------------------------------------------------------------	
	
	function NaoEmp($NumHex,$nrod)
		{
			$RodNaoEmp = '';
		 for($p=0;$p<$nrod;$p++)
				{
				 if((hexdec($NumHex) & pow(2,$p)) > 0)
					 {
							$RodNaoEmp = $RodNaoEmp . " " . ($p+1);
						}
				}
			return $RodNaoEmp;
		}
	// ..............................................................................
	
	function LerArqINI($fileINI)
		{
			global $apresentacao,$NumRodadas,$TituloTorneio,$Organizador,$ArbitroPrincipal,$NumRodadas,$TabulInicial,$MultiGames,$TipoTorneio,$EquipIndiv,$SubEscore,$QtDesempates,$Desempate;
			global $BrightwellCoef,$CorteBhQtMelhor,$CorteBhQtPior,$BuchholzAdjust,$Corte;
			
			//$InfoINI = parse_ini_file($NomeArq . '.ini', true);
			$file = $fileINI;		//$link;
			$fh = fopen($file, 'r');
			$conteudo = fread($fh, filesize($file));
			$InfoINI = parse_ini_string($conteudo, true, INI_SCANNER_RAW);
			//print_r($InfoINI);exit;
			
			///echo '7777'.$fileINI.'7777';
			//print_r(array_keys($InfoINI));
			$Seccoes = array_keys($InfoINI);
			$QtSec = count($Seccoes);
			//echo '+++'.$QtSec . '+++';
			
			if ($apresentacao=='geral') {echo '----- Arquivo INI -------------------------------------------------------------------------------------------------------------- <br>';}
			
			//$QtPior[$icrit] = $Corte[$icrit] % 16;
			//$QtMelhor[$icrit] = (int)($Corte[$icrit] / 16);
			$BrightwellCoefG = 6;
			$CorteBhQtMelhorG = 1;
			$CorteBhQtPiorG = 1;
			$BuchholzAdjustG = 1;
			$CorteG = ($CorteBhQtMelhorG * 16) + $CorteBhQtPiorG;
			for($i=1;$i<=$QtDesempates;$i++)
			 {
					$BrightwellCoef[$i] = $BrightwellCoefG;
					$CorteBhQtMelhor[$i] = $CorteBhQtMelhorG;
					$CorteBhQtPior[$i] = $CorteBhQtPiorG;
					$BuchholzAdjust[$i] = $BuchholzAdjustG;
					$Corte[$i] = ($CorteBhQtMelhor[$i] * 16) + $CorteBhQtPior[$i];
			 }
			
			//echo '<br> QtSec: ' . $QtSec . ' (de 0 a ' . ($QtSec-1) . ')<br>';
			for($i=0;$i<$QtSec;$i++)
			 {
					$Qtchaves[$i] = count($InfoINI[$Seccoes[$i]]);
					$Chaves = array_keys($InfoINI[$Seccoes[$i]]);
					for($j=0;$j<$Qtchaves[$i];$j++)
						{
							$InfoTorneio[$i][$j][0]=$Chaves[$j];
							$InfoTorneio[$i][$j][1]=$InfoINI[$Seccoes[$i]][$Chaves[$j]];
							
							//echo $i . '-' . $j . ' - ' . $Seccoes[$i] . ' * ' . $Chaves[$j] . '<br>';							
							
							//if($i==0)
							if($Seccoes[$i]=='Tournament Info')
								{
									//echo $i . '-' . $j . ' - ' . $Seccoes[$i] . ' * ' . $Chaves[$j] . '<br>';							
									switch ($Chaves[$j])
										{
												case 'Name':
													$TituloTorneio = $InfoINI[$Seccoes[$i]][$Chaves[$j]];
													break;
												case 'Arbiter':
													$ArbitroPrincipal = $InfoINI[$Seccoes[$i]][$Chaves[$j]];
													break;
												case 'Organiser':
													$Organizador = $InfoINI[$Seccoes[$i]][$Chaves[$j]];
													break;
												case 'Rounds':
													$NumRodadas = $InfoINI[$Seccoes[$i]][$Chaves[$j]];
													break;
												case 'TableNosStart':
													$TabulInicial = $InfoINI[$Seccoes[$i]][$Chaves[$j]];
													break;
												case 'Multi Games':
													$MultiGames = $InfoINI[$Seccoes[$i]][$Chaves[$j]];
													break;
												case 'System':
													$TipoTorneio = $InfoINI[$Seccoes[$i]][$Chaves[$j]];
													break;
												case 'Team or Individual':
													$EquipIndiv = $InfoINI[$Seccoes[$i]][$Chaves[$j]];
													//echo '<br><br>' . $EquipIndiv . '<br><br>';
													break;
												case 'Use Minor Scores':
													$SubEscore = $InfoINI[$Seccoes[$i]][$Chaves[$j]];
													break;
												default:
										}
								}
							
							//if($i==2)
							if($Seccoes[$i]=='Standings')
								{
									//echo $i . '-' . $j . ' - ' . $Seccoes[$i] . ' * ' . $Chaves[$j] . '<br>';							
									switch ($Chaves[$j])
										{
												case 'Tie Breaks':	// TieBreak
													$TieBreaks = trim($InfoINI[$Seccoes[$i]][$Chaves[$j]]);
													$TamTieBreaks = strlen($TieBreaks);
													if(substr($TieBreaks,$TamTieBreaks-1,1)==',') {$TieBreaks=substr($TieBreaks,0,$TamTieBreaks-1);}
													$DesempateTemp = explode(",", $TieBreaks);
													$QtDesempates=count($DesempateTemp);
													for($itie=0;$itie<$QtDesempates;$itie++)
														{$Desempate[$itie+1]=((int)($DesempateTemp[$itie]))-1216;}
													unset($DesempateTemp);
													break;
												default:
										}
								}
							
							if($Seccoes[$i]=='TieBreakAdvanced')
								{
							  //echo '<br> Qtchaves: ' . $Qtchaves[$i] . ' (de 0 a ' . ($Qtchaves[$i]-1) . ')<br>';
									switch ($Chaves[$j])
										{
												/*
												case 'Tie Breaks':	// TieBreak
													$TieBreaks = trim($InfoINI[$Seccoes[$i]][$Chaves[$j]]);
													$TamTieBreaks = strlen($TieBreaks);
													if(substr($TieBreaks,$TamTieBreaks-1,1)==',') {$TieBreaks=substr($TieBreaks,0,$TamTieBreaks-1);}
													$DesempateTemp = explode(",", $TieBreaks);
													$QtDesempates=count($DesempateTemp);
													for($itie=0;$itie<$QtDesempates;$itie++)
														{$Desempate[$itie+1]=(int)($DesempateTemp[$itie]);}
													unset($DesempateTemp);
													break;
													*/
												case 'BrightwellCoeff':
													//echo $j . ' - ' . $Chaves[$j] . '<br>';							
													$BrightwellCoefG = $InfoINI[$Seccoes[$i]][$Chaves[$j]];
													break;
												case 'MB-HighestOut':
													//echo $j . ' - ' . $Chaves[$j] . '<br>';							
													$CorteBhQtMelhorG = $InfoINI[$Seccoes[$i]][$Chaves[$j]];
													break;
												case 'MB-LowestOut':
													//echo $j . ' - ' . $Chaves[$j] . '<br>';							
													$CorteBhQtPiorG = $InfoINI[$Seccoes[$i]][$Chaves[$j]];
													break;
												case 'BuchholzAdjust':
													//echo $j . ' - ' . $Chaves[$j] . '<br>';
													$BuchholzAdjustG = $InfoINI[$Seccoes[$i]][$Chaves[$j]];
													break;
													
												default:
										}
									//$CorteG = ($CorteBhQtMelhorG * 16) + $CorteBhQtPiorG;
								}
						}
				}
			
			for($i=1;$i<=$QtDesempates;$i++)
			 {
					$BrightwellCoef[$i] = $BrightwellCoefG;
					if($Desempate[$i]==2)
						{
							$CorteBhQtMelhor[$i] = $CorteBhQtMelhorG;
							$CorteBhQtPior[$i] = $CorteBhQtPiorG;
						}
					else
						{$CorteBhQtMelhor[$i] = 0; $CorteBhQtPior[$i] = 0;}
					if($Desempate[$i]==1 OR $Desempate[$i]==2)
						{$BuchholzAdjust[$i] = $BuchholzAdjustG;}
					else
						{$BuchholzAdjust[$i] = 0;}
					$Corte[$i] = ($CorteBhQtMelhor[$i] * 16) + $CorteBhQtPior[$i];
			 }
			//$BrightwellCoef,$CorteBhQtMelhor,$CorteBhQtPior,$BuchholzAdjust,$Corte
			//echo 'passou 3<br>';
			
			/*
				- 0-Name: I Torneio Prof. Amorim99
				- 1-Arbiter: AF Paulo C. Levy
				- 2-Organiser: Rafael Pires e Paulo Cintra
				- 3-Rounds: 6
				- 4-TableNosStart: 1
				- 5-Multi Games: 1
				- 6-System: 0
				- 7-Team or Individual: 0
				- 8-Use Minor Scores: 0			
			*/
			
			//echo '<br><br> === ' . count($InfoTorneio) . ' === ' . count($InfoTorneio[0]) . ' === ' . count($InfoTorneio[1][1]) . ' === <br><br>' ;
			$QtSec=count($InfoTorneio);
			for($i=0;$i<$QtSec;$i++)
				{
					if ($apresentacao=='geral') {echo '<br><b>' . $Seccoes[$i] . '</b><br>';}
					$Qtchaves=count($InfoTorneio[$i]);
					for($j=0;$j<$Qtchaves;$j++)
						{if ($apresentacao=='geral') {echo ' &nbsp; - ' . $InfoTorneio[$i][$j][0] . ': ' . $InfoTorneio[$i][$j][1] . '<br>';}}
				}
				
			//$NumRodadas = $InfoINI['Tournament Info']['Rounds'];
			//echo '<br><br> ===='.$NumRodadas.'='.$InfoINI['Tournament Info']['Rounds'].'====';
			
			//echo '<br><br> Num. Rodadas: ' . $NumRodadas . '<br><br>';;

			//exit;
				
				/*	
						$ini = parse_ini_file($fileINI, true);
						
						echo '<b>[<u>Tournament Info</u>]</b><br>';
						echo '<table width=90%>';
						echo '<tr>';
						echo '<td>' . '&nbsp; * <b>Título do Torneio</b>: ' . $ini['Tournament Info']['Name'] . '</td>';
						echo '<td>' . '&nbsp; * <b>Árbitro</b>: ' . $ini['Tournament Info']['Arbiter'] . '</td>';
						echo '<td>' . '&nbsp; * <b>Organizador(s)</b>: ' . $ini['Tournament Info']['Organiser'] . '</td>';
						echo '</tr>';
						echo '<tr>';
						$NumRodadas = $ini['Tournament Info']['Rounds'];
						echo '<td>' . '&nbsp; * <b>Num. de Rodadas</b>: ' . $NumRodadas . '</td>';
						echo '<td>' . '&nbsp; * <b>Num. 1º Tabuleiro</b>: ' . $ini['Tournament Info']['TableNosStart'] . '</td>';
						echo '<td>' . '&nbsp; * <b>Multi Games</b>: ' . $ini['Tournament Info']['Multi Games'] . '</td>';
						echo '</tr>';
						echo '<tr>';
						echo '<td>' . '&nbsp; * <b>Tipo</b>: ' . $ini['Tournament Info']['System'];
						if($ini['Tournament Info']['System']==0)
							{echo ' - Suiço</td>';}
						else
							{echo ' - Round-Robin</td>';}
						echo '<td>' . '&nbsp; * <b>Equipe/Individual</b>: ' . $ini['Tournament Info']['Team or Individual'];
						if($ini['Tournament Info']['Team or Individual']==0)
							{echo ' - Individual</td>';}
						else
							{echo ' - Equipes</td';}
						echo '<td>' . '&nbsp; * <b>Minor Score</b>: ' . $ini['Tournament Info']['Use Minor Scores'] . '</td>';
						echo '<tr>';
						echo '</table>';
						echo '<br>';
						
						echo '<b>[<u>View List of Participants</u>]</b><br>';
						echo ' &nbsp; ';
						echo $ini['View List of Participants']['StartNo'] . ' / ';
						echo $ini['View List of Participants']['Surname'] . ' / ';
						echo $ini['View List of Participants']['Federation'] . ' / ';
						echo $ini['View List of Participants']['IntlId'] . ' / ';
						echo $ini['View List of Participants']['LocId'] . ' / ';
						echo $ini['View List of Participants']['RatingIntl'] . ' / ';
						echo $ini['View List of Participants']['RatingLocal'] . ' / ';
						echo $ini['View List of Participants']['Title'] . ' / ';
						echo $ini['View List of Participants']['Sex'] . ' / ';
						echo $ini['View List of Participants']['Club'] . ' / ';
						echo $ini['View List of Participants']['BirthDate'] . ' / ';
						echo $ini['View List of Participants']['PrintHeadings'];
						echo '<br>';
						
						echo '<b>[<u>Standings</u>]</b><br>';
						echo '&nbsp; * <b>Desempates</b>: ' . $ini['Standings']['Tie Breaks'] . '<br>';
						//echo '<br>';
						
						echo '<b>[<u>List Of Participants</u>]</b><br>';
						echo ' &nbsp; ';
						echo $ini['List Of Participants']['Sort Criteria'] . ' / ';
						echo $ini['List Of Participants']['KeepNumbers'] . ' / ';
						echo $ini['List Of Participants']['RandomiseGroups'];
						echo '<br>';
						
						echo '<b>[<u>RatingIntl</u>]</b><br>';
						echo ' &nbsp; ';
						echo $ini['RatingIntl']['DiffUp'] . ' / ';
						echo $ini['RatingIntl']['DiffDn'] . ' / ';
						echo $ini['RatingIntl']['UseDiffs'] . ' / ';
						echo $ini['RatingIntl']['LowThresh'] . ' / ';
						echo $ini['RatingIntl']['KFactor'] . ' / ';
						echo $ini['RatingIntl']['System'] . ' / ';
						echo $ini['RatingIntl']['IgnoreForfeits'];
						echo '<br>';
						
						echo '<b>[<u>RatingLocal</u>]</b><br>';
						echo ' &nbsp; ';
						echo $ini['RatingLocal']['DiffUp'] . ' / ';
						echo $ini['RatingLocal']['DiffDn'] . ' / ';
						echo $ini['RatingLocal']['UseDiffs'] . ' / ';
						echo $ini['RatingLocal']['LowThresh'] . ' / ';
						echo $ini['RatingLocal']['KFactor'] . ' / ';
						echo $ini['RatingLocal']['System'] . ' / ';
						echo $ini['RatingLocal']['IgnoreForfeits'];
						echo '<br>';
						
						echo '<b>[<u>Pairing</u>]</b><br>';
						echo ' &nbsp; ';
						echo $ini['Pairing']['UseRating'] . ' / ';
						echo $ini['Pairing']['SwissSystem'] . ' / ';
						echo $ini['Pairing']['BarCriteria'] . ' / ';
						echo $ini['Pairing']['NoBarBeforeRound'] . ' / ';
						echo $ini['Pairing']['NoBarAfterRound'] . ' / ';
						echo $ini['Pairing']['UseAccelerated'] . ' / ';
						echo $ini['Pairing']['IgnoreColours'] . ' / ';
						echo $ini['Pairing']['UnplayedAgain'] . ' / ';
						echo $ini['Pairing']['BarCaseSensitive'];
						echo '<br>';
						
						echo '<b>[<u>ClubStandings</u>]</b><br>';
						echo ' &nbsp; ';
						echo $ini['ClubStandings']['GroupingCriteria'] . ' / ';
						echo $ini['ClubStandings']['TopScoresCount'];
						echo '<br>';
						
						echo '<b>[<u>Accelerated</u>]</b><br>';
						echo ' &nbsp; ';
						echo $ini['Accelerated']['Groups'] . ' / ';
						echo $ini['Accelerated']['EndRound'];
						echo '<br>';
						
						echo '<b>[<u>Desktop</u>]</b><br>';
						echo ' &nbsp; ';
						echo $ini['Desktop']['ListV'] . ' / ';
						echo $ini['Desktop']['ListVmode'] . ' / ';
						echo $ini['Desktop']['ActiveViews'] . ' / ';
						echo $ini['Desktop']['Round'];
						echo '<br>';

						echo '<b>[<u>Display</u>]</b><br>';
						echo ' &nbsp; ';
						echo $ini['Display']['FontName'] . ' / ';
						echo $ini['Display']['FontSize'] . ' / ';
						echo $ini['Display']['FontScript'];			
						echo '<br>';
			*/
			
			//echo '<br><br> ********* fim do Arquivo INI *************<br><br>';
		}
	
	// ......................................................
	function LerArqTRN($fileTRN)
		{
			global $apresentacao,$QtJogadores,$Order,$ID,$NumInic,$NomeJogador,$PaisJogador,$TitFIDE,$RatFIDE,$RatNacional,$Clube,$PontosJogador,$Federacoes,$TitulosFIDE,$Clubes,$Grupo;
			//global $NumRodadas$
			//$ArqTorneio	= "I_Torneio_Prof_Amorim";
			//$fileTRN = "../TorneiosSP/" . $ArqTorneio . ".trn";
			
			$fhTRN = fopen($fileTRN, 'rb');
			
			//$apresentacao='geral';
			if ($apresentacao=='geral') {echo '----- Arquivo TRN -------------------------------------------------------------------------------------------------------------- <br>';}
			
			$sequenciaC1=TestarSeqAsc($fhTRN,4);
			if ($apresentacao=='geral') {echo '<br>Bloco ñ-Ident01: ' . $sequenciaC1 . '<br><br>';}
			
			
			
			//$QtJogadores=LerCampoInt($fhTRN);														// *** 2019/11/17 ***
			//echo "<br><br>Qt_Jogadores: $QtJogadores<br><br>";exit;			// *** 2019/11/17 ***
			$QtRegistros=LerCampoInt($fhTRN);															// *** 2019/11/17 ***
			//echo "<br><br>Qt_Registros: $QtRegistros<br><br>";exit;			// *** 2019/11/17 ***
			
			
			if ($apresentacao=='geral') {echo 'Quant. Jogadores: ' . $QtJogadores . '<br><br>';}
			
			$sequenciaC2=TestarSeqAsc($fhTRN,26);
			if ($apresentacao=='geral') {echo '<br>Bloco ñ-Ident02: ' . $sequenciaC2 . '<br><br>';}

			if ($apresentacao=='geral') {echo '<table border=1>';}
			for($i=1;$i<25;$i++)
				{
					$NomeCampo[$i]=TestarSeqAsc2($fhTRN,10);
					$Cabecalho1=TestarSeqAsc2($fhTRN,1);
					$Cabecalho2=TestarSeqAsc2($fhTRN,1);
					$Cabecalho3=TestarSeqAsc($fhTRN,4);
					$Cabecalho4=TestarSeqAsc($fhTRN,1);
					$Cabecalho5=TestarSeqAsc($fhTRN,15);
					if ($apresentacao=='geral') {echo '<tr><td>' . $i . '</td><td>' . $NomeCampo[$i] . '</td><td>' . $Cabecalho1 . '</td><td>' . $Cabecalho2 . '</td><td>' . $Cabecalho3 . '</td><td>' . $Cabecalho4 . '</td><td>' . $Cabecalho5 . '</td></tr>';}
				}
			if ($apresentacao=='geral') {echo '</table><br>';}
			
			$PontosJogador[0] = 0;
			
			
			//for($i=1;$i<=$QtJogadores;$i++)			// *** 2019/11/17 ***
			//$Jogador=0;												// *** 2019/11/17 ***
			//$IDxJogador[0]=0;
			//echo '<br>'.$QtRegistros. ' ***'. ord('').'<br>';
			$LoopReal=0;
			for($i=1;$i<=$QtRegistros;$i++)				// *** 2019/11/17 ***
				{
					
					
					
					
					
					
					
					
					
					
					
					
					
					do {
						
						$LoopReal++;
						if($LoopReal>($QtRegistros)) {break 2;}
						
						$Status1[$i]=LerCampoAsc($fhTRN);
						
						$Status2[$i]=LerCampoAsc($fhTRN);
						if($Status2[$i]==32) {$Jogador++;}			// asc 42 = chr * 
						//echo $Status2[$i] . ' - ' . $QtJogadores . '<br>';
						
						$Order[$i]=TestarSeqAsc3($fhTRN,5);
						//if($Order[$i]==32) {$Jogador++;}			// asc 42 = chr * 
						/*
							if($Status2[$i]==42) {							// asc 42 = chr * 
								echo '--' . $Status1[$i].'-'.chr($Status1[$i]) . "-- $nbsp; $nbsp; $nbsp; " . $Status2[$i].'-'.chr($Status2[$i]) . "-- $nbsp; $nbsp; $nbsp; " . $Order[$i].'-'.chr($Order[$i]) . '--<br>';
							}
						*/
						//aqui
						//$ID[$i]=trim(TestarSeqAsc3($fhTRN,5))*1;
						$ID[$i]=trim(TestarSeqAsc3($fhTRN,5))*1;
						//$Jogador_ID[$Jogador]=$ID[$i];
						
						//$IDxJogador[$ID[$i]]=TestarSeqAsc3($fhTRN,5)*1;
						
						//-------------------------------------------------------------------------------------------------
						$NumInic[$i]=TestarSeqAsc3($fhTRN,5)*1;
								//echo 'OIN-'.$i.') '.$Order[$i].'-'.$ID[$i].'-'.$NumInic[$i].'-<br>';
						
						$sobrenome[$i]=utf8_encode(TestarSeqAsc2($fhTRN,25));
						$nome[$i]=utf8_encode(TestarSeqAsc2($fhTRN,25));
						
						$NomeJogador[$i] = $nome[$i] . ' ' . $sobrenome[$i];
					
						//echo ' id: '.$i.' - Status1: '.$Status1[$i].' - Status2: '.$Status2[$i].' - Order: '.$Order[$i].'-ID: '.$ID[$i].'-NumInic: '.$NumInic[$i].' - nome: '.$NomeJogador[$i].' ---<br>';
						//$NomeJogador[$i]      = $nome[$i] . ' ' . $sobrenome[$i];
						$PontosJogador[$i] = 0;
								
						$fed[$i]=TestarSeqAsc2($fhTRN,4);
						$Federacao=$fed[$i];
						$Federacoes[$Federacao]=$Federacoes[$Federacao]+1;
						if(trim($fed[$i])=='') {$fed[$i]='&nbsp;';}
						$PaisJogador[$i]=$fed[$i];
						
						$Clube[$i]=LerCampoStrA($fhTRN,30);
						$Clubes[$Clube[$i]]=$Clubes[$Clube[$i]]+1;
						if(trim($Clube[$i])=='') {$Clube[$i]='&nbsp;';}
						
						$RatInt[$i]=TestarSeqAsc2($fhTRN,4);
						//if(trim($RatInt[$i])=='') {$RatInt[$i]='&nbsp;';}
						$RatFIDE[$i]=trim($RatInt[$i]);
						
						$RatLoc[$i]=TestarSeqAsc2($fhTRN,4);
						//if(trim($RatLoc[$i])=='') {$RatLoc[$i]='&nbsp;';}
						$RatNacional[$i]=trim($RatLoc[$i]);
						
						$RatPrinc[$i]=TestarSeqAsc2($fhTRN,4);
						//if(trim($RatPrinc[$i])=='') {$RatPrinc[$i]='&nbsp;';}
						
						$idFIDE[$i]=TestarSeqAsc2($fhTRN,12);
						if(trim($idFIDE[$i])=='') {$idFIDE[$i]='&nbsp;';}
						
						$idLoc[$i]=TestarSeqAsc2($fhTRN,12);
						if(trim($idLoc[$i])=='') {$idLoc[$i]='&nbsp;';}
						
						//$CodTit[$i]=TestarSeqAsc2($fhTRN,1);
						$CodTit[$i]=fread($fhTRN,1);
						//echo 'kkk: '. $idLoc[$i].'-'.$CodTit[$i].' kk';
						
						switch ($CodTit[$i])
							{
								Case 1:
									$DescrTit[$i] = 'GM';
									break;
								Case 2:
									$DescrTit[$i] = 'WGM';
									break;
								Case 3:
									$DescrTit[$i] = 'IM';
									break;
								Case 4:
									$DescrTit[$i] = 'WIM';
									break;
								Case 5:
									$DescrTit[$i] = 'FM';
									break;
								Case 6:
									$DescrTit[$i] = 'WFM';
									break;
								Case 7:
									$DescrTit[$i] = '.';
									break;
								default:
									$DescrTit[$i] = '';
							}
						if(trim($DescrTit[$i])=='') {$DescrTit[$i]='&nbsp;';}
						$TitFIDE[$i]=$DescrTit[$i];
						$TitulosFIDE[$TitFIDE[$i]]=$TitulosFIDE[$TitFIDE[$i]]+1;
						
						$Nid1[$i]=TestarSeqAsc2($fhTRN,2);
						if(trim($Nid1[$i])=='') {$Nid1[$i]='&nbsp;';}

						$CodSex[$i]=TestarSeqAsc2($fhTRN,1);
						switch ($CodSex[$i])
							{
								Case 1:
									$DescrSex[$i] = 'M';
									break;
								Case 2:
									$DescrSex[$i] = 'F';
									break;
								Case 3:
									$DescrSex[$i] = 'C';
									break;
								default:
									$DescrSex[$i] = '';
							}
						if(trim($DescrSex[$i])=='') {$DescrSex[$i]='&nbsp;';}

						$DtNasc[$i]=TestarSeqAsc2($fhTRN,8);
						if(trim($DtNasc[$i])=='') {$DtNasc[$i]='&nbsp;';}
						
						$RodInic[$i]=TestarSeqAsc2($fhTRN,2);
						if(trim($RodInic[$i])=='') {$RodInic[$i]='&nbsp;';}
						
						$RodDesist[$i]=TestarSeqAsc2($fhTRN,2);
						if(trim($RodDesist[$i])=='') {$RodDesist[$i]='&nbsp;';}
						
						$Nid2[$i]=TestarSeqAsc2($fhTRN,14);
						if(trim($Nid2[$i])=='') {$Nid2[$i]='&nbsp;';}
						
						$RodNE[$i]=TestarSeqAsc2($fhTRN,2);
						if(trim($RodNE[$i])=='') {$RodNE[$i]='&nbsp;';}
						
						for($r=1;$r<=6;$r++)
							{
								$BonusRod[$i][$r]=TestarSeqAsc2($fhTRN,3);
								if(trim($BonusRod[$i][$r])=='') {$BonusRod[$i][$r]='-';}
							}

						$Nid3[$i]=TestarSeqAsc2($fhTRN,1);
						if(trim($Nid3[$i])=='') {$Nid3[$i]='&nbsp;';}

						$Nid4[$i]=TestarSeqAsc2($fhTRN,15);
						if(trim($Nid4[$i])=='') {$Nid4[$i]='&nbsp;';}
						//----------------------------------------------------------------------
						
					 //} while (1*$Status2[$i]<>32 AND $i<$QtRegistros);
					} while (1*$Status2[$i]<>32 AND trim($NomeJogador[$i])=='');	// AND $i<=$QtRegistros);
					
					if ($apresentacao=='geral') {echo ' id: '.$i.' - Status1: '.$Status1[$i].' - Status2: '.$Status2[$i].' - Order: '.$Order[$i].'-ID: '.$ID[$i].'-NumInic: '.$NumInic[$i].' - nome: '.$NomeJogador[$i].' ---<br>';}
					
				}
				$QtJogadoresValidos = $Jogador;
				$QtJogadores = $QtRegistros;
				
				//echo 'QtJogVal: '.$QtJogadoresValidos . ' --- LoopReal: ' . $LoopReal . '<br><br>';
			
			
			$q=0;
			if ($apresentacao=='geral') {echo '<table width="1950" border=1>';}
			if ($apresentacao=='geral') {echo '<tr>';}
			//if ($apresentacao=='geral') {echo '<td>&nbsp;</td>' . '<td>&nbsp;</td>' . '<td>&nbsp;</td>';}
			//if ($apresentacao=='geral') {echo '<td>&nbsp;</td>' . '<td>&nbsp;</td>';}
			if ($apresentacao=='geral') {echo '<td>&nbsp;</td>';}
			for($i=1;$i<25;$i++)
				{
					{if ($apresentacao=='geral') {echo '<td>' . $NomeCampo[$i] . '</td>';}}
				}
			if ($apresentacao=='geral') {echo '</tr>';}
			//for($i=1;$i<=$QtJogadores;$i++)
			for($i=1;$i<=$QtRegistros;$i++)
				{
					//if($Status2[$i]!=99)		// **************************************
						{
							$q=$q+1;
							if ($apresentacao=='geral') {
								if($Order[$i]>0) {
									echo '<tr>';
									//echo '<td>' . $q . ' - (' . $i . ') ' . '</td>';
									
									echo '<td>' . $i . '</td>';
									//echo '<td>s1*' . $Status1[$i] . '*s1</td>';
									//echo '<td>s2*' . $Status2[$i] . '*s2</td>';
									echo '<td>3*' . $Order[$i] . '*3</td>';
									echo '<td>4*' . $ID[$i] . '*4</td>';
									echo '<td>' . $NumInic[$i] . '</td>';
									echo '<td>' . $sobrenome[$i] . '</td>';
									echo '<td>' . $nome[$i] . '</td>';
									echo '<td>' . $fed[$i] . '</td>';
									echo '<td>' . $Clube[$i] . '</td>';
									echo '<td>' . $RatInt[$i] . '</td>';
									echo '<td>' . $RatLoc[$i] . '</td>';
									echo '<td>' . $RatPrinc[$i] . '</td>';
									echo '<td>' . $IdFIDE[$i] . '</td>';
									echo '<td>' . $IdLoc[$i] . '</td>';

									//echo '<td>' . $CodTit[$i] . '-' . $DescrTit[$i] . '</td>';;
									echo '<td>' . $DescrTit[$i] . '</td>';;
									
									//echo '<td>' . $Nid1[$i] . '</td>';;

									//echo '<td>' . $CodSex[$i] . '-' . $DescrSex[$i] . '</td>';;
									echo '<td>' . $DescrSex[$i] . '</td>';;

									echo '<td>' . $DtNasc[$i] . '</td>';;

									echo '<td>' . $RodInic[$i] . '</td>';;

									echo '<td>' . $RodDesist[$i] . '</td>';;
									
								}
							}
							//echo '<td>' . $Nid2[$i] . '</td>';;

							$RodsNE = NaoEmp($RodNE[$i],$NumRodadas);
							if(trim($RodsNE)=="") {$RodsNE='&nbsp;';}
							if ($apresentacao=='geral') {echo '<td>' . $RodsNE . '</td>';}
							
							if ($apresentacao=='geral') {echo '<td>';}
							for($r=1;$r<=$NumRodadas;$r++)
								{
									if ($apresentacao=='geral') {echo $BonusRod[$i][$r];}
								}
							if ($apresentacao=='geral') {echo '</td>';}

							if ($apresentacao=='geral') {echo '<td>' . $Nid3[$i] . '</td>';;}

							if ($apresentacao=='geral') {echo '<td>' . $Nid4[$i] . '</td>';}
							
							if ($apresentacao=='geral') {echo '<td>&nbsp;</td>';}
							if ($apresentacao=='geral') {echo '<td>&nbsp;</td>';}
							if ($apresentacao=='geral') {echo '<td>&nbsp;</td>';}
							
							if ($apresentacao=='geral') {echo '</tr>';}
						}
				}
			if ($apresentacao=='geral') {echo '</table>';}
			if ($apresentacao=='geral') {echo '/';}
			$restante1=TestarSeqAsc($fhTRN,1);
			if ($apresentacao=='geral') {echo $restante1;}
			if ($apresentacao=='geral') {if ($apresentacao=='geral') {echo '/';}}
			$restante2=TestarSeqAsc($fhTRN,1);
			if ($apresentacao=='geral') {echo $restante2;}

			if ($apresentacao=='geral') {echo '/';}
			
			$sequenciaR=TestarSeqAsc($fhTRN,40);
			if ($apresentacao=='geral') {echo '<br>*' . $sequenciaR . '*<br>';}
			
			if ($apresentacao=='geral') {echo '<br><br> ********* fim do Arquivo TRN *************<br><br>';}
			
		}
	
	// ...........................................................................................................
	function LerArqSCO($fileSCO)
		{
			//global $apresentacao,$QtJogadores,$TitFIDE,$NomeJogador,$PaisJogador,$RatFIDE,$RatNacional,$Clube,$Grupo;
			global $apresentacao,$QtJogadores,$Order,$ID,$NumInic,$NomeJogador,$NumRodadas,$PontosJogador,$Resultados,$RodMesaResult,$TabulRodada,$CrossTable;
			global $PtsDesemp,$NumVitJog,$VitBr,$VitNr,$Empatesr,$Ausr,$Totr;
			if ($apresentacao=='geral') {echo '-------------------------------------------------------------------------------------------------------------------------------- <br>';}
			if ($apresentacao=='geral') {echo '----- Arquivo SCO -------------------------------------------------------------------------------------------------------------- <br>';}
			
			for($ij=1;$ij<=$QtJogadores;$ij++)
				{
					for($ir=1;$ir<=$NumRodadas;$ir++)
						{
							$CrossTable[$ij][$ir][1]='-';
							$CrossTable[$ij][$ir][2]='-';
							$CrossTable[$ij][$ir][3]=0;
						}
				}
			
			$fhSCO = fopen($fileSCO, 'rb');
			
			$sequenciaC1=TestarSeqAsc($fhSCO,32);
			if ($apresentacao=='geral') {echo $sequenciaC1 . 'ww<br><br>';}
			
			if ($apresentacao=='geral') {echo 'yy' . $sequenciaC2a . 'yy<br><br>';}
			
			if ($apresentacao=='geral') {echo '<table border=1>';}
			for($i=1;$i<=9;$i++)
				{
					$NomeCampo[$i]=TestarSeqAsc2($fhSCO,10);
					$Cabecalho1=TestarSeqAsc2($fhSCO,1);
					if($Cabecalho1=='') {$Cabecalho1='&nbsp;';}
					$Cabecalho2=TestarSeqAsc2($fhSCO,1);
					$Cabecalho3=TestarSeqAsc($fhSCO,4);
					$Cabecalho4=LerCampoAsc($fhSCO,1);
					$Cabecalho5=TestarSeqAsc($fhSCO,1);
					$Cabecalho6=TestarSeqAsc($fhSCO,14);
					if ($apresentacao=='geral') {echo '<tr><td>' . $i . '</td><td>' . $NomeCampo[$i] . '</td><td>' . $Cabecalho1 . '</td><td>' . $Cabecalho2 . '</td><td>' . $Cabecalho3 . '</td><td>' . $Cabecalho4 . '</td><td>' . $Cabecalho5 . '</td><td>' . $Cabecalho6 . '</td></tr>';}
				}
			if ($apresentacao=='geral') {echo '</table><br>';}
			
			$CabecalhoNull=LerCampoAsc($fhSCO,1);
			
			if ($apresentacao=='geral') {echo $sequenciaC2b . 'zz<br><br>';}
			
			
			
			for ($e=1;$e<=$QtEquipes;$e++)
				{$PontosEquipe[$e]=0;}
			
			for ($j=1;$j<=$QtJogadores;$j++)
				{
					$PontosJogador[$j]=0;
					$JogNegras[$j]=0;
					$NumVitJog[$j]=0;
					$PtsDesemp[0][$j]=$j;
					//$PtsDesemp[1][$j]=7; // ********************************************
					for ($jc=1;$jc<=6;$jc++)
						{
							$PtsDesemp[$jc][$j]=0;
						}
				}
			
			
			
					$CodRes=0; $rod=0; $rodada=$rod; $tabul=0; $MesaTabul=$tabul;
					while (!feof($fhSCO))
						{
							$TabulRodada[$rod]=$MesaTabul;
							$rod=(int)(TestarSeqAsc2($fhSCO,2));
							if(feof($fhSCO)) {break;}
							
							if($rod<>$rodada) {$Ausr[$rodada]=round($Ausr[$rodada]);}
							
							if($rodada<$rod)
								{
									$rodada=$rod; $tabul=0;
									$VitBr[$rodada]=0;$VitNr[$rodada]=0;$Empatesr[$rodada]=0;$Ausr[$rodada]=0;$Totr[$rodada]=0;
									if($rodada>1) {if ($apresentacao=='geral') {echo '</table>';}}
									if ($apresentacao=='geral') {echo '<br>Rodada:YYY ' . $rodada . '<br>';}
									if ($apresentacao=='geral') {echo '<table border=1>';}
									if ($apresentacao=='geral') {echo '<tr><td>Tab</td><td>Jogador (Brancas)</td><td>Pontos</td><td colspan=3>Resultado</td><td>Jogador (Negras)</td><td>Pontos</td><td>SeqDesc</td><td>W_Type</td><td>B_Type</td><td>W_SubSco</td><td>B_SubSco</td></tr>';}
								}
							//aqui
							//$JogA = TestarSeqAsc2($fhSCO,5) * 1;
							//$JogB = TestarSeqAsc2($fhSCO,5) * 1;
							
							$CodJogA = TestarSeqAsc2($fhSCO,5) * 1;
							$CodJogB = TestarSeqAsc2($fhSCO,5) * 1;
							//echo $rodada . ') -*' . $NumInic[$CodJogA] . '*---*' . $CodJogB . '*---<br>';
							
							$PtsJogA = TestarSeqAsc2($fhSCO,3) / 2;
							$PtsJogB = TestarSeqAsc2($fhSCO,3) / 2;
						
							$tabul = $tabul + 1;
							if($CodJogB>0)
								{
									if ($apresentacao=='geral') {echo '<tr><td>' . $tabul . '</td><td>' . $NomeJogador[$CodJogA] . '(' . $NumInic[$CodJogA] . ')' . '</td><td>' .  $PontosJogador[$CodJogA] . '</td><td>' . $PtsJogA . '</td><td> x </td><td>' . $PtsJogB . '</td><td>' . $NomeJogador[$CodJogB] . '(' . $NumInic[$CodJogB] . ')' . '</td><td>' . $PontosJogador[$CodJogB] . '</td>';}
								}
							else
								{
									if ($apresentacao=='geral') {echo '<tr><td>' . $tabul . '</td><td>' . $NomeJogador[$CodJogA] . '(' . $NumInic[$CodJogA] . ')' . '</td><td>' .  $PontosJogador[$CodJogA] . '</td><td>' . $PtsJogA . '</td><td> x </td><td>' . '-' . '</td><td>' . 'Bye' . ' ' . ' ' . ' ' . '</td><td>' . '-' . '</td>';}
								}
							
							$PontosJogador[$CodJogA] = $PontosJogador[$CodJogA] + $PtsJogA;
							$PontosJogador[$CodJogB] = $PontosJogador[$CodJogB] + $PtsJogB;
								
							$SeqDesc=TestarSeqAsc3($fhSCO,2);
							$W_Type=(int)(TestarSeqAsc3($fhSCO,2))*1;
							$B_Type=TestarSeqAsc3($fhSCO,2);
							$W_SubSco=TestarSeqAsc3($fhSCO,4);
							$B_SubSco=TestarSeqAsc3($fhSCO,4);
							
							// W_Type
							// 35 - (+x-)
							// 34 - (-x+)
							// 36 - (-x-)
							//
							//  1	- (1x0) (0x1) (0.5x0.5)
							//  ?	- ( x )
							
								//$CodRes;
								//if($W_Type==1)
								switch($W_Type)
									{
										case 1:
											if($B_Type==0)
												{
													$CodRes=1;
													$Ausr[$rodada]=$Ausr[$rodada]+0.5;
												}
											if($B_Type==1 AND $CodJogB<1)
												{
													$CodRes=12;
													$Ausr[$rodada]=$Ausr[$rodada]+0.5;
												}
											if($PtsJogA==1 && $PtsJogB==0)
												{
													$CodRes=1;
													$VitBr[$rodada]=$VitBr[$rodada]+1;
													$NumVitJog[$CodJogA]=$NumVitJog[$CodJogA]+1;
												}
										 elseif($PtsJogA==0.5 && $PtsJogB==0.5 )
												{
													$CodRes=2;
													$Empatesr[$rodada]=$Empatesr[$rodada]+1;
												}
										 elseif($PtsJogA==0 && $PtsJogB==1)
												{
													$CodRes=3;
													$VitNr[$rodada]=$VitNr[$rodada]+1;
													$NumVitJog[$CodJogB]=$NumVitJog[$CodJogB]+1;
												}
											else
												{$CodRes=0;}
											break;
										case 35:
											$CodRes=4;
											$Ausr[$rodada]=$Ausr[$rodada]+1;
											break;
										case 34:
											$CodRes=5;
											$Ausr[$rodada]=$Ausr[$rodada]+1;
											break;
										case 36:
											$CodRes=6;
											$Ausr[$rodada]=$Ausr[$rodada]+1;
											$Ausr[$rodada]=$Ausr[$rodada]+1;
											break;
									}
							
							$rr=$rodada; $MesaTabul=$tabul;
							$JogA = $NomeJogador[$CodJogA] . " (" . $Order[$CodJogA] . ")";
							$JogB = $NomeJogador[$CodJogB] . " (" . $NumInic[$CodJogB] . ")";							
							//$JogA = $NomeJogador[$ID[$CodJogA]] . " (" . $ID[$CodJogA] . ")";	// ************ Thiago x Tarcisio x Karina **********
							//$JogB = $NomeJogador[$ID[$CodJogB]] . " (" . $ID[$CodJogB] . ")";
							
							//$PontosJogador[$CodJogA] = $PontosJogador[$CodJogA] + $PtsJogA;
							//$PontosJogador[$CodJogB] = $PontosJogador[$CodJogB] + $PtsJogB;
							
							$RodMesaResult[$rr][$MesaTabul][1]=$JogA;
							$RodMesaResult[$rr][$MesaTabul][2]=$PontosJogador[$CodJogA];
							$RodMesaResult[$rr][$MesaTabul][3]=$Resultados[$CodRes];
							if($CodJogB<1)
								{$RodMesaResult[$rr][$MesaTabul][4]=0;}
							else
								{$RodMesaResult[$rr][$MesaTabul][4]=$PontosJogador[$CodJogB];}
							$RodMesaResult[$rr][$MesaTabul][5]=$JogB;
							
							
// *********** CrossTable *** Inicio ****************************************************
								$CrossTable[$CodJogA][$rr][1]=$CodJogB;
								$CrossTable[$CodJogA][$rr][2]='b';
								//if($PontosJogA==0.5)
								if($PtsJogA==0.5)
									{$CrossTable[$CodJogA][$rr][3]='½';}
								else
									{
										//$CrossTable[$CodJogA][$r][3]=$PontosJogA;
										if($CodRes==4)
											{$CrossTable[$CodJogA][$rr][3]='+';}
										elseif($CodRes==5)
											{$CrossTable[$CodJogA][$rr][3]='-';}
										else
											//{$CrossTable[$CodJogA][$rr][3]=$PontosJogA;}
											//{$CrossTable[$CodJogA][$rr][3]=$ResulJogA;}
											{$CrossTable[$CodJogA][$rr][3]=$PtsJogA;}
									}
								
								if($CodJogB>0)
								 {
										$CrossTable[$CodJogB][$rr][1]=$CodJogA;
										$CrossTable[$CodJogB][$rr][2]='n';
										
											//if($Status[$CodJogA]==0 && $Status[$CodJogB]==0)
											//if($Status[$CodJogA]==0 && $CodJogB>0 && ($CodRes==1 || $CodRes==2 || $CodRes==3 || $CodRes==4 || $CodRes==5))
											if($CodRes==1 || $CodRes==2 || $CodRes==3)
												{
													$JogNegras[$CodJogB]=$JogNegras[$CodJogB]+1;
												}
										
										//if($PontosJogB==0.5)
										if($PtsJogB==0.5)
											{$CrossTable[$CodJogB][$rr][3]='½';}
										else
											{
												//$CrossTable[$CodJogB][$rr][3]=$PontosJogB;
												if($CodRes==4)
													{$CrossTable[$CodJogB][$rr][3]='-';}
												elseif($CodRes==5)
													{$CrossTable[$CodJogB][$rr][3]='+';}
												else
													//{$CrossTable[$CodJogB][$rr][3]=$PontosJogB;}
													//{$CrossTable[$CodJogB][$rr][3]=$ResulJogB;}
													{$CrossTable[$CodJogB][$rr][3]=$PtsJogB;}
											}
								 }
								else
								 {
										$CrossTable[$CodJogA][$rr][1]='-';
										$CrossTable[$CodJogA][$rr][2]='-';
									}
								
								/*																					
									bb = ""
									If JogB = "Bye" Then CodRes = CodRes + 12 ': MsgBox (JogA & " - " & CodRes & " - " & Resultados(CodRes))
									If Text2_BlNaoIdent = "N" Or Text2_BlNaoIdent = "T" Then Text2.Text = Text2.Text & JogA & Resultados(CodRes) & JogB & vbCrLf
								*/
								
								$PtsDesemp[1][$CodJogA] = $PontosJogador[$CodJogA];
								if($CodJogB>0)
									{$PtsDesemp[1][$CodJogB] = $PontosJogador[$CodJogB];}
								
// *********** CrossTable *** Final ****************************************************
							
							
							if ($apresentacao=='geral') {echo '<td>' . $SeqDesc . '</td><td>' . $W_Type . '</td><td>' . $B_Type . '</td><td>' . $W_SubSco . '</td><td>' . $B_SubSco . '</td></tr>';}
							
						}
						
					$Ausr[$rodada]=round($Ausr[$rodada]);
					
					if ($apresentacao=='geral') {echo '</table>';}
							
			if ($apresentacao=='geral') {echo '<br> FIM - 10402<br><br>';}
			
			if ($apresentacao=='geral') {echo '<br><br> ********* fim do Arquivo SCO *************<br><br>';}
			
			/*
				echo '<table>';
				for($rr=1;$rr<6;$rr++)
					{
						for($MesaTabul=1;$MesaTabul<5;$MesaTabul++)
							{
								echo '<tr><td>'.$RodMesaResult[$rr][$MesaTabul][1].'</td><td>'.$RodMesaResult[$rr][$MesaTabul][2].'</td><td>'.$RodMesaResult[$rr][$MesaTabul][3].'</td><td>'.$RodMesaResult[$rr][$MesaTabul][4].'</td><td>'.$RodMesaResult[$rr][$MesaTabul][5].'</td></tr>';
							}
					}
				echo '</table>';
			*/
			
			if($apresentacao=='geral')
				{
					echo '<div>';
					echo ' Y Y Y Y Y Y Y Y Y Y Y Y Y Y Y Y Y Y Y ';
					echo '<table>';
					for($rr=1;$rr<=2;$rr++)
						{
							echo '<tr><td colspan=6>Rodada: ' . $rr . '</td></tr>';
							for($MesaTabul=1;$MesaTabul<=$TabulRodada[$rr];$MesaTabul++)
								{
									echo '<tr><td>'.$MesaTabul.'</td><td>'.$RodMesaResult[$rr][$MesaTabul][1].'</td><td>'.$RodMesaResult[$rr][$MesaTabul][2].'</td><td>'.$RodMesaResult[$rr][$MesaTabul][3].'</td><td>'.$RodMesaResult[$rr][$MesaTabul][4].'</td><td>'.$RodMesaResult[$rr][$MesaTabul][5].'</td></tr>';
								}
						}
					echo '</table>';
					echo '</div>';
				}

			/*
			echo '<br><br>' . $NumRodadas . ' - ' . $QtJogadores . '<br><br>';
			for($ij=1;$ij<=$QtJogadores;$ij++)
				{
					for($ir=1;$ir<=$NumRodadas;$ir++)
						{
							if(trim($CrossTable[$ij][$ir][1])=='')
								{
									$CrossTable[$ij][$ir][1]='-';
									$CrossTable[$ij][$ir][2]='-';
									$CrossTable[$ij][$ir][3]=0;
								}
						}
				}
			*/

			
		}
	

	// ......................................................
	//exit;
	// *****************************************************************************
	/*
	function LerCampoStr($PointerFile)
		{
			$TamString=ord(fread($PointerFile, 1)) * 2;
			if ($TamString>0)
				{$VrlString = fread($PointerFile, $TamString);}
			else
				{$VrlString = " ";}
			$Nulo=fread($PointerFile, 1);
			return $VrlString;
		}
 */
function LerCampoStrA($PointerFile,$QtBytes)
	{
		$VrlString = fread($PointerFile, $QtBytes);
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

  function LerCampoLong($PointerFile)
			{
			 $VrlLong = fread($PointerFile, 4);
				//$arr = unpack('Lints4', $VrlLong);
				$arr = unpack('Lints1', $VrlLong);
				//$value = $arr['ints1'] * (256 * 256 * 256) + $arr['ints2'] * (256 * 256) + $arr['ints3'] * (256) + intval($arr['ints4']);
				$value = $arr['ints1'];
				return $value;
			}

  function LerCampoInt($PointerFile)
			{
    $arr[0]=0;
				$VlrInt = strrev(fread($PointerFile, 2));
				$arr = unpack('n', $VlrInt);
    $value = $arr[0]*256 + $arr[1];
				return $value;
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
					  {$VrlAsc = $VrlAsc . $byte;}
					}
				return $VrlAsc;
			}
  
  function TestarSeqAsc3($PointerFile,$QtBytes)
			{
				$VrlAsc = "";
    for ($i=1;$i<=$QtBytes;$i++)
				 {
				  $byte = fread($PointerFile, 1);
				  $cod = ord($byte);
				  if ($cod>0)
					  {$VrlAsc = $VrlAsc . $byte;}
					}
				return (int)(trim($VrlAsc));
			}  
		
		//---------------------------------
		function TestarSeqAsc4($PointerFile,$QtBytes)
			{
				$VrlAsc = "";
    for ($i=1;$i<=$QtBytes;$i++)
				 {
				  $byte = fread($PointerFile, 1);
				  $cod = ord($byte);
				  //if ($cod>0)
					  {$VrlAsc = $VrlAsc . $cod . '/';}
					}
				return $VrlAsc;
			}
		
		//---------------------------------
  function Montar_Matriz_Desempates()
		 {
			 global $Matriz_DesempatesSP,$Idioma;
    $Matriz_DesempatesSP[0][0][1] = " "; $Matriz_DesempatesSP[0][0][2] = "N"; $Matriz_DesempatesSP[0][0][3] = "N"; $Matriz_DesempatesSP[0][0][4] = "N"; $Matriz_DesempatesSP[0][0][5] = "N";
    
    // Idioma 0 - Inglês   --   Swiss Perfect
				$Matriz_DesempatesSP[0][1][1] = "Buchholz";					    $Matriz_DesempatesSP[0][1][2] = "s";	$Matriz_DesempatesSP[0][1][3] = " ";	$Matriz_DesempatesSP[0][1][4] = " ";	$Matriz_DesempatesSP[0][1][5] = " ";
				$Matriz_DesempatesSP[0][2][1] = "Median Buchholz";     	$Matriz_DesempatesSP[0][2][2] = "s";	$Matriz_DesempatesSP[0][2][3] = " ";	$Matriz_DesempatesSP[0][2][4] = " ";	$Matriz_DesempatesSP[0][2][5] = " ";
				$Matriz_DesempatesSP[0][3][1] = "Progress";     				$Matriz_DesempatesSP[0][3][2] = "N";	$Matriz_DesempatesSP[0][3][3] = " ";	$Matriz_DesempatesSP[0][3][4] = " ";	$Matriz_DesempatesSP[0][3][5] = " ";
				$Matriz_DesempatesSP[0][4][1] = "Berger";     					$Matriz_DesempatesSP[0][4][2] = "s";	$Matriz_DesempatesSP[0][4][3] = " ";	$Matriz_DesempatesSP[0][4][4] = " ";	$Matriz_DesempatesSP[0][4][5] = " ";
				$Matriz_DesempatesSP[0][5][1] = "Rating Sum";       		$Matriz_DesempatesSP[0][5][2] = " ";	$Matriz_DesempatesSP[0][5][3] = " ";	$Matriz_DesempatesSP[0][5][4] = " ";	$Matriz_DesempatesSP[0][5][5] = " ";
				$Matriz_DesempatesSP[0][6][1] = "Number of Wins";   		$Matriz_DesempatesSP[0][6][2] = "N";	$Matriz_DesempatesSP[0][6][3] = " ";	$Matriz_DesempatesSP[0][6][4] = " ";	$Matriz_DesempatesSP[0][6][5] = " ";
				$Matriz_DesempatesSP[0][7][1] = "Minor Scores";         $Matriz_DesempatesSP[0][7][2] = " ";	$Matriz_DesempatesSP[0][7][3] = " ";	$Matriz_DesempatesSP[0][7][4] = " ";	$Matriz_DesempatesSP[0][7][5] = " ";
				$Matriz_DesempatesSP[0][8][1] = " ";                    $Matriz_DesempatesSP[0][8][2] = " ";	$Matriz_DesempatesSP[0][8][3] = " ";	$Matriz_DesempatesSP[0][8][4] = " ";	$Matriz_DesempatesSP[0][8][5] = " ";
				$Matriz_DesempatesSP[0][9][1] = " ";										$Matriz_DesempatesSP[0][9][2] = " ";	$Matriz_DesempatesSP[0][9][3] = " ";	$Matriz_DesempatesSP[0][9][4] = " ";	$Matriz_DesempatesSP[0][9][5] = " ";
				$Matriz_DesempatesSP[0][10][1] = "Brightwell";          $Matriz_DesempatesSP[0][10][2] = " ";	$Matriz_DesempatesSP[0][10][3] = " ";	$Matriz_DesempatesSP[0][10][4] = " ";	$Matriz_DesempatesSP[0][10][5] = " ";
				
				
    // Idioma 1 - Português BR   -- Swiss Perfect
				$Matriz_DesempatesSP[1][1][1] = "Buchholz Total";       $Matriz_DesempatesSP[1][1][2] = "s"; $Matriz_DesempatesSP[1][1][3] = " "; $Matriz_DesempatesSP[1][1][4] = " "; $Matriz_DesempatesSP[1][1][5] = " ";
				$Matriz_DesempatesSP[1][2][1] = "Buchholz Mediano";     $Matriz_DesempatesSP[1][2][2] = "s"; $Matriz_DesempatesSP[1][2][3] = " "; $Matriz_DesempatesSP[1][2][4] = " "; $Matriz_DesempatesSP[1][2][5] = " ";
				$Matriz_DesempatesSP[1][3][1] = "Escore Acumulado";     $Matriz_DesempatesSP[1][3][2] = "N"; $Matriz_DesempatesSP[1][3][3] = " "; $Matriz_DesempatesSP[1][3][4] = " "; $Matriz_DesempatesSP[1][3][5] = " ";
				$Matriz_DesempatesSP[1][4][1] = "Sonneborn-Berger";     $Matriz_DesempatesSP[1][4][2] = "s"; $Matriz_DesempatesSP[1][4][3] = " "; $Matriz_DesempatesSP[1][4][4] = " "; $Matriz_DesempatesSP[1][4][5] = " ";
				$Matriz_DesempatesSP[1][5][1] = "Soma de Rating";       $Matriz_DesempatesSP[1][5][2] = " "; $Matriz_DesempatesSP[1][5][3] = " "; $Matriz_DesempatesSP[1][5][4] = " "; $Matriz_DesempatesSP[1][5][5] = " ";
				$Matriz_DesempatesSP[1][6][1] = "Número de Vitórias";		$Matriz_DesempatesSP[1][6][2] = "N"; $Matriz_DesempatesSP[1][6][3] = " "; $Matriz_DesempatesSP[1][6][4] = " "; $Matriz_DesempatesSP[1][6][5] = " ";
				$Matriz_DesempatesSP[1][7][1] = "Sub-Escores";          $Matriz_DesempatesSP[1][7][2] = " "; $Matriz_DesempatesSP[1][7][3] = " "; $Matriz_DesempatesSP[1][7][4] = " "; $Matriz_DesempatesSP[1][7][5] = " ";
				$Matriz_DesempatesSP[1][8][1] = " ";                    $Matriz_DesempatesSP[0][8][2] = " "; $Matriz_DesempatesSP[0][8][3] = " "; $Matriz_DesempatesSP[0][8][4] = " "; $Matriz_DesempatesSP[0][8][5] = " ";
				$Matriz_DesempatesSP[1][9][1] = " ";										$Matriz_DesempatesSP[0][9][2] = " "; $Matriz_DesempatesSP[0][9][3] = " "; $Matriz_DesempatesSP[0][9][4] = " "; $Matriz_DesempatesSP[0][9][5] = " ";
				$Matriz_DesempatesSP[1][10][1] = "Brightwell";          $Matriz_DesempatesSP[1][10][2] = " "; $Matriz_DesempatesSP[1][10][3] = " "; $Matriz_DesempatesSP[1][10][4] = " "; $Matriz_DesempatesSP[1][10][5] = " ";
			}
		
  
		//$CodRes;
		function Montar_Matriz_Resultados()
		 {
			 global $Resultados;
        $Resultados[0] = "   x   ";
        $Resultados[1] = " 1 x 0 ";
        $Resultados[2] = " ½ x ½ ";
        $Resultados[3] = " 0 x 1 ";
        $Resultados[4] = " + x - ";
        $Resultados[5] = " - x + ";
        $Resultados[6] = " - x - ";
        $Resultados[7] = " A x A ";
        $Resultados[8] = " ? x ? ";
        $Resultados[9] = " 1 x - ";
        $Resultados[10] = " 0 x 0 ";
        $Resultados[11] = " 0 x ½ ";
        $Resultados[12] = " ½ x 0 ";
								
        $Resultados[13] = " 1 x - ";  // *** Levy ***
        $Resultados[14] = " ½ x - ";  // *** Levy ***
        $Resultados[15] = " - x - ";  // *** Levy ***

        //$Resultados[13] = " 1   13";  // *** Levy ***
        //$Resultados[14] = " ½   14";  // *** Levy ***
        //$Resultados[15] = " 0   15";  // *** Levy ***

        //$Resultados[21] = " 1 x 0 ";  // *** Continental ***
								
			}
			
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
		
  function CalcularDesempates()
			{
				//global $PtsDesemp,$Desempate,$DigFide9,$Mult,$QtJogadores,$NomeJogador,$Status,$NumRodadas,$PontosJogador,$QtDesempates,$JogNegras,$NumVitJog,$CrossTable,$PontosBH25,$PontosSB,$PontosKoya;
				global $PtsDesemp,$Desempate,$QtPior,$QtMelhor,$PNJ,$ExpPNJ,$DigFide9,$Mult,$QtJogadores,$DM,$NomeJogador,$Status,$NumRodadas,$PontosJogador,$QtDesempates,$JogNegras,$NumVitJog,$CrossTable;
				global $BuchholzAdjust,$Corte,$CorteBhQtMelhor,$CorteBhQtPior;
				for($i=1;$i<=$QtDesempates;$i++)
					{
						switch ($Desempate[$i])
							{
								
								case 1:		//Buchholz Total            //2-Buchholz Tie-Breaks (all Results)
									//
									//if($BuchholzAdjust[$NrDesempate]==1)   ---   if($BuchholzAdjust[$i]==1)
									for($j=1;$j<=$QtJogadores;$j++)
										{
											$PontosBH2[$i][$j] = 0;
											for($r=1;$r<=$NumRodadas;$r++)
												{
													$PontosAdver[$r] = 0;
													$adver=$CrossTable[$j][$r][1];
													for($r1=1;$r1<=$NumRodadas;$r1++)
														{
															switch(''.$CrossTable[$adver][$r1][3].'')
																{
																	case '1':
																		if(''.$CrossTable[$adver][$r1][1].''=='-')
																			{
																				if($BuchholzAdjust[$i]==1)
																					{$PontosAdver[$r] = $PontosAdver[$r] + 0.5;}
																				else
																					{$PontosAdver[$r] = $PontosAdver[$r] + 1;}
																			}
																		else
																			{$PontosAdver[$r] = $PontosAdver[$r] + 1;}
																		break;
																	case "½":
																			{$PontosAdver[$r] = $PontosAdver[$r] + 0.5;}
																		break;
																	case "+":
																			{
																				if($BuchholzAdjust[$i]==1)
																					{$PontosAdver[$r] = $PontosAdver[$r] + 0.5;}
																				else
																					{$PontosAdver[$r] = $PontosAdver[$r] + 1;}
																			}
																		break;
																	case "-":
																			{
																				if($BuchholzAdjust[$i]==1)
																					{$PontosAdver[$r] = $PontosAdver[$r] + 0.5;}
																				else
																					{$PontosAdver[$r] = $PontosAdver[$r] + 0;}
																			}
																		break;
																	case "0":														
																		if(''.$CrossTable[$adver][$r1][1].''=='-')
																			{
																				if($BuchholzAdjust[$i]==1)
																				 {$PontosAdver[$r] = $PontosAdver[$r] + 0.5;}
																				else
																				 {$PontosAdver[$r] = $PontosAdver[$r] + 0;}
																			}
																		else
																			{$PontosAdver[$r] = $PontosAdver[$r] + 0;}
																		break;
																	default:
																		break;
																}
														}
													$PontosBH2[$i][$j] = $PontosBH2[$i][$j] + $PontosAdver[$r];
												}
											
											for($jj=1;$jj<=$QtJogadores;$jj++)
												{
													if($PtsDesemp[0][$jj]==$j)
														{
															$PtsDesemp[$i+1][$jj] = $PontosBH2[$i][$j];
														}
												}
										}
   						break;
								
								case 2:		//Median Buchholz             //37-Buchholz Tie-Breaks (variabel with parameter)
									//$Corte[$NrDesempate] / $CorteBhQtMelhor[$NrDesempate] / $CorteBhQtPior[$NrDesempate]
									//$Corte[$i]           / $CorteBhQtMelhor[$i]           / $CorteBhQtPior[$i]
									//$BuchholzAdjust[$NrDesempate]   ---   $BuchholzAdjust[$i]
									for($j=1;$j<=$QtJogadores;$j++)
										{
											$PontosBH37[$i][$j] = 0;
											for($r=1;$r<=$NumRodadas;$r++)
												{
													$PontosAdver[$r] = 0;
													$adver=$CrossTable[$j][$r][1];
													for($r1=1;$r1<=$NumRodadas;$r1++)
														{
															switch(''.$CrossTable[$adver][$r1][3].'')
																{
																	case '1':
																		if(''.$CrossTable[$adver][$r1][1].''=='-')
																			{
																				if($BuchholzAdjust[$i]==1)
																					{$PontosAdver[$r] = $PontosAdver[$r] + 0.5;}
																				else
																					{$PontosAdver[$r] = $PontosAdver[$r] + 1;}
																			}
																		else
																			{$PontosAdver[$r] = $PontosAdver[$r] + 1;}
																		break;
																	case "½":
																			{$PontosAdver[$r] = $PontosAdver[$r] + 0.5;}
																		break;
																	case "+":
																			{
																				if($BuchholzAdjust[$i]==1)
																					{$PontosAdver[$r] = $PontosAdver[$r] + 0.5;}
																				else
																					{$PontosAdver[$r] = $PontosAdver[$r] + 1;}
																			}
																		break;
																	case "-":
																			{
																				if($BuchholzAdjust[$i]==1)
																					{$PontosAdver[$r] = $PontosAdver[$r] + 0.5;}
																				else
																					{$PontosAdver[$r] = $PontosAdver[$r] + 0;}
																			}
																		break;
																	case "0":														
																		if(''.$CrossTable[$adver][$r1][1].''=='-')
																			{
																				if($BuchholzAdjust[$i]==1)
																				 {$PontosAdver[$r] = $PontosAdver[$r] + 0.5;}
																				else
																				 {$PontosAdver[$r] = $PontosAdver[$r] + 0;}
																			}
																		else
																			{$PontosAdver[$r] = $PontosAdver[$r] + 0;}
																		break;
																	default:
																		break;
																}
														}
													
													
													$PontosBH37[$i][$j] = $PontosBH37[$i][$j] + $PontosAdver[$r];
													if($Corte[$i]>0) {$PontosAdverOrd[$r-1] = $PontosAdver[$r];}
													
												}
											
													if($Corte[$i]>0)
														{
															// ordena resultados-corrigidos dos adversários
															rsort($PontosAdverOrd,SORT_NUMERIC);
															
															$QtAdver=count($PontosAdverOrd);
															
															/*
																if($j==28)
																{
																	for($jz=0;$jz<$QtAdver;$jz++)
																		{
																			echo $PontosAdverOrd[$jz] . '<br>';
																		}
																	echo $QtAdver . ' - ' . count($PontosAdverOrd) . ' - ' . $CorteBhQtPior[$i] . ' - ' . (count($PontosAdverOrd)-$CorteBhQtPior[$i]) . '<br>';
																}
															*/
															
															if($CorteBhQtMelhor[$i]>0)
																{
																	for($jz=0;$jz<$CorteBhQtMelhor[$i];$jz++)
																		{$PontosBH37[$i][$j] = $PontosBH37[$i][$j] - $PontosAdverOrd[$jz];}
																}
															
															if($CorteBhQtPior[$i]>0)
																{
																	for($jz=$QtAdver-1;$jz>=($QtAdver-$CorteBhQtPior[$i]);$jz--)
																		{$PontosBH37[$i][$j] = $PontosBH37[$i][$j] - $PontosAdverOrd[$jz];}
																}
															
															
														}
													
													
												/*
													//$Corte[$i]           / $CorteBhQtMelhor[$i]           / $CorteBhQtPior[$i]
													if($Corte[$i]>0)
														{
															// ordena resultados-corrigidos dos adversários
															rsort($PontosAdverOrd,SORT_NUMERIC);
															
															for($jz=0;$jz<$QtMelhor[$i];$jz++)
															for($jz=$NumRodadas;$jz>$NumRodadas-$QtPior[$i];$jz--)
												*/	
													
													
											for($jj=1;$jj<=$QtJogadores;$jj++)
												{
													if($PtsDesemp[0][$jj]==$j)
														{
															$PtsDesemp[$i+1][$jj] = $PontosBH37[$i][$j];
														}
												}
										}
   						break;
								
								case 3:		// 3 - Progress (Escore Acumulado)															//8 - Fide Tye-Break - (Progressive Score / Cumulative Score)
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
								
								case 6:		// Number of Wins      //12-The greater number of victories
									for($j=1;$j<=$QtJogadores;$j++)
										{
											for($jj=1;$jj<=$QtJogadores;$jj++)
												{
													if($PtsDesemp[0][$jj]==$j)
														{$PtsDesemp[$i+1][$jj] = $NumVitJog[$j];}
												}
										}
									break;
								
								
							
							
							/*	
								case 1: //Progress
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
													// ordena resultados dos adversários e marca os cortes //
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
																			//
																			//	switch(''.$CrossTable[$j][$r][3].'')
																			//		{
																			//			case '1':
																			//				$PontosAdver[2][$r-1] = $PontosJogador[$CrossTable[$j][$r][1]];
																			//				break;
																			//			case '½':
																			//				//$PontosAdver[2][$r-1] = $PontosJogador[$CrossTable[$j][$r][1]]/2;
																			//				$PontosAdver[2][$r-1] = $PontosJogador[$CrossTable[$j][$r][1]];
																			//				break;
																			//			default:
																			//				$PontosAdver[2][$r-1] = 0;
																			//				break;
																			//		}
																			//
																			$PontosAdver[2][$r-1] = $PontosJogador[$CrossTable[$j][$r][1]];
																		}
																}
														}
													
													//
													//	for($zz=0;$zz<$NumRodadas;$zz++)
													//		{echo $j . ' * ' . $PontosAdver[0][$zz].' - '.$PontosAdver[1][$zz].' - '.$PontosAdver[2][$zz].'<br>';}
													//	echo '<br>';
													//
													array_multisort($PontosAdver[2], SORT_NUMERIC, SORT_DESC,
																													$PontosAdver[1], SORT_NUMERIC, SORT_DESC,
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
											$QtCortesM=count($lista_cortes_M);
											$QtCortesP=count($lista_cortes_P);
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
//																		if($Status[$j]=='0' && $Status[''.$CrossTable[$j][$r][1]].''=='0')
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
		//																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																													{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 1;}
																												}
																											break;
																										case "½":
																											if($adver1=='-')
																												{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																											else
																												{
		//																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																														{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																												}
																											break;
																										case "+":
		//																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																												{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																												//{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 1;}
																											break;
																										case "-":
		//																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
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
//																		if($Status[$j]=='0' && $Status[''.$CrossTable[$j][$r][1]].''=='0')
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
//																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																										  {$PontosBH[$i][$j] = $PontosBH[$i][$j] + 1;}
																										}
																									break;
																								case "½":
																									if($adver1=='-')
																										{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																									else
																										{
//																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
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
//																		if($Status[$j]=='0' && $Status[''.$CrossTable[$j][$r][1]].''=='0')
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
			//																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																															{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 1;}
																													}
																												break;
																											case "½":
																												if($adver1=='-')
																													{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																												else
																													{
			//																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																															{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																													}
																												break;
																											case "+":
			//																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																													{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																												break;
																											case "-":
			// 																								if($Status[$adver]=='0' && $Status[$adver1]=='0')
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
		//																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																														{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 1;}
																												}
																											break;
																										case "½":
																											if($adver1=='-')
																												{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																											else
																												{
		//																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																														{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																												}
																											break;
																										case "+":
		//																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																												{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																											break;
																										case "-":
		// 																								if($Status[$adver]=='0' && $Status[$adver1]=='0')
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
//																		if($Status[$j]=='0' && $Status[''.$CrossTable[$j][$r][1]].''=='0')
																			{
																				$adver=''.$CrossTable[$j][$r][1].'';
																				if($adver=='-')
																					{$PontosBH[$i][$j] = $PontosBH[$i][$j] + $NumRodadas/2;}
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
//																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																										  {$PontosBH[$i][$j] = $PontosBH[$i][$j] + 1;}
																										}
																									break;
																								case "½":
																									if($adver1=='-')
																										{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																									else
																										{
//																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																										  {$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																										}
																									break;
																								case "+":
//																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																										{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																										//{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 1;}
																									break;
																								case "-":
//																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
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
//																		if($Status[$j]=='0' && $Status[''.$CrossTable[$j][$r][1]].''=='0')
																			{
																				$adver=''.$CrossTable[$j][$r][1].'';
																				for($r1=1;$r1<=$NumRodadas;$r1++)
																					{
																						$adver1=''.$CrossTable[$adver][$r1][1].'';
																						//$test=''.$CrossTable[$adver][$r1][3].'';
																						switch(''.$CrossTable[$adver][$r1][3].'')
																							{
																								case '1':
//																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																										{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 1;}
																									break;
																								case "½":
																									if($adver1=='-')
																										{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																									else
																										{
//																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																										  {$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																										}
																									break;
																								case "+":
//																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
																										{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 0.5;}
																										//{$PontosBH[$i][$j] = $PontosBH[$i][$j] + 1;}
																									break;
																								case "-":
//																									if($Status[$adver]=='0' && $Status[$adver1]=='0')
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
														{$PtsDesemp[$i+1][$jj] = $PontosKoya[$i][$j];}
												}
										}
								
									break;
								
								case 52:		// 52-Sonneborn-Berger (variavel)
									//$Matriz_DesempatesSP[$Idioma][$Desempate[]][2], $Corte[], $QtPior[], $QtMelhor[], $Adic[], $PNJ_Adic[], $ExpAdic[], $ExpPNJ[]
									
									for($j=1;$j<=$QtJogadores;$j++)
										{
											$PontosSB[$i][$j] = 0;
											
											//********************************************
											//echo "<script language='javascript' type='text/javascript'>alert('Corte: ".$i.'-'.$Corte[$i]."');</script>";													
											if($Corte[$i]>0)
												{
													unset($lista_cortes_M);unset($lista_cortes_P);
													// ordena resultados dos adversários e marca os cortes //
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
													
													//
													//	for($zz=0;$zz<$NumRodadas;$zz++)
													//		{echo $j . ' * ' . $PontosAdver[0][$zz].' - '.$PontosAdver[1][$zz].' - '.$PontosAdver[2][$zz].'<br>';}
													//	echo '<br>';
													//
													array_multisort($PontosAdver[2], SORT_NUMERIC, SORT_DESC,
																													$PontosAdver[1], SORT_NUMERIC, SORT_DESC,
																													$PontosAdver[0]
																												);
											  //echo "<script language='javascript' type='text/javascript'>alert('QtMelhor: ".$QtMelhor[$i]. ' - '.$QtPior[$i]."');</script>";													
													//
													//	for($zz=0;$zz<$NumRodadas;$zz++)
													//		{echo $j . ' * ' . $PontosAdver[0][$zz].' - '.$PontosAdver[1][$zz].' - '.$PontosAdver[2][$zz].'<br>';}
													//	echo '<br>';													
													//
													
													for($jz=0;$jz<$QtMelhor[$i];$jz++)
														{$lista_cortes_M[$jz] = $PontosAdver[0][$jz];}
													for($jz=$QtPior[$i];$jz>$NumRodadas-$QtPior[$i];$jz--)
														{$lista_cortes_P[$jz] = $PontosAdver[0][$jz];}
												}
											else
												{unset($lista_cortes_M);unset($lista_cortes_P);}
											$QtCortesM=count($lista_cortes_M);
											$QtCortesP=count($lista_cortes_P);
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
							*/
							}
					}
			}
		
  function CalcularConfrontoDireto($Crit)
			{
				global $PtsDesemp,$QtJogadores,$PontosJogador,$CrossTable,$NumRodadas;
				
				array_multisort($PtsDesemp[1], SORT_NUMERIC, SORT_DESC,
																				$PtsDesemp[2], SORT_NUMERIC, SORT_DESC,
																				$PtsDesemp[3], SORT_NUMERIC, SORT_DESC,
																				$PtsDesemp[4], SORT_NUMERIC, SORT_DESC,
																				$PtsDesemp[5], SORT_NUMERIC, SORT_DESC,
																				$PtsDesemp[6], SORT_NUMERIC, SORT_DESC,
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
				
				array_multisort($PtsDesemp[1], SORT_NUMERIC, SORT_DESC,
																				$PtsDesemp[2], SORT_NUMERIC, SORT_DESC,
																				$PtsDesemp[3], SORT_NUMERIC, SORT_DESC,
																				$PtsDesemp[4], SORT_NUMERIC, SORT_DESC,
																				$PtsDesemp[5], SORT_NUMERIC, SORT_DESC,
																				$PtsDesemp[6], SORT_NUMERIC, SORT_DESC,
																				$PtsDesemp[0]
																			);
				// Restabelece a Matriz "$PtsDesemp[crit][Jog]" para "Jog" inicial = 1
				for($itb=$QtJogadores;$itb>0;$itb--)
					{ for($ic=0;$ic<=6;$ic++) {$PtsDesemp[$ic][$itb]=$PtsDesemp[$ic][$itb-1];} }
				for($ic=0;$ic<=6;$ic++) {unset($PtsDesemp[$ic][0]);}
				
			}

		// ************ Testes **********
					/*
						$h='3f'; $d=hexdec($h); $b=decbin($d); echo $h . ' ' . $d . ' ' . $b . ' ' . log($d,2) . ' ' . log(63,2) . ' ' . log(64,2) . '<br>';
						for($p=0;$p<6;$p++)
							{
								$tf=(63-8)&pow(2,$p); echo $p . ' - ' . $tf . '<br>';
							}
						
						echo '<br><br> Rod. NE: ' . NaoEmp(37,6) . '<br><br>';
					*/
		// ************ Testes **********		

?>
