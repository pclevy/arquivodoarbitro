<?php

$d=Date('d/m/Y');echo "<br><br>$d<br><br>";//exit;
	
	$array1[0][1]=1;
 $array1[0][2]=2;
 $array1[0][3]=3;
 $array1[0][4]=4;
	$array1[0][5]=5;
	
	$array1[1][1]='Mario';
 $array1[1][2]='Alberto';
 $array1[1][3]='Roberto';
 $array1[1][4]='Fernando';
	$array1[1][5]='Zélio';
	
	$array1[2][1]=3;
 $array1[2][2]=2;
 $array1[2][3]=5;
 $array1[2][4]=5;
	$array1[2][5]=3;
	
	$array1[3][1]=13;
 $array1[3][2]=21;
 $array1[3][3]=25;
 $array1[3][4]=20;
	$array1[3][5]=11;
	
	$array1[4][1]=13;
 $array1[4][2]=21;
 $array1[4][3]=25;
 $array1[4][4]=20;
	$array1[4][5]=11;
	
	$array1[5][1]=13;
 $array1[5][2]=21;
 $array1[5][3]=25;
 $array1[5][4]=20;
	$array1[5][5]=11;
	
	$array1[6][1]=13;
 $array1[6][2]=21;
 $array1[6][3]=25;
 $array1[6][4]=20;
	$array1[6][5]=11;
	
	echo '<br><br>'.$array1[1][3].'<br><br>';
	
	for($i=0;$i<=6;$i++) { unset($array1[$i][2]); }
	
	for($i=1;$i<=5;$i++)
		{
			for($j=0;$j<=6;$j++)
				{
					echo $array1[$j][$i].' - ';
				}
			echo '<br>';
		}
	
	array_multisort($array1[2], SORT_NUMERIC, SORT_DESC,
                 $array1[3], SORT_NUMERIC, SORT_DESC,
                 $array1[4], SORT_NUMERIC, SORT_DESC,
                 $array1[5], SORT_NUMERIC, SORT_DESC,
                 $array1[6], SORT_NUMERIC, SORT_DESC,
																	$array1[0],
																	$array1[1]
																);
	
	echo '<br>';
	for($i=0;$i<=4;$i++)
		{
			for($j=0;$j<=6;$j++)
				{
					echo $array1[$j][$i].' - ';
				}
			echo '<br>';
		}
	
	echo '<br><br>'.$array1[1][3].'<br><br>';


	echo '<br><br>'.$PtsDesemp[1][3].'<br><br>';
	
$PtsDesemp[0][6]=6;$PtsDesemp[1][6]=5;$PtsDesemp[2][6]=17;$PtsDesemp[3][6]=11;$PtsDesemp[4][6]=12;$PtsDesemp[5][6]=15;$PtsDesemp[6][6]=10;
$PtsDesemp[0][5]=5;$PtsDesemp[1][5]=4.5;$PtsDesemp[2][5]=71;$PtsDesemp[3][5]=11;$PtsDesemp[4][5]=12;$PtsDesemp[5][5]=15;$PtsDesemp[6][5]=10;
$PtsDesemp[0][4]=4;$PtsDesemp[1][4]=4.5;$PtsDesemp[2][4]=17;$PtsDesemp[3][4]=11;$PtsDesemp[4][4]=12;$PtsDesemp[5][4]=15;$PtsDesemp[6][4]=10;
$PtsDesemp[0][3]=3;$PtsDesemp[1][3]=7;$PtsDesemp[2][3]=72;$PtsDesemp[3][3]=11;$PtsDesemp[4][3]=12;$PtsDesemp[5][3]=15;$PtsDesemp[6][3]=10;
$PtsDesemp[0][2]=2;$PtsDesemp[1][2]=3.5;$PtsDesemp[2][2]=17;$PtsDesemp[3][2]=11;$PtsDesemp[4][2]=12;$PtsDesemp[5][2]=15;$PtsDesemp[6][2]=10;
$PtsDesemp[0][1]=1;$PtsDesemp[1][1]=2;$PtsDesemp[2][1]=17;$PtsDesemp[3][1]=11;$PtsDesemp[4][1]=12;$PtsDesemp[5][1]=15;$PtsDesemp[6][1]=10;
														
	echo '<br><br>';
	for($j=1;$j<=6;$j++)
		{
			echo $j.': '.$PtsDesemp[0][$j].' - '.$PtsDesemp[1][$j].' - '.$PtsDesemp[2][$j].'<br>';
		}
	
	array_multisort($PtsDesemp[1], SORT_NUMERIC, SORT_DESC,
																	$PtsDesemp[2], SORT_NUMERIC, SORT_DESC,
																	$PtsDesemp[3], SORT_NUMERIC, SORT_DESC,
																	$PtsDesemp[4], SORT_NUMERIC, SORT_DESC,
																	$PtsDesemp[5], SORT_NUMERIC, SORT_DESC,
																	$PtsDesemp[6], SORT_NUMERIC, SORT_DESC,
																	$PtsDesemp[0]
																);
													
	echo '<br><br>';
	for($j=0;$j<=5;$j++)
		{
			echo $j.': '.$PtsDesemp[0][$j].' - '.$PtsDesemp[1][$j].' - '.$PtsDesemp[2][$j].'<br>';
		}

	
?>