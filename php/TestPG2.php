<?php
	// Lendo String de conexão
	//$file = "../../../config/conexao.cfg"; //UERJ
	$file = "../../config/conexao_ca.cfg";
	$fh = fopen($file, 'r');
	$conteudo = explode("*", fread($fh, filesize($file)));
	$strconexao = trim($conteudo[0]);
	$codificacao = trim($conteudo[1]);
	fclose($fh);
	//echo "<option value=''> ******* $strconexao - $resultado *********</option>";
	$conexao=pg_connect($strconexao) or die("erro na conexão");

		$select=0;
	//Selecionando registros

	/*
		$select++;
		$sql =pg_query($conexao,"DELETE from torneios;") or die("erro de SELECT $select");
		$resultado=pg_num_rows($sql);
		echo "--- $resultado <br>";
		
		$select++;
		$sql =pg_query($conexao,"DROP TABLE torneios;") or die("erro de SELECT $select");
		$resultado=pg_num_rows($sql);
		echo "--- $resultado <br>";
	*/
	
 $select++;
 $sql =pg_query($conexao,"
																										SET statement_timeout = 0;
																										SET client_encoding = 'UTF8';
																										SET standard_conforming_strings = off;
																										SET check_function_bodies = false;
																										SET client_min_messages = warning;
																										SET escape_string_warning = off;
																										SET search_path = public, pg_catalog;
																										SET default_tablespace = '';
																										SET default_with_oids = false;
																									") or die("erro de SELECT $select");
	$resultado=pg_num_rows($sql);
	echo "--- $resultado <br>";

 $select++;
	$sql =pg_query($conexao,"
																										CREATE TABLE torneios (
																														reg integer NOT NULL,
																														nome text,
																														arquivo text,
																														remetente text,
																														email text,
																														prog character(2),
																														data_rec date,
																														status character(1)
																										);
																									") or die("erro de SELECT $select");
	$resultado=pg_num_rows($sql);
	echo "--- $resultado <br>";

 $select++;
	$sql =pg_query($conexao,"
																										ALTER TABLE public.torneios OWNER TO pclevy;
																									") or die("erro de SELECT $select");
	$resultado=pg_num_rows($sql);
	echo "--- $resultado <br>";

 $select++;
	$sql =pg_query($conexao,"
																										CREATE SEQUENCE torneios_reg_seq
																														START WITH 1
																														INCREMENT BY 1
																														NO MAXVALUE
																														NO MINVALUE
																														CACHE 1;
																									") or die("erro de SELECT $select");
	$resultado=pg_num_rows($sql);
	echo "--- $resultado <br>";

 $select++;
	$sql =pg_query($conexao,"
																										ALTER TABLE public.torneios_reg_seq OWNER TO pclevy;
																									") or die("erro de SELECT $select");
	$resultado=pg_num_rows($sql);
	echo "--- $resultado <br>";

 $select++;
	$sql =pg_query($conexao,"
																										ALTER SEQUENCE torneios_reg_seq OWNED BY torneios.reg;
																									") or die("erro de SELECT $select");
	$resultado=pg_num_rows($sql);
	echo "--- $resultado <br>";

 $select++;
	$sql =pg_query($conexao,"
																										ALTER TABLE ONLY torneios ALTER COLUMN reg SET DEFAULT nextval('torneios_reg_seq'::regclass);
																									") or die("erro de SELECT $select");
	$resultado=pg_num_rows($sql);
	echo "--- $resultado <br>";

 $select++;
	$sql =pg_query($conexao,"
																										ALTER TABLE ONLY torneios
																														ADD CONSTRAINT torneios_pkey PRIMARY KEY (reg);
																									") or die("erro de SELECT $select");
	$resultado=pg_num_rows($sql);
	echo "--- $resultado <br>";

 $select++;
	$sql =pg_query($conexao,"
																										REVOKE ALL ON TABLE torneios FROM PUBLIC;
																										REVOKE ALL ON TABLE torneios FROM pclevy;
																										GRANT ALL ON TABLE torneios TO pclevy;
																									") or die("erro de SELECT $select");
	$resultado=pg_num_rows($sql);
	echo "--- $resultado <br>";

 $select++;
	$sql =pg_query($conexao,"
																										REVOKE ALL ON SEQUENCE torneios_reg_seq FROM PUBLIC;
																										REVOKE ALL ON SEQUENCE torneios_reg_seq FROM pclevy;
																										GRANT ALL ON SEQUENCE torneios_reg_seq TO pclevy;
																									") or die("erro de SELECT $select");
	$resultado=pg_num_rows($sql);
	echo "--- $resultado <br>";


	
	
 $select++;
	$sql =pg_query($conexao,"
																										SELECT pg_catalog.setval('torneios_reg_seq', 41, true);
																									") or die("erro de SELECT $select");
	$resultado=pg_num_rows($sql);
	echo "--- $resultado <br>";
 
 $select++;
	$sql =pg_query($conexao,"
																										INSERT INTO torneios VALUES (1, 'Olympiad Dresden 2008 Open', 'OLIMPIADA_DRESDEN.TUMx', 'AF Paulo Levy', 'pclevybr@yahoo.com.br', 'SM', '2012-12-02', 'A');
																									") or die("erro de SELECT $select");
 $select++;
	$sql =pg_query($conexao,"
																										INSERT INTO torneios VALUES (2, 'Continental Absolute Championship', 'Continental_2009.TUNx', 'Samuel Levy', 'samuel@meshproducoes.com.br', 'SM', '2012-12-16', 'A');
																									") or die("erro de SELECT $select");
 $select++;
	$sql =pg_query($conexao,"
																										INSERT INTO torneios VALUES (3, 'Torneio Internacional Aberto do Brasil \"UERJ 60 Anos\"', 'irtuerj2.tunx', 'P. Levy', 'pclevybr@yahoo.com.br', 'SM', '2012-12-03', 'A');
																									") or die("erro de SELECT $select");
 $select++;
	$sql =pg_query($conexao,"
																										INSERT INTO torneios VALUES (4, 'ABERTO DO BRASIL BIENAL PROFESSOR JOAO BRAGA 2011', 'I_Bienal_Joao_Braga_2011.TUNX', 'AF Paulo C Levy', 'pclevybr@yahoo.com.br', 'SM', '2012-12-05', 'A');
																									") or die("erro de SELECT $select");
 $select++;
	$sql =pg_query($conexao,"
																										INSERT INTO torneios VALUES (5, 'Circuito Regional de Xadrez por Equipes', 'CRXpEq_Et1.TUMx', 'samuel', 'pclevy@hotmail.com', 'SM', '2012-12-01', 'A');
																									") or die("erro de SELECT $select");
 $select++;
	$sql =pg_query($conexao,"
																										INSERT INTO torneios VALUES (6, 'IRT RIO DE JANEIRO DE OUTONO DE 2012', 'IRTRIO_1.TURx', 'AF Paulo C Levy', 'pclevybr@yahoo.com.br', 'SM', '2012-12-06', 'A');
																									") or die("erro de SELECT $select");
 $select++;
	$sql =pg_query($conexao,"
																										INSERT INTO torneios VALUES (7, '7mo Continental de las Americas - Mar del Plata 2012', 'continetalamerica2012_82703.TUNX', 'AF Paulo C Levy', 'pclevybr@yahoo.com.br', 'SM', '2012-12-04', 'A');
																									") or die("erro de SELECT $select");
 $select++;
	$sql =pg_query($conexao,"
																										INSERT INTO torneios VALUES (8, 'Campeonato Continental para Ciegos y Disminuidos Visuales', 'continental_ciegos_2012_76333.TUNX', 'AF Paulo C Levy', 'pclevybr@yahoo.com.br', 'SM', '2012-12-11', 'A');
																									") or die("erro de SELECT $select");
 $select++;
	$sql =pg_query($conexao,"
																										INSERT INTO torneios VALUES (9, 'XI GRAN TORNEO INTERNACIONAL AFICIONADOS &quot;A&quot; S-23', 'bali_2012_a2300_aficionados_72253.TUNX', 'AF Paulo C Levy', 'pclevybr@yahoo.com.br', 'SM', '2012-12-09', 'A');
																									") or die("erro de SELECT $select");
 $select++;
	$sql =pg_query($conexao,"
																										INSERT INTO torneios VALUES (10, 'II Abierto Internacional de Ajedrez \"Marcel Duchamp\"', 'abiertoduchamp_83522.TUNX', 'AF Paulo C Levy', 'pclevybr@yahoo.com.br', 'SM', '2013-01-11', 'A');
																									") or die("erro de SELECT $select");
 $select++;
	$sql =pg_query($conexao,"
																										INSERT INTO torneios VALUES (11, 'Magistral Internacional \"Reyes y Reinas-Marcel Duchamp\"', 'ittduchamp_83535.TURX', 'AF Paulo C Levy', 'pclevybr@yahoo.com.br', 'SM', '2013-01-11', 'A');
																									") or die("erro de SELECT $select");
 $select++;
	$sql =pg_query($conexao,"
																										INSERT INTO torneios
																										VALUES (12, '4o Magistral Internacional \"Marcel Duchamp\"', 'magistralduchamp_83534.TURX', 'AF Paulo C Levy', 'pclevybr@yahoo.com.br', 'SM', '2013-01-11', 'A');
																									") or die("erro de SELECT $select");
 $select++;
	$sql =pg_query($conexao,"
																										INSERT INTO torneios VALUES (13, 'V Torneo Abierto de La Legislatura \"Norberto La Porta\" - Mas de 2001', 'legislaturamasde2001_87353.TUNX', 'AF Paulo C Levy', 'pclevybr@yahoo.com.br', 'SM', '2013-01-11', 'A');
																									") or die("erro de SELECT $select");
 $select++;
	$sql =pg_query($conexao,"
																										INSERT INTO torneios VALUES (14, 'Magistral La Razon 2012', 'magistral_la_razon_2012_83557.TURX', 'AF Paulo C Levy', 'pclevybr@yahoo.com.br', 'SM', '2013-01-11', 'A');
																									") or die("erro de SELECT $select");
 $select++;
	$sql =pg_query($conexao,"
																										INSERT INTO torneios VALUES (15, 'I Torneio Popular de Verao 2013', 'popular_de_verao_2013_89959.TUNX', 'AF Paulo C Levy', 'pclevybr@yahoo.com.br', 'SM', '2013-01-31', 'A');
																									") or die("erro de SELECT $select");
	$resultado=pg_num_rows($sql);
	echo "--- $resultado <br>";
	exit;
?>