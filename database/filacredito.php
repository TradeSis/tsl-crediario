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

    if ($operacao == "buscar") {
        $filial = $_POST['codigoFilial'];
        $filial = str_replace(array(' ', "'", '"'), '', $filial);

        $apiUrl = "http://10.145.0.233/bsweb/erp/neurotech/neuproposta.php?SAIDA=JSON&POR=VENDEDOR&FILIAL=$filial";

        $submissoes = chamaAPI('http://10.145.0.233', $apiUrl, null, 'GET');

        if (isset($submissoes["rows"])) {
            $submissoes = $submissoes["rows"]; // TRATAMENTO DO RETORNO
        }
        echo json_encode($submissoes);
        return $submissoes;
    }
}
?>