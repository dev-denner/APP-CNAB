<div class="container">
  <form class="form-horizontal" role="form" method="post" action="<?php echo base_url('conta_bancaria/atualizar'); ?>">
    <input type="hidden" name="<?php echo Model_Conta_Bancaria::ID; ?>" id="id_emp" value="<?php echo $conta_bancaria[Model_Conta_Bancaria::ID]; ?>" />
    <fieldset class="col-sm-6 center col-lg-offset-3">
      <div class="form-group">
        <label for="codempresa" class="col-sm-3 control-label">Empresa</label>
        <div class="col-sm-9">
          <select id="empresa" name="<?php echo Model_Conta_Bancaria::EMPRESA; ?>" class="form-control" required>
            <option value="">Selecione uma empresa</option>
            <?php foreach ($empresa as $value) : ?>
              <option value="<?php echo $value[Model_Empresa::ID]; ?>" 
                      <?php echo $conta_bancaria[Model_Conta_Bancaria::EMPRESA] == $value[Model_Empresa::ID] ? 'selected' : ''; ?>>
                        <?php echo $value[Model_Empresa::NOME]; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label for="banco" class="col-sm-3 control-label">Banco</label>
        <div class="col-sm-9">
          <select name="<?php echo Model_Conta_Bancaria::BANCO; ?>" id="banco" class="form-control" required>
            <option value="">Escolha o Banco</option>
            <?php foreach ($banco as $row): ?>
              <option value="<?php echo strtoupper($row[Model_Banco::ID]) ?>"
                      <?php echo $conta_bancaria[Model_Conta_Bancaria::BANCO] == $row[Model_Banco::ID] ? 'selected' : ''; ?>>
                <?php echo $row[Model_Banco::NOME] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      
      <div class="form-group">
        <label for="agencia" class="col-sm-3 control-label">Agência</label>
        <div class="col-sm-9">
          <div class="row">
            <div class="col-sm-9 col-xs-9">
              <input type="text" class="form-control" id="agencia" name="<?php echo Model_Conta_Bancaria::AGENCIA; ?>" placeholder="Agência Bancária da Empresa" value="<?php echo $conta_bancaria[Model_Conta_Bancaria::AGENCIA]; ?>" />
            </div>
            <div class="col-sm-3 col-xs-3">
              <input type="text" class="form-control" id="digitoag" name="<?php echo Model_Conta_Bancaria::DIGITOAG; ?>" placeholder="Dígito" maxlength="1" value="<?php echo $conta_bancaria[Model_Conta_Bancaria::DIGITOAG]; ?>" />
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="conta" class="col-sm-3 control-label">Conta / Dígito</label>
        <div class="col-sm-9">
          <div class="row">
            <div class="col-sm-9 col-xs-9">
              <input type="text" class="form-control" id="conta" name="<?php echo Model_Conta_Bancaria::CONTA; ?>" placeholder="Conta Corrente da Empresa" value="<?php echo $conta_bancaria[Model_Conta_Bancaria::CONTA]; ?>" />
            </div>
            <div class="col-sm-3 col-xs-3">
              <input type="text" class="form-control" id="digito" name="<?php echo Model_Conta_Bancaria::DIGITO; ?>" placeholder="Dígito" required maxlength="1" value="<?php echo $conta_bancaria[Model_Conta_Bancaria::DIGITO]; ?>" />
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="cc" class="col-sm-3 control-label">Razão C/C</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="cc" name="<?php echo Model_Conta_Bancaria::CC; ?>" placeholder="Número da razão da C/C" required value="<?php echo $conta_bancaria[Model_Conta_Bancaria::CC]; ?>" />
        </div>
      </div>
      <div class="form-group">
        <label for="codempresa" class="col-sm-3 control-label">Código Empresa</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="codempresa" name="<?php echo Model_Conta_Bancaria::CODEMPRESA; ?>" placeholder="Código fornecido pelo banco" value="<?php echo $conta_bancaria[Model_Conta_Bancaria::CODEMPRESA]; ?>" />
        </div>
      </div>
      <div class="form-group">
        <label for="convenio" class="col-sm-3 control-label">Convênio</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="convenio" name="<?php echo Model_Conta_Bancaria::CONVENIO; ?>" placeholder="Código do convênio com o banco" maxlength="20" value="<?php echo $conta_bancaria[Model_Conta_Bancaria::CONVENIO]; ?>" />
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