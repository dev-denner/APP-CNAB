<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model_Log
 *
 * @author denner.fernandes
 */
class Model_Log extends MY_Model {

  const TABELA = 'ARQUIVO';
  const ID = 'ID_ARQUIVO';
  const ACAO = 'NM_ACAO';
  const DATA = 'DT_DATA';
  const PROCESSO = 'CD_PROCESSO';
  const USUARIO = 'CD_USUARIO';
  const XLS = 'DS_ARQUIVO_XLS';
  const CNAB = 'DS_ARQUIVO_CNAB';
  const LC = 'DS_ARQUIVO_LF';
  const VALOR = 'VL_TOTAL';
  const BANCO = 'CD_BANCO';
  const LAYOUT = 'DS_LAYOUT';
  const EMPRESAPAG = 'CD_EMPRESAPAG';

  public function __construct() {
    parent::__construct();
  }
  
  public function getByID($ID) {

    try {

      $this->db->where(self::ID, $ID);
      $this->db->order_by(self::ID, "ASC");
      $query = $this->db->get(self::TABELA);
      if (!empty($query)) {
        return $query->result_array();
      } else {
        throw new Exception('Não há registros.');
      }
    } catch (Exception $exc) {
      return $exc->getMessage();
    }
  }
  

  public function get($ID) {
    $query = $this->db->query('
       SELECT LO.' . self::ID . ', TO_CHAR(LO.' . self::DATA . ', \'DD/MM/YYYY HH24:MI:SS\') DATA, LO.' . self::XLS . ', 
              LO.' . self::VALOR . ' TOTAL, LO.' . self::CNAB . ', LO.' . self::PROCESSO . ', US.' . model_usuario::NOME . ',
              EM.' . Model_Empresa::ID . ' IDEMPRESA, EM.' . Model_Empresa::NOME . ' EMPRESA, BA.' . Model_Banco::NOME . ' BANCO, 
              OP.' . Model_Tipo_Operacao::NOME . ' OPERACAO, TO_CHAR(PR.' . Model_Processo::DATAPAG . ', \'DD/MM/YYYY\') DATAPAG,
              PR.' . Model_Processo::VALOR . ' VALOR, BA.' . Model_Banco::ID . ' NUMBANCO, FO.' . Model_Folha::ID . ',
              FO.' . Model_Folha::MES . ' || \'/\' || FO.' . Model_Folha::ANO . ' COMPETENCIA, LO.' . self::LC . '
       FROM ' . self::TABELA . ' LO
       INNER JOIN ' . Model_Processo::TABELA . ' PR ON PR.' . Model_Processo::ID . ' = LO.' . self::PROCESSO . '
       INNER JOIN ' . model_usuario::TABELA . ' US ON US.' . model_usuario::ID . ' = LO.' . self::USUARIO . '
       INNER JOIN ' . Model_Banco::TABELA . ' BA ON BA.' . Model_Banco::ID . ' = LO.' . self::BANCO . '
       INNER JOIN ' . Model_Empresa::TABELA . ' EM ON EM.' . Model_Empresa::ID . ' = PR.' . Model_Processo::EMPRESA . '
       INNER JOIN ' . Model_Tipo_Operacao::TABELA . ' OP ON OP.' . Model_Tipo_Operacao::ID . ' = PR.' . Model_Processo::OPERACAO . '
       INNER JOIN ' . Model_Folha::TABELA . ' FO ON FO.' . Model_Folha::ID . ' = PR.' . Model_Processo::FOLHA . '
       WHERE LO.' . self::PROCESSO . ' = ' . $ID
    );

    if ($query->num_rows() > 0) {

      return $query->result_array();
    } else {
      return NULL;
    }
  }

  public function save_log_canb($dados) {
    $query = $this->db->query('INSERT INTO ' . self::TABELA . '(
                                           ' . self::ID . ', 
                                           ' . self::ACAO . ', 
                                           ' . self::DATA . ', 
                                           ' . self::PROCESSO . ', 
                                           ' . self::USUARIO . ',
                                           ' . self::XLS . ', 
                                           ' . self::VALOR . ', 
                                           ' . self::CNAB . ',
                                           ' . self::BANCO . '
                                           ) VALUES(
                                           ' . $dados[self::ID] . ', 
                                         \'' . $dados[self::ACAO] . '\', 
                                          SYSDATE, 
                                           ' . $dados[self::PROCESSO] . ', 
                                           ' . $dados[self::USUARIO] . ', 
                                         \'' . $dados[self::XLS] . '\', 
                                           ' . $dados[self::VALOR] . ', 
                                         \'' . $dados[self::CNAB] . '\',
                                           ' . $dados[self::BANCO] . ')
                            ');
    if ($query)
      return TRUE;
    else
      return FALSE;
  }

  public function __destruct() {
    
  }
  
}
