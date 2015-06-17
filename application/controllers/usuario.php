<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Usuario extends MY_Controller {

  public function __construct() {
    parent::__construct();
  }

  public function index() {
    $this->data['menu_usuario'] = 'active';
    $this->data['menu_admin'] = 'active';
    $this->data['usuario'] = $this->Model_Usuario->getAll();
    $this->data['breadcrumb'] = $this->breadcrumb(array('Admin', 'usuario'));
    $this->MY_view('usuario/listar', $this->data);
  }

  public function novo() {
    $this->data['menu_usuario'] = 'active';
    $this->data['menu_admin'] = 'active';
    $this->data['breadcrumb'] = $this->breadcrumb(array('Admin', 'usuario', 'novo'));
    $this->MY_view('usuario/novo', $this->data);
  }

  public function cadastrar() {
    try {
      $this->validar();
      $campos = array(model_usuario::LOGIN, model_usuario::SENHA, model_usuario::NOME, model_usuario::EMAIL, model_usuario::ACESSO);
      $dados = elements($campos, $this->POST);
      settype($dados[model_usuario::ACESSO], 'integer');
      $dados[model_usuario::SENHA] = md5($dados[model_usuario::SENHA]);
      $dados[model_usuario::STATUS] = 1;
      $dados[model_usuario::ID] = $this->Model_Usuario->autoincrement();

      $acao = $this->Model_Usuario->save($dados);

      if ($acao) {
        $this->session->set_flashdata('SUCESSO', 'Usuário cadastrado com sucesso.');
      } else {
        $this->session->set_flashdata('ERRO', 'Usuário não cadastrado.');
      }

      redirect('usuario');
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
      redirect('usuario/novo');
    }
  }

  private function validar($pula_login = FALSE) {
    if (!$pula_login) {
      if (empty($this->POST[model_usuario::LOGIN])) {
        throw new Exception('Campo <b>Login</b> não pode ficar vazio.');
      }
      if (isset($this->Model_Usuario->get(array(model_usuario::LOGIN => $this->POST[model_usuario::LOGIN]))[0])) {
        throw new Exception('O usuário digitado já está cadastrado. Digite outro usuario.');
      }
    }
    if (empty($this->POST[model_usuario::SENHA])) {
      throw new Exception('Campo <b>Senha</b> não pode ficar vazio.');
    }
    if ($this->POST[model_usuario::SENHA] != $this->POST['confirm_senha']) {
      throw new Exception('Senhas não conferem. Digite duas senhas iguais');
    }
    if (empty($this->POST[model_usuario::NOME])) {
      throw new Exception('Campo <b>Nome</b> não pode ficar vazio.');
    }
    if (empty($this->POST[model_usuario::EMAIL])) {
      throw new Exception('Campo <b>E-mail</b> não pode ficar vazio.');
    }
    if (empty($this->POST[model_usuario::ACESSO])) {
      throw new Exception('Escolha como este usuário acessará o sistema.');
    }
  }

  public function editar($ID) {
    $this->data['menu_usuario'] = 'active';
    $this->data['menu_admin'] = 'active';
    $this->data['breadcrumb'] = $this->breadcrumb(array('Admin', 'usuario', 'editar'));
    $this->data['usuario'] = $this->Model_Usuario->get($ID)[0];
    $this->MY_view('usuario/editar', $this->data);
  }

  public function atualizar() {
    try {
      $this->validar(TRUE);
      $campos = array(model_usuario::SENHA, model_usuario::NOME, model_usuario::EMAIL, model_usuario::ACESSO);
      $dados = elements($campos, $this->POST);
      settype($dados[model_usuario::ACESSO], 'integer');
      $dados[model_usuario::SENHA] = md5($dados[model_usuario::SENHA]);
      settype($this->POST[model_usuario::ID], 'integer');
      $acao = $this->Model_Usuario->save($dados, $this->POST[model_usuario::ID]);

      if ($acao) {
        $this->session->set_flashdata('SUCESSO', 'Usuário atualizado com sucesso.');
      } else {
        $this->session->set_flashdata('ERRO', 'Usuário não atualizado.');
      }

      redirect('usuario');
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
      redirect('usuario/editar/' . $this->POST[model_usuario::ID]);
    }
  }

  public function desativar($ID = NULL) {
    try {
      if (is_numeric($ID)) {

        $acao = $this->Model_Usuario->desativar(array(model_usuario::STATUS => 0), $ID);

        if ($acao) {
          $this->session->set_flashdata('SUCESSO', 'Usuário desativado com sucesso.');
        } else {
          $this->session->set_flashdata('ERRO', 'Usuário não desativado.');
        }
        redirect('usuario');
      } else {
        $this->session->set_flashdata('ERRO', 'Usuario inválido.');
        redirect('usuario');
      }
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
      redirect('usuario');
    }
  }

  public function ativar($ID = NULL) {
    try {

      if (is_numeric($ID)) {

        $acao = $this->Model_Usuario->desativar(array(model_usuario::STATUS => 1), $ID);

        if ($acao) {
          $this->session->set_flashdata('SUCESSO', 'Usuário ativado com sucesso.');
        } else {
          $this->session->set_flashdata('ERRO', 'Usuário não ativado.');
        }
        redirect('usuario');
      } else {
        $this->session->set_flashdata('ERRO', 'Usuario inválido.');
        redirect('usuario');
      }
    } catch (Exception $exc) {
      echo $exc->getMessage();
    }
  }

}
