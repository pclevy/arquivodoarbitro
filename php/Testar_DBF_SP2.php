<?php
  /* ***** Testar DBF/SP98, Swiss Perfect ***** */
		$ArqTorneio	= 'I_Torneio_Prof_Amorim';
  $file = '../TorneiosSP/' . $ArqTorneio . '.trn';
		// Path to dbase file
		$db_path = $file;
		echo $db_path;
		
$con = dbase_open($db_path,0) or die('Erro na Conexão com o arquivo DBF');

// PHP>5.0 - usa função dbase_get_header_info para ler cabeçario
// PHP<5.0 - Simula a leitura através do conteudo do próprio arquivo
if(function_exists(dbase_get_header_info))   
{$estrutura_arquivo = dbase_get_header_info($con);}               
else   
{$estrutura_arquivo = alternative_dbase_get_header_info($con);}

// imprime o header do arquivo
print_r($estrutura_arquivo);

// ---------------------------------------------

// função alternativa
function alternative_dbase_get_header_info($con)
{

    //Pega o num. de linhas
    $rows = dbase_numrecords($con);
   
    //faz o loop percorrendo todas as linhas e carrega a variável $registro com os dados
    for($i=1;$i<=$rows;$i++)
    {
        //Pega a linha do arquivo DBF e coloca como array
        $registro = dbase_get_record_with_names($con,$i);
           
        $y=0;
               
        // Mostra as Chaves e os valores do array
        foreach ($registro as $chave => $valor)
        {
            // pega as chaves (field - Coluna) só se for a primeira vez
            if($i==1)
            {$estrutura_arquivo[$y]['name'] = $chave;}
   
            // finalmente pega o tipo , o comprimento e a precisão no mesmo padrão que dbase_get_header_info
            if( ( ( ( @checkdate(substr($valor,4,2),substr($valor,-2),substr($valor,0,4)) ) && (strlen(trim($valor))==8) ) || (strlen(trim($valor))==0) ) && $chave != 'deleted') // testa se é data checkdate(MM,DD,AA)
            {
                if(!isset($estrutura_arquivo[$y]['type']))
                {
                    $estrutura_arquivo[$y]['type'] = 'date';
                    $estrutura_arquivo[$y]['length'] = 8;
                    $estrutura_arquivo[$y]['precision'] = '0';
                }
                $e_data = TRUE;
            }
            if(is_numeric(trim($valor)) && strstr($valor, '.'))
            {
                $estrutura_arquivo[$y]['type'] = 'number';
                if($estrutura_arquivo[$y]['length'] < strlen(trim($valor)))
                {$estrutura_arquivo[$y]['length'] = strlen(trim($valor));}
                if($estrutura_arquivo[$y]['precision'] < strlen(strstr(trim($valor),'.'))-1)
                {$estrutura_arquivo [$y]['precision'] = strlen(strstr(trim($valor),'.'))-1;}
            }
            if(is_numeric(trim($valor)) && !strstr($valor, '.') && !$e_data && $estrutura_arquivo[$y]['precision'] < 1 && $estrutura_arquivo[$y]['type'] != 'character')
            {
                $estrutura_arquivo[$y]['type'] = 'number';
                if($estrutura_arquivo[$y]['length'] < strlen(trim($valor)))
                {$estrutura_arquivo[$y]['length'] = strlen(trim($valor));}
                $estrutura_arquivo [$y]['precision'] = '0';
            }
            if( strlen($valor)>10 || (!is_numeric(trim($valor)) && strlen(trim($valor))!=0) )
            {
                $estrutura_arquivo[$y]['type'] = 'character';
                if($estrutura_arquivo[$y]['length'] < strlen($valor))
                {$estrutura_arquivo[$y]['length'] = strlen($valor);}
                $estrutura_arquivo [$y]['precision'] = '0';
            }
               
            $e_data = FALSE;
            $y+=1;
        }
           
    }
       
    return(estrutura_arquivo);
}?>
