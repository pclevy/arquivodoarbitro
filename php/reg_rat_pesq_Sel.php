<?php
/* php/reg_rat_pesq_Sel.php
 * Versão: 2026.11.12 V.1.03
 * Alterado em 2026/11/12, 23:50
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

//Secho "$strconexao";
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
				
		EXTRACT(
			YEAR FROM AGE(
				MAKE_DATE(EXTRACT(YEAR FROM CURRENT_DATE)::integer, 1, 1),
				dt_nasc
			)
		)::integer AS idade,		
				
		rating,
		status
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
echo "var listaClubes = [];";

// ***** 2026/11/04 *****
$listaClubes = [];

while ($jogador = pg_fetch_assoc($sql)) {

    $dados = [
        'reg'    => trim($jogador['reg']),
        'nome'   => $jogador['nomecompleto'],
        'clube'  => $jogador['clube'],
        'genero'  => $jogador['genero'],
        'rating' => trim($jogador['rating']),
        'idade' => trim($jogador['idade']),
        'status' => $jogador['status']
    ];

    echo "listaJogadores.push("
        . json_encode($dados, JSON_UNESCAPED_UNICODE)
        . ");\n";

    // Armazena os clubes
    $listaClubes[] = trim($jogador['clube']);

}

// Elimina clubes repetidos
$listaClubes = array_unique($listaClubes);

// Gera a lista JavaScript
foreach ($listaClubes as $clube) {

    echo "listaClubes.push("
        . json_encode(['clube' => $clube], JSON_UNESCAPED_UNICODE)
        . ");\n";
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
   CONFIGURAÇÃO GERAL E ALTURA DA TELA
   ============================================================ */
*
{
    box-sizing: border-box;
}

html,
body {
    width: 100%;
    height: 100%;
    margin: 0;
    padding: 0;
}

body {
    background-color: #eeeeff;
    font-family: Arial, Helvetica, sans-serif;
}

/* Cabeçalho e formulário ocupam a altura visível do navegador. */
body > center {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
    height: 100vh;
    height: 100dvh;
    overflow: hidden;
}

/* Os <br> antigos não devem consumir espaço como itens flex. */
body > center > br {
    display: none;
}

body > center > span,
body > center > font {
    display: block;
    flex: 0 0 auto;
    max-width: 100%;
}

/* ============================================================
   FORMULÁRIO PRINCIPAL
   ============================================================ */

.formulario {
    display: flex;
    flex-direction: column;
    flex: 1 1 0;
    min-height: 0;
    min-width: 0;

    width: 100%;
    max-width: 420px;
    margin: 0 auto;
    padding: 2px;

    background-color: #DCE9C8;
    overflow: hidden;
    line-height: 1.6;
	
    border: 2px solid #2266AA;
	border-radius: 16px;

	margin-bottom: 5px;
}

.formulario form {
    display: flex;
    flex-direction: column;
    flex: 1 1 0;
    min-height: 0;
    min-width: 0;
    width: 100%;
    margin: 0;
}

/* As tabelas dos campos conservam sua altura natural. */
.formulario form > table {
    width: 100%;
    max-width: 100%;
    border-collapse: collapse;
    table-layout: auto;
    flex-shrink: 0;
}

.formulario td {
    padding: 1px;
    vertical-align: bottom;
}

/* ============================================================
   ÁREA DA LISTA: RECEBE TODO O ESPAÇO VERTICAL RESTANTE
   ============================================================ */

.formulario form > table:nth-of-type(3) {
    display: flex;
    flex-direction: column;
    flex: 1 1 0;
    min-height: 0;
    min-width: 0;
    width: 100%;
}

.formulario form > table:nth-of-type(3) > tbody {
    display: flex;
    flex-direction: column;
    flex: 1 1 0;
    min-height: 0;
    min-width: 0;
}

/* Primeira linha contém a lista; a segunda contém Enviar. */
.formulario form > table:nth-of-type(3) > tbody > tr:first-child {
    display: flex;
    flex: 1 1 0;
    min-height: 0;
    min-width: 0;
}

