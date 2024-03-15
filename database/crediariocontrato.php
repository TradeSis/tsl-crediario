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
function buscaContrassin($contnum=null, $dtproc=null)
{
	$contrassin = array();
	$retorno = array();
	$apiEntrada = 
		array("dadosEntrada" => array(
			array('contnum' => $contnum,
				  'dtproc' => $dtproc)
		));
	

	$retorno = chamaAPI(null, '/crediario/contrassin', json_encode($apiEntrada), 'GET');

   
	if (isset($retorno["contrassin"])) {
		if (isset($retorno["contrassin"])) {
        	$contrassin = $retorno["contrassin"]; // TRATAMENTO DO RETORNO
		}
	}
	return $contrassin;
}


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao == "filtrar") {

		$contnum = isset($_POST["contnum"]) ? $_POST["contnum"] : null;
		$dtproc = isset($_POST["dtproc"]) ? $_POST["dtproc"] : null;

		if ($contnum == "") {
			$contnum = null;
		}
		if ($dtproc == "") {
			$dtproc = null;
		}

		$apiEntrada = 
			array("dadosEntrada" => array(
				array('contnum' => $contnum,
					'dtproc' => $dtproc)
			));

			$contrassin = chamaAPI(null, '/crediario/contrassin', json_encode($apiEntrada), 'GET');

			if (isset($contrassin["contrassin"])) {
				if (isset($contrassin["contrassin"])) {
					$contrassin = $contrassin["contrassin"]; // TRATAMENTO DO RETORNO
				}
			}
			echo json_encode($contrassin);
			return $contrassin;
	}
}

?>