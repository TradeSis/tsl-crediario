<?php
// gabriel 260723

include_once('../conexao.php');


$vfilial = explode(".", $_SERVER['REMOTE_ADDR']);
if ($vfilial[0] == 172 or $vfilial[0] == 192) {
    if ($vfilial[1] == 17 or $vfilial[1] == 23 or $vfilial[1] == 168) {
        $numeroFilial == $vfilial[2];
        function buscaFiliais($numeroFilial)
        {
            $filiais = array();
            $apiEntrada =
                array(
                    "dadosEntrada" => array(
                        array('numeroFilial' => $numeroFilial)
                    )
                );
            $filiais = chamaAPI(null, '/crediario/estab', json_encode($apiEntrada), 'GET');
            return $filiais;
        }
    }
} else {
    function buscaFiliais()
    {
        $filiais = array();
        $filiais = chamaAPI(null, '/crediario/estab', null, 'GET');
        return $filiais;
    }
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
            array(
                "dadosEntrada" => array(
                    array(
                        'codigoFilial' => $codigoFilial,
                        'nome_pessoa' => $nome_pessoa
                    )
                )
            );

        $submissoes = chamaAPI(null, '/crediario/filacredito', json_encode($apiEntrada), 'GET');

        if (isset($submissoes["neuproposta"])) {
            $submissoes = $submissoes["neuproposta"];
        }
        echo json_encode($submissoes);
        return $submissoes;

    }
}
?>