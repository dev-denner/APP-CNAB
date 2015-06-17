<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Dashboard extends MY_Controller {

  public function __construct() {
    parent::__construct();
  }

  public function index() {
    $this->data['menu_dashboard'] = 'active';
    $this->data['breadcrumb'] = $this->breadcrumb('Dashboard');
    $this->MY_view('dashboard', $this->data);
  }

}
