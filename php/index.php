<!DOCTYPE html>
<html dir="ltr" lang="pt-BR">
	<!-- php/index.php - Versão: 2026/09/01 -->

	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<meta
			name="keywords"
			content="xadrez, DV, cego, inclusão,xadrezdeolhonofuturo,esfinge"
		/>

		<link
			rel="icon"
			type="image/png"
			href="../imagens/arquivo_do_arbitro.png"
		/>

		<title>AI Paulo Levy</title>

		<!-- =========================================================
		     Google Analytics 4
		     ========================================================= -->
		<?php include __DIR__ . "/google_analytics.php"; ?>

		<!-- =========================================================
		     Google AdSense
		     ========================================================= -->
		<script
			async
			src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7174891341008290"
			crossorigin="anonymous"
		></script>

		<style type="text/css">

			h1 {
				font-family: arial, verdana, sans-serif;
				font-size: 10px;
			}

			div.transbox90 {
				filter: alpha(opacity=90);
				-moz-opacity: .90;
				opacity: 0.90;
			}

		</style>
	</head>

	<body bgcolor="eeeeff">

		<div
			id="geral"
			style="
				position:absolute;
				width:997px;
				height:580px;
				left:1px;
				top:1px;
				border:1px solid #000000;
			"
		>

			<!-- =====================================================
			     TÍTULO
			     ===================================================== -->

			<div
				style="
					position:absolute;
					width:993px;
					left:1px;
					top:1px;
					font-size:3px;
					text-align:center;
					border:1px solid #2266AA;
				"
			>

				<font size="6">
					<b>Arquivo do Árbitro</b><br>
				</font>

				<br>

			</div>


			<!-- =====================================================
			     MENU LATERAL
			     ===================================================== -->

			<div
				class="transbox90"
				id="blocomovel_5"
				style="
					z-index:1;
					visibility:visible;
					position:absolute;
					width:124px;
					height:500px;
					left:1px;
					top:45px;
					font-size:12px;
					font-family:Arial Narrow Bold, Arial Narrow, Arial, sans-serif, Liberation Sans Narrow;
					padding-left:2px;
					font-weight:600;
					border:1px solid #2266AA;
				"
			>

				<a href="index.php?page=page_home">
					Apresentação
				</a><br>

				<span style="font-size:6px;"><br></span>


				<!-- Pesquisar Torneios -->

				<span
					title="Pesquisar Torneios"
					style="color:#999; cursor:not-allowed;"
				>
					Pesquisar Torneios
				</span><br>

				<span style="font-size:6px;"><br></span>


				<!-- Enviar Torneios SM -->

				<span
					title="Torneios Swiss Manager"
					style="color:#999; cursor:not-allowed;"
				>
					Enviar Torneios SM
				</span><br>


				<!-- Enviar Torneios SP -->

				<span
					title="Torneios Swiss Perfect"
					style="color:#999; cursor:not-allowed;"
				>
					Enviar Torneios SP
				</span><br>


				<!-- Enviar Torneios VG -->

				<span
					title="Torneios Vega"
					style="color:#999; cursor:not-allowed;"
				>
					Enviar Torneios VG
				</span><br>


				<span style="font-size:6px;"><br></span>


				<!-- Rating -->

				<a href="index.php?page=RatingFexerj">
					Rating Fexerj
				</a>

				<hr>


				<!-- =================================================
				     EVENTOS PRÓXIMOS
				     ================================================= -->

				<span style="font-size:16px;">
					Eventos próximos:
				</span>

				<span style="font-size:6px;"><br></span>

				<a
					href="https://www.fexerj.org.br/blog/circuito-de-regionais-fexerj-etapa-nova-frinurgo"
				>
					Regional de Nova Friburgo - RJ
				</a>

				<br>

				<span style="font-size:6px;"><br></span>

				<hr>


				<!-- Links -->

				<a href="index.php?page=Links">
					Links
				</a><br>

			</div>


			<!-- =====================================================
			     ADSENSE
			     ===================================================== -->

			<div
				style="
					position:absolute;
					bottom:30px;
					width:124px;
					height:260px;
					left:2px;
				"
			>

				<!--
					Anúncio AdSense 120x240

					O código antigo show_ads.js foi removido.
					O carregamento do AdSense é feito no <head>.
				-->

				<ins
					class="adsbygoogle"
					style="
						display:inline-block;
						width:120px;
						height:240px;
					"
					data-ad-client="ca-pub-7174891341008290"
					data-ad-slot="9550926365"
				></ins>

				<script>
					(adsbygoogle = window.adsbygoogle || []).push({});
				</script>

			</div>


			<!-- =====================================================
			     RODAPÉ / NAVEGADORES
			     ===================================================== -->

			<div
				style="
					position:absolute;
					bottom:2px;
					float:left;
					padding-left:2px;
					font-size:10px;
					font-family:Arial Narrow Bold, Arial Narrow, Arial, sans-serif, Liberation Sans Narrow;
					width:124px;
					left:1px;
					border:1px solid #2266AA;
				"
			>

				Otimizado para Navegadores:<br />

				Android, Windows, Linux.

			</div>


			<!-- =====================================================
			     ÁREA PRINCIPAL
			     ===================================================== -->

			<div
				id="DivPrincipal"
				name="DivPrincipal"
				style="
					position:absolute;
					visibility:visible;
					width:864px;
					height:530px;
					left:130px;
					top:45px;
					border:1px solid #2266AA;
				"
			>

				<?php

					/* -------------------------------------------------
					   Página solicitada
					   ------------------------------------------------- */

					$page = isset($_GET['page'])
						? $_GET['page']
						: '';


					if (strlen($page) === 0) {
						$page = 'page_home';
					}


					/* -------------------------------------------------
					   Seleção da página
					   ------------------------------------------------- */

					switch ($page) {

						case "page_home":

							$file = "../page_home.php";

							break;


						case "PesquisarTorneios":

							$file = "torneios_pesq_Sel.php";

							break;


						case "EnviarTorneioSM":

							$file = "af_receberarquivo_SM.php";

							break;


						case "EnviarTorneioSP":

							$file = "af_receberarquivo_SP.php";

							break;


						case "EnviarTorneioVG":

							$file = "receberarquivo_VG.php";

							break;


						case "RatingFexerj":

							$file = "reg_rat_pesq_Sel.php";

							break;


						case "Links":

							$file = "../links_arbitragem.html";

							break;


						default:

							$file = $page;

							break;
					}


					/* -------------------------------------------------
					   Inclui a página selecionada
					   ------------------------------------------------- */

					if (isset($file) && file_exists($file)) {

						include $file;

					} else {

						echo "<p style='padding:20px; color:red;'>";
						echo "Página não encontrada.";
						echo "</p>";

					}

				?>

			</div>

		</div>

	</body>
</html>
```
