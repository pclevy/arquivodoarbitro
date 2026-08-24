<?php
/*
 * php/reg_rat_pesq.php - Alterado em 2026/08/19
 *
 * Pesquisa de Enxadristas + parâmetros para GerarGrafico.php
 *
 * Correções:
 * - AdSense manual responsivo
 * - Google Analytics mantido
 * - Consulta de tabelas_rating executada uma única vez
 * - Proteção de dados enviados para JavaScript
 * - Escape de dados exibidos em HTML
 * - Correções de CSS/HTML
 * - Mantida a lógica original de pesquisa e gráficos
 */

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);


/* ---------------------------------------------------------------------- */
/* Funções auxiliares                                                       */
/* ---------------------------------------------------------------------- */

function in($name, $default = '')
{
    return isset($_POST[$name])
        ? trim($_POST[$name])
        : $default;
}


/**
 * Escape para literal PostgreSQL.
 */
function esc($con, $val)
{
    return pg_escape_literal($con, $val);
}


/**
 * Escape para saída HTML.
 */
function h($value)
{
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}


/**
 * Retira acentos.
 */
function retirarAcentos($s)
{
    $map = [
        'Á'=>'A','À'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A',
        'á'=>'a','à'=>'a','â'=>'a','ã'=>'a','ä'=>'a',

        'É'=>'E','È'=>'E','Ê'=>'E','Ë'=>'E',
        'é'=>'e','è'=>'e','ê'=>'e','ë'=>'e',

        'Í'=>'I','Ì'=>'I','Î'=>'I','Ï'=>'I',
        'í'=>'i','ì'=>'i','î'=>'i','ï'=>'i',

        'Ó'=>'O','Ò'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O',
        'ó'=>'o','ò'=>'o','ô'=>'o','õ'=>'o','ö'=>'o',

        'Ú'=>'U','Ù'=>'U','Û'=>'U','Ü'=>'U',
        'ú'=>'u','ù'=>'u','û'=>'u','ü'=>'u',

        'Ç'=>'C','ç'=>'c'
    ];

    return strtr($s, $map);
}


/**
 * Verifica se existe fotografia do enxadrista.
 */
function PesqFoto($foto_reg)
{
    $file = __DIR__ . '/../fotos/reg' . $foto_reg . '.jpg';

    return file_exists($file);
}


/**
 * Pesquisa rating histórico.
 *
 * Retorna: * clube/rating/rpd/blz
 */
function pesq_rating($conexaoL, $data_baseL, $reg)
{
    /*
     * data_baseL vem da tabela tabelas_rating e possui formato YYYYMMDD.
     *
     * O nome da tabela é montado internamente e não vem diretamente
     * do usuário.
     */

    //$tabela = 'r' . preg_replace('/[^0-9]/', '', $data_baseL);
	//                'r' . pg_escape_string($conexaoL, $data_baseL)
    //    FROM { $tabela }

    $sqlhist = "
        SELECT clube, rating, rpd, blz
        FROM r" . pg_escape_string($conexaoL, $data_baseL) . "
        WHERE reg = " . pg_escape_literal($conexaoL, $reg);

    $rs = @pg_query($conexaoL, $sqlhist);

    if (!$rs || pg_num_rows($rs) < 1) {
        return " /0/0/0";
    }

    $ret = " /0/0/0";

    while ($row = pg_fetch_assoc($rs)) {

        $ret =
            ($row['clube'] ?? '') . "/" .
            ($row['rating'] ?? '0') . "/" .
            ($row['rpd'] ?? '0') . "/" .
            ($row['blz'] ?? '0');
    }

    pg_free_result($rs);

    return $ret;
}


/* ---------------------------------------------------------------------- */
/* Captura Inputs                                                        		*/
/* ---------------------------------------------------------------------- */

$clube               = strtoupper(in('clube'));
$titulo              = strtoupper(in('titulo'));
$incluir_desfiliados = in('status', 'S');

$rat_min = in('rat_min');
$rat_max = in('rat_max');

$ritmo = in('ritmo', 'S');

$enxadrista_nome_Sel = in('enxadrista');
$enxadrista_reg      = in('enxadrista_reg');

$chave1 = in('chave1');
$chave2 = in('chave2');
$chave3 = in('chave3');


/* ---------------------------------------------------------------------- */
/* Carrega string de conexão                                          */
/* ---------------------------------------------------------------------- */

