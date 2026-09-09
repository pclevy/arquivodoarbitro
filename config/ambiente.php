<?php
	/*
	 * config/ambiente.php
	 *
	 * Configuração automática:
	 * - localhost / 127.0.0.1 = desenvolvimento
	 * - qualquer outro domínio = produção
	 */

	$host = $_SERVER['HTTP_HOST'] ?? '';

	if (
		$host === 'localhost' ||
		$host === '127.0.0.1'
	) {
		// DESENVOLVIMENTO
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
	} else {
		// PRODUÇÃO
		ini_set('display_errors', 0);
		ini_set('display_startup_errors', 0);
	}
	error_reporting(E_ALL);
?>