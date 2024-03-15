<?php
// lucas 120320204 id884 bootstrap local - alterado head
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
                <?php if (isset($_GET['numeroContrato'])) { ?>
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
                <div class="tab" id="tab-produ">Produtos</div>
                <div class="tab" id="tab-contrassin">Assinatura</div>

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
                            <input type="text" class="form-control" value="FILIAL <?php //echo $contrato['loja']      ?>"
                                readonly>
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
                            <label class="form-label ts-label">Total</label>
                            <input type="text" class="form-control" value="<?php echo $contrato['valorTotal'] ?>"
                                readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">Aberto</label>
                            <input type="text" class="form-control" value="<?php echo $contrato['valorAberto'] ?>"
                                readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">Vencido</label>
                            <input type="text" class="form-control" value="<?php echo $contrato['valorVencido'] ?>"
                                readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">Entrada</label>
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
                </div>

                <div class="tabContent">
                    <h5>Contrassin</h5>
                    <div class="table mt-2 ts-divTabela ts-tableFiltros text-center">
                        <table class="table table-sm table-hover">
                            <thead class="ts-headertabelafixo">
                                <tr class="ts-headerTabelaLinhaCima">
                                    <th>Contrato</th>
                                    <th>ID</th>
                                    <th>dtinclu</th>
                                    <th class="col-2">dtproc</th>
                                    <th>hrproc</th>
                                    <th>etbcod</th>
                                    <th>cxacod</th>
                                    <th>ctmcod</th>
                                    <th>nsu</th>
                                    <th>clicod</th>
                                </tr>
                            </thead>

                            <tbody id='dadosContrassin' class="fonteCorpo">

                            </tbody>
                        </table>
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
            if (id === 'produ') {
                showTabsContent(2);
            }
            if (id === 'contrassin') {
                showTabsContent(3);
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

        buscar(<?php echo $contrato['numeroContrato'] ?>);

        function buscar(contnum) {
            //alert (buscaPessoa);
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '../database/crediariocontrato.php?operacao=filtrar',
                beforeSend: function () {
                    $("#dados").html("Carregando...");
                },
                data: {
                    contnum: contnum
                },
                success: function (msg) {
                    //alert("segundo alert: " + msg);
                    var json = JSON.parse(msg);

                    var linha = "";
                    for (var $i = 0; $i < json.length; $i++) {
                        var object = json[$i];

                        linha = linha + "<tr>";
                        linha = linha + "<td>" + object.contnum + "</td>";
                        linha = linha + "<td>" + object.idBiometria + "</td>";
                        linha = linha + "<td>" + (object.dtinclu ? formatarData(object.dtinclu) : "--") + "</td>";
                        linha = linha + "<td>" + (object.dtproc ? formatarData(object.dtproc) : "--") + "</td>";
                        linha = linha + "<td>" + object.hrproc + "</td>";
                        linha = linha + "<td>" + object.etbcod + "</td>";
                        linha = linha + "<td>" + object.cxacod + "</td>";
                        linha = linha + "<td>" + object.ctmcod + "</td>";
                        linha = linha + "<td>" + object.nsu + "</td>";
                        linha = linha + "<td>" + object.clicod + "</td>";

                        linha = linha + "</tr>";
                    }
                    $("#dadosContrassin").html(linha);
                }
            });
        }
        function formatarData(data) {
            var parts = data.split('-');
            var year = parseInt(parts[0], 10);
            var month = parseInt(parts[1], 10) - 1; 
            var day = parseInt(parts[2], 10);

            var d = new Date(Date.UTC(year, month, day));

            var dia = d.getUTCDate().toString().padStart(2, '0');
            var mes = (d.getUTCMonth() + 1).toString().padStart(2, '0');
            var ano = d.getUTCFullYear();

            return dia + '/' + mes + '/' + ano;
        }
    </script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>