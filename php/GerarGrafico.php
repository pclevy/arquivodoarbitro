<?php


header('Content-type: image/png');

// ----------------------------------------
// Tabela de cores
// ----------------------------------------
$verm = $verd = $azul = [];

$i_cor = 0;  $verm[$i_cor]=255; $verd[$i_cor]=0;   $azul[$i_cor]=0;
$i_cor = 1;  $verm[$i_cor]=255; $verd[$i_cor]=165; $azul[$i_cor]=0;
$i_cor = 2;  $verm[$i_cor]=255; $verd[$i_cor]=255; $azul[$i_cor]=0;
$i_cor = 3;  $verm[$i_cor]=0;   $verd[$i_cor]=255; $azul[$i_cor]=0;
$i_cor = 4;  $verm[$i_cor]=0;   $verd[$i_cor]=255; $azul[$i_cor]=255;
$i_cor = 5;  $verm[$i_cor]=255; $verd[$i_cor]=170; $azul[$i_cor]=0;
$i_cor = 6;  $verm[$i_cor]=255; $verd[$i_cor]=0;   $azul[$i_cor]=255;
$i_cor = 7;  $verm[$i_cor]=170; $verd[$i_cor]=255; $azul[$i_cor]=255;
$i_cor = 8;  $verm[$i_cor]=0;   $verd[$i_cor]=255; $azul[$i_cor]=77;
$i_cor = 9;  $verm[$i_cor]=255; $verd[$i_cor]=100; $azul[$i_cor]=100;
$i_cor = 10; $verm[$i_cor]=255; $verd[$i_cor]=165; $azul[$i_cor]=100;
$i_cor = 11; $verm[$i_cor]=77;  $verd[$i_cor]=200; $azul[$i_cor]=170;
$i_cor = 12; $verm[$i_cor]=200; $verd[$i_cor]=170; $azul[$i_cor]=0;
$i_cor = 13; $verm[$i_cor]=200; $verd[$i_cor]=255; $azul[$i_cor]=77;
$i_cor = 14; $verm[$i_cor]=200; $verd[$i_cor]=77;  $azul[$i_cor]=255;
$i_cor = 15; $verm[$i_cor]=77;  $verd[$i_cor]=200; $azul[$i_cor]=170;
$i_cor = 16; $verm[$i_cor]=255; $verd[$i_cor]=77;  $azul[$i_cor]=0;
$i_cor = 17; $verm[$i_cor]=0;   $verd[$i_cor]=170; $azul[$i_cor]=255;
$i_cor = 18; $verm[$i_cor]=200; $verd[$i_cor]=170; $azul[$i_cor]=0;
$i_cor = 19; $verm[$i_cor]=170; $verd[$i_cor]=255; $azul[$i_cor]=200;
$i_cor = 20; $verm[$i_cor]=255; $verd[$i_cor]=0;   $azul[$i_cor]=170;
$i_cor = 21; $verm[$i_cor]=255; $verd[$i_cor]=127; $azul[$i_cor]=80;

$cor_max = $i_cor;

// ----------------------------------------
// Processa parâmetros recebidos
// ----------------------------------------
$parametros = $_SERVER['QUERY_STRING'];
$list_par = explode('&', $parametros);

$qt = count($list_par);

// remove tudo após o marcador 9999
for ($i = 0; $i < $qt; $i++) {
    $pos = strpos($list_par[$i], "=");
    $list_par[$i] = substr($list_par[$i], $pos + 1);
    if ($list_par[$i] == 9999) {
        $fim_par = $i;
    }
}
while (count($list_par) > $fim_par) {
    array_pop($list_par);
}

$qt = count($list_par);

$v1 = $list_par[0] + 20;  // largura
$v2 = $list_par[1];       // altura

$larg_bar = 21;
$margem_inf = 5;
$margem_sup = 5;
$alt_min = 50;

$alt_max = $v2 - $margem_inf - $margem_sup;

// ----------------------------------------
// Monta vetores rating/mesano
// ----------------------------------------
$rating = [];
$mesano = [];
$min = 3000;
$max = 0;

$j = 0;
for ($i = 2; $i < $qt; $i += 2) {
    $rating[$j] = intval($list_par[$i]);
    $mesano[$j] = $list_par[$i + 1];

    if ($rating[$j] > 0) {
        if ($rating[$j] < $min) $min = $rating[$j];
        if ($rating[$j] > $max) $max = $rating[$j];
    }
    $j++;
}
$qtr = $j;

// ----------------------------------------
// Imagem
// ----------------------------------------
$larg_img = $v1;
$alt_img = $v2;

// CORRIGIDO: largura primeiro, altura depois
$imagem = imagecreatetruecolor($larg_img, $alt_img);

// cores básicas
$fundo = imagecolorallocate($imagem, 255, 255, 180);
$branco = imagecolorallocate($imagem, 255, 255, 255);
$preto = imagecolorallocate($imagem, 0, 0, 0);

imagefilledrectangle($imagem, 0, 0, $larg_img - 1, $alt_img - 1, $fundo);

// ----------------------------------------
// Escala vertical
// ----------------------------------------
if ($max - $min == 0) {
    $coef = ($alt_max - $alt_min);
} else {
    $coef = ($alt_max - $alt_min) / ($max - $min);
}
if ($coef > 2) $coef = 2;

// ----------------------------------------
// Desenha as barras
// ----------------------------------------
$cor_disp = 0;

for ($i = 0; $i < $qtr; $i++) {

    // pega cor
    $cor_bar = imagecolorallocate(
        $imagem,
        $verm[$cor_disp],
        $verd[$cor_disp],
        $azul[$cor_disp]
    );
    $cor_disp = ($cor_disp < $cor_max) ? $cor_disp + 1 : 0;

    $y1 = $i * $larg_bar + 2;
    $y2 = $i * $larg_bar + $larg_bar;

    if ($rating[$i] < 1) {

        imagefilledrectangle($imagem, 4, $y1, 40, $y2, $fundo);
        imagestring($imagem, 2, 5, $y1 + 3, $mesano[$i], $preto);

    } else {

        $alt_rat = ($rating[$i] - $min) * $coef + $alt_min;

        imagefilledrectangle($imagem, 4, $y1, $alt_rat, $y2, $cor_bar);
        imagestring($imagem, 2, 5, $y1 + 3, $mesano[$i], $preto);
    }
}

// ----------------------------------------
// Desenha valores do rating ANTES da rotação
// ----------------------------------------
for ($i = 0; $i < $qtr; $i++) {

    if ($rating[$i] < 1) {
        imagestring($imagem, 1, 3 + $i * $larg_bar, 150, "ND", $preto);
    } else {
        $alt_rat = ($rating[$i] - $min) * $coef + $alt_min;
        imagestring($imagem, 1, 3 + $i * $larg_bar, $alt_img - $alt_rat - 9, $rating[$i], $preto);
    }
}

// ----------------------------------------
// ROTACIONA APÓS DESENHAR TUDO
// ----------------------------------------
$imagem = imagerotate($imagem, 90, 0);

// ----------------------------------------
// Saída final
// ----------------------------------------
imagepng($imagem);
imagedestroy($imagem);

?>