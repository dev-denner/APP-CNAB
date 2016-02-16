<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model_Processo
 *
 * @author denner.fernandes
 */
class Model_Processo extends MY_Model {

  const TABELA = 'PROCESSO';
  const ID = 'ID_PROCESSO';
  const EMPRESA = 'CD_EMPRESA';
  const FILIAL = 'CD_FILIAL';
  const BANCO = 'CD_BANCO';
  const OPERACAO = 'ID_TIPO_OPERACAO';
  const DATA = 'DT_PROCESSO';
  const DATAPAG = 'DT_DATA_PAGTO';
  const USUARIO = 'ID_USUARIO';
  const VALOR = 'VL_TOTAL';
  const FOLHA = 'CD_FOLHA';

  public function __construct() {
    parent::__construct();
  }

  public function setData($ID, $data) {
    $query = $this->db->query('UPDATE '
            . self::TABELA . ' SET '
            . self::DATA . ' = SYSDATE, '
            . self::DATAPAG . ' = TO_DATE(\'' . $data . '\', \'DD/MM/YYYY\') 
                               WHERE ' . self::ID . ' = ' . $ID);

    return $query;
  }

  public function deletar($ID) {
    $lancamento_financeiro = $this->db->query('
      DELETE FROM ' . Model_Lancamento_Financeiro::TABELA . '
      WHERE ' . Model_Lancamento_Financeiro::LOG . ' IN (
	SELECT DISTINCT LF.' . Model_Lancamento_Financeiro::LOG . '
  	FROM ' . Model_Lancamento_Financeiro::TABELA . ' LF
  	RIGHT JOIN ' . Model_Log::TABELA . ' AR
  	ON AR.' . Model_Log::ID . '      = LF.' . Model_Lancamento_Financeiro::LOG . '
  	WHERE AR.' . Model_Log::PROCESSO . ' = ' . $ID . '
      )');

    if (!$lancamento_financeiro) {
      throw new Exception('Falha ao excluir Lançamento Financeiro.');
    }

    $itens_arquivo = $this->db->query('
            DELETE FROM ' . Model_Cnab_Novo::TABELA . ' 
            WHERE ' . Model_Cnab_Novo::PROCESSO . ' = ' . $ID);

    if (!$itens_arquivo) {
      throw new Exception('Falha ao excluir Itens do Arquivo.');
    }

    $arquivo = $this->db->query('
            DELETE FROM ' . Model_Log::TABELA . '
            WHERE ' . Model_Log::PROCESSO . ' = ' . $ID);

    if (!$arquivo) {
      throw new Exception('Falha ao excluir Arquivo.');
    }

    $base_processo = $this->db->query('
            DELETE FROM ' . Model_Base_Processo::TABELA . '
            WHERE ' . Model_Base_Processo::PROCESSO . ' = ' . $ID);

    if (!$base_processo) {
      throw new Exception('Falha ao excluir Base Processo.');
    }

    $processo = $this->db->query('
            DELETE FROM ' . Model_Processo::TABELA . '
            WHERE ' . Model_Processo::ID . ' = ' . $ID);

    if (!$processo) {
      throw new Exception('Falha ao excluir Processo.');
    }

    return TRUE;
  }

  public function __destruct() {
    
  }

}
