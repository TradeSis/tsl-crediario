<?php
// gabriel 16022023 15:34 - alterado para somente receber o pdf
// gabriel 16022023 14:17


include_once '../head.php';


$PDF = $_GET['PDF'];


?>

<body class="bg-transparent">
    <div class="container-fluid">
        <div class="card shadow">
            <div class="card-header border-1">
                <div class="row">
                    <div class="col-sm">
                        <h3 class="col">Certificado</h3>
                    </div>
                    <div class="col-sm" style="text-align:right">
                        <a href="#" onclick="history.back()" role="button" class="btn btn-primary btn-sm">Voltar</a>
                    </div>
                </div>
            </div>
            <div class="ExternalFiles full-width">
                <iframe class="container-fluid full-width" id="myIframe" src="<?php echo $PDF ?>" frameborder="0" scrolling="yes" height="550"></iframe>
            </div>
        </div>

</body>

</html>