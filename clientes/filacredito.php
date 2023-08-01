<?php

include_once '../head.php';
include_once '../database/filacredito.php';

$filiais = buscaFiliais();

$response = [];


?>


<body class="bg-transparent">
    <div class="container-fluid text-center mt-4">
        <div class="row">
            <div class="col-sm-3">
                <p class="tituloTabela">Fila Credito</p>
            </div>

            <div class="col-sm-4" style="margin-top:-10px;">
                <div class="input-group">
                    <form action="" method="post">
                        <select class="form-control text-center" name="codigoFilial" id="FiltroFilial"
                            autocomplete="off">
                            <option value="<?php echo null ?>"><?php echo "Selecione a Filial" ?></option>
                            <?php foreach ($filiais as $filial) { ?>
                                <option value="<?php echo $filial['id'] ?>"><?php echo $filial['value'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </form>
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
                            <th class="text-center"></th>
                            <th class="text-center">
                                <form action="" method="post">
                                    <select class="form-control" name="dtinclu" id="FiltroDtinclu">
                                    </select>
                                </form>
                            </th>
                            <th class="text-center"></th>
                            <th class="text-center">
                                <form action="" method="post">
                                    <select class="form-control" name="cpfcnpj" id="FiltroCpfcnpj">
                                    </select>
                                </form>
                            </th>
                            <th class="text-center">
                                <form action="" method="post">
                                    <select class="form-control" name="clicod" id="FiltroClicod">
                                    </select>
                                </form>
                            </th>
                            <th class="text-center">
                                <form action="" method="post">
                                    <select class="form-control" name="nome_pessoa" id="FiltroNome_pessoa">
                                    </select>
                                </form>
                            </th>
                            <th class="text-center">
                                <form action="" method="post">
                                    <select class="form-control" name="etbcad" id="FiltroEtbcad">
                                    </select>
                                </form>
                            </th>
                            <th class="text-center">
                                <form action="" method="post">
                                    <select class="form-control" name="sit_credito" id="FiltroSit_credito">
                                    </select>
                                </form>
                            </th>
                            <th class="text-center"></th>
                            <th class="text-center"></th>
                            <th class="text-center">
                                <form action="" method="post">
                                    <select class="form-control" name="tipoconsulta" id="FiltroTipoconsulta">
                                    </select>
                                </form>
                            </th>
                            <th class="text-center"></th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody id='dados' class="fonteCorpo">
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <script>
        buscar($("#FiltroDtinclu").val(), $("#FiltroCpfcnpj").val(), $("#FiltroNome_pessoa").val(), $("#FiltroFilial").val(), $("#FiltroClicod").val(), $("#FiltroEtbcad").val(), $("#FiltroTipoconsulta").val(), $("#FiltroSit_credito").val());

        function buscar(codigoFilial, dtinclu) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '../database/filacredito.php?operacao=buscar',
                beforeSend: function () {
                    $("#dados").html("Carregando...");
                },
                data: {
                    codigoFilial: codigoFilial,
                    dtinclu: dtinclu,
                    cpfcnpj: cpfcnpj,
                    clicod: clicod,
                    nome_pessoa: nome_pessoa,
                    etbcad: etbcad,
                    sit_credito: sit_credito,
                    tipoconsulta: tipoconsulta
                },
                success: function (response) {
                    var linha = "";
                    for (var i = 0; i < response.length; i++) {
                        var object = response[i];

                        linha += "<tr>";
                        linha += "<td>" + object.etbcod + "</td>";
                        linha += "<td>" + object.dtinclu + "</td>";
                        linha += "<td>" + object.hrinclu + "</td>";
                        linha += "<td>" + object.cpfcnpj + "</td>";
                        linha += "<td>" + object.clicod + "</td>";
                        linha += "<td>" + object.nome_pessoa + "</td>";
                        linha += "<td>" + object.etbcad + "</td>";
                        linha += "<td>" + object.sit_credito + "</td>";
                        linha += "<td>" + object.vctolimite + "</td>";
                        linha += "<td>" + object.vlrlimite + "</td>";
                        linha += "<td>" + object.tipoconsulta + "</td>";
                        linha += "<td>" + object.neu_cdoperacao + "</td>";
                        linha += "<td>" + object.neu_resultado + "</td>";
                        linha += "</tr>";
                    }

                    $("#dados").html(linha);
                    SelectOptions(response);
                }
            });
        }

        $("#FiltroFilial").change(function () {
            buscar($("#FiltroDtinclu").val(), $("#FiltroCpfcnpj").val(), $("#FiltroNome_pessoa").val(), $("#FiltroFilial").val(), $("#FiltroClicod").val(), $("#FiltroEtbcad").val(), $("#FiltroTipoconsulta").val(), $("#FiltroSit_credito").val());
        });

        $("#FiltroDtinclu").change(function () {
            buscar($("#FiltroDtinclu").val(), $("#FiltroCpfcnpj").val(), $("#FiltroNome_pessoa").val(), $("#FiltroFilial").val(), $("#FiltroClicod").val(), $("#FiltroEtbcad").val(), $("#FiltroTipoconsulta").val(), $("#FiltroSit_credito").val());
        });

        $("#FiltroCpfcnpj").change(function () {
            buscar($("#FiltroDtinclu").val(), $("#FiltroCpfcnpj").val(), $("#FiltroNome_pessoa").val(), $("#FiltroFilial").val(), $("#FiltroClicod").val(), $("#FiltroEtbcad").val(), $("#FiltroTipoconsulta").val(), $("#FiltroSit_credito").val());
        });

        $("#FiltroClicod").change(function () {
            buscar($("#FiltroDtinclu").val(), $("#FiltroCpfcnpj").val(), $("#FiltroNome_pessoa").val(), $("#FiltroFilial").val(), $("#FiltroClicod").val(), $("#FiltroEtbcad").val(), $("#FiltroTipoconsulta").val(), $("#FiltroSit_credito").val());
        });

        $("#FiltroNome_pessoa").change(function () {
            buscar($("#FiltroDtinclu").val(), $("#FiltroCpfcnpj").val(), $("#FiltroNome_pessoa").val(), $("#FiltroFilial").val(), $("#FiltroClicod").val(), $("#FiltroEtbcad").val(), $("#FiltroTipoconsulta").val(), $("#FiltroSit_credito").val());
        });

        $("#FiltroEtbcad").change(function () {
            buscar($("#FiltroDtinclu").val(), $("#FiltroCpfcnpj").val(), $("#FiltroNome_pessoa").val(), $("#FiltroFilial").val(), $("#FiltroClicod").val(), $("#FiltroEtbcad").val(), $("#FiltroTipoconsulta").val(), $("#FiltroSit_credito").val());
        });

        $("#FiltroTipoconsulta").click(function () {
            buscar($("#FiltroDtinclu").val(), $("#FiltroCpfcnpj").val(), $("#FiltroNome_pessoa").val(), $("#FiltroFilial").val(), $("#FiltroClicod").val(), $("#FiltroEtbcad").val(), $("#FiltroTipoconsulta").val(), $("#FiltroSit_credito").val());
        });

        $("#FiltroSit_credito").click(function () {
            buscar($("#FiltroDtinclu").val(), $("#FiltroCpfcnpj").val(), $("#FiltroNome_pessoa").val(), $("#FiltroFilial").val(), $("#FiltroClicod").val(), $("#FiltroEtbcad").val(), $("#FiltroTipoconsulta").val(), $("#FiltroSit_credito").val());
        });

        document.addEventListener("keypress", function (e) {
            if (e.key === "Enter") {
                buscar($("#FiltroDtinclu").val(), $("#FiltroCpfcnpj").val(), $("#FiltroNome_pessoa").val(), $("#FiltroFilial").val(), $("#FiltroClicod").val(), $("#FiltroEtbcad").val(), $("#FiltroTipoconsulta").val(), $("#FiltroSit_credito").val());
            }
        });
        function SelectOptions(data) {
            let uniqueData = {
                dtinclu: new Set(),
                cpfcnpj: new Set(),
                clicod: new Set(),
                nome_pessoa: new Set(),
                etbcad: new Set(),
                sit_credito: new Set(),
                tipoconsulta: new Set()
            };

            for (let i = 0; i < data.length; i++) {
                let obj = data[i];
                uniqueData.dtinclu.add(obj.dtinclu);
                uniqueData.cpfcnpj.add(obj.cpfcnpj);
                uniqueData.clicod.add(obj.clicod);
                uniqueData.nome_pessoa.add(obj.nome_pessoa);
                uniqueData.etbcad.add(obj.etbcad);
                uniqueData.sit_credito.add(obj.sit_credito);
                uniqueData.tipoconsulta.add(obj.tipoconsulta);
            }

            let dtincluOptions = '<option value="">Datas</option>';
            let cpfcnpjOptions = '<option value="">Cpf/Cnpj</option>';
            let clicodOptions = '<option value="">Codigo</option>';
            let nome_pessoaOptions = '<option value="">Cliente</option>';
            let etbcadOptions = '<option value="">Loja</option>';
            let sit_creditoOptions = '<option value="">Sit</option>';
            let tipoconsultaOptions = '<option value="">Tipo</option>';

            uniqueData.dtinclu.forEach(value => {
                dtincluOptions += '<option value="' + value + '">' + value + '</option>';
            });

            uniqueData.cpfcnpj.forEach(value => {
                cpfcnpjOptions += '<option value="' + value + '">' + value + '</option>';
            });

            uniqueData.clicod.forEach(value => {
                clicodOptions += '<option value="' + value + '">' + value + '</option>';
            });

            uniqueData.nome_pessoa.forEach(value => {
                nome_pessoaOptions += '<option value="' + value + '">' + value + '</option>';
            });

            uniqueData.etbcad.forEach(value => {
                etbcadOptions += '<option value="' + value + '">' + value + '</option>';
            });

            uniqueData.sit_credito.forEach(value => {
                sit_creditoOptions += '<option value="' + value + '">' + value + '</option>';
            });

            uniqueData.tipoconsulta.forEach(value => {
                tipoconsultaOptions += '<option value="' + value + '">' + value + '</option>';
            });

            $("#dtinclu").html(dtincluOptions);
            $("#cpfcnpj").html(cpfcnpjOptions);
            $("#clicod").html(clicodOptions);
            $("#nome_pessoa").html(nome_pessoaOptions);
            $("#etbcad").html(etbcadOptions);
            $("#sit_credito").html(sit_creditoOptions);
            $("#tipoconsulta").html(tipoconsultaOptions);
        }


        //**************exporta excel 
        function exportToExcel() {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '../database/filacredito.php?operacao=buscar',
                data: {
                    codigoFilial: $("#FiltroFilial").val(),
                    dtinclu: $("#FiltroDtinclu").val(),
                    cpfcnpj: $("#FiltroCpfcnpj").val(),
                    clicod: $("#FiltroClicod").val(),
                    nome_pessoa: $("#FiltroNome_pessoa").val(),
                    etbcad: $("#FiltroEtbcad").val(),
                    sit_credito: $("#FiltroSit_credito").val(),
                    tipoconsulta: $("#FiltroTipoconsulta").val()
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

                    var excelBlob = new Blob([excelContent], { type: 'application/vnd.ms-excel' });
                    var excelUrl = URL.createObjectURL(excelBlob);
                    var link = document.createElement("a");
                    link.setAttribute("href", excelUrl);
                    link.setAttribute("download", "demandas.xls");
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
                    dtinclu: $("#FiltroDtinclu").val(),
                    cpfcnpj: $("#FiltroCpfcnpj").val(),
                    clicod: $("#FiltroClicod").val(),
                    nome_pessoa: $("#FiltroNome_pessoa").val(),
                    etbcad: $("#FiltroEtbcad").val(),
                    sit_credito: $("#FiltroSit_credito").val(),
                    tipoconsulta: $("#FiltroTipoconsulta").val()
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
                    link.setAttribute("download", "demandas.csv");
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
                    dtinclu: $("#FiltroDtinclu").val(),
                    cpfcnpj: $("#FiltroCpfcnpj").val(),
                    clicod: $("#FiltroClicod").val(),
                    nome_pessoa: $("#FiltroNome_pessoa").val(),
                    etbcad: $("#FiltroEtbcad").val(),
                    sit_credito: $("#FiltroSit_credito").val(),
                    tipoconsulta: $("#FiltroTipoconsulta").val()
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

                    var printWindow = window.open('', '', 'width=800,height=600');
                    printWindow.document.open();
                    printWindow.document.write('<html><head><title>Demandas</title></head><body>');
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
            } else if (selectedOption === "pdf") {
                exportToPDF();
            } else if (selectedOption === "csv") {
                exportToCSV();
            }
        });



    </script>
</body>

</html>