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

class Filial extends MY_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->Model('Model_Filial');
    $this->load->Model('Model_Empresa');
  }

  public function index() {

    $this->data['menu_filial'] = 'active';
    $this->data['breadcrumb'] = $this->breadcrumb(array('Admin', 'filial'));
    $this->data['filial'] = $this->Model_Filial->getAll();
    $this->MY_view('filial/listar', $this->data);
  }

  public function novo() {

    $this->data['menu_filial'] = 'active';
    $this->data['menu_admin'] = 'active';
    $this->data['empresa'] = $this->Model_Empresa->getAll();
    $this->data['breadcrumb'] = $this->breadcrumb(array('Admin', 'filial', 'novo'));
    $this->MY_view('filial/novo', $this->data);
  }

  public function cadastrar() {
    try {
      $this->validar();
      $campos = array(Model_Filial::EMPRESA, Model_Filial::NOME, Model_Filial::FILIAL);
      $dados = elements($campos, $this->POST);
      $dados[Model_Filial::ID] = $this->Model_Filial->autoincrement();
      settype($dados[Model_Filial::ID], 'integer');
      settype($dados[Model_Filial::EMPRESA], 'integer');
      settype($dados[Model_Filial::FILIAL], 'integer');
      $acao = $this->Model_Filial->save($dados);

      if ($acao) {
        $this->session->set_flashdata('SUCESSO', 'Filial cadastrada com sucesso.');
      } else {
        $this->session->set_flashdata('ERRO', 'Filial não cadastrada.');
      }

      redirect('filial');
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
      redirect('filial/novo');
    }
  }

  private function validar() {
    if (empty($this->POST[Model_Filial::NOME])) {
      throw new Exception('Campo <b>Nome</b> não pode ficar vazio.');
    }
    if (empty($this->POST[Model_Filial::FILIAL])) {
      throw new Exception('Campo <b>Filial</b> não pode ficar vazio.');
    }
    if (!is_numeric($this->POST[Model_Filial::FILIAL])) {
      throw new Exception('Campo <b>Filial</b> deve ser numérico.');
    }
  }

  public function editar($ID) {
    $this->data['menu_filial'] = 'active';
    $this->data['menu_admin'] = 'active';
    $this->data['breadcrumb'] = $this->breadcrumb(array('Admin', 'filial', 'editar'));
    $this->data['empresa'] = $this->Model_Empresa->getAll();
    $this->data['filial'] = $this->Model_Filial->get($ID)[0];
    $this->MY_view('filial/editar', $this->data);
  }

  public function atualizar() {
    try {
      $this->validar();
      $campos = array(Model_Filial::EMPRESA, Model_Filial::NOME, Model_Filial::FILIAL);
      $dados = elements($campos, $this->POST);
      settype($dados[Model_Filial::EMPRESA], 'integer');
      settype($dados[Model_Filial::FILIAL], 'integer');
      $acao = $this->Model_Filial->save($dados, $this->POST[Model_Filial::ID]);

      if ($acao) {
        $this->session->set_flashdata('SUCESSO', 'Filial atualizada com sucesso.');
      } else {
        $this->session->set_flashdata('ERRO', 'Filial não atualizada.');
      }

      redirect('filial');
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
      redirect('filial/editar/' . $this->POST[Model_Filial::ID]);
    }
  }

  public function deletar($ID = NULL) {
    try {
      if (is_numeric($ID)) {

        $acao = $this->Model_Filial->deletar($ID);

        if ($acao) {
          $this->session->set_flashdata('SUCESSO', 'Filial deletada com sucesso.');
        } else {
          $this->session->set_flashdata('ERRO', 'Filial não deletada.');
        }
        redirect('filial');
      } else {
        $this->session->set_flashdata('ERRO', 'Filial inválida.');
        redirect('filial');
      }
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
      redirect('filial');
    }
  }

  public function selectFilial($ID) {
    try {
      if (is_numeric($ID)) {
        $filial = $this->Model_Filial->get(array(Model_Filial::EMPRESA => $ID));
        $retorno = '';
        if (is_array($filial)) {
          foreach ($filial as $row => $value) {
            $retorno .= '<option value="' . $value[Model_Filial::ID] . '">' . $value[Model_Filial::FILIAL] . ' - ' . $value[Model_Filial::NOME] . '</option>';
          }
        } else {
          $retorno = '<option value="">Não há filiais cadastradas para esta empresa.</option>';
        }
        echo $retorno;
      } else {
        throw new Exception('ERRO!!|Acesso não permitido a função.');
      }
    } catch (Exception $exc) {
      echo $exc->getMessage();
    }
  }

}
