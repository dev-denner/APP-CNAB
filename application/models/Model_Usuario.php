<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model_Usuario
 *
 * @author denner.fernandes
 */
class model_usuario extends MY_Model {

  const TABELA = 'USUARIO';
  const ID = 'ID_USUARIO';
  const LOGIN = 'DS_LOGIN';
  const SENHA = 'DS_SENHA';
  const NOME = 'NM_NOME';
  const EMAIL = 'DS_EMAIL';
  const TOKEN = 'DS_TOKEN';
  const STATUS = 'ST_STATUS';
  const ACESSO = 'NU_PRIVILEGIO';
  
  public function __construct() {
    parent::__construct();
  }

  public function get($criterio = NULL) {
    switch ($criterio) {
      case is_numeric($criterio):
        $this->getByID($criterio);
        break;

      case filter_var($criterio, FILTER_VALIDATE_EMAIL):
        $this->getByEmail($EMAIL);
        break;

      case is_string($criterio):
        $this->getByToken($criterio);
        break;

      case is_array($criterio):
        $this->getByCriterio($criterio);
        break;
    }
    return $this->dados;
  }

  private function getByID($ID) {
    $this->db->where(self::ID, $ID);
    $query = $this->db->get(self::TABELA);
    $this->dados = $query->result_array();
  }

  public function setToken($ID = NULL) {
    if (is_numeric($ID)) {
      $this->db->where(self::ID, $ID);
      $token[self::TOKEN] = md5(mktime() . $ID);
      $this->db->update(self::TABELA, $token);
    }
    return $token[self::TOKEN];
  }

  private function getByToken($token) {
    $this->db->where(self::TOKEN, $token);
    $this->db->where(self::STATUS, 1);
    $query = $this->db->get(self::TABELA);
    $this->dados = $query->result_array();
  }

  private function getByEmail($email) {
    $this->db->where(self::EMAIL, $email);
    $query = $this->db->get(self::TABELA);
    $this->dados = $query->result_array();
  }

  private function getByCriterio($criterio) {
    $this->db->where($criterio);
    $query = $this->db->get(self::TABELA);
    $this->dados = $query->result_array();
  }

}
