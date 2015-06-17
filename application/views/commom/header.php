<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/main.css">
    <script src="<?php echo base_url('assets/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js') ?>"></script>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url() ?>assets/icons/favicon.ico">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <!--[if lt IE 7]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
    <![endif]-->

    <header class="navbar navbar-default"  role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" id="logo" href="<?php site_url(); ?>">
            <h1 class="text-hide">Grupo MPE</h1>
          </a>
        </div>
        <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
          <ul class="nav navbar-nav">
            <li class="<?php echo isset($menu_dashboard) ? $menu_dashboard : ''; ?>">
              <a href="<?php echo site_url(); ?>dashboard"><span class="glyphicon glyphicon-dashboard"></span> Dashboard</a>
            </li>
            <li class="dropdown <?php echo isset($menu_cnab) ? $menu_cnab : ''; ?>">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-money"></i> CNAB <b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                <li class="<?php echo isset($menu_cnab_gerar) ? $menu_cnab_gerar : ''; ?>">
                  <a href="<?php echo site_url(); ?>cnab">Gerar</a>
                </li>
                <li class="<?php echo isset($menu_cnab_validar) ? $menu_cnab_validar : ''; ?>">
                  <a href="<?php echo site_url(); ?>cnab/validar">Validar</a>
                </li>
              </ul>
            </li>
            <li class="dropdown <?php echo isset($menu_admin) ? $menu_admin : ''; ?>">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="glyphicon glyphicon-wrench"></span> Admin <b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                <?php if ($user['NU_PRIVILEGIO'] >= 90): ?>
                  <li class="<?php echo isset($menu_empresa) ? $menu_empresa : ''; ?>">
                    <a href="<?php echo site_url(); ?>empresa">Empresa</a>
                  </li>
                  <li class="<?php echo isset($menu_filial) ? $menu_filial : ''; ?>">
                    <a href="<?php echo site_url(); ?>filial">Filial</a>
                  </li>
                  <li class="<?php echo isset($menu_banco) ? $menu_banco : ''; ?>">
                    <a href="<?php echo site_url(); ?>banco">Banco</a>
                  </li>
                  <li class="<?php echo isset($menu_conta_bancaria) ? $menu_conta_bancaria : ''; ?>">
                    <a href="<?php echo site_url(); ?>conta_bancaria">Conta Bancária</a>
                  </li>
                  <li class="<?php echo isset($menu_tipo_operacao) ? $menu_tipo_operacao : ''; ?>">
                    <a href="<?php echo site_url(); ?>tipo_operacao">Tipo de Operação</a>
                  </li>
                  <li role="presentation" class="divider"></li>
                  <li class="<?php echo isset($menu_usuario) ? $menu_usuario : ''; ?>">
                    <a href="<?php echo site_url(); ?>usuario">Usuários</a>
                  </li>
                <?php endif; ?>
                <li class="<?php echo isset($menu_funcionario) ? $menu_funcionario : ''; ?>">
                  <a href="<?php echo site_url(); ?>funcionario">Funcionário</a>
                </li>
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="glyphicon glyphicon-user"></span> <?php echo $user[Model_Usuario::NOME]; ?> <b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                <li class="dropdown-header">INFO</li>
                <li class="divider"></li>
                <li><a><b>E-mail:</b> <?php echo $user[Model_Usuario::EMAIL]; ?></a></li>
                <li><a><b>AD:</b> <?php echo $user[Model_Usuario::LOGIN]; ?></a></li>
                <li><a href="<?php echo site_url(); ?>usuario/editar/<?php echo $user[Model_Usuario::ID]; ?>"><span class="glyphicon glyphicon-edit"></span> Editar Usuario</a></li>
              </ul>
            </li>
            <li class=""><a href="<?php echo site_url(); ?>login/logoff"><span class="glyphicon glyphicon-off"></span> Sair</a></li>
          </ul>
        </nav><!--/.navbar-collapse -->
      </div>
    </header>
    <div class="container">
      <?php echo $breadcrumb; ?>
    </div>
    <div class="overlay">
      <div class="media">
        <a class="pull-left">
          <img src="<?php echo base_url(); ?>assets/img/loader.gif" />
        </a>
        <div class="media-body">
          <h4 class="media-heading">Carregando...</h4>

        </div>
      </div>
    </div>