<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Login extends MY_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->library('form_validation');
  }

  public function index() {
    $this->load->view('commom/alerts', $this->data);
    $this->load->view('login', $this->data);
  }

  public function logar() {

    if ($this->POST) {
      if ($this->POST['login'] && !empty($this->POST['senha'])) {

        $criterio = array(model_usuario::LOGIN => $this->POST['login'], model_usuario::SENHA => md5($this->POST['senha']), model_usuario::STATUS => 1);
        
        $usuario = $this->Model_Usuario->get($criterio);

        if (empty($usuario)) {
          $this->session->set_flashdata('ERRO', '<b>Login e/ou Senha não encontradas!</b>');
          redirect('login');
        } else {
          $token = $this->Model_Usuario->setToken($usuario[0][model_usuario::ID]);
          $this->session->set_userdata(parent::session_usu, $token);
          redirect('dashboard');
        }
      } else {
        $this->session->set_flashdata('ERRO', '<b>Digite seu Login e Senha!</b>');
        redirect('login');
      }
    } else {
      $this->load->view('login', $data);
    }
  }

  public function logoff() {
    $this->session->sess_destroy();
    redirect();
  }

}
