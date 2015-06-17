<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model_Lancamento_Financeiro
 *
 * @author denner.fernandes
 */
class Model_Lancamento_Financeiro extends MY_Model {

  const TABELA = 'LANCAMENTO_FINANCEIRO';
  const ID = 'ID_LANCAMENTO_FINANCEIRO';
  const FILIAL = 'CD_FILIAL';
  const PREFIXO = 'DS_PREFIX';
  const SEQUENCIAL = 'NU_SEQUENCIAL';
  const PARCELA = 'NU_PARCELA';
  const TIPO = 'DS_TIPO';
  const NATUREZA = 'NU_NATUREZA';
  const DIRF = 'NU_DIRF';
  const CODRET = 'DS_CODRET';
  const CODFORNECEDOR = 'CD_FORNECEDOR';
  const LOJA = 'CD_LOJA';
  const NOMEFORNECEDOR = 'NM_FORNECEDOR';
  const EMISSAO = 'DS_EMISSAO';
  const VENCIMENTO = 'DS_VENCIMENTO';
  const VALOR = 'VL_VALOR';
  const HISTORICO = 'DS_HISTORICO';
  const RATEIO = 'DS_RATEIO';
  const ACRESCIMO = 'NU_ACRESCIMO';
  const DECRESCIMO = 'NU_DECRESCIMO';
  const CCUSTO = 'CD_CCUSTO';
  const CONTA_ORCA = 'NU_CONTA_ORCAMENTARIA';
  const LOG = 'CD_LOG';

  public function __construct() {
    parent::__construct();
  }

}
