<div class="container">
  <form class="form-horizontal" role="form" method="post" action="<?php echo base_url('empresa/atualizar'); ?>">
    <input type="hidden" name="<?php echo Model_Empresa::ID; ?>" id="id_emp" value="<?php echo $empresa[Model_Empresa::ID];?>" />
    <fieldset class="col-sm-6 center col-lg-offset-3">
      <div class="form-group">
        <label for="razao" class="col-sm-3 control-label">Razão Social</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="razao" name="<?php echo Model_Empresa::NOME; ?>" placeholder="Nome da Empresa" required value="<?php echo $empresa[Model_Empresa::NOME]; ?>" />
        </div>
      </div>
      <div class="form-group">
        <label for="fantasia" class="col-sm-3 control-label">Nome Fantasia</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="fantasia" name="<?php echo Model_Empresa::FANTASIA; ?>" placeholder="Nome Fantasia da Empresa" required value="<?php echo $empresa[Model_Empresa::FANTASIA]; ?>" />
        </div>
      </div>
      <div class="form-group">
        <label for="coligada" class="col-sm-3 control-label">Coligada</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="coligada" name="<?php echo Model_Empresa::COLIGADA; ?>" placeholder="Código da Empresa" required value="<?php echo $empresa[Model_Empresa::COLIGADA]; ?>" maxlength="2" />
        </div>
      </div>
      
      <div class="form-group">
        <label for="erp" class="col-sm-3 control-label">Cod. do ERP</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="erp" name="<?php echo Model_Empresa::CODERP; ?>" placeholder="Código da Empresa no ERP" required value="<?php echo $empresa[Model_Empresa::CODERP]; ?>" />
        </div>
      </div>
      <div class="form-group">
        <label for="loja" class="col-sm-3 control-label">Loja ERP</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="loja" name="<?php echo Model_Empresa::LOJA; ?>" placeholder="Código da Loja no ERP" required maxlength="2" value="<?php echo $empresa[Model_Empresa::LOJA]; ?>" />
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