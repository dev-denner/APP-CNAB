<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Log extends MY_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->Model('Model_Log');
  }

  public function index() {
    $this->load->view('commom/alerts', $this->data);
    $this->load->view('log/listar', $this->data);
  }

}