<?php
$log_datahora_ini = date("dmYHis");
$acao="filacredito"; 
$arqlog = defineCaminhoLog()."apilebes_".$acao."_".date("dmY").".log";
$arquivo = fopen($arqlog,"a");
$identificacao=$log_datahora_ini.$acao;
fwrite($arquivo,$identificacao."-ENTRADA->".json_encode($jsonEntrada)."\n");

$conteudoEntrada = json_encode($jsonEntrada);

    $progr = new chamaprogress();
    $retorno = $progr->executarprogress("crediario/app/1/filacredito",$conteudoEntrada);
    fwrite($arquivo,$identificacao."-RETORNO->".$retorno."\n");

    $jsonSaida = json_decode($retorno,true);
    
    fwrite($arquivo,$identificacao."-SAIDA->".json_encode($jsonSaida)."\n");

fclose($arquivo);
    
?>