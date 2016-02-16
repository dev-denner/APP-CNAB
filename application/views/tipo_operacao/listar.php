<div class="container">
  <div>
    <a href="<?php echo base_url('tipo_operacao/novo'); ?>" class="btn btn-success">
      <span class="fa fa-asterisk"></span> Novo Tipo de Operação
    </a>
  </div>
  <br />
  <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover table-condensed datatable">
      <thead>
        <tr>
          <th>ID</th>
          <th>NOME</th>
          <th>NATUREZA</th>
          <th>TIPO</th>
          <th>PERIODO</th>
          <th width="100">AÇÕES</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($tipo_operacao as $row): ?>
          <tr>
            <td><?php echo $row[Model_Tipo_Operacao::ID] ?></td>
            <td><?php echo $row[Model_Tipo_Operacao::NOME] ?></td>
            <td><?php echo $row[Model_Tipo_Operacao::NATUREZA] ?></td>
            <td><?php echo $row[Model_Tipo_Operacao::TIPO] ?></td>
            <td><?php echo $row[Model_Tipo_Operacao::PERIODO] ?></td>
            <td>
              <div class="btn-group">
                <a href="<?php echo site_url(); ?>tipo_operacao/editar/<?php echo $row[Model_Tipo_Operacao::ID]; ?>" class="btn btn-primary">
                  <span class="glyphicon glyphicon-edit tooltips" title="Editar"></span>
                </a>
                <button type="button" onclick="deletar('<?php echo site_url(); ?>tipo_operacao/deletar/<?php echo $row[Model_Tipo_Operacao::ID]; ?>', '<?php echo site_url(); ?>tipo_operacao'); " class="btn btn-danger">
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