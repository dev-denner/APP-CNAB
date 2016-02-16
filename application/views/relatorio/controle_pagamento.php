<div class="container">

  <?php echo form_open_multipart('relatorio/controle_pagamento', 'class="form-horizontal clearfix" onsubmit="overlay(true)"'); ?>
  <fieldset class="col-sm-6 center col-lg-offset-3">
    <legend>Defina os parâmetros para gerar o relatório</legend>

    <div class="form-group">
      <label for="empresa_cnab" class="col-sm-4 control-label">Empresa</label>
      <div class="col-sm-8">
        <select name="<?php echo Model_Empresa::ID; ?>" id="empresa_cnab" class="form-control">
          <option value="">Escolha uma Empresa</option>
          <?php foreach ($empresa as $row): ?>
            <option value="<?php echo strtoupper($row[Model_Empresa::ID]) ?>"><?php echo $row[Model_Empresa::NOME] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="filial" class="col-sm-4 control-label">Filial</label>
      <div class="col-sm-8">
        <select name="filial" id="filial" class="form-control">
          <option value="">Escolha uma Empresa</option>
        </select>
      </div>
    </div>
    
    <div class="form-group">
      <label for="gestor" class="col-sm-4 control-label">Gestor</label>
      <div class="col-sm-8">
        <select name="gestor" id="gestor" class="form-control">
          <option value="">Escolha um Gestor</option>
          <?php foreach ($gestor as $row): ?>
            <option value="<?php echo strtoupper($row['ID']) ?>"><?php echo $row['NOME'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    
    <div class="form-group">
      <label for="cc_de" class="col-sm-4 control-label">Centro de Custo</label>
      <div class="col-sm-4">
        <input type="text" name="cc_de" id="cc_de" class="form-control" placeholder="CC de" />
      </div>
      <div class="col-sm-4">
        <input type="text" name="cc_ate" id="cc_ate" class="form-control" placeholder="CC até" />
      </div>
    </div>
    
    <div class="form-group">
      <label for="chapa" class="col-sm-4 control-label">Funcionário</label>
      <div class="col-sm-8">
        <input type="text" name="chapa" id="chapa" class="form-control" placeholder="Digite a Chapa ou Nome ou CPF" />
      </div>
    </div>

    <div class="form-group">
      <label for="tipo_operacao" class="col-sm-4 control-label">Tipo de Pagamento</label>
      <div class="col-sm-8">
        <select name="<?php echo Model_Tipo_Operacao::ID; ?>" id="tipo_operacao" class="form-control">
          <option value="">Defina o Tipo de Pagamento</option>
          <?php foreach ($tipo_operacao as $row): ?>
            <option value="<?php echo $row[Model_Tipo_Operacao::ID] ?>"><?php echo $row[Model_Tipo_Operacao::NOME] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="data_pagto" class="col-sm-4 control-label">Competência</label>
      <div class="col-sm-8">
        <input type="month" name="competencia" id="competencia" class="form-control" />
      </div>
    </div>
    
    <div class="form-group">
      <label for="periodo" class="col-sm-4 control-label">Período</label>
      <div class="col-sm-8">
        <select name="periodo" id="periodo" class="form-control">
          <option value="">Defina o Período</option>
          <?php foreach ($periodo as $row): ?>
            <option value="<?php echo $row['CODCLIENTE']; ?>"><?php echo $row['CODCLIENTE'], ' - ', $row['DESCRICAO'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-6 col-sm-6">
        <button type="submit" class="btn btn-primary">Gerar</button>
      </div>
    </div>
  </fieldset>
</form>
<?php if (isset($relatorio)): ?>
  <div class="table-responsive">
    <?php if (is_array($relatorio)): ?>
      <table class="table table-striped table-bordered table-hover table-condensed datatable">
        <thead>
          <tr>
            <th width="30">FOLHA</th>
            <th>PROCESSO</th>
            <th>EMPRESA</th>
            <th>FILIAL</th>
            <th>T. OPERAÇÃO</th>
            <th>BANCO</th>
            <th>COMPETÊNCIA</th>
            <th>TOTAL</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($relatorio as $row): ?>
            <tr>
              <td><?php echo str_pad($row['FOLHA'], 4, '0', STR_PAD_LEFT); ?></td>
              <td><?php echo str_pad($row['PROCESSO'], 4, '0', STR_PAD_LEFT); ?></td>
              <td><?php echo $row['EMPRESA']; ?></td>
              <td><?php echo $row['FILIAL']; ?></td>
              <td><?php echo $row['OPERACAO']; ?></td>
              <td><?php echo $row['BANCO']; ?></td>
              <td><?php echo $row['COMPETENCIA']; ?></td>
              <td><?php echo 'R$ ' . number_format(str_replace(',', '.', $row['VALOR']), 2, ',', '.'); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th><?php echo $relatorio; ?></th>
          </tr>
        </thead>
      </table>
    <?php endif; ?>
  </div>
<?php endif; ?>
</div> <!-- /container --> 