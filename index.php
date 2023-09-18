<!DOCTYPE html>
<head>
        <title>Cadastros</title>
</head>
<html>

<?php
include_once __DIR__ . "/../config.php";
include_once ROOT . "/sistema/painel.php";
include_once ROOT . "/sistema/database/loginAplicativo.php";

$nivelMenuLogin = buscaLoginAplicativo($_SESSION['idLogin'], 'Crediario');

$nivelMenu = $nivelMenuLogin['nivelMenu'];



?>


<div class="container-fluid mt-1">
    <div class="row">
        <div class="col-md-12 d-flex justify-content-center">
            <ul class="nav a" id="myTabs">


                <?php
                $tab = '';

                if (isset($_GET['tab'])) {
                    $tab = $_GET['tab'];
                }
                if ($tab == '') {
                    $tab = 'historico';
                } 
                ?>


                <?php if ($nivelMenu >= 1) {        ?>
                    <li class="nav-item mr-1">
                        <a class="nav-link1 nav-link <?php if ($tab == "historico") {
                            echo " active ";
                        } ?>" href="?tab=historico"
                            role="tab">Histórico</a>
                    </li>
                <?php }
                if ($nivelMenu >= 1) { ?>
                    <li class="nav-item mr-1">
                        <a class="nav-link1 nav-link <?php if ($tab == "contratos") {
                            echo " active ";
                        } ?>" href="?tab=contratos"
                            role="tab">Contratos</a>
                    </li>
                <?php }
                if ($nivelMenu == 5) { // SOMENTE TRADESIS, POIS ESTA EM DEV ?>
                    <li class="nav-item mr-1">
                        <a class="nav-link1 nav-link <?php if ($tab == "seguros") {
                            echo " active ";
                        } ?>" href="?tab=seguros"
                            role="tab">Seguros</a>
                    </li>
                    <?php  }
                if ($nivelMenu >= 1) { ?>
                    <li class="nav-item mr-1">
                        <a class="nav-link1 nav-link <?php if ($tab == "filacredito") {
                            echo " active ";
                        } ?>" href="?tab=filacredito"
                            role="tab">Fila Crédito</a>
                    </li>
                <?php } ?>
            </ul>

        </div>

    </div>

</div>

<?php
$src = "";

if ($tab == "historico") {
    $src = "clientes/historico_parametros.php";
}
if ($tab == "contratos") {
    $src = "clientes/contratos_parametros.php";
}
if ($tab == "seguros") {
    $src = "consultas/seguros_parametros.php";
}
if ($tab == "filacredito") {
    $src = "consultas/filacredito.php";
}

if ($src !== "") {
    //echo URLROOT ."/crediario/". $src;
    ?>
    <div class="diviFrame">
        <iframe class="iFrame container-fluid " id="iFrameTab"
            src="<?php echo URLROOT ?>/crediario/<?php echo $src ?>"></iframe>
    </div>
    <?php
}
?>

</body>

</html>