<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model_Folha
 *
 * @author denner.fernandes
 */
class Model_Folha extends MY_Model {

  const TABELA = 'FOLHA';
  const ID = 'ID_FOLHA';
  const EMPRESA = 'CD_EMPRESA';
  const FILIAL = 'CD_FILIAL';
  const MES = 'NU_MESCOMP';
  const ANO = 'NU_ANOCOMP';
  const VALOR = 'VL_TOTAL';

  public function __construct() {
    parent::__construct();
  }

}
