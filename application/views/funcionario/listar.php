<div class="container">
  <div>
    <a href="<?php echo base_url('funcionario/importar'); ?>" class="btn btn-success tooltips" title="Importar Funcionários">
      <i class="fa fa-group"></i> Importar Funcionários
    </a>
  </div>
  <br />
  <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover table-condensed datatable">
      <thead>
        <tr>
          <th width="30">ID</th>
          <th>EMPRESA</th>
          <th>FILIAL</th>
          <th>CHAPA</th>
          <th>NOME</th>
          <th>CPF</th>
          <th>BANCO</th>
          <th>C.CUSTO</th>
          <th>P.PAGTO</th>
          <th>SITUAÇÃO</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($funcionario as $row): ?>
          <tr>
            <td><?php echo str_pad($row[Model_Funcionario::ID], 4, "0", STR_PAD_LEFT); ?></td>
            <td><?php echo $row['EMPRESA']; ?></td>
            <td><?php echo $row['FILIAL']; ?></td>
            <td><?php echo $row[Model_Funcionario::CHAPA]; ?></td>
            <td><?php echo $row[Model_Funcionario::NOME]; ?></td>
            <td><?php echo $row[Model_Funcionario::CPF]; ?></td>
            <td><?php echo $row['BANCO']; ?></td>
            <td><?php echo $row[Model_Funcionario::CCUSTO]; ?></td>
            <td><?php echo $row[Model_Funcionario::PERIODO]; ?></td>
            <td><?php echo $row[Model_Funcionario::SITUACAO]; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</div> <!-- /container --> 