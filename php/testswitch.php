<?php
	$test='+';
	switch($test)
		{
			case '1':
				echo 'test1: '.$test;
				break;
			case "+":
				$testz='+';
				switch($testz)
					{
						case '1':
							echo 'test1: '.$testz;
							break;
						case "+":
							echo 'test+: '.$testz;
							break;
						case 2:
							echo 'test2: '.$testz;
							break;
						case "-":
							echo 'test-: '.$testz;
							break;
						case "0":														
							break;
						default:
					}
				break;
			case 2:
				echo 'test2: '.$test;
				break;
			case "-":
				echo 'test-: '.$test;
				break;
			case "0":														
				break;
			default:
		}
?>