<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class MY_Controller extends CI_Controller {

  const session_usu = 'Token';

  public $data = array();
  protected $POST = NULL;
  protected $user_info = array();

  public function __construct() {
    parent::__construct();

    date_default_timezone_set('America/Sao_Paulo');

    $this->VAR['URI'] = $this->uri->segment_array();
    if (@$this->VAR['URI'][1] != 'login') {
      $this->set_user_info();
    }
    $this->data['ERRO'] = $this->session->flashdata('ERRO');
    $this->data['INFO'] = $this->session->flashdata('INFO');
    $this->data['SUCESSO'] = $this->session->flashdata('SUCESSO');

    $this->POST = $this->input->post(NULL, TRUE);
  }

  public function is_ajax() {
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
  }

  public function MY_view($view, $data, $header = true, $footer = true) {

    if ($header) {
      $this->load->view('commom/header', $data);
    } else {
      $this->load->view($header, $data);
    }
    $this->load->view('commom/alerts');
    $this->load->view($view, $data);

    if ($footer) {
      $this->load->view('commom/footer', $data);
    } else {
      $this->load->view($footer, $data);
    }
  }

  private function set_user_info() {

    $token = $this->session->userdata(self::session_usu);
    if ($token) {
      $this->user_info = $this->Model_Usuario->get($token);

      if (empty($this->user_info)) {
        redirect('login');
      } else {
        $this->user_info = $this->user_info[0];
        $this->data['user'] = $this->user_info;
      }
    } else {
      redirect('login');
    }
  }

  protected function breadcrumb($migalhas = 'dashboard') {
    $retorno = '<ol class="breadcrumb">';
    $mig = '';
    if (is_array($migalhas)) {
      for ($i = 0; $i < count($migalhas) - 1; $i++) {
        if ($migalhas[$i] == 'Admin' || $migalhas[$i] == 'Relatório') {
          $retorno .= '<li>' . $migalhas[$i] . '</li>';
        } else {

          $arrayView = explode('_', $migalhas[$i]);

          $view = '';
          for ($j = 0; $j <= count($arrayView) - 1; $j++) {
            $view .= ucfirst($arrayView[$j]) . ' ';
          }

          $retorno .= '<li><a href="' . base_url() . $mig . $migalhas[$i] . '">' . $view . '</a></li>';
          $mig .= $migalhas[$i] . '/';
        }
      }

      $arrayView = explode('_', $migalhas[count($migalhas) - 1]);

      $view = '';
      for ($j = 0; $j <= count($arrayView) - 1; $j++) {
        $view .= ucfirst($arrayView[$j]) . ' ';
      }

      $retorno .= '<li class="active">' . $view . '</li></ol>';
    } else {
      $retorno .= '<li class="active">' . $migalhas . '</li></ol>';
    }
    return $retorno;
  }

  function create_breadcrumb() {
    $ci = &get_instance();
    $i = 1;
    $uri = $ci->uri->segment($i);
    $link = '<ol class="breadcrumb">';

    while ($uri != '') {
      $prep_link = '';
      for ($j = 1; $j <= $i; $j++) {
        $prep_link .= $ci->uri->segment($j) . '/';
      }

      if ($ci->uri->segment($i + 1) == '') {
        $link.='<li>»<a href="' . site_url($prep_link) . '"><b>';
        $link.=$ci->uri->segment($i) . '</b></a></li> ';
      } else {
        $link.='<li>» <a href="' . site_url($prep_link) . '">';
        $link.=$ci->uri->segment($i) . '</a></li> ';
      }

      $i++;
      $uri = $ci->uri->segment($i);
    }
    $link .= '</ol>';
    return $link;
  }

  public function __destruct() {
    
  }

}
