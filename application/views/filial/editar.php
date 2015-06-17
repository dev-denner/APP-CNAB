<div class="container">
  <form class="form-horizontal" role="form" method="post" action="<?php echo base_url('filial/atualizar'); ?>">
    <input type="hidden" name="<?php echo Model_Filial::ID; ?>" id="id_emp" value="<?php echo $filial[Model_Filial::ID];?>" />
    <fieldset class="col-sm-6 center col-lg-offset-3">
      <div class="form-group">
        <label for="empresa" class="col-sm-3 control-label">Empresa</label>
        <div class="col-sm-9">
          <select id="empresa" name="<?php echo Model_Filial::EMPRESA; ?>" class="form-control" required>
            <option value="">Selecione uma empresa</option>
            <?php foreach ($empresa as $value) : ?>
            <option value="<?php echo $value[Model_Empresa::ID]; ?>" 
              <?php echo $filial[Model_Filial::EMPRESA] == $value[Model_Empresa::ID] ? 'selected': ''; ?>>
              <?php echo $value[Model_Empresa::NOME]; ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label for="nome" class="col-sm-3 control-label">Nome</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="nome" name="<?php echo Model_Filial::NOME; ?>" placeholder="Nome da Filial" required value="<?php echo $filial[Model_Filial::NOME]; ?>" />
        </div>
      </div>
      <div class="form-group">
        <label for="filial" class="col-sm-3 control-label">Filial</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="filial" name="<?php echo Model_Filial::FILIAL; ?>" placeholder="Código da Filial" required value="<?php echo $filial[Model_Filial::FILIAL]; ?>" />
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