$cfg_file = __DIR__ . "/../config/conexao_ca.cfg";

if (!file_exists($cfg_file)) {
    die("Arquivo de configuração não encontrado.");
}

$conteudo = explode(
    "*",
    file_get_contents($cfg_file)
);

$strconexao = trim($conteudo[0] ?? '');
$codificacao = trim($conteudo[1] ?? '');


/* ---------------------------------------------------------------------- */
/* Conexão PostgreSQL                                                     */
/* ---------------------------------------------------------------------- */

$conexao = @pg_connect($strconexao);

if (!$conexao) {
    die("Erro ao conectar ao banco PostgreSQL.");
}


/* ---------------------------------------------------------------------- */
/* Montagem da Query                                                    */
/* ---------------------------------------------------------------------- */

$sqlexp1 = "
    SELECT
        reg,
        sobrenome,
        nome,
        clube,
        titulo,
        rating,
        status
    FROM cadastro
";


if ($incluir_desfiliados === 'N') {

    $sqlexp2 = "WHERE status='A' AND ";

} else {

    $sqlexp2 = "WHERE status<>'X' AND ";
}


$parts = [];


/* Clube */

if ($clube !== '') {

    $parts[] =
        "trim(clube)=" .
        esc($conexao, $clube);
}


/* Título */

if ($titulo !== '') {

    $parts[] =
        "trim(titulo)=" .
        esc($conexao, $titulo);
}


/* Registro */

if ($enxadrista_reg !== '') {

    $parts[] =
        "reg=" .
        esc($conexao, $enxadrista_reg);
}


/* Rating mínimo */

$rat_min =
    ($rat_min === '')
        ? 0
        : intval($rat_min);


/* Rating máximo */

$rat_max =
    ($rat_max === '')
        ? 3000
        : intval($rat_max);


$parts[] =
    "CAST(rating AS integer) >= " .
    $rat_min;

$parts[] =
    "CAST(rating AS integer) <= " .
    $rat_max;


/* Nome do enxadrista */

if ($enxadrista_nome_Sel !== '') {

    $nameEsc =
        pg_escape_string(
            $conexao,
            $enxadrista_nome_Sel
        );

    $parts[] =
        "trim(nome)||' '||trim(sobrenome) ILIKE " .
        esc(
            $conexao,
            "%$nameEsc%"
        );
}


/* ---------------------------------------------------------------------- */
/* Chaves                                                                  */
/* ---------------------------------------------------------------------- */

$chaves = array_filter(
    [
        trim($chave1),
        trim($chave2),
        trim($chave3)
    ],
    function ($value) {
        return $value !== '';
    }
);


if (!empty($chaves)) {

    $sub = [];

    foreach ($chaves as $ch) {

        $chEsc =
            pg_escape_string(
                $conexao,
                $ch
            );

        $sub[] =
            "sem_acento(chave1) ILIKE " .
            esc($conexao, "%$chEsc%");

        $sub[] =
            "sem_acento(chave2) ILIKE " .
            esc($conexao, "%$chEsc%");

        $sub[] =
            "sem_acento(chave3) ILIKE " .
            esc($conexao, "%$chEsc%");
    }

    $parts[] =
        "(" .
        implode(
            " OR ",
            array_unique($sub)
        ) .
        ")";
}


/* ---------------------------------------------------------------------- */
/* WHERE final                                                            */
/* ---------------------------------------------------------------------- */

$where =
    $sqlexp2 .
    implode(
        " AND ",
        $parts
    );


$sqlexp =
    $sqlexp1 .
    $where .
    " ORDER BY trim(nome), trim(sobrenome)";


/* ---------------------------------------------------------------------- */
/* Executa Query                                                          */
/* ---------------------------------------------------------------------- */

$res = pg_query(
    $conexao,
    $sqlexp
);


if (!$res) {

    die(
        "Erro na consulta SQL:<br>" .
        h(pg_last_error($conexao))
    );
}


$total = pg_num_rows($res);


/* ---------------------------------------------------------------------- */
/* CARREGA TABELAS DE RATING UMA ÚNICA VEZ                                */
/* ---------------------------------------------------------------------- */

$sqltabs = pg_query(
    $conexao,
    "
    SELECT nome_tab
    FROM tabelas_rating
    ORDER BY nome_tab
    "
);


if (!$sqltabs) {

    die(
        "Erro ao consultar tabelas de rating:<br>" .
        h(pg_last_error($conexao))
    );
}


