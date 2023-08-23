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
        $nome_pessoa = $_POST['nome_pessoa'];

        if ($codigoFilial == "") {
            $codigoFilial = null;
        }
        if ($nome_pessoa == "") {
            $nome_pessoa = null;
        }

        /*$IP = explode(".", $_SERVER['REMOTE_ADDR']);
        $IP = $IP[2]; */

        $apiEntrada = 
		array("dadosEntrada" => array(
			array('codigoFilial' => $codigoFilial,
                  'nome_pessoa' => $nome_pessoa)
		));

		$submissoes = chamaAPI(null, '/crediario/filacredito', json_encode($apiEntrada), 'GET');

        if (isset($submissoes["neuproposta"])) {
            $submissoes = $submissoes["neuproposta"]; // TRATAMENTO DO RETORNO
        }
        return $submissoes;
    }
}
?>