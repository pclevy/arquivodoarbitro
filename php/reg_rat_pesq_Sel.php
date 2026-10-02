<?php
/* php/reg_rat_pesq_Sel.php
 * Versão: 2026.10.02
 * Alterado em 2026/10/02
 */

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

$file = "../config/conexao_ca.cfg";

$fh = fopen($file, 'r');
$conteudo = explode("*", fread($fh, filesize($file)));
$strconexao = trim($conteudo[0]);
$codificacao = trim($conteudo[1]);
fclose($fh);

$conexao = pg_connect($strconexao) or die("erro na conexão");

$sql = pg_query($conexao, "
    SELECT
        reg,
        nome,
        sobrenome,
        trim(nome) || ' ' || trim(sobrenome) AS nomecompleto,
        CASE
            WHEN clube IS NULL OR trim(clube) = ''
            THEN trim(municipio)
            ELSE trim(clube)
        END AS clube,
        sexo AS genero,
        dt_nasc,
        right(trim(dt_nasc), 4) AS ano_nasc
    FROM cadastro
    ORDER BY nome;
");

if (!$sql) {
    die("Erro na consulta ao banco de dados.");
}

$resultado = pg_num_rows($sql);
$i = 0;

echo "<script type='text/javascript'>";
echo "var listaJogadores = [];";

while ($i < $resultado) {

    $reg = trim(" " . pg_fetch_result($sql, $i, 'reg'));
    $nome = pg_fetch_result($sql, $i, 'nomecompleto');
    $clube = pg_fetch_result($sql, $i, 'clube');

    echo "listaJogadores.push({";
    echo "reg:" . json_encode($reg, JSON_UNESCAPED_UNICODE) . ",";
    echo "nome:" . json_encode($nome, JSON_UNESCAPED_UNICODE) . ",";
    echo "clube:" . json_encode($clube, JSON_UNESCAPED_UNICODE);
    echo "});";

    $i++;
}

echo "</script>";
?>

<html dir="ltr" lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<meta
    name="keywords"
    content="xadrez, DV, cego, inclusão,xadrezdeolhonofuturo,esfinge"
>

<link
    rel="icon"
    type="image/png"
    href="../imagens/arquivo_do_arbitro.png"
>

<title>Consultar Registro e Rating Fexerj!</title>

<?php include "google_analytics.php"; ?>

<script
    type="text/javascript"
    src="../js/jstrim.js">
</script>


<style>

/* ============================================================
   BOTÕES DE ORDENAÇÃO
   ============================================================ */

.botao-ordenacao {
    padding: 3px 10px;
    margin-left: 4px;

    border: 2px outset #cccccc;
    border-radius: 4px;

    background-color: #eeeeee;
    color: #000000;

    cursor: pointer;
    font-weight: normal;
}


/* Botão quando o mouse passa sobre ele */

.botao-ordenacao:hover {
    background-color: #dddddd;
}


/* Botão quando recebe foco pelo teclado */

.botao-ordenacao:focus {
    outline: 2px solid #2266AA;
    outline-offset: 2px;
}


/* Botão da ordenação atualmente selecionada */

.botao-ordenacao.ativo {
    background-color: #cccccc;
    color: #000000;
    font-weight: bold;

    border: 2px inset #888888;

    box-shadow:
        inset 2px 2px 3px rgba(0, 0, 0, 0.35);

    transform: translateY(1px);






</style>


<script type="text/javascript">

    var listaJogadoresMostrada = [];


    /*
     * Inicializa a lista de jogadores.
     */
    function inicializarLista() {

        listaJogadoresMostrada = listaJogadores.slice();

        atualizarListaJogadores();

        /*
         * A ordenação inicial é por Nome.
         */
        document.getElementById("btnNome")
            .classList.add("ativo");

        document.getElementById("btnReg")
            .classList.remove("ativo");
    }


    /*
     * Atualiza o SELECT.
     */
    function atualizarListaJogadores() {

        var lista_loc =
            document.getElementById("enxadrista_list");

        if (!lista_loc) {
            return;
        }


        /*
         * Remove todas as opções.
         */
        lista_loc.innerHTML = "";


        /*
         * Primeira opção.
         */
        var primeiraOpcao =
            document.createElement("option");

        primeiraOpcao.value = "";
        primeiraOpcao.text =
            "Digite parte do nome ou Selecione abaixo ...";
        primeiraOpcao.style.fontWeight = "bold";

        lista_loc.appendChild(primeiraOpcao);


        /*
         * Insere os jogadores.
         */
        listaJogadoresMostrada.forEach(
            function(jogador, index) {

                var option =
                    document.createElement("option");

                option.value = index;

                option.text =
                    String(jogador.reg).padStart(4, "0")
                    + "-"
                    + jogador.nome.substring(0, 49)
                    + " ("
                    + jogador.clube
                    + ")";

                lista_loc.appendChild(option);
            }
        );
    }


    /*
     * Ordenar por Nome.
     */
    function ordenarPorNome() {

        listaJogadoresMostrada.sort(
            function(a, b) {

                return a.nome.localeCompare(
                    b.nome,
                    "pt-BR",
                    {
                        sensitivity: "base"
                    }
                );
            }
        );

        atualizarListaJogadores();


        /*
         * Nome fica ativo.
         */
        document.getElementById("btnNome")
            .classList.add("ativo");

        document.getElementById("btnReg")
            .classList.remove("ativo");
    }


    /*
     * Ordenar por Registro.
     */
    function ordenarPorReg() {

        listaJogadoresMostrada.sort(
            function(a, b) {

                return Number(a.reg) - Number(b.reg);
            }
        );

        atualizarListaJogadores();


        /*
         * Registro fica ativo.
         */
        document.getElementById("btnReg")
            .classList.add("ativo");

        document.getElementById("btnNome")
            .classList.remove("ativo");
    }


    /*
     * Pesquisa por nome.
     */
    function pesq_nome(strDigitada) {

        var strPesq =
            trim(strDigitada).toUpperCase();

        document.getElementById(
            "enxadrista_reg"
        ).value = "";


        if (strPesq === "") {

            listaJogadoresMostrada =
                listaJogadores.slice();

        } else {

            listaJogadoresMostrada =
                listaJogadores.filter(
                    function(jogador) {

                        return jogador.nome
                            .toUpperCase()
                            .indexOf(strPesq) >= 0;
                    }
                );
        }


        atualizarListaJogadores();
    }


    /*
     * Seleção de jogador.
     */
    function Select_Click(elemento, typeClick) {

        var indice =
            elemento.options[
                elemento.selectedIndex
            ].value;


        if (indice !== "") {

            var jogador =
                listaJogadoresMostrada[
                    Number(indice)
                ];


            document.getElementById(
                "enxadrista_reg"
            ).value = jogador.reg;


            document.getElementById(
                "enxadrista"
            ).value = jogador.nome;


            var isTouch =
                navigator.maxTouchPoints > 0;


            if (
                typeClick === "dbl"
                || isTouch
            ) {

                document.getElementById(
                    "SubmitButton"
                ).click();
            }
        }
    }


    /*
     * Aguarda o HTML estar disponível.
     */
    document.addEventListener(
        "DOMContentLoaded",
        function() {

            inicializarLista();

        }
    );

</script>

</head>

<body bgcolor="eeeeff">

<center>

<span style="font-size: 1.3em; line-height: 0.7;">
    <b>Projeto &nbsp; Esfinge</b><br>
</span>

<span style="font-size: 1.5em; line-height: 1.0;">
    <b>Xadrez de Olho no Futuro</b><br>
</span>

<span style="font-size: 0.6em; line-height: 0.4;">
    (Antigo<b> Xadrez UERJ</b>)
</span>
<br>

<font size="4">
    Arquivo do Árbitro
</font>
<br>

<font size="2">
    <b>Pesquisa de Enxadristas - por nome</b>
</font>
<br />
<font size="2" color="red">
    (Lista de Rating de julho/2026! V. 1.7)
</font>
<br>

<div
    style="
        max-width:400px;
        background-color:#DCE9C8;
        line-height:30px;
        border:3px solid #2266AA;
    "
>

    <form
        name="reg_rat_pesq"
        action="reg_rat_pesq.php"
        method="post"
        autocomplete="off"
    >

        <table width="100%">

            <tr>

                <td valign="top" colspan="4">

                    Clube:

                    <input
                        name="clube"
                        id="clube"
                        type="text"
                        value=""
                        size="10"
                        maxlength="10"
                    >

                    &nbsp; &nbsp;
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;

                    Status:

                    <select
                        name="status"
                        id="status"
                    >

                        <option value="N">
                            Filiados
                        </option>

                        <option
                            value="S"
                            selected
                        >
                            Todos
                        </option>

                    </select>

                </td>

            </tr>


            <tr>

                <td valign="top" colspan="2">

                    Ratings&nbsp;de:

                    <input
                        name="rat_min"
                        id="rat_min"
                        type="text"
                        value="0"
                        size="2"
                        maxlength="4"
                    >

                     a 

                    <input
                        name="rat_max"
                        id="rat_max"
                        type="text"
                        value="3000"
                        size="2"
                        maxlength="4"
                    >

                    &nbsp;

                    Ritmo1:

                    <select
                        name="ritmo"
                        id="ritmo"
                    >

                        <option
                            value="S"
                            selected
                        >
                            Clássico
                        </option>

                        <option value="Q">
                            Rápido
                        </option>

                        <option value="B">
                            Relâmpago
                        </option>

                    </select>

                </td>

            </tr>

        </table>
		<table width="100%">

            <tr>

                <!--td valign="top" max-width:200px> -->
				<th  valign="top" align="left" style="width:10px">
                    Nome:
                </th>

                <td colspan="5">

                    <input
                        name="enxadrista"
                        id="enxadrista"
                        type="text"
                        value=""
                        size="44%"
                        maxlength="60%"
                        onkeyup="
                            pesq_nome(this.value);
                        "
                    >

                    <input
                        name="enxadrista_reg"
                        id="enxadrista_reg"
                        type="hidden"
                        value=""
                    >

                </td>

            </tr>

        </table>
		<table width="100%">

            <tr>
                <td colspan="5">

                    <select
                        name="enxadrista_list"
                        id="enxadrista_list"
                        size="15"
                        style="max-width:96%"
                        onchange="
                            Select_Click(this,'clk');
                        "
                        ondblclick="
                            Select_Click(this,'dbl');
                        "
                    >

                        <option
                            style="font-weight:bold"
                            value=""
                        >
                            Carregando lista...
                        </option>

                    </select>

                </td>

            </tr>


            <tr>

                <td>&nbsp;</td>

                <td>

                    <input
                        id="SubmitButton"
                        type="submit"
                        onclick="
                            if(
                                enxadrista_reg.value < 1
                                &&
                                clube.value == ''
                                &&
                                rat_min.value == ''
                                &&
                                rat_max.value == ''
                            ){
                                alert(
                                    'Clique em um nome da Lista e/ou escolha um outro critério!!'
                                );

                                enxadrista_list.focus();

                                return false;
                            }
                        "
                        name="Enviar"
                        value="Enviar"
                    >

                </td>

            </tr>


            <!-- Ordenação -->

            <tr>

                <td colspan="4">

                    Ordenar por:

                    <button
                        type="button"
                        id="btnNome"
                        class="botao-ordenacao ativo"
                        onclick="ordenarPorNome();"
                    >
                        Nome
                    </button>

                    <button
                        type="button"
                        id="btnReg"
                        class="botao-ordenacao"
                        onclick="ordenarPorReg();"
                    >
                        Registro
                    </button>

                </td>

            </tr>

        </table>

    </form>

</div>

</center>

</body>

</html>
