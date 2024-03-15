<?php
// lucas 120320204 id884 bootstrap local - alterado head
// gabriel 27022023 13:51

include_once '../head.php';
include_once '../database/crediariocliente.php';

$codigoCliente = $_GET['codigoCliente'];

if (empty($cpfCNPJ)) {
    $cpfCNPJ = null;
} // Se estiver vazio, coloca null

$situacao = 'PAG';

$historico = buscaHistoricoCliente($codigoCliente, $cpfCNPJ, $situacao);
$cliente = $historico["cliente"][0];
$contratos = $historico["contratos"];

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body class="bg-transparent">
    <div class="container-fluid mt-3">
        <div class="card">
            <div class="card-header border-1">
                <div class="row">
                    <div class="col-sm-10">
                        <h3 class="col">Cod.
                            <?php echo $cliente['codigoCliente'] ?> -
                            <?php echo $cliente['nomeCliente'] ?>
                        </h3>
                    </div>
                    <div class="col-sm" style="text-align:right">
                        <a href="historico_parametros.php" role="button" class="btn btn-primary btn-sm">Voltar</a>
                    </div>
                </div>
            </div>


            <div class="container-fluid">
                <h5>Dados Cliente</h5>
                <div class="row">
                    <div class="col">
                        <label>Código Cliente</label>
                        <input type="text" class="form-control"
                            value=" <?php echo $cliente['codigoCliente'] ?> - <?php echo $cliente['nomeCliente'] ?>"
                            readonly>

                    </div>
                    <div class="col">
                        <label>CPF/CNPJ</label>
                        <input type="text" class="form-control" value="<?php echo $cliente['cpfCNPJ'] ?>" readonly>
                    </div>
                </div>
                <hr>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active"
                            href="historico_cliente.php?codigoCliente=<?php echo $cliente['codigoCliente'] ?>">Abertos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" style="color:blue" href="#">Pagos</a>
                    </li>
                </ul>
                <h5>Contratos</h5>
                <div class="table table-responsive">
                    <table class="table table-sm table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Cliente</th>
                                <th class="text-center">Contrato</th>
                                <th class="text-center">Dt Emissão</th>
                                <th class="text-center">Dt Venc</th>
                                <th class="text-center">Valor Total</th>
                                <th class="text-center">Valor Entrada</th>
                                <th class="text-center">Valor Aberto</th>
                                <th class="text-center">Situação</th>
                                <th class="text-center">Parcelas</th>
                            </tr>
                        </thead>
                        <?php foreach ($contratos as $contrato) { ?>
                            <tr>
                                <td class="text-center">
                                    <?php echo $contrato['codigoCliente'] ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $contrato['numeroContrato'] ?>
                                </td>
                                <td class="text-center">
                                    <?php echo date('d/m/Y', strtotime($contrato['dtemissao'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                    if ($contrato['dtProxVencimento'] !== null) {
                                        echo date('d/m/Y', strtotime($contrato['dtProxVencimento']));
                                    } ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $contrato['valorTotal'] ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $contrato['valorEntrada'] ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $contrato['valorAberto'] ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $contrato['situacao'] ?>
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-primary btn-sm"
                                        href="contratos.php?numeroContrato=<?php echo $contrato['numeroContrato'] ?>"
                                        role="button"><i class="bi bi-eye-fill"></i></a>
                                </td>
                            </tr>
                        <?php } ?>

                    </table>
                </div>
            </div>
        </div>
    </div>


<!-- LOCAL PARA COLOCAR OS JS -->

<?php include_once ROOT . "/vendor/footer_js.php"; ?>

<!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>