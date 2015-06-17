<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cnab
 *
 * @author denner.fernandes
 */
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Periodo_Pagto extends MY_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->Model('Model_Periodo_Pagto');
  }

  public function index() {

    $this->data['menu_periodo_pagto'] = 'active';
    $this->data['breadcrumb'] = $this->breadcrumb(array('Admin', 'periodo_pagto'));
    $this->data['periodo_pagto'] = $this->Model_Periodo_Pagto->getAll();
    $this->MY_view('periodo_pagto/listar', $this->data);
  }

  public function novo() {

    $this->data['menu_periodo_pagto'] = 'active';
    $this->data['menu_admin'] = 'active';
    $this->data['breadcrumb'] = $this->breadcrumb(array('Admin', 'periodo_pagto', 'novo'));
    $this->MY_view('periodo_pagto/novo', $this->data);
  }

  public function cadastrar() {
    try {
      $this->validar();
      $campos = array(Model_Periodo_Pagto::NOME);
      $dados = elements($campos, $this->POST);
      $dados[Model_Periodo_Pagto::ID] = $this->Model_Periodo_Pagto->autoincrement();
      settype($dados[Model_Periodo_Pagto::ID], 'integer');
      $acao = $this->Model_Periodo_Pagto->save($dados);

      if ($acao) {
        $this->session->set_flashdata('SUCESSO', 'Período Pagamento cadastrado com sucesso.');
      } else {
        $this->session->set_flashdata('ERRO', 'Período Pagamento não cadastrado.');
      }

      redirect('periodo_pagto');
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
      redirect('periodo_pagto/novo');
    }
  }

  private function validar() {
    if (empty($this->POST[Model_Periodo_Pagto::NOME])) {
      throw new Exception('Campo <b>Nome</b> não pode ficar vazio.');
    }
  }

  public function editar($ID) {
    $this->data['menu_periodo_pagto'] = 'active';
    $this->data['menu_admin'] = 'active';
    $this->data['breadcrumb'] = $this->breadcrumb(array('Admin', 'periodo_pagto', 'editar'));
    $this->data['periodo_pagto'] = $this->Model_Periodo_Pagto->get($ID)[0];
    $this->MY_view('periodo_pagto/editar', $this->data);
  }

  public function atualizar() {
    try {
      $this->validar();
      $campos = array(Model_Periodo_Pagto::NOME);
      $dados = elements($campos, $this->POST);
      $acao = $this->Model_Periodo_Pagto->save($dados, $this->POST[Model_Periodo_Pagto::ID]);

      if ($acao) {
        $this->session->set_flashdata('SUCESSO', 'Período Pagamento atualizada com sucesso.');
      } else {
        $this->session->set_flashdata('ERRO', 'Período Pagamento não atualizada.');
      }

      redirect('periodo_pagto');
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
      redirect('periodo_pagto/editar/' . $this->POST[Model_Periodo_Pagto::ID]);
    }
  }

  public function deletar($ID = NULL) {
    try {
      if (is_numeric($ID)) {

        $acao = $this->Model_Periodo_Pagto->deletar($ID);

        if ($acao) {
          $this->session->set_flashdata('SUCESSO', 'Período Pagamento deletada com sucesso.');
        } else {
          $this->session->set_flashdata('ERRO', 'Período Pagamento não deletada.');
        }
        redirect('periodo_pagto');
      } else {
        $this->session->set_flashdata('ERRO', 'Período Pagamento inválida.');
        redirect('periodo_pagto');
      }
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
      redirect('periodo_pagto');
    }
  }

}
