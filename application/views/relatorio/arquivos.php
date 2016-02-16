<div class="container">

  <?php echo form_open_multipart('relatorio/arquivos', 'class="form-horizontal clearfix" onsubmit="overlay(true)"'); ?>
  <fieldset class="col-sm-6 center col-lg-offset-3">
    <legend>Defina os parâmetros para gerar o relatório</legend>

    <div class="form-group">
      <label for="empresa_cnab" class="col-sm-4 control-label">Empresa</label>
      <div class="col-sm-8">
        <select name="<?php echo Model_Empresa::ID; ?>" id="empresa_cnab" class="form-control">
          <option value="">Defina a Empresa</option>
          <?php foreach ($empresa as $row): ?>
            <option value="<?php echo strtoupper($row[Model_Empresa::ID]) ?>"><?php echo $row[Model_Empresa::NOME] ?></option>
          <?php endforeach; ?>
        </select>
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
      <label for="banco" class="col-sm-4 control-label">Banco</label>
      <div class="col-sm-8">
        <select name="<?php echo Model_Banco::ID; ?>" id="banco" class="form-control">
          <option value="">Defina o Banco</option>
          <?php foreach ($banco as $row): ?>
            <option value="<?php echo $row[Model_Banco::ID] ?>"><?php echo $row[Model_Banco::NOME] ?></option>
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
      <div class="col-sm-offset-6 col-sm-6">
        <button type="submit" class="btn btn-primary btn-lg">Gerar</button>
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
            <th>FOLHA</th>
            <th>PROCESSO</th>
            <th>EMPRESA</th>
            <th>TP. PAGTO</th>
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
              <td><?php echo $row['OPERACAO']; ?></td>
              <td><?php echo $row['BANCO']; ?></td>
              <td><?php echo $row['COMPETENCIA']; ?></td>
              <td><?php echo 'R$ ' . number_format(str_replace(',', '.', $row['VALOR']), 2, ',', '.'); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <?php
        $relatorio_json = json_encode($relatorio);
        ?>
        <tfoot>
          <tr>
            <th colspan="6"></th>
            <th>
        <form class="form-horizontal" role="form" method="post" action="<?php echo base_url('relatorio/imprimirRelatorio'); ?>" target="_blank">
          <input type="hidden" value='<?php echo $relatorio_json; ?>' name="dados" />
          <button type="submit" class="btn btn-success btn-xs tooltips" title="Download">
            <i class="fa fa-download"></i> Excel
          </button>
        </form>
        </th>
        </tr>
        </tfoot>
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