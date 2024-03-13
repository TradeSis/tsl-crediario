<?php
// lucas 120320204 id884 bootstrap local - alterado head

include_once '../head.php';
include_once '../database/filacredito.php';

$IP = $_SERVER['REMOTE_ADDR'];

$vfilial = explode(".", $IP);
$codigoFilial = null;

if ($vfilial[0] == 172 || $vfilial[0] == 192) {
    if ($vfilial[1] == 17 || $vfilial[1] == 23 || $vfilial[1] == 168) {
        $codigoFilial = $vfilial[2];
        $filiais = buscaFiliais($codigoFilial);
        $filiais = $filiais[0];

    }
} else {

    if ($IP == "10.146.0.15" && URLROOT == "/tslebes" && $_SERVER['SERVER_ADDR'] == "10.145.0.60") { // Simulacao da 188 no servidor winjump
        $codigoFilial = 188;
        $filiais = buscaFiliais($codigoFilial);
        $filiais = $filiais[0];
    } else {
        $filiais = buscaFiliais();
    }

}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body class="bg-transparent">
    <div class="container-fluid text-center mt-4">
        <div class="row">
            <div class="col-sm-2">
                <p class="tituloTabela">Fila Credito (
                    <?php echo $IP ?>)
                </p>
            </div>
            <div class="col-sm-2" style="margin-top:-10px;">
                <div class="input-group">
                    <form action="" method="post">
                        <?php if (isset($codigoFilial)) { ?>
                            <input type="text" class="form-control" value="<?php echo $filiais['value'] ?>" readonly>
                            <input type="number" class="form-control" value="<?php echo $filiais['id'] ?>"
                                name="codigoFilial" id="FiltroFilial" hidden>
                        <?php } else { ?>
                            <select class="form-control text-center" name="codigoFilial" id="FiltroFilial"
                                autocomplete="off">
                                <option value="<?php echo null ?>">
                                    <?php echo "Selecione a Filial" ?>
                                </option>
                                <?php foreach ($filiais as $filial) { ?>
                                    <option value="<?php echo $filial['id'] ?>">
                                        <?php echo $filial['value'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        <?php } ?>
                    </form>
                </div>
            </div>
            <div class="col-sm-4" style="margin-top:-10px;">
                <div class="input-group">
                    <input type="text" class="form-control" id="FiltroNome_pessoa" placeholder="Buscar cliente...">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" id="buscar" type="button" style="margin-top:10px;">
                            <span style="font-size: 20px;font-family: 'Material Symbols Outlined'!important;"
                                class="material-symbols-outlined">search</span>
                        </button>
                    </span>
                </div>
            </div>
            <div class="col-sm-1" style="margin-top:-10px;">
                <form class="d-flex" action="" method="post" style="text-align: right;">
                    <select class="form-control" name="exportoptions" id="exportoptions">
                        <option value="excel">Excel</option>
                        <option value="pdf">PDF</option>
                        <option value="csv">csv</option>
                    </select>
                </form>
            </div>
            <div class="col-sm-1" style="margin-left:-30px;">
                <button class="btn btn-warning" id="export" name="export" type="submit">Gerar</button>
            </div>
        </div>

        <div class="card mt-2">
            <div class="table table-sm table-hover table-striped table-wrapper-scroll-y my-custom-scrollbar diviFrame">
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">Loja</th>
                            <th class="text-center">Data</th>
                            <th class="text-center">Hora</th>
                            <th class="text-center">Cpf</th>
                            <th class="text-center">Codigo</th>
                            <th class="text-center">Cliente</th>
                            <th class="text-center">Lj_Cad</th>
                            <th class="text-center">Sit</th>
                            <th class="text-center">Vcto_limite</th>
                            <th class="text-center">limite</th>
                            <th class="text-center">TC</th>
                            <th class="text-center">Operacao</th>
                            <th class="text-center">Resultado</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th style="width: 10%;">
                                <input type="date" class="form-control text-center" id="FiltroDtinclu"
                                    style="font-size: 14px; font-style:italic;margin-left:10px;margin-top:-5px;margin-bottom:-6px;width:130px;"
                                    name="dtinclu" autocomplete="off">
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id='dados' class="fonteCorpo">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- LOCAL PARA COLOCAR OS JS -->

<?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        
        if ($("#FiltroFilial").val() == "") {
            $("#dados").html("Selecione Filial...");
        } else {
            buscar($("#FiltroFilial").val(), $("#FiltroNome_pessoa").val(), $("#FiltroDtinclu").val());
        }

        function buscar(codigoFilial, nome_pessoa, dtinclu) {
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '../database/filacredito.php?operacao=buscar',
                beforeSend: function () {
                    $("#dados").html("Carregando...");
                },
                data: {
                    codigoFilial: codigoFilial,
                    nome_pessoa: nome_pessoa,
                    dtinclu: dtinclu
                },
                success: function (response) {
                    var json = JSON.parse(response);
                    var linha = "";
                    for (var $i = 0; $i < json.length; $i++) {
                        var object = json[$i];

                        var dtinclu = new Date(object.dtinclu);
                        dtincluForm = (`${dtinclu.getUTCDate().toString().padStart(2, '0')}/${(dtinclu.getUTCMonth() + 1).toString().padStart(2, '0')}/${dtinclu.getUTCFullYear()}`);

                        var vctolimite = new Date(object.vctolimite);
                        vctolimiteForm = (`${vctolimite.getUTCDate().toString().padStart(2, '0')}/${(vctolimite.getUTCMonth() + 1).toString().padStart(2, '0')}/${vctolimite.getUTCFullYear()}`);

                        linha += "<tr>";
                        linha += "<td>" + object.etbcod + "</td>";
                        linha += "<td>" + dtincluForm + "</td>";
                        linha += "<td>" + object.hrinclu + "</td>";
                        linha += "<td>" + object.cpfcnpj + "</td>";
                        linha += "<td>" + object.clicod + "</td>";
                        linha += "<td>" + object.nome_pessoa + "</td>";
                        linha += "<td>" + object.etbcad + "</td>";
                        linha += "<td>" + object.sit_credito + "</td>";
                        linha += "<td>" + vctolimiteForm + "</td>";
                        linha += "<td>" + object.vlrlimite + "</td>";
                        linha += "<td>" + object.tipoconsulta + "</td>";
                        linha += "<td>" + object.neu_cdoperacao + "</td>";
                        linha += "<td>" + object.neu_resultado + "</td>";
                        linha += "</tr>";
                    }
                    $("#dados").html(linha);
                }
            });
        }

        $("#FiltroFilial").change(function () {
            buscar($("#FiltroFilial").val(), $("#FiltroNome_pessoa").val(), $("#FiltroDtinclu").val());
        });
        $("#FiltroDtinclu").change(function () {
            buscar($("#FiltroFilial").val(), $("#FiltroNome_pessoa").val(), $("#FiltroDtinclu").val());
        });
        $("#buscar").click(function () {
            buscar($("#FiltroFilial").val(), $("#FiltroNome_pessoa").val(), $("#FiltroDtinclu").val());
        });

        document.addEventListener("keypress", function (e) {
            if (e.key === "Enter") {
                buscar($("#FiltroFilial").val(), $("#FiltroNome_pessoa").val(), $("#FiltroDtinclu").val());
            }
        });


        //**************exporta excel 
        function exportToExcel() {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '../database/filacredito.php?operacao=buscar',
                data: {
                    codigoFilial: $("#FiltroFilial").val(),
                    nome_pessoa: $("#FiltroNome_pessoa").val(),
                    dtinclu: $("#FiltroDtinclu").val()
                },
                success: function (json) {
                    var excelContent =
                        "<html xmlns:x='urn:schemas-microsoft-com:office:excel'>" +
                        "<head>" +
                        "<meta charset='UTF-8'>" +
                        "</head>" +
                        "<body>" +
                        "<table>";

                    excelContent += "<tr><th>Loja</th><th>Data</th><th>Hora</th><th>Cpf</th><th>Codigo</th><th>Cliente</th><th>Lj_Cad</th><th>Sit</th><th>Vcto_limite</th><th>limite</th><th>TC</th><th>Operacao</th><th>Resultado</th></tr>";

                    for (var i = 0; i < json.length; i++) {
                        var object = json[i];
                        excelContent += "<tr><td>" + object.etbcod + "</td>" +
                            "<td>" + object.dtinclu + "</td>" +
                            "<td>" + object.hrinclu + "</td>" +
                            "<td>" + object.cpfcnpj + "</td>" +
                            "<td>" + object.clicod + "</td>" +
                            "<td>" + object.nome_pessoa + "</td>" +
                            "<td>" + object.etbcad + "</td>" +
                            "<td>" + object.sit_credito + "</td>" +
                            "<td>" + object.vctolimite + "</td>" +
                            "<td>" + object.vlrlimite + "</td>" +
                            "<td>" + object.tipoconsulta + "</td>" +
                            "<td>" + object.neu_cdoperacao + "</td>" +
                            "<td>" + object.neu_resultado + "</td></tr>";
                    }

                    excelContent += "</table></body></html>";

                    var excelBlob = new Blob([excelContent], {
                        type: 'application/vnd.ms-excel'
                    });
                    var excelUrl = URL.createObjectURL(excelBlob);
                    var link = document.createElement("a");
                    link.setAttribute("href", excelUrl);
                    link.setAttribute("download", "filacredito.xls");
                    document.body.appendChild(link);

                    link.click();

                    document.body.removeChild(link);
                },
                error: function (e) {
                    alert('Erro: ' + JSON.stringify(e));
                }
            });
        }
        //**************exporta csv
        function exportToCSV() {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '../database/filacredito.php?operacao=buscar',
                data: {
                    codigoFilial: $("#FiltroFilial").val(),
                    nome_pessoa: $("#FiltroNome_pessoa").val(),
                    dtinclu: $("#FiltroDtinclu").val()
                },
                success: function (json) {
                    var csvContent = "data:text/csv;charset=utf-8,\uFEFF";
                    csvContent += "Loja,Data,Hora,Cpf,Codigo,Cliente,Lj_Cad,Sit,Vcto_limite,limite,TC,Operacao,Resultado\n";

                    for (var i = 0; i < json.length; i++) {
                        var object = json[i];
                        csvContent += object.etbcod + "," +
                            object.dtinclu + "," +
                            object.hrinclu + "," +
                            object.cpfcnpj + "," +
                            object.clicod + "," +
                            object.nome_pessoa + "," +
                            object.etbcad + "," +
                            object.sit_credito + "," +
                            object.vctolimite + "," +
                            object.vlrlimite + "," +
                            object.tipoconsulta + "," +
                            object.neu_cdoperacao + "," +
                            object.neu_resultado + "\n";
                    }

                    var encodedUri = encodeURI(csvContent);
                    var link = document.createElement("a");
                    link.setAttribute("href", encodedUri);
                    link.setAttribute("download", "filadecredito.csv");
                    document.body.appendChild(link);

                    link.click();

                    document.body.removeChild(link);
                },
                error: function (e) {
                    alert('Erro: ' + JSON.stringify(e));
                }
            });
        }

        //**************exporta PDF
        function exportToPDF() {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '../database/filacredito.php?operacao=buscar',
                data: {
                    codigoFilial: $("#FiltroFilial").val(),
                    nome_pessoa: $("#FiltroNome_pessoa").val(),
                    dtinclu: $("#FiltroDtinclu").val()
                },
                success: function (json) {
                    var tableContent =
                        "<table>" +
                        "<tr><th>Loja</th><th>Data</th><th>Hora</th><th>Cpf</th><th>Codigo</th><th>Cliente</th><th>Lj_Cad</th><th>Sit</th><th>Vcto_limite</th><th>limite</th><th>TC</th><th>Operacao</th><th>Resultado</th></tr>";

                    for (var i = 0; i < json.length; i++) {
                        var object = json[i];
                        tableContent += "<tr><td>" + object.etbcod + "</td>" +
                            "<td>" + object.dtinclu + "</td>" +
                            "<td>" + object.hrinclu + "</td>" +
                            "<td>" + object.cpfcnpj + "</td>" +
                            "<td>" + object.clicod + "</td>" +
                            "<td>" + object.nome_pessoa + "</td>" +
                            "<td>" + object.etbcad + "</td>" +
                            "<td>" + object.sit_credito + "</td>" +
                            "<td>" + object.vctolimite + "</td>" +
                            "<td>" + object.vlrlimite + "</td>" +
                            "<td>" + object.tipoconsulta + "</td>" +
                            "<td>" + object.neu_cdoperacao + "</td>" +
                            "<td>" + object.neu_resultado + "</td></tr>";
                    }

                    tableContent += "</table>";

                    var printWindow = window.open('', '', 'width=1200,height=800');
                    printWindow.document.open();
                    printWindow.document.write('<html><head><title>Fila de Credito</title></head><body>');
                    printWindow.document.write(tableContent);
                    printWindow.document.write('</body></html>');
                    printWindow.document.close();

                    printWindow.onload = function () {
                        printWindow.print();
                        printWindow.onafterprint = function () {
                            printWindow.close();
                        };
                    };

                    printWindow.onload();
                },
                error: function (e) {
                    alert('Erro: ' + JSON.stringify(e));
                }
            });
        }

        $("#export").click(function () {
            var selectedOption = $("#exportoptions").val();
            if (selectedOption === "excel") {
                exportToExcel();
            }
            if (selectedOption === "pdf") {
                exportToPDF();
            }
            if (selectedOption === "csv") {
                exportToCSV();
            }
        });

    </script>

<!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>