<?php
/* php/reg_rat_pesq_Sel.php
 * Versão: 2026.10.02
 * Alterado em 2026/10/02
 */

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

echo "Passou aqui 01.<br><br>";

$file = "../../config/conexao_ca.cfg";

$fh = fopen($file, 'r');
$conteudo = explode("*", fread($fh, filesize($file)));
$strconexao = trim($conteudo[0]);
$codificacao = trim($conteudo[1]);
fclose($fh);

echo "String de conex&atilde;o: $strconexao <br>"; 

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
	WHERE nomecompleto LIKE '%Levy'
    ORDER BY nome;
");

if (!$sql) {
    die("Erro na consulta ao banco de dados.");
}

$resultado = pg_num_rows($sql);

echo "<br>passou aqui 02.<br><br>";

echo "Linhas: $resultado";

pg_close($conexao);

exit;
?>