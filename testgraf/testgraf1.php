	<?php

//var_dump(gd_info());

//Include a classe phplot
//include('./PHPlot.php');//mude de acrodo com a sua situação

//Define o objeto
$graph = new PHPlot();

//Define alguns valores
$example_data = array(
     array('a',3),
     array('b',5),
     array('c',7),
     array('d',8),
     array('e',2),
     array('f',6),
     array('g',7)
);
$graph->SetDataValues($example_data);

$graph->DrawGraph(); //Desenha o gráfico 
?>