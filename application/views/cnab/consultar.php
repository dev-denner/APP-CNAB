<div class="container">
  <div class="well clearfix">
    <?php echo form_open('cnab/consultar', 'class="form-horizontal" onsubmit="overlay(true);"'); ?>
    <?php echo form_fieldset('Defina os parâmetros para a pesquisar', array('class' => 'col-lg-6 col- center col-lg-offset-3')); ?>
    <div class="form-group">
      <label for="empresa_cnab" class="col-sm-4 control-label">Empresa</label>
      <div class="col-sm-8">
        <select name="empresa" id="empresa_cnab" class="form-control">
          <option value="">Defina a Empresa</option>
          <?php foreach ($empresa as $row): ?>
            <option value="<?php echo strtoupper($row[Model_Empresa::ID]) ?>"><?php echo $row[Model_Empresa::COLIGADA], ' - ', $row[Model_Empresa::NOME] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="tipo_pagto" class="col-sm-4 control-label">Tipo de Pagamento</label>
      <div class="col-sm-8">
        <select name="tipo_pagto" id="tipo_pagto" class="form-control">
          <option value="">Defina o Tipo de Pagamento</option>
          <?php foreach ($tipo_pagto as $row): ?>
            <option value="<?php echo strtoupper($row[Model_Tipo_Operacao::ID]); ?>"><?php echo $row[Model_Tipo_Operacao::NOME]; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="nome_arquivo" class="col-sm-4 control-label">Nome do Arquivo</label>
      <div class="col-sm-8">
        <input type="text" name="nome_arquivo" id="nome_arquivo" class="form-control" />
      </div>
    </div>

    <div class="form-group">
      <label for="banco" class="col-sm-4 control-label">Banco</label>
      <div class="col-sm-8">
        <select name="banco" id="banco" class="form-control">
          <option value="">Defina o Banco</option>
          <?php foreach ($banco as $row): ?>
            <option value="<?php echo strtoupper($row[Model_Banco::ID]); ?>"><?php echo $row[Model_Banco::NOME]; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="competencia" class="col-sm-4 control-label">Competência</label>
      <div class="col-sm-8">
        <input type="month" name="competencia" id="competencia" class="form-control" />
      </div>
    </div>

    <div class="form-group">
      <label for="data_processo_de" class="col-sm-4 control-label">Data de Processamento</label>
      <div class="col-sm-4">
        <label for="data_processo_de" class="control-label">De</label>
        <input type="date" name="data_processo_de" id="data_processo_de" class="form-control" />
      </div>
      <div class="col-sm-4">
        <label for="data_processo_ate" class="control-label">Até</label>
        <input type="date" name="data_processo_ate" id="data_processo_ate" class="form-control" />
      </div>
    </div>

    <div class="form-group">
      <label for="data_real_de" class="col-sm-4 control-label">Data Real de Pagamento</label>
      <div class="col-sm-4">
        <label for="data_real_de" class="control-label">De</label>
        <input type="date" name="data_real_de" id="data_real_de" class="form-control" />
      </div>
      <div class="col-sm-4">
        <label for="data_real_ate" class="control-label">Até</label>
        <input type="date" name="data_real_ate" id="data_real_ate" class="form-control" />
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-6 col-sm-6">
        <button type="submit" class="btn btn-primary">Pesquisar</button>
      </div>
    </div>
    <?php echo form_fieldset_close(); ?>
    <?php echo form_close(); ?>
  </div>
  <?php if (isset($consultar)): ?>
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover table-condensed datatable">
        <thead>
          <tr>
            <th>FOLHA</th>
            <th>PROCESSO</th>
            <th>EMPRESA</th>
            <th>TP. PAGTO</th>
            <th>COMPETÊNCIA</th>
            <th>PROCESSADO</th>
            <th>TOTAL</th>
            <th width='100'>VER</th>
          </tr>
        </thead>
        <?php if (is_array($consultar)): ?>
          <tbody>
            <?php foreach ($consultar as $row): ?>
              <tr>
                <td><?php echo str_pad($row['FOLHA'], 4, '0', STR_PAD_LEFT); ?></td>
                <td><?php echo str_pad($row['PROCESSO'], 4, '0', STR_PAD_LEFT); ?></td>
                <td><?php echo $row['EMPRESA']; ?></td>
                <td><?php echo $row['OPERACAO']; ?></td>
                <td><?php echo $row['COMPETENCIA']; ?></td>
                <td><?php echo $row['PROCESSADO']; ?></td>
                <td><?php echo 'R$ ' . number_format(str_replace(',', '.', $row['VALOR']), 2, ',', '.'); ?></td>
                <td>
                  <?php if ($user['NU_PRIVILEGIO'] >= 90): ?>
                    <div class="btn-group">
                    <?php endif; ?>
                    <a href="<?php echo site_url('cnab/listar/' . $row['PROCESSO']); ?>" class="btn btn-default">
                      <span class="glyphicon glyphicon-eye-open tooltips" title="Visualizar Processo"></span>
                    </a>
                    <?php if ($user['NU_PRIVILEGIO'] >= 90): ?>
                      <a onclick="deletar('<?php echo site_url('cnab/deletar/' . $row['PROCESSO']); ?>', '<?php echo site_url('cnab/consultar'); ?>');" class="btn btn-danger">
                        <span class="glyphicon glyphicon-trash tooltips" title="Deletar"></span>
                      </a>
                    </div>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        <?php endif; ?>
      </table>    
    </div>
  <?php endif; ?>

</div> <!-- /container --> 