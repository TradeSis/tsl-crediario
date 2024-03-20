<?php
// gabriel 22022023 16:00

include_once ('../conexao.php');

function buscaContratos($numeroContrato)
{
	$contrato = array();
	$retorno = array();
	$apiEntrada =
		array(
			"dadosEntrada" => array(
				array('numeroContrato' => $numeroContrato)
			)
		);
	$retorno = chamaAPI(null, '/crediario/contrato', json_encode($apiEntrada), 'GET');
	if (isset ($retorno["conteudoSaida"])) {
		if (isset ($retorno["conteudoSaida"]["contrato"])) {
			$contrato = $retorno["conteudoSaida"]["contrato"]; // TRATAMENTO DO RETORNO
		}
	}
	return $contrato;
}
function buscaAssinatura($contnum = null)
{
	$assinatura = array();
	$retorno = array();
	$apiEntrada =
		array(
			"dadosEntrada" => array(
				array('contnum' => $contnum)
			)
		);
	$retorno = chamaAPI(null, '/crediario/assinatura', json_encode($apiEntrada), 'GET');
	if (isset ($retorno["contrassin"])) {
		if (isset ($retorno["contrassin"])) {
			$assinatura = $retorno["contrassin"][0]; // TRATAMENTO DO RETORNO
		}
	}
	return $assinatura;
}


if (isset ($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao == "filtrar") {

		$contnum = $_POST["contnum"];
		$dtproc = $_POST["dtproc"];
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
		$_SESSION['filtro_contrassin'] = $apiEntrada['dadosEntrada'][0];
		$assinatura = chamaAPI(null, '/crediario/assinatura', json_encode($apiEntrada), 'GET');
		if (isset ($assinatura["contrassin"])) {
			if (isset ($assinatura["contrassin"])) {
				$assinatura = $assinatura["contrassin"]; // TRATAMENTO DO RETORNO
			}
		}
		echo json_encode($assinatura);
		return $assinatura;
	}

	if ($operacao == "processarAssinatura") {
		$contnum = isset($_POST["contnum"]) ? $_POST["contnum"] : null;

        if ($contnum == "") {
			$contnum = null;
		}

		$apiEntrada = 
		array("dadosEntrada" => array(
			array('numeroContrato' => $contnum)
		));
		$assinaturaProc = chamaAPI(null, '/crediario/assinaContrato', json_encode($apiEntrada), 'POST');
		if (isset ($assinaturaProc["conteudoSaida"])) {
			if (isset ($assinaturaProc["conteudoSaida"])) {
				$assinaturaProc = $assinaturaProc["conteudoSaida"][0]; // TRATAMENTO DO RETORNO
			}
		}
		echo json_encode($assinaturaProc);
		return $assinaturaProc;
	}
}

?>