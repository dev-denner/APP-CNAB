<div class="container">
  <?php if ($ERRO) { ?>
    <div class="alert alert-danger alert-dismissable">
      <button class="close" data-dismiss="alert"  aria-hidden="true">×</button>
      <strong>ERRO!</strong> <?php echo $ERRO; ?> 
    </div>
  <?php } ?>

  <?php if ($INFO) { ?>
    <div class="alert alert-info alert-dismissable">
      <button class="close" data-dismiss="alert">×</button>
      <strong>INFO!</strong> <?php echo $INFO; ?>
    </div>
  <?php } ?>

  <?php if ($SUCESSO) { ?>
    <div class="alert alert-success alert-dismissable">
      <button class="close" data-dismiss="alert">×</button>
      <strong>SUCESSO!</strong> <?php echo $SUCESSO; ?>
    </div>
  <?php } ?>
</div>