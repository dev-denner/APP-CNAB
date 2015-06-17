<div class="container">
  <div>
    <a href="<?php echo base_url('empresa/novo'); ?>" class="btn btn-success">
      <span class="fa fa-suitcase"></span> Nova Empresa
    </a>
  </div>
  <br />
  <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover table-condensed datatable">
      <thead>
        <tr>
          <th width="30">ID</th>
          <th>RAZÃO SOCIAL</th>
          <th>NOME FANTASIA</th>
          <th>COLIGADA</th>
          <th>COD. ERP</th>
          <th>LOJA ERP</th>
          <th width="100">AÇÕES</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($empresa as $row): ?>
          <tr>
            <td><?php echo $row[Model_Empresa::ID] ?></td>
            <td><?php echo $row[Model_Empresa::NOME] ?></td>
            <td><?php echo $row[Model_Empresa::FANTASIA] ?></td>
            <td><?php echo $row[Model_Empresa::COLIGADA] ?></td>
            <td><?php echo $row[Model_Empresa::CODERP] ?></td>
            <td><?php echo $row[Model_Empresa::LOJA] ?></td>
            <td>
              <div class="btn-group">
                <a href="<?php echo site_url(); ?>empresa/editar/<?php echo $row[Model_Empresa::ID]; ?>" class="btn btn-primary">
                  <span class="glyphicon glyphicon-edit tooltips" title="Editar"></span>
                </a>
                <button type="button" onclick="deletar('<?php echo site_url(); ?>empresa/deletar/<?php echo $row[Model_Empresa::ID]; ?>', '<?php echo site_url(); ?>empresa'); " class="btn btn-danger">
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