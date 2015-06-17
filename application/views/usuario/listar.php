<div class="container">
  <div>
    <a href="<?php echo base_url('usuario/novo'); ?>" class="btn btn-success">
      <span class="glyphicon glyphicon-user"></span> Novo Usuário
    </a>
  </div>
  <br />
  <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover table-condensed datatable">
      <thead>
        <tr>
          <th>ID</th>
          <th>LOGIN</th>
          <th>NOME</th>
          <th>EMAIL</th>
          <th>STATUS</th>
          <th>PERFIL</th>
          <th>AÇÕES</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($usuario as $row): ?>
          <tr>
            <td><?php echo $row[model_usuario::ID] ?></td>
            <td><?php echo $row[model_usuario::LOGIN] ?></td>
            <td><?php echo $row[model_usuario::NOME] ?></td>
            <td><?php echo $row[model_usuario::EMAIL] ?></td>
            <td><?php echo $row[model_usuario::STATUS] == 1 ? 'ATIVO' : 'DESATIVADO' ?></td>
            <td><?php echo $row[model_usuario::ACESSO] ?></td>
            <td>
              <?php if ($row[model_usuario::STATUS] == 1): ?>
                <div class="btn-group">
                  <a href="<?php echo site_url(); ?>usuario/editar/<?php echo $row[model_usuario::ID]; ?>" class="btn btn-primary ">
                    <span class="glyphicon glyphicon-edit tooltips" title="Editar"></span>
                  </a>
                  <a href="<?php echo site_url(); ?>usuario/desativar/<?php echo $row[model_usuario::ID]; ?>" class="btn btn-danger">
                    <span class="glyphicon glyphicon-remove-circle tooltips" title="Desativar"></span>
                  </a>
                </div>
              <?php else: ?>
                <a href="<?php echo site_url(); ?>usuario/ativar/<?php echo $row[model_usuario::ID]; ?>" class="btn btn-success ">
                  <span class="glyphicon glyphicon-exclamation-sign tooltips" title="Ativar"></span>
                </a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</div> <!-- /container --> 