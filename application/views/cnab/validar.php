<div class="container">

  <?php echo form_open_multipart('cnab/valideArquivo', 'class="form-horizontal" onsubmit="overlay(true)"'); ?>
  <fieldset class="col-sm-6 center col-lg-offset-3">
    <legend>Insirar o arquivo para validar</legend>

    <div class="form-group">
      <label for="empresa_cnab" class="col-sm-4 control-label">Empresa</label>
      <div class="col-sm-8">
        <select name="<?php echo Model_Processo::EMPRESA; ?>" id="empresa_cnab" class="form-control" required>
          <option value="">Defina a Empresa</option>
          <?php foreach ($empresa as $row): ?>
            <option value="<?php echo strtoupper($row[Model_Empresa::ID]) ?>"><?php echo $row[Model_Empresa::NOME] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <!--    <div class="form-group">
          <label for="filial" class="col-sm-4 control-label">Filial</label>
          <div class="col-sm-8">
            <select name="<?php echo Model_Processo::FILIAL; ?>" id="filial" class="form-control" required>
              <option value="">Defina a Filial</option>
            </select>
          </div>
        </div>-->

    <div class="form-group">
      <label for="xls" class="col-sm-4 control-label">Enviar Arquivo</label>
      <div class="col-sm-8">
        <input type="file" name="xls" id="xls" class="form-control" required />
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-6 col-sm-6">
        <button type="submit" class="btn btn-primary btn-lg">Validar</button>
      </div>
    </div>
  </fieldset>
</form>


<div class="col-sm-10 col-lg-offset-1">
  <div class="list-group">
    <?php if (!is_null($erro)) : ?>
      <h3 class="list-group-item list-group-item-heading">Erros Encontrados</h3>
      <?php foreach ($erro as $value) : ?>
        <p class="list-group-item list-group-item-danger"><?php echo $value; ?></p>
        <?php
      endforeach;
    endif;
    ?>
    <?php if (!is_null($sucesso)) : ?> 
      <p class="list-group-item text-success"><?php echo $sucesso; ?></p>
    <?php endif; ?>
  </div>
</div>

</div> <!-- /container --> 