<?php
  /* ***** Testar DBF/SP98, Swiss Perfect ***** */
		$ArqTorneio	= 'I_Torneio_Prof_Amorim';
  $file = '../TorneiosSP/' . $ArqTorneio . '.sco';
		// Path to dbase file
		$db_path = $file;
		echo $db_path;
		
		// Open dbase file
		$dbh = dbase_open($db_path, 0) or die("Error! Could not open dbase database file '$db_path'.");

		// Get column information
		$column_info = dbase_get_header_info($dbh);

		// Display information
		print_r($column_info);
?>
