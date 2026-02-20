<?php
/* Alterado em 2026/02/12, 18:50 */

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
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<title>Pesquisa de Enxadristas</title>

<style>
body { font-family: Arial; font-size: 14px; }
#resumot1 {
    position:absolute; top:80px; left:483px;
    width:510px; height:449px;
    overflow:auto; padding:1px;
    background:#F9FFF9; border:1px solid #2266AA;
}
.enxrow {
    width:480px; background:#EEFFCC;
    padding:6px; margin-bottom:6px;
    cursor:pointer;
}
.enxrow:hover { background:#BBFFBB; }
</style>

<script>
// recebe HTML e insere no quadro
function setResumoHtml(html) {
    document.getElementById("resumot1").innerHTML = html;
}

// monta o gráfico
function muda_res(param, ritmoTitulo) {
    const html =
      "<b>Gráfico do Rating Z2</b>: " + ritmoTitulo +
      "<br><img src='GerarGrafico.php?" + param +
      "' style='border:1px solid #999;margin-top:6px;' />";
    document.getElementById("resumot1").innerHTML += "<br>" + html;
}
</script>

</head>
<body>
<?php
/* ---------------------------------------------------------------------- */
/* Cabeçalho                                                              */
/* ---------------------------------------------------------------------- */

echo "<div><b>Xadrez UERJ</b><br>";
echo "<font size='5'>Arquivo do Árbitro</font><br>";
echo "Encontrado(s) <b>$total</b> enxadrista(s)</div>";

echo "<div id='resumot1'><br><b><font color='blue'>Clique em um enxadrista</font></b></div>";

echo "<div style='position:absolute; width:480px; top:80px; height:449px; overflow:auto; border:1px solid #2266AA;'>";

/* ---------------------------------------------------------------------- */
/* Montagem dos blocos de enxadristas                                    */
/* ---------------------------------------------------------------------- */

for ($i = 0; $i < $total; $i++) {

    $row = pg_fetch_assoc($res);
    if (!$row) continue;

    $reg   = $row['reg'];
    $nome  = trim($row['nome']) . " " . trim($row['sobrenome']);
    $clb   = trim($row['clube']);
    $tit   = trim($row['titulo']);
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

        $mesref[] = substr($data_base,0,4)."/".substr($data_base,4,2);
        $ret = pesq_rating($conexao, $data_base, $reg);
        $parts = explode("/", $ret);
        $vals[] = $parts[1] ?? "0";
    }

    /* Se rating vazio, usa ultimo */
    if ($rat < 1) {
        $rat = intval(end($vals));
    }

    /* monta param */
    $param = "v1=654&v2=227";
    for ($z = count($vals)-1; $z >= 0; $z--) {
        $param .= "&r1=" . substr("0000".$vals[$z], -4);
        //$param .= "&m1=" . urlencode($mesref[$z]);
        $param .= "&m1=" . $mesref[$z];		// ***** 2026/02/06, 16:13 *****
    }
    $param .= "&r1=9999";

    /* resumo */
    $Resumo = "";
    if ($foto)
        $Resumo .= "<img src='$foto' width='148' height='159' align='left' style='border:1px solid #999;margin-right:6px;'>";

    $Resumo .= "<b><font size='+1'>$nome</font></b><br><b>Histórico:</b><br>";
    foreach ($mesref as $k => $m)
        $Resumo .= "$m: <b>{$vals[$k]}</b>; ";

    $Resumo = addslashes($Resumo);
    $paramJS = addslashes($param);

    echo "<div class='enxrow'
            onclick=\"setResumoHtml('$Resumo'); muda_res('$paramJS','Clássico');\">
            <table width='100%'>
            <tr><td>Reg: $reg</td><td>Clube: $clb</td><td>Título: $tit</td></tr>
            <tr><td colspan='2'>Nome: $nome</td><td>Rat: $rat</td></tr>
            </table>
          </div>";
}

echo "</div>";

pg_free_result($res);
pg_close($conexao);
?>
</body>
</html>

