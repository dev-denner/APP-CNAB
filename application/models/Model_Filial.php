<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model_Filial
 *
 * @author denner.fernandes
 */
class Model_Filial extends MY_Model {

  const TABELA = 'FILIAL';
  const ID = 'ID_FILIAL';
  const EMPRESA = 'CD_EMPRESA';
  const FILIAL = 'CD_FILIAL';
  const NOME = 'DS_DESCRICAO';

  public function __construct() {
    parent::__construct();
  }

  public function getAll($page = NULL, $paginacao = NULL) {

    try {

      $return = NULL;

      $this->db->select('FI.' . self::ID . ', EM.' . Model_Empresa::NOME . ' EMPRESA, ' .
                        'FI.' . self::NOME . ' FILIAL, ' . 'FI.' . self::FILIAL);
      $this->db->order_by(self::ID, "ASC");
      $this->db->join(Model_Empresa::TABELA . ' EM', 'EM.' . Model_Empresa::ID . ' = ' . self::EMPRESA);

      if (!is_null($page) && !is_null($paginacao)) {
        $query = $this->db->get(self::TABELA . ' FI', $page, $paginacao);
      } else {
        $query = $this->db->get(self::TABELA . ' FI');
      }

      $return = $query->result_array();

      if (!is_null($return)) {
        return $return;
      } else {
        throw new Exception('Não há registros.');
      }
    } catch (Exception $exc) {
      return $exc->getMessage();
    }
  }

}
