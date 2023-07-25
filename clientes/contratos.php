<?php
// gabriel 23022023 09:50

include_once '../head.php';
include_once '../database/crediariocontrato.php';

if (isset($_GET['parametros'])) {
    $numeroContrato = $_POST['numeroContrato'];
}
if (isset($_GET['numeroContrato'])) {
    $numeroContrato = $_GET['numeroContrato'];
}


$contrato = buscaContratos($numeroContrato);
$contrato = $contrato[0];
$parcelas = $contrato["parcelas"];
$produtos = $contrato["produtos"];
?>

<body class="bg-transparent">
    <div class="container-fluid mt-3">
        <div class="card">
            <div class="card-header border-1">
                <div class="row">
                    <div class="col-sm-10">
                        <h3 class="col">Contrato
                            <?php echo $contrato['numeroContrato'] ?>
                        </h3>
                    </div>
                    <div class="col-sm" style="text-align:right">
                        <?php if (isset($_GET['numeroContrato'])) { ?>
                            <a href="historico_cliente.php?codigoCliente=<?php echo $contrato['codigoCliente'] ?>"
                                role="button" class="btn btn-primary btn-sm">Voltar</a>
                        <?php } else { ?>
                            <a href="#" onclick="history.back()" role="button" class="btn btn-primary btn-sm">Voltar</a>
                        <?php } ?>
                    </div>
                </div>
            </div>


            <div class="container-fluid">

                <div class="row">
                    <div class="col">
                        <label>Contrato</label>
                        <input type="text" class="form-control" value="<?php echo $contrato['numeroContrato'] ?>"
                            readonly>
                        <label>Cliente</label>
                        <input type="text" class="form-control"
                            value="<?php echo $contrato['codigoCliente'] ?> - <?php echo $contrato['nomeCliente'] ?>"
                            readonly>
                        <label>Loja</label>
                        <input type="text" class="form-control" value="FILIAL <?php //echo $contrato['loja'] ?>" readonly>
                    </div>
                    <div class="col">
                        <label>Data Inicial</label>
                        <input type="text" class="form-control" value="<?php echo date('d/m/Y', strtotime($contrato['dtemissao'])) ?>" readonly>
                        <label>Situação</label>
                        <input type="text" class="form-control" value="<?php echo $contrato['situacao'] ?>" readonly>
                        <label>Modalidade</label>
                        <input type="text" class="form-control" value="<?php echo $contrato['modalidade'] ?>" readonly>
                    </div>
                </div>
                <hr>
                <div>
                    <div class="row">
                        <div class="col-4 mb-3">
                            <label>Total</label>
                            <input type="text" class="form-control" value="<?php echo $contrato['valorTotal'] ?>"
                                readonly>
                            <label>Aberto</label>
                            <input type="text" class="form-control" value="<?php echo $contrato['valorAberto'] ?>"
                                readonly>
                            <label>Vencido</label>
                            <input type="text" class="form-control" value="<?php echo $contrato['valorVencido'] ?>"
                                readonly>
                            <label>Entrada</label>
                            <input type="text" class="form-control" value="<?php echo $contrato['valorEntrada'] ?>"
                                readonly>

                        </div>
                        <div class="col-8">
                            <div class="table table-responsive">
                                <table class="table table-sm table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Contrato</th>
                                            <th class="text-center">Parc</th>
                                            <th class="text-center">Dt Venc</th>
                                            <th class="text-center">Vl Parc</th>
                                            <th class="text-center">Situação</th>
                                            <th class="text-center">Dt Pag</th>
                                            <th class="text-center">Vl Pag</th>
                                        </tr>
                                    </thead>
                                    <?php foreach ($parcelas as $parcela) { ?>
                                        <tr>
                                            <td class="text-center">
                                                <?php echo $parcela['numeroContrato'] ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo $parcela['parcela'] ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo date('d/m/Y', strtotime($parcela['dtVencimento'])) ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo $parcela['vlrParcela'] ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo $parcela['situacao'] ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($parcela['dtPagamento'] !== null) {
                                                    echo date('d/m/Y', strtotime($parcela['dtPagamento']));
                                                } ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo $parcela['vlrPago'] ?>
                                            </td>
                                        </tr>
                                    <?php } ?>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <h5>Produtos</h5>
                <div class="table table-responsive">
                    <table class="table table-sm table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Código</th>
                                <th class="text-center">Nome</th>
                                <th class="text-center">Preço</th>
                                <th class="text-center">Quantidade</th>
                                <th class="text-center">Valor Total</th>
                            </tr>
                        </thead>
                        <?php foreach ($produtos as $produto) { ?>
                            <tr>
                                <td class="text-center">
                                    <?php echo $produto['codigoProduto'] ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $produto['nomeProduto'] ?>
                                </td>
                                <td class="text-center">
                                    <?php echo number_format($produto['precoVenda'], 2, ',', '.') ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $produto['quantidade'] ?>
                                </td>
                                <td class="text-center">
                                    <?php echo number_format($produto['valorTotal'], 2, ',', '.') ?>
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