$tabelasRating = [];


while ($t = pg_fetch_assoc($sqltabs)) {

    $tab = $t['nome_tab'] ?? '';

    /*
     * Esperado:
     * rYYYYMMDD
     */

    $data_base =
        substr($tab, 1, 8);

    /*
     * Só aceita data formada por 8 dígitos.
     */

    if (
        preg_match(
            '/^\d{8}$/',
            $data_base
        )
    ) {

        $tabelasRating[] = [
            'nome_tab'  => $tab,
            'data_base' => $data_base,
            'mesref'    =>
                substr($data_base, 0, 4) .
                "/" .
                substr($data_base, 4, 2)
        ];
    }
}


pg_free_result($sqltabs);


/* ---------------------------------------------------------------------- */
/* HTML INÍCIO                                                            */
/* ---------------------------------------------------------------------- */
?>

<!DOCTYPE html>

<html
    dir="ltr"
    lang="pt-BR"
>

<head>

    <meta charset="UTF-8" />

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    />

    <meta
        name="keywords"
        content="xadrez, DV, cego, inclusão, xadrezdeolhonofuturo, esfinge"
    />

    <link
        rel="icon"
        type="image/png"
        href="../imagens/arquivo_do_arbitro.png"
    />


    <!-- ============================================================= -->
    <!-- Google AdSense                                                -->
    <!-- ============================================================= -->

    <script
        async
        src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7174891341008290"
        crossorigin="anonymous"
    ></script>


    <!-- ============================================================= -->
    <!-- Google Analytics                                              -->
    <!-- ============================================================= -->

    <script
        async
        src="https://www.googletagmanager.com/gtag/js?id=G-SWZJG4W36F"
    ></script>

    <script>

        window.dataLayer =
            window.dataLayer || [];

        function gtag() {

            dataLayer.push(arguments);
        }

        gtag(
            "js",
            new Date()
        );

        gtag(
            "config",
            "G-SWZJG4W36F"
        );

    </script>


    <title>
        Pesquisa de Enxadristas
    </title>


    <!-- ============================================================= -->
    <!-- CSS                                                            -->
    <!-- ============================================================= -->

    <style>

        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 8px;
        }


        #resumot1 {

            position: absolute;

            margin-right: 2px;

            max-width: 96%;
            height: auto;

            overflow: auto;

            padding: 1px;

            background: #F9FFF9;

            border: 1px solid #2266AA;
        }


        #atletas {

            height: auto;

            z-index: 1;

            border: 1px solid #2266AA;
        }


        .enxrow {

            background: #EEFFCC;

            padding: 2px;

            cursor: pointer;
        }


        .enxrow:hover {

            background: #BBFFBB;
        }


        .container {

            display: flex;

            flex-wrap: wrap;

            gap: 20px;

            width: 100%;
        }


        .coluna {

            flex: 1;

            min-width: 300px;

            background-color: #f0f0f0;

            padding: 20px;

            box-sizing: border-box;
        }


        /*
         * Área do anúncio.
         *
         * Mantida separada da lista de enxadristas.
         */

        .ads-container {

            width: 100%;

            max-width: 970px;

            margin: 12px auto;

            text-align: center;

            overflow: hidden;

            min-height: 0;
        }


        @media (max-width: 600px) {

            .coluna {

                flex: 100%;
            }


            .ads-container {

                max-width: 100%;
            }
        }

    </style>


    <!-- ============================================================= -->
    <!-- JavaScript                                                     -->
    <!-- ============================================================= -->

    <script>

        /*
         * Recebe HTML e insere no quadro de resumo.
         */

        function setResumoHtml(html) {

            const elemento =
                document.getElementById(
                    "resumot1"
                );

            if (elemento) {

                elemento.innerHTML = html;
            }
        }


        /*
         * Monta o gráfico.
         */

        function muda_res(
            param,
            ritmoTitulo
        ) {

            const grafico =
                document.getElementById(
                    "grafico"
                );

            if (!grafico) {

                return;
            }


            const html =
                "<b>Gráfico do Rating</b>: " +
                ritmoTitulo +
                "<br>" +
                "<img src='GerarGrafico.php?" +
                param +
                "' " +
                "style='border:1px solid #999;" +
                "margin-top:3px;" +
                "max-width:100%;' />";


            grafico.innerHTML += html;
        }

    </script>

