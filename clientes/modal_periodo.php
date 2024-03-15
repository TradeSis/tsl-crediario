<!--------- FILTRO PERIODO --------->
<div class="modal" id="periodoModal" tabindex="-1"
    aria-labelledby="periodoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Filtro Periodo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post">
            <div class="row">
              <div class="col">
                <label class="labelForm">dtproc</label>
                <?php if ($dtproc != null) { ?>
                <input type="date" class="data select form-control" id="dtproc"
                  value="<?php echo $dtproc ?>" name="dtproc" autocomplete="off">
                <?php } else { ?>
                <input type="date" class="data select form-control" id="dtproc" name="dtproc"
                  autocomplete="off">
                <?php } ?>
              </div>
            </div>
            </div>
            <div class="modal-footer border-0">
              <div class="col-sm text-end">
                <button type="button" class="btn btn-success" id="filtrarButton" data-dismiss="modal">Filtrar</button>
              </div>
            </div>
          </form>
        
      </div>
    </div>
  </div>
