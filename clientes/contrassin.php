<?php
include_once '../head.php';


$dtproc = null;
if (isset($_SESSION['filtro_contrassin'])) {
  $filtroEntrada = $_SESSION['filtro_contrassin'];
  $dtproc = $filtroEntrada['dtproc'];
}
?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body>
    <div class="container-fluid">

        <div class="row ">
            <!--<BR> MENSAGENS/ALERTAS -->
        </div>
        <div class="row">
            <!-- <BR>  BOTOES AUXILIARES -->
        </div>
        <div class="row d-flex align-items-center justify-content-center mt-1 pt-1 ">

            <div class="col-5 col-lg-5">
                <h2 class="ts-tituloPrincipal">Assinatura</h2>
            </div>

            <div class="col-3 col-lg-3">
                <div class="input-group">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#periodoModal"><i class="bi bi-calendar3"></i></button>
                    <a onClick="naoproc()" role=" button" class="ms-4 btn btn-sm btn-info">Não Processados</a>
                </div>
            </div>

            <div class="col-4 col-lg-4">
                <div class="input-group">
                    <input type="text" class="form-control ts-input" id="contnum" placeholder="Buscar Contrato">
                    <button class="btn btn-primary rounded" type="button" id="buscar"><i
                            class="bi bi-search"></i></button>
                </div>
            </div>

        </div>

        <div class="table mt-2 ts-divTabela ts-tableFiltros text-center">
            <table class="table table-sm table-hover">
                <thead class="ts-headertabelafixo">
                    <tr>
                        <th>Filial</th>
                        <th>Contrato</th>
                        <th>Cliente</th>
                        <th>Nome</th>
                        <th>Cpf/Cnpj</th>
                        <th class="col-3">ID Biometria</th>
                        <th>Data</th>
                        <th colspan="2">Ação</th>
                    </tr>
                </thead>

                <tbody id='dados' class="fonteCorpo">

                </tbody>
            </table>
        </div>
    </div>

     <!--------- FILTRO PERIODO --------->
    <?php include_once 'modal_periodo.php' ?>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        buscar($("#contnum").val(), $("#dtproc").val());

        function naoproc() {
            buscar(null, null);
        }

        function buscar(contnum, dtproc) {
            //alert (buscaPessoa);
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '../database/crediariocontrato.php?operacao=filtrar',
                beforeSend: function () {
                    $("#dados").html("Carregando...");
                },
                data: {
                    contnum: contnum,
                    dtproc: dtproc
                },
                success: function (msg) {
                    //alert("segundo alert: " + msg);
                    var json = JSON.parse(msg);

                    var linha = "";
                    for (var $i = 0; $i < json.length; $i++) {
                        var object = json[$i];

                        linha = linha + "<tr>";
                        linha = linha + "<td>" + object.etbcod + "</td>";
                        linha = linha + "<td>" + object.contnum + "</td>";
                        linha = linha + "<td>" + object.clicod + "</td>";
                        linha = linha + "<td>" + object.nomeCliente + "</td>";
                        linha = linha + "<td>" + object.cpfCNPJ + "</td>";
                        linha = linha + "<td>" + object.idBiometria + "</td>";
                        linha = linha + "<td>" + (object.dtinclu ? formatarData(object.dtinclu) : "--") + "</td>";
                        linha = linha + "<td>" + "<a class='btn btn-primary btn-sm' href='contratos.php?numeroContrato=" + object.contnum + "' role='button'><i class='bi bi-eye-fill'></i></a>";
                        if (!object.dtproc) {
                            linha = linha + "<button type='button' class='btn btn-warning btn-sm processar-btn' data-contnum='" + object.contnum + "' title='Processar Assinatura'><i class='bi bi-check-circle-fill'></i></button>";
                        }
                        linha = linha + "</tr>";
                    }
                    $("#dados").html(linha);
                }
            });
        }

        $("#buscar").click(function () {
            buscar($("#contnum").val(), $("#dtproc").val());
        })
        $(document).ready(function() {
            $("#filtrarButton").click(function() {

                buscar($("#contnum").val(), $("#dtproc").val());
                $('#periodoModal').modal('hide');

            });

            $('.processar-btn').click(function () {
                $('body').css('cursor', 'progress');
                var contnum = $(this).attr("data-contnum");

                $.ajax({
                    method: "POST",
                    dataType: 'json',
                    url: "../database/contratos.php?operacao=processarAssinatura",
                    data: { contnum: contnum },
                    success: function (msg) {
                        $('body').css('cursor', 'default');
                        if (msg.retorno === "ok") {
                            window.location.reload();
                        }
                        if (msg.status === 400) {
                            alert(msg.retorno);
                            window.location.reload();
                        }
                    }
                });
            });
        });

        document.addEventListener("keypress", function (e) {
            if (e.key === "Enter") {
                buscar($("#contnum").val(), $("#dtproc").val());
            }
        });

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