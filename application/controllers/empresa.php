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

class Empresa extends MY_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->Model('Model_Empresa');
  }
  
  public function index() {
    $this->data['menu_empresa'] = 'active';
    $this->data['menu_admin'] = 'active';
    $this->data['breadcrumb'] = $this->breadcrumb(array('Admin', 'empresa'));
    $this->data['empresa'] = $this->Model_Empresa->getAll();
    $this->MY_view('empresa/listar', $this->data);
  }
  
  public function novo() {
    $this->data['menu_empresa'] = 'active';
    $this->data['menu_admin'] = 'active';
    $this->data['breadcrumb'] = $this->breadcrumb(array('Admin', 'empresa', 'novo'));
    $this->MY_view('empresa/novo', $this->data);
  }

  public function cadastrar() {
    try {
      $this->validar();
      $campos = array(Model_Empresa::NOME, Model_Empresa::COLIGADA, Model_Empresa::FANTASIA, Model_Empresa::CODERP, Model_Empresa::LOJA);
      $dados = elements($campos, $this->POST);
      $dados[Model_Empresa::ID] = $this->Model_Empresa->autoincrement();
      settype($dados[Model_Empresa::ID], 'integer');
      settype($dados[Model_Empresa::COLIGADA], 'integer');
      $acao = $this->Model_Empresa->save($dados);

      if ($acao) {
        $this->session->set_flashdata('SUCESSO', 'Empresa cadastrada com sucesso.');
      } else {
        $this->session->set_flashdata('ERRO', 'Empresa não cadastrada.');
      }

      redirect('empresa');
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
      redirect('empresa/novo');
    }
  }

  private function validar() {
    if (empty($this->POST[Model_Empresa::NOME])) {
      throw new Exception('Campo <b>Nome</b> não pode ficar vazio.');
    }
    if (empty($this->POST[Model_Empresa::COLIGADA])) {
      throw new Exception('Campo <b>Coligada</b> não pode ficar vazio.');
    }
    if (!is_numeric($this->POST[Model_Empresa::COLIGADA])) {
      throw new Exception('Campo <b>Coligada</b> deve ser numérico.');
    }
  }

  public function editar($ID) {
    $this->data['menu_empresa'] = 'active';
    $this->data['menu_admin'] = 'active';
    $this->data['breadcrumb'] = $this->breadcrumb(array('Admin', 'empresa', 'editar'));
    $this->data['empresa'] = $this->Model_Empresa->get($ID)[0];
    $this->MY_view('empresa/editar', $this->data);
  }

  public function atualizar() {
    try {
      $this->validar();
      $campos = array(Model_Empresa::NOME, Model_Empresa::COLIGADA, Model_Empresa::FANTASIA, Model_Empresa::CODERP, Model_Empresa::LOJA);
      settype($dados[Model_Empresa::COLIGADA], 'integer');
      $dados = elements($campos, $this->POST);
      $acao = $this->Model_Empresa->save($dados, $this->POST[Model_Empresa::ID]);

      if ($acao) {
        $this->session->set_flashdata('SUCESSO', 'empresa atualizada com sucesso.');
      } else {
        $this->session->set_flashdata('ERRO', 'Empresa não atualizada.');
      }

      redirect('empresa');
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
      redirect('empresa/editar/' . $this->POST[Model_Empresa::ID]);
    }
  }

  public function deletar($ID = NULL) {
    try {
      if (is_numeric($ID)) {

        $acao = $this->Model_Empresa->deletar($ID);

        if ($acao) {
          $this->session->set_flashdata('SUCESSO', 'Empresa deletada com sucesso.');
        } else {
          $this->session->set_flashdata('ERRO', 'Empresa não deletada.');
        }
        redirect('empresa');
      } else {
        $this->session->set_flashdata('ERRO', 'Empresa inválida.');
        redirect('empresa');
      }
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
      redirect('empresa');
    }
  }

}
