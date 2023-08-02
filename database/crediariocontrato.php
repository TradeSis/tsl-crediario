<?php
// gabriel 22022023 16:00

include_once('../conexao.php');

function buscaContratos($numeroContrato)
{
	$contrato = array();
	$retorno = array();
	$apiEntrada = 
		array("dadosEntrada" => array(
			array('numeroContrato' => $numeroContrato)
		));
	

	$retorno = chamaAPI(null, '/crediario/contrato', json_encode($apiEntrada), 'GET');

   
	if (isset($retorno["conteudoSaida"])) {
		if (isset($retorno["conteudoSaida"]["contrato"])) {
        	$contrato = $retorno["conteudoSaida"]["contrato"]; // TRATAMENTO DO RETORNO
		}
	}

	return $contrato;
}



?>