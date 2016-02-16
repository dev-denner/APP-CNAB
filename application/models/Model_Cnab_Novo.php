<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model_Cnab_Novo
 *
 * @author denner.fernandes
 */
class Model_Cnab_Novo extends MY_Model {

  const TABELA = 'ITENS_ARQUIVO';
  const ID = 'ID_ITENS_ARQUIVO';
  const PROCESSO = 'ID_PROCESSO';
  const LINHA = 'TX_LINHA';
  const BANCO = 'CD_BANCO';
  const BASE = 'CD_BASE_PROCESSO';

  public function __construct() {
    parent::__construct();
  }

  public function getByCriterio($criterio = NULL) {

    try {

      $this->db->select('BP.' . Model_Base_Processo::CCUSTO . ', BP.' . Model_Base_Processo::FILIAL . ', SUM(BP.' . Model_Base_Processo::VALOR . ')' . Model_Base_Processo::VALOR);
      $this->db->where($criterio);
      $this->db->join(Model_Banco::TABELA . ' BA', 'BA.' . Model_Banco::ID . ' = CN.' . self::BANCO);
      $this->db->join(Model_Base_Processo::TABELA . ' BP', 'BP.' . Model_Base_Processo::ID . ' = CN.' . self::BASE);
      $this->db->group_by('BP.' . Model_Base_Processo::CCUSTO . ', BP.' . Model_Base_Processo::FILIAL);
      $query = $this->db->get(self::TABELA . ' CN');
      if ($query) {
        return $query->result_array();
      } else {
        throw new Exception('Não há registros.');
      }
    } catch (Exception $exc) {
      return $exc->getMessage();
    }
  }

  public function __destruct() {
    
  }

}
