<div class="container">
  <form class="form-horizontal" role="form" method="post" action="<?php echo base_url('banco/cadastrar'); ?>">
    <fieldset class="col-sm-6 center col-lg-offset-3">
      <div class="form-group">
        <label for="codigo" class="col-sm-3 control-label">Código</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="codigo" name="<?php echo Model_Banco::COD; ?>" placeholder="Código do Banco" maxlength="4" required autofocus />
        </div>
      </div>
      <div class="form-group">
        <label for="nome" class="col-sm-3 control-label">Nome</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="nome" name="<?php echo Model_Banco::NOME; ?>" placeholder="Nome do Banco" required />
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-9">
          <button type="submit" class="btn btn-primary">Cadastrar</button>
        </div>
      </div>
    </fieldset>
  </form>

</div> <!-- /container --> 