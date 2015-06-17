<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('assets/icons/favicon.ico'); ?>">

    <title>Grupo MPE :: CNAB</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/main.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/login.css'); ?>">    
    <script src="<?php echo base_url('assets/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js'); ?>"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
      <?php echo validation_errors(); ?>
      <?php echo form_open('login/logar', 'class="form-signin"'); ?>
      <h1 class="form-signin-heading text-center">
        <img src="<?php echo base_url() ?>assets/img/logo.png" title="GRUPO MPE" />
      </h1>
      <h2 class="form-signin-heading text-center">CNAB</h2>
      <?php echo form_input('login', '', 'class="form-control" placeholder="Login" required autofocus'); ?>
      <?php echo form_password('senha', '', 'class="form-control" placeholder="Senha" required'); ?>
      <!--<label class="checkbox">
        <input type="checkbox" value="remember-me"> Lembrar
      </label>-->
      <?php echo form_submit('submit', 'Entrar', 'class="btn btn-lg btn-primary btn-block"'); ?>
      <?php form_close(); ?>
    </div> <!-- /container -->
    <footer id="footer">
      <div class="container">
        <p class="text-right text-success">&copy; Grupo MPE 2014</p>
      </div>
    </footer>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="<?php echo base_url('assets/js/vendor/jquery-1.10.1.min.js'); ?>"><\/script>')</script>
    <script src="<?php echo base_url('assets/js/vendor/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/main.js'); ?>"></script>
  </body>
</html>