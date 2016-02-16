<div class="container">
  <div>
    <a href="<?php echo base_url('conta_bancaria/novo'); ?>" class="btn btn-success">
      <span class="fa fa-clipboard"></span> Novo Dado Bancário
    </a>
  </div>
  <br />
  <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover table-condensed datatable">
      <thead>
        <tr>
          <th width="30">ID</th>
          <th>EMPRESA</th>
          <th>BANCO</th>
          <th>AGÊNCIA DIG</th>
          <th>CONTA DIG</th>
          <th>RAZÃO CC</th>
          <th>CNPJ</th>
          <th width="100">AÇÕES</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($conta_bancaria as $row): ?>
          <tr>
            <td><?php echo $row[Model_Conta_Bancaria::ID]; ?></td>
            <td><?php echo $row['EMPRESA']; ?></td>
            <td><?php echo $row['BANCO']; ?></td>
            <td><?php echo $row[Model_Conta_Bancaria::AGENCIA] . ' ' . $row[Model_Conta_Bancaria::DIGITOAG]; ?></td>
            <td><?php echo $row[Model_Conta_Bancaria::CONTA] . ' ' . $row[Model_Conta_Bancaria::DIGITO]; ?></td>
            <td><?php echo $row[Model_Conta_Bancaria::CC]; ?></td>
            <td><?php echo $row[Model_Conta_Bancaria::CODEMPRESA]; ?></td>
            <td>
              <div class="btn-group">
                <a href="<?php echo site_url(); ?>conta_bancaria/editar/<?php echo $row[Model_Conta_Bancaria::ID]; ?>" class="btn btn-primary">
                  <span class="glyphicon glyphicon-edit tooltips" title="Editar"></span>
                </a>
                <button type="button" onclick="deletar('<?php echo site_url(); ?>conta_bancaria/deletar/<?php echo $row[Model_Conta_Bancaria::ID]; ?>', '<?php echo site_url(); ?>conta_bancaria'); " class="btn btn-danger">
                  <span class="glyphicon glyphicon-trash tooltips" title="Deletar"></span>
                </button>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</div> <!-- /container --> 