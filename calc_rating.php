	<?php
		// Lendo String de conexão
		
		$dif_max_rating = 400;
		
		//echo "teste1<br>";
		$file = "../config/conexao_ca.cfg";
		$fh = fopen($file, 'r');
		$conteudo = explode("*", fread($fh, filesize($file)));
		$strconexao = trim($conteudo[0]);
		$codificacao = trim($conteudo[1]);
		fclose($fh);
		$conexao=pg_connect($strconexao) or die("erro na conexão");
		
		//Expectância
		//echo "Expectância<br>";
		$sql =pg_query($conexao,"SELECT id, dif_min, dif_max, perc_sup, perc_inf FROM expectancia ORDER BY id") or die("erro de SELECT");
		$linhas=pg_num_rows($sql);
		$qtfaixas=$linhas;
		$i=0;
		//echo "Qt. de Faixas: $qtfaixas<br>";
		for ($j2=0;$j2<=3;$j2++)
		{
			for ($j1=1;$j1<=$qtfaixas;$j1++)
			{
				$lista_faixas[$j1][$j2]=0;
			}
		}
		while ($i<$qtfaixas)
		{
			$id = trim(pg_result($sql,$i,'id'));
			$dif_min = trim(pg_result($sql,$i,'dif_min'));
			$dif_max = trim(pg_result($sql,$i,'dif_max'));
			$perc_sup = trim(pg_result($sql,$i,'perc_sup'));
			$perc_inf = trim(pg_result($sql,$i,'perc_inf'));
			$lista_faixas[$i][0]=$dif_min;
			$lista_faixas[$i][1]=$dif_max;
			$lista_faixas[$i][2]=$perc_inf;
			$lista_faixas[$i][3]=$perc_sup;
			//echo "$id, $dif_min, $dif_max, $perc_sup, $perc_inf <br>";
			$i++;
		}
		//echo "<br><br>";
		
		$torneio=27509;
		//Jogadores
		//echo "Jogadores<br>";
		$sql =pg_query($conexao,"SELECT id, torneioid, numjog, nome, rating, k FROM torneios_jogadores WHERE torneioid=$torneio ORDER BY torneioid, numjog") or die("erro de SELECT");
		$linhas=pg_num_rows($sql);
		$qtjogadores=$linhas;
		$i=0;
		echo "Num. de Jogadores: $qtjogadores<br>";
		for ($j2=0;$j2<=12;$j2++)
		{
			for ($j1=1;$j1<=$qtjogadores;$j1++)
			{
				$lista_jogadores[$j1][$j2]=0;
			}
		}
		echo '<table border=1>';
		echo "<tr><td>ord</td><td>id</td><td>torneioid</td><td>numjog</td><td>nome</td><td>rating</td><td>k</td><td>";
		while ($i<$qtjogadores)
		{
			$id = trim(pg_result($sql,$i,'id'));
			$torneioid = trim(pg_result($sql,$i,'torneioid'));
			$numjog = trim(pg_result($sql,$i,'numjog'));
			$nome = trim(pg_result($sql,$i,'nome'));
			$rating = trim(pg_result($sql,$i,'rating'));
			$k = trim(pg_result($sql,$i,'k'));
			echo "<tr><td>$i</td><td>$id</td><td>$torneioid</td><td>$numjog</td><td>$nome</td><td>$rating</td><td>$k</td><td>";
			$lista_jogadores[$numjog][0]=$rating;
			$lista_jogadores[$numjog][1]=$k;
			$lista_jogadores[$numjog][12]=$nome;
			//echo $lista_jogadores[$numjog][0] . '</td><td>' . $lista_jogadores[$numjog][1] . '</td></tr>';
			$i++;
		}
		echo "</table>";
		//echo "<br><br>";
		
		//Rodadas
		//echo "Rodadas<br>";
		$sql =pg_query($conexao,"SELECT id, torneioid, rodada, tabuleiro, numjogb, numjogp, resultado FROM torneios_rodadas WHERE torneioid=$torneio AND resultado<=3 ORDER BY rodada, tabuleiro") or die("erro de SELECT");
		$linhas=pg_num_rows($sql);
		$qtrodadas=$linhas;
		$i=0;
		echo "<br>Num. de Rodadas: $qtrodadas<br>";
		echo 'Resultados: (1-vitória das brancas - 2-empate - 3-vitória das pretas)<br>';
		echo '<table border=1>';
		echo "<tr><td>ord</td><td>id</td><td>torneioid</td><td>rodada</td><td>tabuleiro</td><td>numjogb</td><td>numjogp</td><td>resultado</td></tr>";
		while ($i<$qtrodadas)
		{
			$id = trim(pg_result($sql,$i,'id'));
			$torneioid = trim(pg_result($sql,$i,'torneioid'));
			$rodada = trim(pg_result($sql,$i,'rodada'));
			$tabuleiro = trim(pg_result($sql,$i,'tabuleiro'));
			$numjogb = trim(pg_result($sql,$i,'numjogb'));
			$numjogp = trim(pg_result($sql,$i,'numjogp'));
			$resultado = trim(pg_result($sql,$i,'resultado'));
			echo "<tr><td>$i</td><td>$id</td><td>$torneioid</td><td>$rodada</td><td>$tabuleiro</td><td>$numjogb</td><td>$numjogp</td><td>$resultado</td></tr>";
			switch ($resultado)
			{
				case 1:
					$lista_jogadores[$numjogb][2]=$lista_jogadores[$numjogb][2]+1; //pts
					$lista_jogadores[$numjogb][3]=$lista_jogadores[$numjogb][3]+$lista_jogadores[$numjogp][0]; //soma de ratings
					$lista_jogadores[$numjogb][4]=$lista_jogadores[$numjogb][4]+1; //pts
					$lista_jogadores[$numjogp][3]=$lista_jogadores[$numjogp][3]+$lista_jogadores[$numjogb][0]; //soma de ratings
					$lista_jogadores[$numjogp][4]=$lista_jogadores[$numjogp][4]+1; //partidas
					break;
				case 2:
					$lista_jogadores[$numjogb][2]=$lista_jogadores[$numjogb][2]+0.5; //pts
					$lista_jogadores[$numjogb][3]=$lista_jogadores[$numjogb][3]+$lista_jogadores[$numjogp][0]; //soma de ratings
					$lista_jogadores[$numjogb][4]=$lista_jogadores[$numjogb][4]+1; //partidas
					$lista_jogadores[$numjogp][2]=$lista_jogadores[$numjogp][2]+0.5; //pts
					$lista_jogadores[$numjogp][3]=$lista_jogadores[$numjogp][3]+$lista_jogadores[$numjogb][0]; //soma de ratings
					$lista_jogadores[$numjogp][4]=$lista_jogadores[$numjogp][4]+1; //partidas
					break;
				case 3:
					$lista_jogadores[$numjogp][2]=$lista_jogadores[$numjogp][2]+1; //pts
					$lista_jogadores[$numjogp][3]=$lista_jogadores[$numjogp][3]+$lista_jogadores[$numjogb][0]; //soma de ratings
					$lista_jogadores[$numjogp][4]=$lista_jogadores[$numjogp][4]+1; //partidas
					$lista_jogadores[$numjogb][3]=$lista_jogadores[$numjogb][3]+$lista_jogadores[$numjogp][0]; //soma de ratings
					$lista_jogadores[$numjogb][4]=$lista_jogadores[$numjogb][4]+1; //partidas
					break;
				default:
					break;
			}
			$i++;
		}
		echo "</table>";
		
		echo '<br>Planilha: (Rating Inicial=Média dos Oponentes / pelo menos 1/2ponto) - ';
		echo 'máx. diferença de rating: 400<br>';
		$i=1;
		while ($i<=$qtjogadores)
		{
			$lista_jogadores[$i][5]=round($lista_jogadores[$i][3]/$lista_jogadores[$i][4],0);
			
			
			if($lista_jogadores[$i][0]<1)
				{
					if($lista_jogadores[$i][2]==0)
						{$lista_jogadores[$i][6]=$lista_jogadores[$i][0]-$lista_jogadores[$i][5];}	//rating inicial=0
					else
						{$lista_jogadores[$i][6]=$lista_jogadores[$i][5]-$lista_jogadores[$i][5];}	//rating inicial=0 ******
				}
			else
				{$lista_jogadores[$i][6]=$lista_jogadores[$i][0]-$lista_jogadores[$i][5];}
			for($f=0;$f<$qtfaixas;$f++)
			{
				if(abs($lista_jogadores[$i][6])<=$lista_faixas[$f][1]) {$faixa=$f;$f=$qtfaixas;}
			} 
			
			if(abs($lista_jogadores[$i][6])>$dif_max_rating)
			{
				if($lista_jogadores[$i][6]>$dif_max_rating) {$lista_jogadores[$i][6]=$dif_max_rating;}
				else
				if($lista_jogadores[$i][6]<-$dif_max_rating) {$lista_jogadores[$i][6]=-$dif_max_rating;}
			}
			
			if($lista_jogadores[$i][6]>0)
			{$lista_jogadores[$i][7]=$lista_faixas[$faixa][3];}
			else
			{$lista_jogadores[$i][7]=$lista_faixas[$faixa][2];}
			$lista_jogadores[$i][8]=$lista_jogadores[$i][4]*$lista_jogadores[$i][7]/100;
			$lista_jogadores[$i][9]=$lista_jogadores[$i][2]-$lista_jogadores[$i][8];
			if($lista_jogadores[$i][0]<1)
			{	//rating inicial=0
				if($lista_jogadores[$i][2]==0)
					{
						$lista_jogadores[$i][10]=30*$lista_jogadores[$i][9];
						$lista_jogadores[$i][11]=round($lista_jogadores[$i][0]+$lista_jogadores[$i][10]);
					}
				else
					{
						$lista_jogadores[$i][10]=30*$lista_jogadores[$i][9];
						$lista_jogadores[$i][11]=round($lista_jogadores[$i][5]+$lista_jogadores[$i][10]);
					}
			}
			else
			{
				$lista_jogadores[$i][10]=$lista_jogadores[$i][1]*$lista_jogadores[$i][9];
				$lista_jogadores[$i][11]=round($lista_jogadores[$i][0]+$lista_jogadores[$i][10]);
			}
			
			
			$i++;
		}
		
		$i=1;
		echo '<table border=1>';
		echo '<tr><td>ord</td><td>nome do jogador</td><td>rating</td><td>k</td><td>pts</td><td>';
		echo 'soma_rating</td><td>num.part.</td><td>media</td><td>Dif.rating</td><td>Perc.esp.</td><td>pts.esp.</td><td>var</td><td>var*k</td><td>Novo Rat.</td></tr>';
		while ($i<=$qtjogadores)
		{
			echo '<tr>';
			echo '<td>' . $i . '</td><td>' . $lista_jogadores[$i][12] . '</td><td>' . $lista_jogadores[$i][0] . '</td><td>';
			echo $lista_jogadores[$i][1] . '</td><td>' . $lista_jogadores[$i][2] . '</td><td>';
			echo $lista_jogadores[$i][3] . '</td><td>' . $lista_jogadores[$i][4] . '</td><td>';
			echo $lista_jogadores[$i][5] . '</td><td> (' . $lista_jogadores[$i][6] . ')</td><td>';
			echo $lista_jogadores[$i][7] . '</td><td>' . $lista_jogadores[$i][8] . '</td><td>';
			echo $lista_jogadores[$i][9] . '</td><td>' . $lista_jogadores[$i][10] . '</td><td>';
			echo $lista_jogadores[$i][11] . '</td>';
			echo '</tr>';
			$i++;
		}
		echo '</table>';
		
	?>