.formulario form > table:nth-of-type(3) > tbody > tr:first-child > td {
    display: flex;
    flex: 1 1 0;
    min-height: 0;
    min-width: 0;
    padding: 2px;
}

#enxadrista_list {
    display: block;
    flex: 1 1 0;
    width: 100%;
    height: 100%;
    min-height: 0;
    max-width: 100%;
    max-height: none;
    box-sizing: border-box;
    overflow-y: auto;
}

/* ============================================================
   CAMPOS DE PESQUISA
   ============================================================ */

.formulario input[type="text"],
.formulario select {
    max-width: 100%;
}

#clube {
    width: 3.5em;
}

#status {
    width: 3.5em;
}

#genero {
    width: 3.2em;
}

#ritmo {
    width: 3.4em;
}

#rat_min,
#rat_max {
    width: 2.6em;
}

#idade_min,
#idade_max {
    width: 2em;
}

#SM_Categorias {
    width: 6em;
}

#enxadrista {
    width: 100%;
    min-width: 0;
}

/* ============================================================
   BOTÕES DE ORDENAÇÃO
   ============================================================ */

.botoes-ordenacao {
    flex: 0 0 auto;
    text-align: center;
    padding: 4px 0;
}

.botao-ordenacao {
    min-height: 32px;
    padding: 3px 10px;
    margin: 2px 0 2px 4px;

    border: 2px outset #cccccc;
    border-radius: 4px;
    background-color: #eeeeee;
    color: #000000;
    cursor: pointer;
    font-weight: normal;
}

.botao-ordenacao:hover {
    background-color: #dddddd;
}

.botao-ordenacao:focus {
    outline: 2px solid #2266AA;
    outline-offset: 2px;
}

.botao-ordenacao.ativo {
    background-color: #cccccc;
    color: #000000;
    font-weight: bold;
    border: 2px inset #888888;
    box-shadow: inset 2px 2px 3px rgba(0, 0, 0, 0.35);
    transform: translateY(1px);
}

/* ============================================================
   CELULARES
   ============================================================ */

@media (max-width: 420px) {
    .formulario {
        padding: 2px;
		max-height: 89%;
        border-width: 1px;
		border-radius: 16px;
    }

    .formulario td {
        padding: 1px;
    }

    .botao-ordenacao {
        padding: 3px 8px;
    }
}

@media (max-height: 600px) {
    .botoes-ordenacao {
        padding: 1px 0;
    }
}

</style>

<script type="text/javascript">

    var listaJogadoresMostrada = [];

    /*
     * Inicializa a lista de jogadores.
     */
    function inicializarLista() {

        listaJogadoresMostrada = listaJogadores.slice();
		
		    carregarClubes();
			
        atualizarListaJogadores();

        /*
         * A ordenação inicial é por Nome.
         */
        document.getElementById("btnNome")
            .classList.add("ativo");

        document.getElementById("btnReg")
            .classList.remove("ativo");

        document.getElementById("btnClube")
            .classList.remove("ativo");

        document.getElementById("btnRating")
            .classList.remove("ativo");
    }

    /*
     * Atualiza o SELECT.
     */
