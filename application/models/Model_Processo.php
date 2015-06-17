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
    $query = $this->db->query('UPDATE ' . self::TABELA . ' SET ' 
                                        . self::DATA . ' = SYSDATE, '
                                        . self::DATAPAG . ' = TO_DATE(\'' . $data . '\', \'DD/MM/YYYY\') 
                               WHERE ' . self::ID . ' = ' . $ID);

    return $query;
  }

}
