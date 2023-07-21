<?php
// gabriel 22022023 16:00

include_once('../conexao.php');

function buscaHistoricoCliente($codigoCliente,$cpfCNPJ,$situacao)
{
	$historico = array();
	$retorno = array();
	$apiEntrada = 
		array("cliente" => array(
			array('codigoCliente' => $codigoCliente,
				  'cpfCNPJ' => $cpfCNPJ,
				  'situacao' => $situacao)
		));
	

	$retorno = chamaAPI(null, '/crediario/cliente', json_encode($apiEntrada), 'GET');

   
	if (isset($retorno["conteudoSaida"])) {
		if (isset($retorno["conteudoSaida"]["cliente"])) {
        	$historico = $retorno["conteudoSaida"]; // TRATAMENTO DO RETORNO
		}
	}

	return $historico;
}



?>