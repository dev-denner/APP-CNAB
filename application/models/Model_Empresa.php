<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model_Empresa
 *
 * @author denner.fernandes
 */
class Model_Empresa extends MY_Model {

  const TABELA = 'EMPRESA';
  const ID = 'ID_EMPRESA';
  const NOME = 'DS_DESCRICAO';
  const COLIGADA = 'CD_COLIGADA';
  const FANTASIA = 'DS_NOME_FANTASIA';
  const CODERP = 'CD_COD_PROTHEUS';
  const LOJA = 'CD_LOJA_PROTHEUS';

  public function __construct() {
    parent::__construct();
  }

  public function __destruct() {
  }

}
