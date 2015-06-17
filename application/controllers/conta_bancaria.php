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

class Conta_Bancaria extends MY_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->Model('Model_Conta_Bancaria');
    $this->load->Model('Model_Empresa');
    $this->load->Model('Model_Banco');
  }

  public function index() {
    $this->data['menu_conta_bancaria'] = 'active';
    $this->data['breadcrumb'] = $this->breadcrumb(array('Admin', 'conta_bancaria'));
    $this->data['conta_bancaria'] = $this->Model_Conta_Bancaria->getAll();
    $this->MY_view('conta_bancaria/listar', $this->data);
  }

  public function novo() {
    $this->data['menu_conta_bancaria'] = 'active';
    $this->data['menu_admin'] = 'active';
    $this->data['empresa'] = $this->Model_Empresa->getAll();
    $this->data['banco'] = $this->Model_Banco->getAll();
    $this->data['breadcrumb'] = $this->breadcrumb(array('Admin', 'conta_bancaria', 'novo'));
    $this->MY_view('conta_bancaria/novo', $this->data);
  }

  public function cadastrar() {
    try {
      $this->validar();
      $campos = array(Model_Conta_Bancaria::AGENCIA, Model_Conta_Bancaria::CONTA, Model_Conta_Bancaria::DIGITO, Model_Conta_Bancaria::CC, Model_Conta_Bancaria::CODEMPRESA, Model_Conta_Bancaria::EMPRESA, Model_Conta_Bancaria::BANCO, Model_Conta_Bancaria::DIGITOAG, Model_Conta_Bancaria::CONVENIO);
      $dados = elements($campos, $this->POST);
      $dados[Model_Conta_Bancaria::ID] = $this->Model_Conta_Bancaria->autoincrement();

      settype($dados[Model_Conta_Bancaria::ID], 'integer');
      settype($dados[Model_Conta_Bancaria::AGENCIA], 'integer');
      settype($dados[Model_Conta_Bancaria::CONTA], 'integer');
      settype($dados[Model_Conta_Bancaria::CC], 'integer');
      settype($dados[Model_Conta_Bancaria::EMPRESA], 'integer');

      $acao = $this->Model_Conta_Bancaria->save($dados);

      if ($acao) {
        $this->session->set_flashdata('SUCESSO', 'Conta Bancária cadastrada com sucesso.');
      } else {
        $this->session->set_flashdata('ERRO', 'Conta Bancária não cadastrada.');
      }

      redirect('conta_bancaria');
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
      redirect('conta_bancaria/novo');
    }
  }

  private function validar() {
    if (empty($this->POST[Model_Conta_Bancaria::AGENCIA])) {
      throw new Exception('Campo <b>Agência</b> não pode ficar vazio.');
    }
    if (empty($this->POST[Model_Conta_Bancaria::CONTA])) {
      throw new Exception('Campo <b>Conta</b> não pode ficar vazio.');
    }
    if (empty($this->POST[Model_Conta_Bancaria::CC])) {
      throw new Exception('Campo <b>Dígito</b> não pode ficar vazio.');
    }
    if (empty($this->POST[Model_Conta_Bancaria::BANCO])) {
      throw new Exception('Campo <b>Banco</b> não pode ficar vazio.');
    }
    if (!is_numeric($this->POST[Model_Conta_Bancaria::AGENCIA])) {
      throw new Exception('Campo <b>Agência</b> deve ser numérico.');
    }
    if (!is_numeric($this->POST[Model_Conta_Bancaria::CONTA])) {
      throw new Exception('Campo <b>Conta</b> deve ser numérico.');
    }
    if (!is_numeric($this->POST[Model_Conta_Bancaria::CC])) {
      throw new Exception('Campo <b>Razão C/C</b> deve ser numérico.');
    }
  }

  public function editar($ID) {
    $this->data['menu_conta_bancaria'] = 'active';
    $this->data['menu_admin'] = 'active';
    $this->data['breadcrumb'] = $this->breadcrumb(array('Admin', 'conta_bancaria', 'editar'));
    $this->data['empresa'] = $this->Model_Empresa->getAll();
    $this->data['banco'] = $this->Model_Banco->getAll();
    $this->data['conta_bancaria'] = $this->Model_Conta_Bancaria->get($ID)[0];
    $this->MY_view('conta_bancaria/editar', $this->data);
  }

  public function atualizar() {
    try {
      $this->validar();
      $campos = array(Model_Conta_Bancaria::AGENCIA, Model_Conta_Bancaria::CONTA, Model_Conta_Bancaria::DIGITO, Model_Conta_Bancaria::CC, Model_Conta_Bancaria::CODEMPRESA, Model_Conta_Bancaria::EMPRESA, Model_Conta_Bancaria::BANCO, Model_Conta_Bancaria::DIGITOAG, Model_Conta_Bancaria::CONVENIO);
      $dados = elements($campos, $this->POST);

      settype($dados[Model_Conta_Bancaria::AGENCIA], 'integer');
      settype($dados[Model_Conta_Bancaria::CONTA], 'integer');
      settype($dados[Model_Conta_Bancaria::CC], 'integer');
      settype($dados[Model_Conta_Bancaria::EMPRESA], 'integer');
      settype($dados[Model_Conta_Bancaria::BANCO], 'integer');

      $acao = $this->Model_Conta_Bancaria->save($dados, $this->POST[Model_Conta_Bancaria::ID]);

      if ($acao) {
        $this->session->set_flashdata('SUCESSO', 'Conta Bancária atualizada com sucesso.');
      } else {
        $this->session->set_flashdata('ERRO', 'Conta Bancária não atualizada.');
      }

      redirect('conta_bancaria');
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
      redirect('conta_bancaria/editar/' . $this->POST[Model_Conta_Bancaria::ID]);
    }
  }

  public function deletar($ID = NULL) {
    try {
      if (is_numeric($ID)) {

        $acao = $this->Model_Conta_Bancaria->deletar($ID);

        if ($acao) {
          $this->session->set_flashdata('SUCESSO', 'Conta Bancária deletada com sucesso.');
        } else {
          $this->session->set_flashdata('ERRO', 'Conta Bancária não deletada.');
        }
        redirect('conta_bancaria');
      } else {
        $this->session->set_flashdata('ERRO', 'Conta Bancária inválida.');
        redirect('conta_bancaria');
      }
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
      redirect('conta_bancaria');
    }
  }

}
