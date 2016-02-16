<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model_Tipo_Operacao
 *
 * @author denner.fernandes
 */
class Model_Tipo_Operacao extends MY_Model {

  const TABELA = 'TIPO_OPERACAO';
  const ID = 'ID_TIPO_OPERACAO';
  const NOME = 'DS_DESCRICAO';
  const NATUREZA = 'CD_NATUREZA';
  const TIPO = 'CD_TIPO';
  const PERIODO = 'CD_PERIODO';

  private $PROTHEUS;

  public function __construct() {
    parent::__construct();
    $this->PROTHEUS = $this->load->database('protheus', TRUE);
  }

  public function getSX5Tipo() {
    $query = $this->PROTHEUS->query('SELECT TRIM(SX5.X5_CHAVE) X5_CHAVE, TRIM(SX5.X5_DESCRI) X5_DESCRI
                                     FROM PRODUCAO_9ZGXI5.SX5010 SX5
                                     WHERE SX5.X5_TABELA = \'05\'
                                     AND SX5.D_E_L_E_T_ = \' \'
                                     ORDER BY SX5.X5_CHAVE'
            );

    if ($query->num_rows > 0) {
      return $query->result_array();
    } else {
      return NULL;
    }
  }

  public function __destruct() {
    
  }
  
}
