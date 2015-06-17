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

class Tipo_Operacao extends MY_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->Model('Model_Tipo_Operacao');
  }

  public function index() {

    $this->data['menu_tipo_operacao'] = 'active';
    $this->data['breadcrumb'] = $this->breadcrumb(array('Admin', 'tipo_operacao'));
    $this->data['tipo_operacao'] = $this->Model_Tipo_Operacao->getAll();
    $this->MY_view('tipo_operacao/listar', $this->data);
  }

  public function novo() {

    $this->data['menu_tipo_operacao'] = 'active';
    $this->data['menu_admin'] = 'active';
    $this->data['breadcrumb'] = $this->breadcrumb(array('Admin', 'tipo_operacao', 'novo'));
    $this->MY_view('tipo_operacao/novo', $this->data);
  }

  public function cadastrar() {
    try {
      $this->validar();
      $campos = array(Model_Tipo_Operacao::NOME);
      $dados = elements($campos, $this->POST);
      $dados[Model_Tipo_Operacao::ID] = $this->Model_Tipo_Operacao->autoincrement();
      settype($dados[Model_Tipo_Operacao::ID], 'integer');
      $acao = $this->Model_Tipo_Operacao->save($dados);

      if ($acao) {
        $this->session->set_flashdata('SUCESSO', 'Tipo de Operação cadastrada com sucesso.');
      } else {
        $this->session->set_flashdata('ERRO', 'Tipo de Operação não cadastrada.');
      }

      redirect('tipo_operacao');
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
      redirect('tipo_operacao/novo');
    }
  }

  private function validar() {
    if (empty($this->POST[Model_Tipo_Operacao::NOME])) {
      throw new Exception('Campo <b>Nome</b> não pode ficar vazio.');
    }
  }

  public function editar($ID) {
    $this->data['menu_tipo_operacao'] = 'active';
    $this->data['menu_admin'] = 'active';
    $this->data['breadcrumb'] = $this->breadcrumb(array('Admin', 'tipo_operacao', 'editar'));
    $this->data['tipo_operacao'] = $this->Model_Tipo_Operacao->get($ID)[0];
    $this->MY_view('tipo_operacao/editar', $this->data);
  }

  public function atualizar() {
    try {
      $this->validar();
      $campos = array(Model_Tipo_Operacao::NOME);
      $dados = elements($campos, $this->POST);
      $acao = $this->Model_Tipo_Operacao->save($dados, $this->POST[Model_Tipo_Operacao::ID]);

      if ($acao) {
        $this->session->set_flashdata('SUCESSO', 'Tipo de Operação atualizada com sucesso.');
      } else {
        $this->session->set_flashdata('ERRO', 'Tipo de Operação não atualizada.');
      }

      redirect('tipo_operacao');
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
      redirect('tipo_operacao/editar/' . $this->POST[Model_Tipo_Operacao::ID]);
    }
  }

  public function deletar($ID = NULL) {
    try {
      if (is_numeric($ID)) {

        $acao = $this->Model_Tipo_Operacao->deletar($ID);

        if ($acao) {
          $this->session->set_flashdata('SUCESSO', 'Tipo de Operação deletada com sucesso.');
        } else {
          $this->session->set_flashdata('ERRO', 'Tipo de Operação não deletada.');
        }
        redirect('tipo_operacao');
      } else {
        $this->session->set_flashdata('ERRO', 'Tipo de Operação inválida.');
        redirect('tipo_operacao');
      }
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
      redirect('tipo_operacao');
    }
  }

}
