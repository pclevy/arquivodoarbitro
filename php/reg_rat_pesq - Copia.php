<?php
/* php/reg_rat_pesq.php /* Alterado em 2026/02/21, 23:49 */

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

/*
  Pesquisa de Enxadristas + parâmetros para GerarGrafico.php
  Versão revisada e corrigida: 2025-11-19
*/

/* ---------------------------------------------------------------------- */
/* Funções auxiliares                                                     */
/* ---------------------------------------------------------------------- */

function in($name, $default = '') {
    return isset($_POST[$name]) ? trim($_POST[$name]) : $default;
}

function esc($con, $val) {
    return pg_escape_literal($con, $val);
}

function retirarAcentos($s) {
    $map = [
        'Á'=>'A','À'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A',
        'á'=>'a','à'=>'a','â'=>'a','ã'=>'a','ä'=>'a',
        'É'=>'E','È'=>'E','Ê'=>'E','é'=>'e','è'=>'e','ê'=>'e',
        'Í'=>'I','Ì'=>'I','Î'=>'I','í'=>'i','ì'=>'i','î'=>'i',
        'Ó'=>'O','Ò'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O',
        'ó'=>'o','ò'=>'o','ô'=>'o','õ'=>'o','ö'=>'o',
        'Ú'=>'U','Ù'=>'U','Û'=>'U','ú'=>'u','ù'=>'u','û'=>'u',
        'Ç'=>'C','ç'=>'c'
    ];
    return strtr($s, $map);
}

function PesqFoto($foto_reg) {
    $file = __DIR__ . '/../fotos/reg' . $foto_reg . '.jpg';
    return file_exists($file);
}

/**
 * pesq_rating:
 * retorna "clube/rating/rpd/blz"
 */
function pesq_rating($conexaoL, $data_baseL, $reg) {
    $sqlhist = "SELECT clube, rating, rpd, blz
                FROM r" . pg_escape_string($conexaoL, $data_baseL) . "
                WHERE reg=" . pg_escape_literal($conexaoL, $reg);

    $rs = @pg_query($conexaoL, $sqlhist);
    if (!$rs || pg_num_rows($rs) < 1) {
        return " /0/0/0";
    }

    $ret = " /0/0/0";
    while ($row = pg_fetch_assoc($rs)) {
        $ret = ($row['clube'] ?? '') . "/" .
               ($row['rating'] ?? '0') . "/" .
               ($row['rpd'] ?? '0') . "/" .
               ($row['blz'] ?? '0');
    }
    return $ret;
}

/* ---------------------------------------------------------------------- */
/* Captura Inputs                                                         */
/* ---------------------------------------------------------------------- */

$clube               = strtoupper(in('clube'));
$titulo              = strtoupper(in('titulo'));
$incluir_desfiliados = in('status', 'S');
$rat_min             = in('rat_min');
$rat_max             = in('rat_max');
$ritmo               = in('ritmo', 'S');
$enxadrista_nome_Sel = in('enxadrista');
$enxadrista_reg      = in('enxadrista_reg');
$chave1              = in('chave1');
$chave2              = in('chave2');
$chave3              = in('chave3');

/* ---------------------------------------------------------------------- */
/* Carrega string de Conexão                                              */
/* ---------------------------------------------------------------------- */

$cfg_file = __DIR__ . "/../config/conexao_ca.cfg";
if (!file_exists($cfg_file)) {
    die("Arquivo de configuração não encontrado: $cfg_file");
}

$conteudo = explode("*", file_get_contents($cfg_file));
$strconexao = trim($conteudo[0]);
$codificacao = trim($conteudo[1]);

/* ---------------------------------------------------------------------- */
/* Conexão PostgreSQL                                                     */
/* ---------------------------------------------------------------------- */

$conexao = @pg_connect($strconexao);
if (!$conexao) {
    die("Erro ao conectar ao banco PostgreSQL.");
}

/* ---------------------------------------------------------------------- */
/* Montagem da Query                                                      */
/* ---------------------------------------------------------------------- */

$sqlexp1 = "SELECT reg, sobrenome, nome, clube, titulo, rating, status FROM cadastro ";

$sqlexp2 = ($incluir_desfiliados === 'N')
        ? "WHERE status='A' AND "
        : "WHERE status<>'X' AND ";

$parts = [];

if ($clube !== '')  $parts[] = "trim(clube)="  . esc($conexao, $clube);
if ($titulo !== '') $parts[] = "trim(titulo)=" . esc($conexao, $titulo);
if ($enxadrista_reg !== '') $parts[] = "reg=" . esc($conexao, $enxadrista_reg);

$rat_min = ($rat_min === '') ? 0 : intval($rat_min);
$rat_max = ($rat_max === '') ? 3000 : intval($rat_max);

$parts[] = "CAST(rating AS integer) >= $rat_min";
$parts[] = "CAST(rating AS integer) <= $rat_max";

if ($enxadrista_nome_Sel !== '') {
    $nameEsc = pg_escape_string($conexao, $enxadrista_nome_Sel);
    $parts[] = "trim(nome)||' '||trim(sobrenome) ILIKE " . esc($conexao, "%$nameEsc%");
}