function atualizarListaJogadores() {

    var lista_loc = document.getElementById("enxadrista_list");
    if (!lista_loc) { return; }

    // Limites de rating
    var ratMin = parseInt(document.getElementById("rat_min").value) || 0;
    var ratMax = parseInt(document.getElementById("rat_max").value) || 3000;
	
	// Gênero selecionado
	var genero = document.getElementById("genero").value;	

	// Status selecionado
	var status = document.getElementById("status").value;	

    /*
     * Remove todas as opções.
     */
    lista_loc.innerHTML = "";

    /*
     * Primeira opção.
     */
    var primeiraOpcao = document.createElement("option");

    primeiraOpcao.value = "";
    primeiraOpcao.text = "Digite parte do nome ou Selecione abaixo ...";
    primeiraOpcao.style.fontWeight = "bold";

    lista_loc.appendChild(primeiraOpcao);

    /*
     * Insere os jogadores considerando os limites de rating.
     */

var menorIdade = Infinity;
var maiorIdade = 0;
var total = 0;

listaJogadoresMostrada.forEach(function(jogador, index) {

/*
    console.log(
        "Total de I:",
        listaJogadores.filter(j => j.status === "I").length
    );
*/

    // Verifica o gênero
    if (genero !== "todos" && jogador.genero !== genero) {
        return;
    }

    // Verifica o status
    if (status !== "todos" && jogador.status !== status) {
        return;
    }

    var rating = parseInt(jogador.rating) || 0;

    // Verifica os limites de rating
    if (rating < ratMin || rating > ratMax) {
        return;
    }

    // Calcula a maior idade entre os filtrados
    var idade = parseInt(jogador.idade) || 0;

    if (idade < menorIdade) {
    menorIdade = idade;
}
	if (idade > maiorIdade) {
        maiorIdade = idade;
    }

    // Cria a opção
    var option = document.createElement("option");

    option.value = index;

    option.text =
        String(jogador.reg).padStart(4, "0")
        + "-"
        + jogador.nome.substring(0, 49)
        + " ("
        + jogador.genero
        + ")("
        + jogador.clube
        + ")("
        + jogador.rating
        + ")";

    lista_loc.appendChild(option);

    total++;
});

// Atualiza a menor idade
document.getElementById("idade_min").value =
    menorIdade === Infinity ? 0 : menorIdade;

// Atualiza a maior idade
document.getElementById("idade_max").value = maiorIdade;

// Atualiza o total de jogadores
document.getElementById("total").textContent = total;

}    
/* filtrarClubes */
function filtrarClube(clube) {

    clube = clube.trim().toLocaleUpperCase('pt-BR');

    if (clube === "") {
        listaJogadoresMostrada = [...listaJogadores];
    } else {
        listaJogadoresMostrada = listaJogadores.filter(
            function(jogador) {
                return jogador.clube.trim()
                    .toLocaleUpperCase('pt-BR') === clube;
            }
        );
    }

    atualizarListaJogadores();
}
    
