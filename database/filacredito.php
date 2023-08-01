<?php
// gabriel 260723

include_once('../conexao.php');

function buscaFiliais()
{
    $filiais = array();
    $filiais = chamaAPI('http://10.145.0.233', '/bsweb/erp/zoom/estab.php?POR=MEUIP', null, 'GET');
    return $filiais;
}

if (isset($_GET['operacao'])) {
    $operacao = $_GET['operacao'];

    /*if ($operacao == "buscar") {
        if (isset($_POST['codigoFilial'])) {
            $filial = $_POST['codigoFilial'];
            $apiUrl = '/bsweb/erp/neurotech/neuproposta.php?SAIDA=JSON&POR=VENDEDOR&FILIAL=' . $filial;
        } else {
            $apiUrl = '/bsweb/erp/neurotech/neuproposta.php?SAIDA=JSON&POR=VENDEDOR&FILIAL=';
        }

        $submissoes = chamaAPI('http://10.145.0.233', $apiUrl, null, 'GET');

        if (isset($submissoes["rows"])) {
            $submissoes = $submissoes["rows"]; // TRATAMENTO DO RETORNO
        }
        echo json_encode($submissoes);
        return $submissoes;
    } */

    if ($operacao == "buscar") {

        $codigoFilial = $_POST['codigoFilial'];
        $dtinclu = $_POST['dtinclu'];
        $cpfcnpj = $_POST['cpfcnpj'];
        $clicod = $_POST['clicod'];
        $nome_pessoa = $_POST['nome_pessoa'];
        $etbcad = $_POST['etbcad'];
        $sit_credito = $_POST['sit_credito'];
        $tipoconsulta = $_POST['tipoconsulta'];

        if ($codigoFilial == "") {
            $codigoFilial = null;
        }
        if ($dtinclu == "") {
            $dtinclu = null;
        }
        if ($cpfcnpj == "") {
            $cpfcnpj = null;
        }
        if ($clicod == "") {
            $clicod = null;
        }
        if ($nome_pessoa == "") {
            $nome_pessoa = null;
        }
        if ($etbcad == "") {
            $etbcad = null;
        }
        if ($sit_credito == "") {
            $sit_credito = null;
        }
        if ($tipoconsulta == "") {
            $tipoconsulta = null;
        }

        /*$IP = explode(".", $_SERVER['REMOTE_ADDR']);
        $IP = $IP[2]; */

        $parametros = array(
			'IP' => $_SERVER['REMOTE_ADDR'],
			'codigoFilial' => $codigoFilial,
			'dtinclu' => $dtinclu,
			'cpfcnpj' => $cpfcnpj,
			'clicod' => $clicod,
			'nome_pessoa' => $nome_pessoa,
			'etbcad' => $etbcad,
			'sit_credito' => $sit_credito,
			'tipoconsulta' => $tipoconsulta
		);

        $apiEntrada = array(
			'Entrada' => array($parametros)
		);

		$submissoes = chamaAPI(null, '/crediario/filacredito', json_encode($apiEntrada), 'GET');

		echo json_encode($submissoes);
		return $submissoes;
    }
}
?>