<div class="container">

  <?php echo form_open_multipart('relatorio/liquido_folha', 'class="form-horizontal clearfix" onsubmit="overlay(true)"'); ?>
  <fieldset class="col-sm-6 center col-lg-offset-3">
    <legend>Defina os parâmetros para gerar o relatório</legend>

    <div class="form-group">
      <label for="periodo" class="col-sm-4 control-label">Período</label>
      <div class="col-sm-8">
        <select name="periodo" id="periodo" class="form-control">
          <option value="">Defina o Período</option>
          <?php foreach ($periodo as $row): ?>
            <option value="<?php echo strtoupper($row['CODCLIENTE']) ?>"><?php echo $row['CODCLIENTE'], ' - ', $row['DESCRICAO'] ?></option>
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
            <!--<th>EMPREENDIMENTO</th>-->
            <th>FILIAL</th>
            <th>ANO</th>
            <th>MÊS</th>
            <th>PERIODO</th>
            <th>BB</th>
            <th>BRADESCO</th>
            <th>CITY</th>
            <th>SANTANDER</th>
            <th>TOTAL</th>
            <th width="30">VER</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 0; ?>
          <?php foreach ($relatorio as $row): ?>
            <tr <?php //echo $row['NM_EMPDMT'] == 'NÃO SE APLICA' ? 'class="danger"' : ''; ?>>
              <!--<td><?php //echo $row['NM_EMPDMT']; ?></td>-->
              <td><?php echo str_pad($row['CD_FILIAL'], 2, '0', STR_PAD_LEFT); ?></td>
              <td><?php echo $row['NU_ANO']; ?></td>
              <td><?php echo str_pad($row['NU_MES'], 2, '0', STR_PAD_LEFT); ?></td>
              <td><?php echo str_pad($row['NU_PERIODO'], 2, '0', STR_PAD_LEFT); ?></td>
              <td><?php echo 'R$ ' . number_format(str_replace(',', '.', $row['BB']), 2, ',', '.'); ?></td>
              <td><?php echo 'R$ ' . number_format(str_replace(',', '.', $row['BRADESCO']), 2, ',', '.'); ?></td>
              <td><?php echo 'R$ ' . number_format(str_replace(',', '.', $row['CITY']), 2, ',', '.'); ?></td>
              <td><?php echo 'R$ ' . number_format(str_replace(',', '.', $row['SANTANDER']), 2, ',', '.'); ?></td>
              <td><?php echo 'R$ ' . number_format(str_replace(',', '.', $row['TOTAL']), 2, ',', '.'); ?></td>
              <td style="text-align: center;">
                <a class="badge" onclick="relatorio_analitico('<?php echo '.mark' . ++$i; ?>', '<?php //echo $row['NM_EMPDMT']; ?>', '<?php echo $row['CD_FILIAL']; ?>', '<?php echo $row['NU_ANO']; ?>', '<?php echo $row['NU_MES']; ?>', '<?php echo $row['NU_PERIODO']; ?>');"><i class="fa fa-plus mark<?php echo $i; ?>"></i></a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <?php
        $relatorio_json = json_encode($relatorio);
        ?>
        <tfoot>
          <tr>
            <th colspan="8"></th>
            <th>
        <form class="form-horizontal" role="form" method="post" action="<?php echo base_url('relatorio/imprimirRelatorio'); ?>" target="_blank">
          <input type="hidden" value='<?php echo $relatorio_json; ?>' name="dados" />
          <button type="submit" class="btn btn-success btn-xs tooltips" title="Download Relatório Sintético">
            <i class="fa fa-download"></i> Excel Sintético
          </button>
        </form>
        </th>
        <th>
        <form class="form-horizontal" role="form" method="post" action="<?php echo base_url('relatorio/imprimirRelatorioAnalitico'); ?>" target="_blank">
          <input type="hidden" value='<?php echo $perguntas; ?>' name="dados" />
          <button type="submit" class="btn btn-success btn-xs tooltips" title="Download Relatório Analítico">
            <i class="fa fa-download"></i> Excel Analítico
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