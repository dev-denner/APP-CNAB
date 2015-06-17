<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model_Banco
 *
 * @author denner.fernandes
 */
class Model_Banco extends MY_Model {

  const TABELA = 'BANCO';
  const ID = 'ID_BANCO';
  const COD = 'CD_BANCO';
  const NOME = 'DS_DESCRICAO';

  public function __construct() {
    parent::__construct();
  }


}
