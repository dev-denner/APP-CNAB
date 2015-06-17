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

class Banco extends MY_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->Model('Model_Banco');
  }

  public function index() {

    $this->data['menu_banco'] = 'active';
    $this->data['breadcrumb'] = $this->breadcrumb(array('Admin', 'banco'));
    $this->data['banco'] = $this->Model_Banco->getAll();
    $this->MY_view('banco/listar', $this->data);
  }

  public function novo() {

    $this->data['menu_banco'] = 'active';
    $this->data['menu_admin'] = 'active';
    $this->data['breadcrumb'] = $this->breadcrumb(array('Admin', 'banco', 'novo'));
    $this->MY_view('banco/novo', $this->data);
  }

  public function cadastrar() {
    try {
      $this->validar();
      $campos = array(Model_Banco::COD, Model_Banco::NOME);
      $dados = elements($campos, $this->POST);
      $dados[Model_Banco::ID] = $this->Model_Banco->autoincrement();
      settype($dados[Model_Banco::ID], 'integer');
      $acao = $this->Model_Banco->save($dados);

      if ($acao) {
        $this->session->set_flashdata('SUCESSO', 'Banco cadastrada com sucesso.');
      } else {
        $this->session->set_flashdata('ERRO', 'Banco não cadastrada.');
      }

      redirect('banco');
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
      redirect('banco/novo');
    }
  }

  private function validar() {
    if (empty($this->POST[Model_Banco::COD])) {
      throw new Exception('Campo <b>Código</b> não pode ficar vazio.');
    }
    if (empty($this->POST[Model_Banco::NOME])) {
      throw new Exception('Campo <b>Nome</b> não pode ficar vazio.');
    }
    if (!is_numeric($this->POST[Model_Banco::COD])) {
      throw new Exception('Campo <b>Código</b> deve ser numérico.');
    }
  }

  public function editar($ID) {
    $this->data['menu_banco'] = 'active';
    $this->data['menu_admin'] = 'active';
    $this->data['breadcrumb'] = $this->breadcrumb(array('Admin', 'banco', 'editar'));
    $this->data['banco'] = $this->Model_Banco->get($ID)[0];
    $this->MY_view('banco/editar', $this->data);
  }

  public function atualizar() {
    try {
      $this->validar();
      $campos = array(Model_Banco::COD, Model_Banco::NOME);
      $dados = elements($campos, $this->POST);
      $acao = $this->Model_Banco->save($dados, $this->POST[Model_Banco::ID]);

      if ($acao) {
        $this->session->set_flashdata('SUCESSO', 'Banco atualizada com sucesso.');
      } else {
        $this->session->set_flashdata('ERRO', 'Banco não atualizada.');
      }

      redirect('banco');
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
      redirect('banco/editar/' . $this->POST[Model_Banco::ID]);
    }
  }

  public function deletar($ID = NULL) {
    try {
      if (is_numeric($ID)) {

        $acao = $this->Model_Banco->deletar($ID);

        if ($acao) {
          $this->session->set_flashdata('SUCESSO', 'Banco deletada com sucesso.');
        } else {
          $this->session->set_flashdata('ERRO', 'Banco não deletada.');
        }
        redirect('banco');
      } else {
        $this->session->set_flashdata('ERRO', 'Banco inválida.');
        redirect('banco');
      }
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
      redirect('banco');
    }
  }

}
