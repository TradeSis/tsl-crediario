<?php
// gabriel 22022023 16:00

include_once '../head.php';


?>
<!DOCTYPE html>
<html lang="pt-BR">


<body class="bg-transparent">

    <div class="container mt-3" style="width:500px">
        <div class="card">
            <div class="card-header border-1">
                <h3>Busca Contrato</h3>
            </div>

            <div class="container">
                <form action="contratos.php?parametros" method="POST">
                    <div class="form-group">
                        <label>Número Contrato</label>
                        <input type="number" class="form-control" name="numeroContrato">
                    </div>
                    <div class="card-footer bg-transparent" style="text-align:right">
                        <button type="submit" class="btn btn-sm btn-success">Consultar Contrato</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>