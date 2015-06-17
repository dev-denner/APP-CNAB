<div class="container">
  <div>
    <a href="<?php echo base_url('filial/novo'); ?>" class="btn btn-success">
      <span class="fa fa-credit-card"></span> Nova Filial
    </a>
  </div>
  <br />
  <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover table-condensed datatable">
      <thead>
        <tr>
          <th width="30">ID</th>
          <th>EMPRESA</th>
          <th>NOME</th>
          <th>FILIAL</th>
          <th width="100">AÇÕES</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($filial as $row): ?>
          <tr>
            <td><?php echo str_pad($row[Model_Filial::ID], 2, '0', STR_PAD_LEFT); ?></td>
            <td><?php echo $row['EMPRESA']; ?></td>
            <td><?php echo $row['FILIAL']; ?></td>
            <td><?php echo $row[Model_Filial::FILIAL]; ?></td>
            <td>
              <div class="btn-group">
                <a href="<?php echo site_url(); ?>filial/editar/<?php echo $row[Model_Filial::ID]; ?>" class="btn btn-primary">
                  <span class="glyphicon glyphicon-edit tooltips" title="Editar"></span>
                </a>
                <button type="button" onclick="deletar('<?php echo site_url(); ?>filial/deletar/<?php echo $row[Model_Filial::ID]; ?>', '<?php echo site_url(); ?>filial');
                        " class="btn btn-danger">
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