/* filtrarGenero */
function filtrarGenero(genero) {

    //clube = clube.trim().toLocaleUpperCase('pt-BR');

    if (genero === "") {
        listaJogadoresMostrada = [...listaJogadores];
    } else {
        listaJogadoresMostrada = listaJogadores.filter(
            function(jogador) {
                return jogador.genero.trim()
                    .toLocaleUpperCase('pt-BR') === genero;
            }
        );
    }

    atualizarListaJogadores();
}
	
	/* Ordenar por Nome. */
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

        /* Nome fica ativo */
        document.getElementById("btnNome")
            .classList.add("ativo");

        document.getElementById("btnReg")
            .classList.remove("ativo");

        document.getElementById("btnClube")
            .classList.remove("ativo");

        document.getElementById("btnRating")
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
        
		document.getElementById("btnClube")
            .classList.remove("ativo");

        document.getElementById("btnRating")
            .classList.remove("ativo");
			
	}

    /*
     * Ordenar por Clube.
     */
    function ordenarPorClube() {

        listaJogadoresMostrada.sort(
            function(a, b) {

                return a.clube.localeCompare(
                    b.clube,
                    "pt-BR",
                    {
                        sensitivity: "base"
                    }
                );
            }
        );

        atualizarListaJogadores();

        /*
         * Clube fica ativo.
         */
        document.getElementById("btnClube")
            .classList.add("ativo");

        document.getElementById("btnReg")
            .classList.remove("ativo");

        document.getElementById("btnNome")
            .classList.remove("ativo");

        document.getElementById("btnRating")
            .classList.remove("ativo");			
    }

    /*
     * Ordenar por Rating.
     */
    function ordenarPorRating() {

        listaJogadoresMostrada.sort(
            function(a, b) {

                return Number(b.rating) - Number(a.rating);
            }
        );

        atualizarListaJogadores();

        /*
         * Rating fica ativo.
         */
        document.getElementById("btnReg")
            .classList.remove("ativo");

        document.getElementById("btnNome")
            .classList.remove("ativo");
        
		document.getElementById("btnClube")
            .classList.remove("ativo");
			
        document.getElementById("btnRating")
            .classList.add("ativo");
    }

    /*** Pesquisa por nome ***/
    function pesq_nome(strDigitada) {

        var strPesq = trim(strDigitada).toUpperCase();
		
		var genero = document.getElementById("genero").value;
		
		var status = document.getElementById("status").value;
		
        document.getElementById(
            "enxadrista_reg"
        ).value = "";
		
        document.getElementById(
            "enxadrista_status"
        ).value = "";
		
        //if (strPesq === "") {
         //   listaJogadoresMostrada = listaJogadores.slice();
        //} else {
			 listaJogadoresMostrada = listaJogadores.filter(
				function(jogador) {
					
					// Genero
					if (genero !== "todos" && jogador.genero !== genero) {
						return false;
					}
					
					// Status
					if (status !== "todos" && jogador.status !== status) {
						return false;
					}

					// Clube e Nome
					if (document.getElementById("clube").value != "") {
						if (jogador.clube === document.getElementById("clube").value) {
							return jogador.nome.toUpperCase().indexOf(strPesq) >= 0;
						}
					}
					else {
						return jogador.nome.toUpperCase().indexOf(strPesq) >= 0;
					}

				}
            );
        //}
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

            
			
			document.getElementById(
                "enxadrista_status"
            ).value = jogador.status;

            
			
			
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

function carregarClubes() {

    var datalist = document.getElementById("listaClubes");

    // Limpa as opções anteriores
    datalist.innerHTML = "";

    // Obtém clubes únicos e ordenados
    var clubes = [...new Set(
        listaClubes.map(j => j.clube.trim()).filter(clube => clube !== "")
    )].sort((a, b) => a.localeCompare(b, "pt-BR"));

    clubes.forEach(function(clube) {

        var option = document.createElement("option");

        option.value = clube;

        datalist.appendChild(option);
    });
}
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
    <b>Pesquisa de Enxadristas</b>&nbsp;
    <font color="red">(outubro/2026, V. 1.2)</font>
</font>
<br>

<div class="formulario"  style="font: 11px Arial Narrow">
    <form
        name="reg_rat_pesq"
        action="reg_rat_pesq.php"
        method="post"
        autocomplete="off"
    >
		<table>
			<tr width="100%">
				<td valign="bottom">clube:<input
							type="text"
							name="clube"
							id="clube"
							list="listaClubes"
							oninput="this.value = this.value.toLocaleUpperCase('pt-BR')"
							onchange="filtrarClube(this.value)"
							autocomplete="off"
							maxlength="8"
							style="font: 11px 'Arial Narrow', sans-serif;box-sizing: border-box;"
						>
						<datalist id="listaClubes"></datalist>
				</td>
				<td>
					status:<select name="status" id="status" onchange="atualizarListaJogadores()"
					style="font: 11px 'Arial Narrow';box-sizing: border-box;">
						<option value="todos">Todos</option>
						<option value="A">Ativo</option>
						<option value="I">Inativo</option>
						<option value="F">Finado</option>
					</select>												
				</td>
				<td valign="bottom">
					gên:<select name="genero" id="genero" onchange="atualizarListaJogadores()"
					style="box-sizing: border-box;font: 11px Arial Narrow">
						<option value="todos"> </option>
						<option value="M">M</option>
						<option value="F">F</option>
					</select>
                </td>
				<td>
					ritmo:<select name="ritmo" id="ritmo"
							style="box-sizing: border-box;font: 11px Arial Narrow">
                        <option value="S" selected>cláss</option>
                        <option value="Q">ráp</option>
                        <option value="B">blitz</option>
                    </select>
				</td>
            </tr>
			<!--
			</table>
			<table>
			-->
			<tr width="100%">
                <td valign="bottom">
                    rating:<input
						name="rat_min"
						id="rat_min"
						type="text"
						value="0"
						maxlength="4"
						style="box-sizing: border-box;font: 11px Arial Narrow"
						onchange="atualizarListaJogadores()"
					>
                    a<input
						name="rat_max"
						id="rat_max"
						type="text"
						value="3000"
						maxlength="4"
						style="box-sizing: border-box;font: 11px Arial Narrow"
						onchange="atualizarListaJogadores()"
					>
                    &nbsp; 
				</td>
				<td valign="bottom">
                    idade:<input disabled
						name="idade_min"
						id="idade_min"
						type="text"
						value=""
						maxlength="3"
						style="box-sizing: border-box;font: 11px Arial Narrow"
						onchange="atualizarListaJogadores()"
					>
                    a<input disabled
						name="idade_max"
						id="idade_max"
						type="text"
						value=""
						maxlength="3"
						style="box-sizing: border-box;font: 11px Arial Narrow"
						onchange="atualizarListaJogadores()"
					>	
                    &nbsp;
				</td>
				<td valign="bottom">
					SM:<input disabled
						name="SM_Categorias"
						id="SM_Categorias"
						type="text"
						value=ex.'U14,U18,S60"
						style="box-sizing: border-box; font: 11px Arial Narrow"
						onchange="atualizarListaJogadores()"
					>
				</td>
            </tr>
        </table>
		
		<table>
            <tr>
				<td valign="bottom" align="left" style="width:9px">
                    Nome:
                </td>
                <td>
					<input
						name="enxadrista"
						id="enxadrista"
						type="text"
						value=""
						size="37"
						maxlength="44"
						onkeyup="pesq_nome(this.value); "
					>
					<input
                        name="enxadrista_reg"
                        id="enxadrista_reg"
                        type="hidden"
                        value=""
                    >
					<input
                        name="enxadrista_status"
                        id="enxadrista_status"
                        type="hidden"
                        value=""
                    >
                </td>
            </tr>
        </table>
		
		<table>
            <tr>
                <td colspan="5">
 					<select
						name="enxadrista_list"
						id="enxadrista_list"
						size="30"
						onchange="Select_Click(this,'clk');"
                        ondblclick="Select_Click(this,'dbl');"
						style="font: 14px Arial Narrow"
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
                <td></td>
				<td align='right'>
					&nbsp; &nbsp; &nbsp; Total de atletas encontrados: <span id="total">0</span>
				</td>
            </tr>
        </table>
			
            <!-- Ordenação -->
            <div class="botoes-ordenacao">Ordenar por:<button
					type="button"
					id="btnNome"
					class="botao-ordenacao"
					onclick="ordenarPorNome();"
				>
					Nome
				</button><button
					style="margin: 0;"
					type="button"
					id="btnReg"
					class="botao-ordenacao"
					onclick="ordenarPorReg();"
				>
					Registro
				</button>
				
				<button
					style="margin: 0;"
					type="button"
					id="btnClube"
					class="botao-ordenacao"
					onclick="ordenarPorClube();"
				>
					Clube
				</button>
				
				<button
					style="margin: 0;"
					type="button"
					id="btnRating"
					class="botao-ordenacao"
					onclick="ordenarPorRating();"
				>
					Rating
				</button>
				
				</div>
    </form>
</div>
</center>
		<!--
			SELECT nome,sobrenome,dt_nasc,EXTRACT(YEAR FROM CURRENT_DATE)::integer-EXTRACT(YEAR FROM dt_nasc)::integer AS idade,status
			FROM cadastro
			WHERE status = 'A' ORDER BY nome;
		-->
		
		<!-- para 'sub14', em 2026, Digite 2026U14; para 'S60', em 2026, Digite 2026S60; -->
		
</body>
</html>
