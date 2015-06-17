<div class="container">
  <div>
    <a href="<?php echo base_url('banco/novo'); ?>" class="btn btn-success tooltips" title="Novo Banco">
      <i class="fa fa-building-o" ></i> Novo Banco
    </a>
  </div>
  <br />
  <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover table-condensed datatable">
      <thead>
        <tr>
          <th width="30">ID</th>
          <th width="50">CÓDIGO</th>
          <th>NOME</th>
          <th width="100">AÇÕES</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($banco as $row): ?>
          <tr>
            <td><?php echo $row[Model_Banco::ID] ?></td>
            <td><?php echo $row[Model_Banco::COD] ?></td>
            <td><?php echo $row[Model_Banco::NOME] ?></td>
            <td>
              <div class="btn-group">
                <a href="<?php echo site_url(); ?>banco/editar/<?php echo $row[Model_Banco::ID]; ?>" class="btn btn-primary">
                  <span class="glyphicon glyphicon-edit tooltips" title="Editar"></span>
                </a>
                <button type="button" onclick="deletar('<?php echo site_url(); ?>banco/deletar/<?php echo $row[Model_Banco::ID]; ?>', '<?php echo site_url(); ?>banco'); " class="btn btn-danger">
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