<?php
/* atualizar_cadastro_com_Insert.php
 * Versão: 2026.10.08
 * Alterado em 2026/10/08
 */

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

echo "Passou aqui 01.<br>1.00<br>";

$file = "../../config/conexao_ca.cfg";

$fh = fopen($file, 'r');
$conteudo = explode("*", fread($fh, filesize($file)));
$strconexao = trim($conteudo[0]);
$codificacao = trim($conteudo[1]);
fclose($fh);

echo "String de conex&atilde;o: $strconexao <br>"; 

$conexao = pg_connect($strconexao) or die("erro na conexão");

$sql = pg_query($conexao, "
INSERT INTO public.cadastro
	(reg, nome, sobrenome, titulo, primeiro_clube, clube, primeiro_rating, rating, rapido, blitz, sexo, uf, pais, k, qt_part, dt_nasc, dt_lanc, atv, cat, fide_reg, fide_rat, tit_fide, endereco, bairro, cep, municipio, tel_res, tel_cel, cbx_reg, dt_atu, tel_text, status, ajuste, uf_cbx, pais_fide)
VALUES
	
	(5720, 'Walmir de Souza', 'Figueiredo Jr', NULL, 'CXC', 'CXC', 10, 10, 0, 0, 'M', 'RJ ', 'BRA ', '40', '0', '30/04/2001', '08/10/2026', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '08/10/2026', NULL, 'A', NULL, 'RJ', 'BRA'), 
	(5721, 'Andre Henrique Lemos de', 'Freitas', 'NM', 'ALEX', 'ALEX', 2039, 2039, 0, 0, 'M', 'RJ ', 'BRA ', '10', '0', '22/03/1968', '08/10/2026', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '08/10/2026', NULL, 'A', NULL, 'RJ', 'BRA'), 
	(5722, 'Edgar Jose da', 'Silva Jr', NULL, 'AFLUX', 'AFLUX', 1548, 1548, 0, 0, 'M', 'RJ ', 'BRA ', '0', '0', '15/6/1985', '08/10/2026', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '08/10/2026', NULL, 'A', NULL, 'RJ', 'BRA'), 
	(5723, 'Thais Emanuely Neto', 'Aguiar', NULL, 'AFLUX', 'AFLUX', 10, 10, 0, 0, 'F', 'RJ ', 'BRA ', '0', '0', '19/11/2007', '08/10/2026', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '08/10/2026', NULL, 'A', NULL, 'RJ', 'BRA'), 
	(5724, 'Maria Eduarda Lima da', 'Costa', NULL, 'AFLUX', 'AFLUX', 10, 10, 0, 0, 'F', 'RJ ', 'BRA ', '0', '0', '08/09/2007', '08/10/2026', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '08/10/2026', NULL, 'A', NULL, 'RJ', 'BRA'), 
	(5725, 'Felipe Gabriel de Carvalho', 'Ferreira', NULL, 'NXN', 'NXN', 10, 10, 0, 0, 'M', 'RJ ', 'BRA ', '0', '0', '14/12/2009', '08/10/2026', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '08/10/2026', NULL, 'A', NULL, 'RJ', 'BRA'), 
	(5726, 'Miguel Cordeiro da', 'Cruz', NULL, 'NXN', 'NXN', 10, 10, 0, 0, 'M', 'RJ ', 'BRA ', '0', '0', '04/06/2012', '08/10/2026', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '08/10/2026', NULL, 'A', NULL, 'RJ', 'BRA'), 
	(5727, 'Thadeu Pereira Santos', 'Lima', NULL, 'CMUN', 'CMUN', 10, 10, 0, 0, 'M', 'RJ ', 'BRA ', '0', '0', '29/9/1994', '08/10/2026', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '08/10/2026', NULL, 'A', NULL, 'RJ', 'BRA'), 
	(5728, 'Joao Pedro Vieira Meirelles', 'Aurelio', NULL, 'CMUN', 'CMUN', 10, 10, 0, 0, 'M', 'RJ ', 'BRA ', '0', '0', '31/7/2004', '08/10/2026', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '08/10/2026', NULL, 'A', NULL, 'RJ', 'BRA');
");

if (!$sql) {
    die("Erro na consulta ao banco de dados.");
}

$resultado = pg_num_rows($sql);

echo "<br>passou aqui 02.<br><br>";

echo "Linhas: $resultado";


for($i=0;$i<$resultado;$i++)
	{
		echo "<br>Nome: ";
		echo pg_result($sql, $i, 'nomecompleto');
		echo "<br>";
	}

pg_close($conexao);

exit;
?>