<div class="container">
  <form class="form-horizontal" role="form" method="post" action="<?php echo base_url('periodo_pagto/atualizar'); ?>">
    <input type="hidden" name="<?php echo Model_Periodo_Pagto::ID; ?>" id="id_emp" value="<?php echo $periodo_pagto[Model_Periodo_Pagto::ID];?>" />
    <fieldset class="col-sm-6 center col-lg-offset-3">
      <div class="form-group">
        <label for="nome" class="col-sm-3 control-label">Nome</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="nome" name="<?php echo Model_Periodo_Pagto::NOME; ?>" placeholder="Nome do Período de Pagamento" required value="<?php echo $periodo_pagto[Model_Periodo_Pagto::NOME]; ?>" />
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-9">
          <button type="submit" class="btn btn-primary">Atualizar</button>
        </div>
      </div>
    </fieldset>
  </form>
</div> <!-- /container --> 