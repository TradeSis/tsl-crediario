<?php
// lucas 120320204 id884 bootstrap local - alterado head
// gabriel 23022023 09:50

include_once '../head.php';
include_once '../database/crediariocontrato.php';

if (isset ($_GET['parametros'])) {
    $numeroContrato = $_POST['numeroContrato'];
}
if (isset ($_GET['numeroContrato'])) {
    $numeroContrato = $_GET['numeroContrato'];
}


$contrato = buscaContratos($numeroContrato);
$contrato = $contrato[0];
$parcelas = $contrato["parcelas"];
$produtos = $contrato["produtos"];

$assinatura = buscaAssinatura($numeroContrato);

$urlFoto = "https://img.freepik.com/premium-vector/avatar-icon002_750950-52.jpg";
?>

<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>


<body>
    <div class="card container-fluid mt-2">
        <div class="row mt-3"> <!-- LINHA SUPERIOR A TABLE -->
            <div class="col-3">
                <!-- TITULO -->
                <h3 class="col">Contrato
                    <?php echo $contrato['numeroContrato'] ?>
                </h3>
            </div>
            <div class="col-7">
                <!-- FILTROS -->
            </div>

            <div class="col-2 text-end">
                <?php if (isset ($_GET['numeroContrato'])) { ?>
                    <a href="historico_cliente.php?codigoCliente=<?php echo $contrato['codigoCliente'] ?>" role="button"
                        class="btn btn-primary btn-sm">Voltar</a>
                <?php } else { ?>
                    <a href="#" onclick="history.back()" role="button" class="btn btn-primary btn-sm">Voltar</a>
                <?php } ?>
            </div>
        </div>
        <div class="container-fluid mt-3">
            <div id="ts-tabs">
                <div class="tab whiteborder" id="tab-nfe">Dados Contrato</div>
                <div class="tab" id="tab-parcela">Parcelas</div>
             <!--  *****Produtos comentados at� possuir dados reais
                   <div class="tab" id="tab-produ">Produtos</div> -->
                <div class="tab" id="tab-assinatura">Assinatura</div>

                <div class="line"></div>

                <div class="tabContent">
                    <!-- *****************NOTAFISCAL***************** -->
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
                            <input type="text" class="form-control"
                                value="FILIAL <?php //echo $contrato['loja']            ?>" readonly>
                        </div>
                        <div class="col">
                            <label>Data Inicial</label>
                            <input type="text" class="form-control"
                                value="<?php echo date('d/m/Y', strtotime($contrato['dtemissao'])) ?>" readonly>
                            <label>Situação</label>
                            <input type="text" class="form-control" value="<?php echo $contrato['situacao'] ?>"
                                readonly>
                            <label>Modalidade</label>
                            <input type="text" class="form-control" value="<?php echo $contrato['modalidade'] ?>"
                                readonly>
                        </div>
                    </div>
                    <div class="row">
                        <h6>Valores</h6>
                        <div class="col-md">
                            <label>Total</label>
                            <input type="text" class="form-control" value="<?php echo $contrato['valorTotal'] ?>"
                                readonly>
                        </div>
                        <div class="col-md">
                            <label>Aberto</label>
                            <input type="text" class="form-control" value="<?php echo $contrato['valorAberto'] ?>"
                                readonly>
                        </div>
                        <div class="col-md">
                            <label>Vencido</label>
                            <input type="text" class="form-control" value="<?php echo $contrato['valorVencido'] ?>"
                                readonly>
                        </div>
                        <div class="col-md">
                            <label>Entrada</label>
                            <input type="text" class="form-control" value="<?php echo $contrato['valorEntrada'] ?>"
                                readonly>
                        </div>
                    </div>
                </div>

                <div class="tabContent">
                    <!-- *****************Parcelas Contrato***************** -->
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

               <!--  *****Produtos comentados at� possuir dados reais
                <div class="tabContent">
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
                </div> -->

                <div class="tabContent">
                    <div class="row">
                        <div class="col">
                            <div class="col-md">
                                <label>etbcod</label>
                                <input type="text" class="form-control" value="<?php echo $assinatura['etbcod'] ?>"
                                    readonly>
                            </div>
                            <label>contnum</label>
                            <input type="text" class="form-control" value="<?php echo $assinatura['contnum'] ?>"
                                readonly>
                            <label>idBiometria</label>
                            <input type="text" class="form-control" value="<?php echo $assinatura['idBiometria'] ?>"
                                readonly>
                        </div>
                        <div class="col text-center">
                            <img src="<?php echo $urlFoto ?>" class="img-fluid" alt="Image Preview"
                                style="max-width: 150px; max-height: 150px;">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md">
                            <label>Data de Inclusão</label>
                            <input type="text" class="form-control"
                                value="<?php echo date('d/m/Y', strtotime($assinatura['dtinclu'])) ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label>Data de Processamento</label>
                            <input type="text" class="form-control"
                                value="<?php echo $assinatura['dtproc'] !== null ? date('d/m/Y', strtotime($assinatura['dtproc'])) : null ?>"
                                readonly>
                        </div>
                        <div class="col-md">
                            <label>hrproc</label>
                            <input type="text" class="form-control" value="<?php echo $assinatura['hrproc'] ?>"
                                readonly>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md">
                            <label>etbcod</label>
                            <input type="text" class="form-control" value="<?php echo $assinatura['etbcod'] ?>"
                                readonly>
                        </div>
                        <div class="col-md">
                            <label>cxacod</label>
                            <input type="text" class="form-control" value="<?php echo $assinatura['cxacod'] ?>"
                                readonly>
                        </div>
                        <div class="col-md">
                            <label>ctmcod</label>
                            <input type="text" class="form-control" value="<?php echo $assinatura['ctmcod'] ?>"
                                readonly>
                        </div>
                        <div class="col-md">
                            <label>nsu</label>
                            <input type="text" class="form-control" value="<?php echo $assinatura['nsu'] ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label>clicod</label>
                            <input type="text" class="form-control" value="<?php echo $assinatura['clicod'] ?>"
                                readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        var tab;
        var tabContent;

        window.onload = function () {
            tabContent = document.getElementsByClassName('tabContent');
            tab = document.getElementsByClassName('tab');
            hideTabsContent(1);

            var urlParams = new URLSearchParams(window.location.search);
            var id = urlParams.get('id');
            if (id === 'parcela') {
                showTabsContent(1);
            }
            /* if (id === 'produ') {
                showTabsContent(2);
            } */
            if (id === 'assinatura') {
                showTabsContent(2);
            }
        }

        document.getElementById('ts-tabs').onclick = function (event) {
            var target = event.target;
            if (target.className == 'tab') {
                for (var i = 0; i < tab.length; i++) {
                    if (target == tab[i]) {
                        showTabsContent(i);
                        break;
                    }
                }
            }
        }

        function hideTabsContent(a) {
            for (var i = a; i < tabContent.length; i++) {
                tabContent[i].classList.remove('show');
                tabContent[i].classList.add("hide");
                tab[i].classList.remove('whiteborder');
            }
        }

        function showTabsContent(b) {
            if (tabContent[b].classList.contains('hide')) {
                hideTabsContent(0);
                tab[b].classList.add('whiteborder');
                tabContent[b].classList.remove('hide');
                tabContent[b].classList.add('show');
            }
        }

    </script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>