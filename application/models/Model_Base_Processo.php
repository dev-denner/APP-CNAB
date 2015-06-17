<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model_Base_Processo
 *
 * @author denner.fernandes
 */
class Model_Base_Processo extends MY_Model {

  const TABELA = 'BASE_PROCESSO';
  const ID = 'ID_BASE_PROCESSO';
  const PROCESSO = 'ID_PROCESSO';
  const CHAPA = 'CD_CHAPA';
  const NOME = 'NM_NOME';
  const VALOR = 'VL_VALOR';
  const BANCO = 'CD_BANCO';
  const AGENCIA = 'CD_AGENCIA';
  const DIGITOAG = 'CD_AGENCIA_DIGITO';
  const CONTA = 'CD_CONTA';
  const DIGITO = 'CD_CONTA_DIGITO';
  const CCUSTO = 'CD_CCUSTO';
  const SECAO = 'CD_SECAO';
  const PERIODO = 'CD_PERIODO_PAGTO';
  const SITUACAO = 'DS_SITUACAO';
  const CPF = 'CD_CPF';

  public function __construct() {
    parent::__construct();
  }

  public function getByCriterio($criterio = NULL) {

    try {

      $this->db->where($criterio);
      $this->db->order_by(self::ID, "ASC");
      $this->db->join(Model_Banco::TABELA . ' BA', 'BA.' . Model_Banco::ID . ' = BP.' . self::BANCO);
      $query = $this->db->get(self::TABELA . ' BP');
      if ($query) {
        return $query->result_array();
      } else {
        throw new Exception('Não há registros.');
      }
    } catch (Exception $exc) {
      return $exc->getMessage();
    }
  }

  public function getSomaValor($ID, $banco) {
    $query = $this->db->query('SELECT SUM(' . self::VALOR . ') VALOR FROM ' . self::TABELA .
            ' WHERE ' . self::PROCESSO . ' = ' . $ID .
            'AND ' . self::BANCO . ' = ' . $banco);

    if ($query->num_rows() > 0) {
      return $query->row_array();
    } else {
      throw new Exception('Não há registros.');
    }
  }
  
}