/* chaves */
$chaves = array_filter([trim($chave1), trim($chave2), trim($chave3)]);
if (!empty($chaves)) {
    $sub = [];
    foreach ($chaves as $ch) {
        $esc = pg_escape_string($conexao, $ch);
        $sub[] = "sem_acento(chave1) ILIKE " . esc($conexao, "%$esc%");
        $sub[] = "sem_acento(chave2) ILIKE " . esc($conexao, "%$esc%");
        $sub[] = "sem_acento(chave3) ILIKE " . esc($conexao, "%$esc%");
    }
    $parts[] = "(" . implode(" OR ", array_unique($sub)) . ")";
}

$where = $sqlexp2 . implode(" AND ", $parts);
$sqlexp = $sqlexp1 . $where . " ORDER BY trim(nome), trim(sobrenome)";

/* ---------------------------------------------------------------------- */
/* Executa Query                                                          */
/* ---------------------------------------------------------------------- */

$res = pg_query($conexao, $sqlexp);
if (!$res) {
    die("Erro na consulta SQL:<br>" . pg_last_error($conexao));
}

$total = pg_num_rows($res);

/* ---------------------------------------------------------------------- */
/* HTML INÍCIO                                                            */
/* ---------------------------------------------------------------------- */
?>
<!DOCTYPE html>

<html dir="ltr" lang="pt-BR">
	<head>
		<!meta charset="windows-1252">
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="keywords" content="xadrez, DV, cego, inclusão,xadrezdeolhonofuturo,esfinge"/>
		<link rel="icon" type="image/png" href="../imagens/arquivo_do_arbitro.png" />

        <!-- Google AdSense - Início-->
            <script
            async
            src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7174891341008290"
            crossorigin="anonymous"
            ></script>
        <!-- Google AdSense - Fim -->

        <!-- Google tag (gtag.js) -- Inicio -->
            <script
            async
            src="https://www.googletagmanager.com/gtag/js?id=G-SWZJG4W36F"
            ></script>

            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag() {
                    dataLayer.push(arguments);
                }
                gtag("js", new Date());
                
                gtag("config", "G-SWZJG4W36F");
            </script>
        <!-- Google tag (gtag.js) -- Fim -->

        <title>Pesquisa de Enxadristas</title>

        <style>
        body { font-family: Arial; font-size: 14px; }
        #resumot1 {
			position: absolute;
			/*margin-top:2px;*/
			
			
			margin-right: 2px;
			max-width:96%; height:auto;
			/*width:94%;*/
            overflow:auto; padding:1px;
            background:#F9FFF9;
			border:1px solid #2266AA;
        }
		
