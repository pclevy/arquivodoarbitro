<?php
echo "<br>teste 1<br>";

$a="window.document.write('Escreveu<br>";
$b="<img src='testgraf4.php?v1=400&v2=200&r1=1800&r2=1850&r3=1900&r4=1870&r5=1810&r6=1860&r7=1910&r8=9999&r9=7777&r10=falso'>";
$c="')";
$d=$a.$b.$c;

echo "<span onclick=<script>$d</script>;> Clique aqui.</span><br><br>";

//echo "<script>$d</script>";

echo "<br>teste 2<br>";
?>