</head>


<body>

<?php

/* ---------------------------------------------------------------------- */
/* Cabeçalho                                                              */
/* ---------------------------------------------------------------------- */

?>

<div
    style="
        max-width:100%;
        margin-bottom:8px;
    "
>

    <b>Xadrez UERJ</b>

    <br>

    <font size="5">
        Arquivo do Árbitro
    </font>

    <br>

    Encontrado(s)
    <b><?= h($total) ?></b>
    enxadrista(s)

</div>


<?php

/* ---------------------------------------------------------------------- */
/* Google AdSense - bloco manual                                         */
/* ---------------------------------------------------------------------- */

?>

<div class="ads-container">

    <ins
        class="adsbygoogle"
        style="display:block"
        data-ad-client="ca-pub-7174891341008290"
        data-ad-slot="9948140848"
        data-ad-format="auto"
        data-full-width-responsive="true"
    ></ins>

    <script>

        (adsbygoogle =
            window.adsbygoogle ||
            []
        ).push({});

    </script>

</div>


<?php

/* ---------------------------------------------------------------------- */
/* Área de resumo                                                        */
/* ---------------------------------------------------------------------- */

?>

<div
    id="resumot1"
    style="
        float:left;
        width:200px;
        height:auto;
        position:absolute;
        overflow:auto;
    "
>

    <br>

    <b>
        <font color="blue">
            Clique em um enxadrista
        </font>
    </b>

</div>


<?php

/* ---------------------------------------------------------------------- */
/* Lista de enxadristas                                                  */
/* ---------------------------------------------------------------------- */

?>

<div
    id="atletas"
    style="
        position:absolute;
        top:120px;
        width:auto;
        height:auto;
        overflow:auto;
    "
>


<?php

/* ---------------------------------------------------------------------- */
/* Montagem dos blocos de enxadristas                                    */
/* ---------------------------------------------------------------------- */

