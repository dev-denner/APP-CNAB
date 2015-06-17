<div class="container">
  <?php echo form_open_multipart('funcionario/processarImportacao', 'class="form-horizontal" onsubmit="overlay(true)"'); ?>
    <fieldset class="col-sm-6 center col-lg-offset-3">
      <div class="form-group">
        <label for="empresa" class="col-sm-4 control-label">Empresa</label>
        <div class="col-sm-8">
          <select class="form-control" id="empresa" name="<?php echo Model_Funcionario::EMPRESA; ?>" required autofocus>
            <option value="">Escolha uma empresa</option>
            <?php foreach ($empresa as $value) : ?>
            <option value="<?php echo $value[Model_Empresa::ID]; ?>" <?php echo $value[Model_Empresa::COLIGADA] == 1 ? 'disabled' : ''; ?>>
              <?php echo $value[Model_Empresa::COLIGADA], ' - ', $value[Model_Empresa::NOME]; ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label for="file" class="col-sm-4 control-label">Arquivo EXCEL</label>
        <div class="col-sm-8">
          <input type="file" name="file" id="file" class="form-control" required />
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-6 col-sm-6">
          <button type="submit" class="btn btn-primary btn-lg">Importar</button>
        </div>
      </div>
    </fieldset>
  </form>

</div> <!-- /container --> 