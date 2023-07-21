<?php
// gabriel 27022023 13:51 - ajustado direcionamento para consulta de contrato
// gabriel 23022023 09:50

include_once '../head.php';
include_once '../database/crediariocliente.php';

if (isset($_GET['parametros'])) {
    $codigoCliente = $_POST['codigoCliente'];
    $cpfCNPJ = $_POST['cpfCNPJ'];
}
if (isset($_GET['codigoCliente'])) {
    $codigoCliente = $_GET['codigoCliente'];
}

if (empty($cpfCNPJ)) {
    $cpfCNPJ = null;
} 
$situacao = '';

$historico = buscaHistoricoCliente($codigoCliente, $cpfCNPJ, $situacao);
$cliente = $historico["cliente"][0];
$contratos = $historico["contratos"];

?>

<body class="bg-transparent">
    <div class="container-fluid mt-3">
        <div class="card">
            <div class="card-header border-1">
                <div class="row">
                    <div class="col-sm-10">
                        <h3 class="col">Cod.
                            <?php echo $cliente['codigoCliente'] ?> - <?php echo $cliente['nomeCliente'] ?>
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
                        <input type="text" class="form-control" value=" <?php echo $cliente['codigoCliente'] ?> - <?php echo $cliente['nomeCliente'] ?>" readonly>

                    </div>
                    <div class="col">
                        <label>CPF/CNPJ</label>
                        <input type="text" class="form-control" value="<?php echo $cliente['cpfCNPJ'] ?>" readonly>
                    </div>
                </div>
                <hr>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" style="color:blue" href="#">Abertos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="historico_clientepag.php?codigoCliente=<?php echo $codigoCliente ?>">Pagos</a>
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
                                <td class="text-center"><?php echo $contrato['codigoCliente'] ?></td>
                                <td class="text-center"><?php echo $contrato['numeroContrato'] ?></td>
                                <td class="text-center"><?php echo date('d/m/Y', strtotime($contrato['dtemissao'])) ?></td>
                                <td class="text-center"><?php echo date('d/m/Y', strtotime($contrato['dtProxVencimento'])) ?></td>
                                <td class="text-center"><?php echo number_format($contrato['valorTotal'], 2, ',', '.') ?></td>
                                <td class="text-center"><?php echo number_format($contrato['valorEntrada'], 2, ',', '.') ?></td>
                                <td class="text-center"><?php echo number_format($contrato['valorAberto'], 2, ',', '.') ?></td>
                                <td class="text-center"><?php echo $contrato['situacao'] ?></td>
                                <td class="text-center">
                                    <a class="btn btn-primary btn-sm" href="contratos.php?numeroContrato=<?php echo $contrato['numeroContrato'] ?>" role="button">Consultar</a>
                                </td>
                            </tr>
                        <?php } ?>

                    </table>
                </div>
            </div>
        </div>
    </div>



</body>

</html>