for (
    $i = 0;
    $i < $total;
    $i++
) {

    $row =
        pg_fetch_assoc($res);


    if (!$row) {

        continue;
    }


    /* -------------------------------------------------------------- */
    /* Dados básicos                                                   */
    /* -------------------------------------------------------------- */

    $reg =
        $row['reg'] ?? '';

    $nome =
        trim($row['nome'] ?? '') .
        " " .
        trim($row['sobrenome'] ?? '');

    $clb =
        trim($row['clube'] ?? '');

    $tit =
        trim(
            ($row['titulo'] ?? '') .
            " "
        );

    $rat =
        intval(
            $row['rating'] ?? 0
        );


    /* -------------------------------------------------------------- */
    /* Fotografia                                                      */
    /* -------------------------------------------------------------- */

    $regFoto =
        substr(
            "000" . $reg,
            -4
        );


    $foto = '';


    if (PesqFoto($regFoto)) {

        $foto =
            "../fotos/reg" .
            $regFoto .
            ".jpg";
    }


    /* -------------------------------------------------------------- */
    /* Histórico                                                        */
    /* -------------------------------------------------------------- */

    $mesref = [];

    $vals = [];

    $qtBarras = 0;


    foreach (
        $tabelasRating
        as $tabInfo
    ) {

        $data_base =
            $tabInfo['data_base'];

        $mes =
            $tabInfo['mesref'];


        $mesref[] =
            $mes;


        $ret =
            pesq_rating(
                $conexao,
                $data_base,
                $reg
            );


        $partsRating =
            explode(
                "/",
                $ret
            );


        $valorRating =
            $partsRating[1]
            ?? "0";


        $vals[] =
            $valorRating;


        if (
            intval($valorRating) > 0
        ) {

            $qtBarras++;
        }
    }


    /* -------------------------------------------------------------- */
    /* Rating atual                                                    */
    /* -------------------------------------------------------------- */

    if ($rat < 1) {

        $ultimo =
            end($vals);

        $rat =
            intval($ultimo);
    }


    /* -------------------------------------------------------------- */
    /* Parâmetros do gráfico                                           */
    /* -------------------------------------------------------------- */

    $qttabelas =
        count($vals);


    /*
     * Mantida a lógica original:
     *
     * v1 = quantidade de ratings válidos - 1
     * v2 = 227
     */

    $v1 =
        ($qtBarras - 1) * 21;


    $param =
        "v1=" .
        $v1 .
        "&v2=227";


    for (
        $z = $qttabelas - 1;
        $z >= 0;
        $z--
    ) {

        if (
            isset($vals[$z]) &&
            intval($vals[$z]) > 0
        ) {

            $param .=
                "&r1=" .
                substr(
                    "0000" .
                    $vals[$z],
                    -4
                );


            $param .=
                "&m1=" .
                $mesref[$z];
        }
    }


    /*
     * Marcador final mantido da implementação original.
     */

    $param .=
        "&r1=9999";


    /* -------------------------------------------------------------- */
    /* Resumo                                                          */
    /* -------------------------------------------------------------- */

    $Resumo = '';


    $Resumo .=
        "<div " .
        "class='historico' " .
        "style='" .
        "overflow:auto;" .
        "border:1px solid #00ffff;" .
        "'>";


    if ($foto !== '') {

        $Resumo .=
            "<img " .
            "src='" . h($foto) . "' " .
            "width='158' " .
            "alt='Fotografia de " . h($nome) . "' " .
            "style='" .
            "border:1px solid #999;" .
            "margin-right:6px;" .
            "float:left;" .
            "'>";
    }


    $Resumo .=
        "<b>" .
        "<font size='+1'>" .
        h($nome) .
        "</font>" .
        "</b>" .
        "<br>" .
        "<b>Histórico:</b> " .
        "(mais recente primeiro)" .
        "<font " .
        "size='-1' " .
        "face='Arial Narrow'>" ;


    /*
     * Histórico mais recente primeiro.
     */

    foreach (
        array_reverse(
            $mesref,
            true
        )
        as $k => $m
    ) {

        if (
            isset($vals[$k]) &&
            intval($vals[$k]) > 0
        ) {

            $Resumo .=
                h($m) .
                ":<b>" .
                h($vals[$k]) .
                "</b>; ";
        }
    }


    $Resumo .=
        "</font>";


    $Resumo .=
        "</div>";


    /*
     * Área do gráfico.
     *
     * ID único dentro do resumo atualmente exibido,
     * pois setResumoHtml() substitui o conteúdo anterior.
     */

    $Resumo .=
        "<div " .
        "id='grafico' " .
        "style='" .
        "overflow:auto;" .
        "border:1px solid #ff0000;" .
        "'>" .
        "</div>";


    /* -------------------------------------------------------------- */
    /* Prepara dados para JavaScript                                   */
    /* -------------------------------------------------------------- */

    /*
     * json_encode() é utilizado em vez de addslashes()
     * para produzir strings JavaScript válidas.
     */

    $ResumoJS =
        json_encode(
            $Resumo,
            JSON_HEX_TAG |
            JSON_HEX_APOS |
            JSON_HEX_AMP |
            JSON_HEX_QUOT |
            JSON_UNESCAPED_UNICODE
        );


    $paramJS =
        json_encode(
            $param,
            JSON_HEX_TAG |
            JSON_HEX_APOS |
            JSON_HEX_AMP |
            JSON_HEX_QUOT |
            JSON_UNESCAPED_UNICODE
        );


    $ritmoJS =
        json_encode(
            "Clássico",
            JSON_HEX_TAG |
            JSON_HEX_APOS |
            JSON_HEX_AMP |
            JSON_HEX_QUOT |
            JSON_UNESCAPED_UNICODE
        );


    /* -------------------------------------------------------------- */
    /* Linha do enxadrista                                             */
    /* -------------------------------------------------------------- */

    ?>

    <div
        class="enxrow"
        onclick="
            setResumoHtml(<?= $ResumoJS ?>);
            muda_res(
                <?= $paramJS ?>,
                <?= $ritmoJS ?>
            );
        "
    >

        <table width="100%">

            <tr>

                <td>
                    Reg:
                    <?= h($reg) ?>
                </td>

                <td>
                    Clube:
                    <?= h($clb) ?>
                </td>

                <td>
                    Título:
                    <?= h($tit) ?>
                </td>

                <td>
                    Rat:
                    <?= h($rat) ?>
                </td>

            </tr>


            <tr>

                <td colspan="4">

                    Nome:
                    <?= h($nome) ?>

                </td>

            </tr>

        </table>

    </div>


    <?php

}


/* ---------------------------------------------------------------------- */
/* Finalização                                                            */
/* ---------------------------------------------------------------------- */

?>

</div>


<?php

pg_free_result($res);

pg_close($conexao);

?>

</body>

</html>