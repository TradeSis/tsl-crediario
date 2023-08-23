<?php
include_once('../head.php');
?>

<body>
    <div class="container" style="margin-top:200px">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-7">
                <div class="card bg-gray-200 shadow border-1">

                    <div class="card-body px-lg-4 py-lg-6">
                        <form role="form" action="../database/filacredito.php?operacao=buscar" method="post">
                            <div class="form-group mb-3">

                                <div class="input-group input-group-alternative">
                                    <input class="form-control" placeholder="codigoFilial" type="text" name="codigoFilial"
                                        autocomplete="off" autofocus="on">
                                </div>
                                <div class="input-group input-group-alternative mt-2">
                                    <input class="form-control" placeholder="nome_pessoa" type="text" name="nome_pessoa"
                                        autocomplete="off" autofocus="on">
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary my-4">Entrar</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>



</body>

</html>