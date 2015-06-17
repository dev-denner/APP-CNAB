<div class="container">
  <form class="form-horizontal" role="form" method="post" action="<?php echo base_url('conta_bancaria/cadastrar'); ?>">
    <fieldset class="col-sm-6 center col-lg-offset-3">
      <div class="form-group">
        <label for="codempresa" class="col-sm-3 control-label">Empresa</label>
        <div class="col-sm-9">
          <select name="<?php echo Model_Conta_Bancaria::EMPRESA; ?>" id="empresa" class="form-control" required>
            <option value="">Escolha a Empresa</option>
            <?php foreach ($empresa as $row): ?>
              <option value="<?php echo strtoupper($row[Model_Empresa::ID]) ?>"><?php echo $row[Model_Empresa::NOME] ?></option>
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
              <option value="<?php echo strtoupper($row[Model_Banco::ID]) ?>"><?php echo $row[Model_Banco::NOME] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      
      <div class="form-group">
        <label for="agencia" class="col-sm-3 control-label">Agência</label>
        <div class="col-sm-9">
          <div class="row">
            <div class="col-sm-9 col-xs-9">
              <input type="text" class="form-control" id="agencia" name="<?php echo Model_Conta_Bancaria::AGENCIA; ?>" placeholder="Agência Bancária da Empresa" required />
            </div>
            <div class="col-sm-3 col-xs-3">
              <input type="text" class="form-control" id="digitoag" name="<?php echo Model_Conta_Bancaria::DIGITOAG; ?>" placeholder="Dígito" maxlength="1" />
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="conta" class="col-sm-3 control-label">Conta / Dígito</label>
        <div class="col-sm-9">
          <div class="row">
            <div class="col-sm-9 col-xs-9">
              <input type="text" class="form-control" id="conta" name="<?php echo Model_Conta_Bancaria::CONTA; ?>" placeholder="Conta Corrente da Empresa" />
            </div>
            <div class="col-sm-3 col-xs-3">
              <input type="text" class="form-control" id="digito" name="<?php echo Model_Conta_Bancaria::DIGITO; ?>" placeholder="Dígito" maxlength="1" required />
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="cc" class="col-sm-3 control-label">Razão C/C</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="cc" name="<?php echo Model_Conta_Bancaria::CC; ?>" placeholder="Número da razão da C/C" />
        </div>
      </div>
      <div class="form-group">
        <label for="codempresa" class="col-sm-3 control-label">Código Empresa</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="codempresa" name="<?php echo Model_Conta_Bancaria::CODEMPRESA; ?>" placeholder="Código fornecido pelo banco" />
        </div>
      </div>
      <div class="form-group">
        <label for="convenio" class="col-sm-3 control-label">Convênio</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="convenio" name="<?php echo Model_Conta_Bancaria::CONVENIO; ?>" placeholder="Código do convênio com o banco" maxlength="20" />
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