#atletas {
    /*position: absolute;*/
	height:auto;
    top: 80px;
    /*width: 94%;*/
	z-index: -1;
	border:1px solid #2266AA;
	}
			
        .enxrow {
            /*width:380px;*/
			/*height: auto;*/
            background:#EEFFCC;
            padding:2px;
			/*margin-bottom:6px;*/
            cursor:pointer;
        }
        .enxrow:hover { background:#BBFFBB; }

            .container {
                display: flex;
                flex-wrap: wrap; /* Permite quebra de linha em telas pequenas */
                gap: 20px; /* Espaço entre as colunas */
                width: 100%;
            }
            .coluna {
                flex: 1; /* Divide o espaço igualmente */
                min-width: 300px; /* Largura mínima antes de quebrar */
                background-color: #f0f0f0;
                padding: 20px;
                box-sizing: border-box; /* Garante que o padding não aumente a largura */
            }

            /* Responsividade: em telas menores, ocupar 100% */
            @media (max-width: 600px) {
                .coluna {
                    flex: 100%;
                }
            }
        </style>

        <script>
            // recebe HTML e insere no quadro
            function setResumoHtml(html) {
                document.getElementById("resumot1").innerHTML = html;
            }

            // monta o gráfico
            function muda_res(param, ritmoTitulo) {
                const html =
                "<b>Gráfico do Rating</b>: " + ritmoTitulo + 
                "<br><img src='GerarGrafico.php?" + param +
                "' style='border:1px solid #999;margin-top:3px;' />";
                document.getElementById("grafico").innerHTML += html;
            }
        </script>

    </head>
<body>
<?php
/* ---------------------------------------------------------------------- */
/* Cabeçalho                                                              */
/* ---------------------------------------------------------------------- */

echo "<div style='max-width:\'100px\';'><b>Xadrez UERJ</b><br>";
echo "<font size='5'>Arquivo do Árbitro</font><br>";
echo "Encontrado(s) <b>$total</b> enxadrista(s)</div>";

/*echo "<div id='resumot1'><br><b><font color='blue'>Clique em um enxadrista</font></b></div>";*/
echo "<div id='resumot1' style='float:left; width=200px; height=auto; position:absolute; overflow: auto;'><br><b><font color='blue'>Clique em um enxadrista</font></b></div>";

/*echo "<div style='position:absolute; width:480px; top:80px; height:449px; overflow:auto; border:1px solid #2266AA;'>";*/
/*echo "<div  id='atletas' style='float:left; display:flex; top:80px; height:449px; overflow: auto; border:1px solid #00ff00;'>";*/
echo "<div  id='atletas' style='position:absolute; top:120px; width:auto;height:auto; overflow: auto;'>";

/* ---------------------------------------------------------------------- */
/* Montagem dos blocos de enxadristas                                    */
/* ---------------------------------------------------------------------- */

for ($i = 0; $i < $total; $i++) {

    $row = pg_fetch_assoc($res);
    if (!$row) {continue;}

    $reg   = $row['reg'];
    $nome  = trim($row['nome']) . " " . trim($row['sobrenome']);
    $clb   = trim($row['clube']);
    $tit   = trim($row['titulo'] . " ");
    $rat   = intval($row['rating']);

    $regFoto = substr("000$reg", -4);
    $foto = PesqFoto($regFoto)
        ? "../fotos/reg$regFoto.jpg"
        : "";

    /* Busca histórico */
    $sqltabs = pg_query($conexao, "SELECT nome_tab FROM tabelas_rating ORDER BY nome_tab");
    $mesref = [];
    $vals = [];

    while ($t = pg_fetch_assoc($sqltabs)) {
        $tab = $t["nome_tab"];
        $data_base = substr($tab, 1, 8); // YYYYMMDD
		
		//echo $data_base . " ";

        $mesref[] = substr($data_base,0,4)."/".substr($data_base,4,2);
        $ret = pesq_rating($conexao, $data_base, $reg);
        $parts = explode("/", $ret);
		
        //$vals[] = $parts[1] ?? "0";
		if (isset($parts[1])) {
				if($parts[1] > 0) {
					$vals[] = $parts[1];
				} else {
				}
		} else {
			//$vals[] = "0";
		}
		
		//echo $parts[1] . " - ";
		//echo $vals[0] . " - " . $vals[1]. " - " . $vals[2] ;
    }
		//echo count($vals) . ": " . $vals[0] . " - " . $vals[1] . " - " . $vals[2]  . " - " . $vals[3] . " - " . $vals[4] . " - " . $vals[5] . " - " . $vals[6] . " - " . $vals[7] . " - " . $vals[8]  . " - " . $vals[9]. " - " . $vals[10] . " - " . $vals[11] . " - " . $vals[12] ;

	$qttabelas = count($vals);
	//echo "Qt. Tabelas: $qttabelas";
	
    /* Se rating vazio, usa ultimo */
    if ($rat < 1) {
        $rat = intval(end($vals));
    }

    /* monta param */
    /*$param = "v1=654&v2=227"; */
	
	$v1=$qttabelas*21;
    $param = "v1=$v1&v2=227";

    for ($z = $qttabelas-1; $z >= 0; $z--) {
		
		//echo $param;
        if($vals[$z]<1) {continue;}				// ***** 2026/07/31 ***** Sugestão Pedro Nunes *****
		
        $param .= "&r1=" . substr("0000".$vals[$z], -4);
        //$param .= "&m1=" . urlencode($mesref[$z]);
        $param .= "&m1=" . $mesref[$z];		// ***** 2026/02/06, 16:13 *****
    }
    $param .= "&r1=9999";
	
    /* resumo */
    $Resumo = "";
    if ($foto)


    $Resumo .= "<div id='historico' style='overflow: auto; border:1px solid #00ffff;'>";
    $Resumo .= "<img src='$foto' width=158px align='left' style='border:1px solid #999;margin-right:6px;'>";
    $Resumo .= "<b><font size='+1'>$nome</font></b><br><b>Histórico:</b> (mais recente primeiro)<font size='-1' face='Arial Narrow'><br>" ;
	
    /*foreach ($mesref as $k => $m)
        $Resumo .= "$m:<b>{$vals[$k]}</b>; ";
	*/
	foreach (array_reverse($mesref, true) as $k => $m) {
		if($vals[$k]>0) {
			$Resumo .= "$m:<b>{$vals[$k]}</b>; ";
			//echo $vals[$k];
		}		// ****** 2026/07/31 *****
	}		
    
	$Resumo .= "</div>";
    $Resumo .= "<div id='grafico' style='overflow: auto; border:1px solid #ff0000;'></div>";

    $Resumo = addslashes($Resumo);
    $paramJS = addslashes($param);
	
	//echo $Resumo;exit;
	//echo $param;exit;

    echo "<div class='enxrow' 
            onclick=\"setResumoHtml('$Resumo'); muda_res('$paramJS','Clássico');\">
            <table width='100%'>
            <tr><td>Reg: $reg</td><td>Clube: $clb</td><td>Título: $tit</td><td>Rat: $rat</td></tr>
            <tr><td colspan='4'>Nome: $nome</td></tr>
            </table>
          </div>";
}

//echo "yyyyyy";
echo "</div>";

pg_free_result($res);
pg_close($conexao);
?>
</body>
</html>
