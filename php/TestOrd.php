<?php
 
	echo "teste<br>";
 
	$teste[0]=3;
 $teste[1]=2;
 $teste[2]=7;
 $teste[3]=5;
 $teste[4]=4;
	
	echo $teste[3] . '<br><br>';
	
	for($i=0;$i<=4;$i++)
	 {echo $i . ' - ' . $teste[$i] . '<br>';}
	
	echo "<br>";
	
	rsort($teste,SORT_NUMERIC);
	
	for($i=0;$i<=4;$i++)
	 {echo $i . ' - ' . $teste[$i] . '<br>';}
	
?>