<div class="container">
  <div class="table-responsive">
    <form class="form-horizontal" role="form" method="post" action="<?php echo base_url('cnab/baixar_pagamento/' . $id); ?>">
      <table class="table table-bordered table-striped table-hover table-condensed datatable">
        <thead>
          <tr>
            <th>BAIXAR</th>
            <th>ID</th>
            <th>CHAPA</th>
            <th>NOME</th>
            <th width="120">CPF</th>
            <th>VALOR</th>
            <th>DT. BAIXA</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($baixa as $row): $voltar = $row[Model_Base_Processo::PROCESSO]; ?>

            <tr>
              <td class="center">
                <input type="checkbox" name="baixar[]" value="<?php echo $row[Model_Base_Processo::ID]; ?>" class="checkbox_baixar" />
              </td>
              <td><?php echo str_pad($row[Model_Base_Processo::ID], 9, 0, STR_PAD_LEFT); ?></td>
              <td><?php echo $row[Model_Base_Processo::CHAPA]; ?></td>
              <td><?php echo $row[Model_Base_Processo::NOME]; ?></td>
              <td><?php echo $row[Model_Base_Processo::CPF]; ?></td>
              <td><?php echo 'R$' . number_format(str_replace(',', '.', $row[Model_Base_Processo::VALOR]), 2, ',', '.'); ?></td>
              <td><?php echo $row[Model_Base_Processo::DT_REAL]; ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <th class="center">
              <label>
                <input type="checkbox" id="todos_checkbox"> <span class="texto_todos">Marcar todos</span>
              </label>
            </th>
            <td></td>
            <th colspan="2">
              <div class="form-group">
                <label for="baixar_data" class="col-sm-4 control-label">Data de Baixa:</label>
                <div class="col-sm-8">
                  <input type="date" class="col-xs-12" id="baixar_data" name="baixar_data" />
                </div>
              </div>

            </th>
            <th><span class="pull-right">Valor da Baixa: </span></th>
            <th><?php echo 'R$' . number_format(str_replace(',', '.', $total), 2, ',', '.'); ?></th>
            <th><button type="submit" class="btn btn-primary">Baixar</button></th>
          </tr>
          <tr>
            <td colspan="7">
              <a href="<?php echo base_url('cnab/listar/' . $voltar) ?>" class="btn btn-default pull-right">Voltar</a>
            </td>
          </tr>
        </tfoot>
      </table>
    </form>
  </div>


</div> <!-- /container --> 