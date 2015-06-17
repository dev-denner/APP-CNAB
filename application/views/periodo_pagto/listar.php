<div class="container">
  <div>
    <a href="<?php echo base_url('periodo_pagto/novo'); ?>" class="btn btn-success">
      <span class="glyphicon glyphicon-user tooltips" title="Novo"></span> Novo
    </a>
  </div>
  <br />
  <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover table-condensed datatable">
      <thead>
        <tr>
          <th width="30">ID</th>
          <th>NOME</th>
          <th width="100">AÇÕES</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($periodo_pagto as $row): ?>
          <tr>
            <td><?php echo $row[Model_Periodo_Pagto::ID] ?></td>
            <td><?php echo $row[Model_Periodo_Pagto::NOME] ?></td>
            <td>
              <div class="btn-group">
                <a href="<?php echo site_url(); ?>periodo_pagto/editar/<?php echo $row[Model_Periodo_Pagto::ID]; ?>" class="btn btn-primary">
                  <span class="glyphicon glyphicon-edit tooltips" title="Editar"></span>
                </a>
                <button type="button" onclick="deletar('<?php echo site_url(); ?>periodo_pagto/deletar/<?php echo $row[Model_Periodo_Pagto::ID]; ?>', '<?php echo site_url(); ?>periodo_pagto'); " class="btn btn-danger">
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