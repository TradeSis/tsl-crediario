<?php
// gabriel 15022023 14:54 adicionado ?parametros na ação
// gabriel 10022023 16:23

include_once '../head.php';


?>
<!DOCTYPE html>
<html lang="pt-BR">


<body class="bg-transparent">

    <div class="container mt-3" style="width:500px">
        <div class="card">
            <div class="card-header border-1">
                <h3>Parametros Seguro</h3>
            </div>

            <div class="container">
                <form action="seguros.php?parametros" method="post">
                    <div class="form-group">
                        <label>Seguro</label>
                        <select class="form-control" name="nomeTipoSeguro">
                            <option value=""></option>
                            <option value="Seguro Prestamista">Seguro Prestamista</option>
                            <option value="Garantia Estendida">Garantia Estendida</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Filial</label>
                        <input type="number" class="form-control" name="codigoFilial">
                    </div>
                    <div class="form-group">
                        <label>Cliente</label>
                        <input type="number" class="form-control" name="codigoCliente">
                    </div>
                    <div class="card-footer bg-transparent" style="text-align:right">
                        <button type="submit" class="btn btn-sm btn-success">Verificar Seguros</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>