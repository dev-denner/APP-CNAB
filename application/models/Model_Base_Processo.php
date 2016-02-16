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
  const FILIAL = 'CD_FILIAL';
  const DT_REAL = 'DT_REAL_PAGTO';

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

  public function getBaixarPagto($ID) {
    $query = $this->db->query('SELECT BP.' . self::ID . ', BP.' . self::CHAPA . ', BP.' . self::NOME . ', 
                                      BP.' . self::VALOR . ', BP.' . self::CPF . ', BP.' . self::PROCESSO . ', 
                                      TO_CHAR(BP.' . self::DT_REAL . ', \'DD/MM/YYYY\') ' . self::DT_REAL . '
                               FROM ' . self::TABELA . ' BP
                               INNER JOIN ' . Model_Log::TABELA . ' AR
                                 ON AR.' . Model_Log::PROCESSO . ' = BP.' . self::PROCESSO . ' 
                                AND BP.' . self::BANCO . ' = AR.' . Model_Log::BANCO . '
                               WHERE ' . Model_Log::ID . '  = ' . $ID);

    if ($query) {
      return $query->result_array();
    } else {
      throw new Exception('Não há registros.');
    }
  }

  public function getBaixarPagtoTotal($ID) {
    $query = $this->db->query('SELECT SUM(' . self::VALOR . ') VALOR
                               FROM ' . self::TABELA . ' BP
                               INNER JOIN ' . Model_Log::TABELA . ' AR
                                 ON AR.' . Model_Log::PROCESSO . ' = BP.' . self::PROCESSO . ' 
                                AND BP.' . self::BANCO . ' = AR.' . Model_Log::BANCO . '
                               WHERE ' . Model_Log::ID . '  = ' . $ID . '
                                AND BP.' . self::DT_REAL . ' IS NOT NULL');

    if ($query->num_rows() > 0) {
      return $query->row_array();
    } else {
      throw new Exception('Não há registros.');
    }
  }

  public function desfazerBaixa($ID) {
    $query = $this->db->query('UPDATE ' . self::TABELA . ' SET ' . self::DT_REAL . ' = null
                               WHERE ' . self::ID . ' IN(SELECT BP.' . self::ID . '
                               FROM ' . self::TABELA . ' BP
                               INNER JOIN ' . Model_Log::TABELA . ' AR
                                 ON AR.' . Model_Log::PROCESSO . ' = BP.' . self::PROCESSO . ' 
                                AND BP.' . self::BANCO . ' = AR.' . Model_Log::BANCO . '
                               WHERE ' . Model_Log::ID . '  = ' . $ID . ')');

    if ($query) {
      return true;
    }
    return false;
  }

  public function setBaixarPagto($dados = array()) {
    
    if (!empty($dados['baixar'])) {
      $idsBp = $dados['baixar'];
      $data = empty($dados['baixar_data']) ? date('Y-m-d') : $dados['baixar_data'];

      foreach ($idsBp as $key => $value) {
        $query = $this->db->query(
                'UPDATE ' . self::TABELA . ' SET ' . self::DT_REAL . ' = TO_DATE(\'' . $data . '\', \'YYYY-MM-DD\')
                 WHERE ' . self::ID . ' = ' . $value);

        if (!$query) {
          throw new Exception('Erro ao realizar conciliação da Base de Processo de id ' . $value);
        }
      }
      return true;
    }
    return false;
  }

  public function __destruct() {
    
  }

}
