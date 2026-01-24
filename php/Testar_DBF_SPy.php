<?php
  /* ***** Testar DBF/SP98, Swiss Perfect ***** */
		$db_path = "I_Torneio_Prof_Amorim.trn";
		echo $db_path;

		echo "<br><br> -- 0 --";
		// Open dbase file
		$dbh = dbase_open($db_path, 0); // or die("Error! Could not open dbase database file '$db_path'.");

		echo "<br><br> -- 1 --";
		// Get column information
		//$column_info = dbase_get_header_info($dbh);
		$NumCampos = dbase_numfields($dbh);

		// Display information
		//print_r($column_info);
		echo "<br><br> -- 2 --";
		echo "<br><br> -- $NumCampos --";
		echo "<br><br> -- 3 --";
?>
