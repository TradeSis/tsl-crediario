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
        $submissoes = chamaAPI('http://10.145.0.233', "/bsweb/erp/neurotech/neuproposta.php?SAIDA=JSON&POR=VENDEDOR&FILIAL=$filial", null, 'GET');

        if (isset($submissoes["rows"])) {
            $submissoes = $submissoes["rows"]; // TRATAMENTO DO RETORNO
        }
        echo json_encode($submissoes);
        echo json_encode(chamaAPI('http://10.145.0.233', "/bsweb/erp/neurotech/neuproposta.php?SAIDA=JSON&POR=VENDEDOR&FILIAL=$filial", null, 'GET'));
        echo json_encode($submissoes);
        return $submissoes;
    }
}
?>