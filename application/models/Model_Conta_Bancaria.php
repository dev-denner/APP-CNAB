<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model_Conta_Bancaria
 *
 * @author denner.fernandes
 */
class Model_Conta_Bancaria extends MY_Model {

  const TABELA = 'CONTA_BANCARIA';
  const ID = 'ID_CONTA_BANCARIA';
  const AGENCIA = 'CD_AGENCIA';
  const DIGITOAG = 'CD_AGENCIA_DIGITO';
  const CONTA = 'CD_CONTA';
  const DIGITO = 'CD_CONTA_DIGITO';
  const CC = 'CD_RAZAO_CC';
  const CODEMPRESA = 'CD_EMPRESA_BANCO';
  const EMPRESA = 'CD_EMPRESA';
  const BANCO = 'CD_BANCO';
  const CONVENIO = 'CD_CONVENIO';

  public function __construct() {
    parent::__construct();
  }

  public function getAll($page = NULL, $paginacao = NULL) {

    try {

      $return = NULL;

      $this->db->select('CB.' . self::ID . ', EM.' . Model_Empresa::NOME . ' EMPRESA, BA.' . Model_Banco::NOME . ' BANCO, 
                         CB.' . self::AGENCIA . ', CB.' . self::CONTA . ', CB.' . self::DIGITO . ', CB.' . self::CC . ', 
                         CB.' . self::CODEMPRESA . ', CB.' . self::DIGITOAG);
      $this->db->join(Model_Empresa::TABELA . ' EM', 'EM.' . Model_Empresa::ID . ' = ' . 'CB.' . self::EMPRESA, 'inner');
      $this->db->join(Model_Banco::TABELA . ' BA', 'BA.' . Model_Banco::ID . ' = ' . 'CB.' . self::BANCO, 'left');
      $this->db->order_by(self::ID, "ASC");

      if (!is_null($page) && !is_null($paginacao)) {
        $query = $this->db->get(self::TABELA . ' CB', $page, $paginacao);
      } else {
        $query = $this->db->get(self::TABELA . ' CB');
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
