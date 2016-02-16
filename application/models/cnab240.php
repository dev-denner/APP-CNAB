<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cnab240
 *
 * @author denner.fernandes
 */
class cnab240 extends MY_Model {

  /**
   * 
   * Empresa
   * 
   * */
  public $banco;
  public $tipoInscricaoEmpresa;
  public $numInscricaoEmpresa;
  public $codConvenioNoBanco = ' ';
  public $agenciaEmpresa;
  public $digAgenciaEmpresa;
  public $contaEmpresa;
  public $digContaEmpresa;
  public $digAgenciaContaEmpresa = ' ';
  public $nomeEmpresa;

  /**
   * 
   * Header Arquivo
   * 
   * */
  public $loteServicoArquivo = '0000';
  public $tipoRegistroArquivo = 0;
  public $nomeBanco;
  public $codRemessa = 1;
  public $numSequencialArquivo = 1;
  public $versaoLayoutArquivo = '088';
  public $densidadeGravacaoArquivo = 0;
  public $reservadoEmpresa;

  /**
   * 
   * Trailer Arquivo
   * 
   * */
  public $loteServicoTrailerArquivo = '9999';
  public $tipoRegistroTrailerArquivo = '9';
  public $qtdLotesArquivo;
  public $qtdRegistroArquivo;
  public $qtdContasArquivo;

  /**
   * 
   * Header Lote
   * 
   * */
  public $loteServicoLote;
  public $tipoRegistroLote = 1;
  public $tipoOperacao = 'C';
  public $tipoServico;
  public $formaLancamento = 1;
  public $versaoLayoutLote = '045';
  public $mensagem = ' ';
  public $logradouroEndereco;
  public $numEndereco;
  public $complEndereco = ' ';
  public $cidadeEndereco;
  public $cepEndereco;
  public $complCepEndereco;
  public $ufEndereco;
  public $idFormaPagto = ' ';
  public $ocorrenciaHeaderLote = ' ';

  /**
   * 
   * Registro
   * 
   * */
  public $tipoRegistro = 3;
  public $codSegmentoRegistro = 'A';
  public $tipoMovimento = 0;
  public $codInstrucaoMovimento = 0;
  public $codCamaraCentral = 0;
  public $codBancoFavorecido;
  public $agenciaFavorecido;
  public $digAgenciaFavorecido;
  public $contaFavorecido;
  public $digContaFavorecido;
  public $digAgenciaContaFavorecido = ' ';
  public $nomeFavorecido;
  public $numDocEmpresa;
  public $dataPagto;
  public $tipoMoeda = 'BRL';
  public $qtdMoeda = 0;
  public $valorPagto;
  public $numDocBanco = ' ';
  public $dataRealEfetivacaoPagto;
  public $valorRealEfetivacaoPagto;
  public $informacao2;
  public $complTipoServico;
  public $codFinalidadeTed;
  public $complFinalidadePagto;
  public $avisoFavorecido = 0;
  public $ocorrenciaRegistro;

  /**
   * 
   * Trailer Lote
   * 
   * */
  public $tipoRegistroTrailer = 5;
  public $qtdRegistroLote;
  public $somatorioValores;
  public $somatorioQtdMoeda;
  public $numAvisoDebito;
  public $ocorrenciaTrailerLote;

  public function __construct() {
    parent::__construct();
  }

  public function headerArquivo() {

    $linha = str_pad($this->banco, 3, '0', STR_PAD_LEFT);
    $linha .= str_pad($this->loteServicoArquivo, 4, '0', STR_PAD_LEFT);
    $linha .= substr($this->tipoRegistroArquivo, 0, 1);
    $linha .= str_pad('', 9, ' ', STR_PAD_RIGHT);
    $linha .= substr($this->tipoInscricaoEmpresa, 0, 1);
    $linha .= str_pad($this->numInscricaoEmpresa, 14, '0', STR_PAD_LEFT);
    $linha .= str_pad($this->codConvenioNoBanco, 20, ' ', STR_PAD_LEFT);
    $linha .= str_pad($this->agenciaEmpresa, 5, '0', STR_PAD_LEFT);
    $linha .= substr(str_pad($this->digAgenciaEmpresa, 1, ' ', STR_PAD_LEFT), 0, 1);
    $linha .= str_pad($this->contaEmpresa, 12, '0', STR_PAD_LEFT);
    $linha .= substr($this->digContaEmpresa, 0, 1);
    $linha .= substr($this->digAgenciaContaEmpresa, 0, 1);
    $linha .= substr(str_pad($this->nomeEmpresa, 30, ' ', STR_PAD_RIGHT), 0, 30);
    $linha .= substr(str_pad($this->nomeBanco, 30, ' ', STR_PAD_RIGHT), 0, 30);
    $linha .= str_pad('', 10, ' ', STR_PAD_RIGHT);
    $linha .= substr($this->codRemessa, 0, 1);
    $linha .= date('dmYHis');
    $linha .= str_pad($this->numSequencialArquivo, 6, '0', STR_PAD_LEFT);
    $linha .= substr($this->versaoLayoutArquivo, 0, 3);
    $linha .= str_pad($this->densidadeGravacaoArquivo, 5, '0', STR_PAD_LEFT);
    $linha .= str_pad('', 20, ' ', STR_PAD_RIGHT);
    $linha .= str_pad($this->reservadoEmpresa, 20, ' ', STR_PAD_RIGHT);
    $linha .= str_pad('', 29, ' ', STR_PAD_RIGHT);

    return $linha;
  }

  public function headerLote() {

    $linha = substr(str_pad($this->banco, 3, '0', STR_PAD_LEFT), 0, 3);
    $linha .= substr(str_pad($this->loteServicoLote, 4, '0', STR_PAD_LEFT), 0, 4);
    $linha .= substr($this->tipoRegistroLote, 0, 1);
    $linha .= substr($this->tipoOperacao, 0, 1);
    $linha .= substr(str_pad($this->tipoServico, 2, '0', STR_PAD_LEFT), 0, 2);
    $linha .= substr(str_pad($this->formaLancamento, 2, '0', STR_PAD_LEFT), 0, 2);
    $linha .= substr(str_pad($this->versaoLayoutLote, 3, '0', STR_PAD_LEFT), 0, 3);
    $linha .= ' ';
    $linha .= substr($this->tipoInscricaoEmpresa, 0, 1);
    $linha .= substr(str_pad($this->numInscricaoEmpresa, 14, '0', STR_PAD_LEFT), 0, 14);
    $linha .= substr(str_pad($this->codConvenioNoBanco, 20, ' ', STR_PAD_LEFT), 0, 20);
    $linha .= substr(str_pad($this->agenciaEmpresa, 5, '0', STR_PAD_LEFT), 0, 5);
    $linha .= substr(str_pad($this->digAgenciaEmpresa, 1, ' ', STR_PAD_LEFT), 0, 1);
    $linha .= substr(str_pad($this->contaEmpresa, 12, '0', STR_PAD_LEFT), 0, 12);
    $linha .= substr($this->digContaEmpresa, 0, 1);
    $linha .= substr($this->digAgenciaContaEmpresa, 0, 1);
    $linha .= substr(str_pad($this->nomeEmpresa, 30, ' ', STR_PAD_RIGHT), 0, 30);
    $linha .= substr(str_pad($this->mensagem, 40, ' ', STR_PAD_RIGHT), 0, 40);
    $linha .= substr(str_pad($this->logradouroEndereco, 30, ' ', STR_PAD_RIGHT), 0, 30);
    $linha .= substr(str_pad($this->numEndereco, 5, '0', STR_PAD_LEFT), 0, 5);
    $linha .= substr(str_pad($this->complEndereco, 15, ' ', STR_PAD_RIGHT), 0, 15);
    $linha .= substr(str_pad($this->cidadeEndereco, 20, ' ', STR_PAD_RIGHT), 0, 20);
    $linha .= substr(str_replace('-', '', $this->cepEndereco), 0, 8);
    $linha .= substr(str_pad($this->complCepEndereco, 3, ' ', STR_PAD_RIGHT), 0, 3);
    $linha .= substr(str_pad($this->ufEndereco, 2, ' ', STR_PAD_RIGHT), 0, 2);
    $linha .= substr(str_pad($this->idFormaPagto, 2, ' ', STR_PAD_RIGHT), 0, 2);
    $linha .= str_pad('', 6, ' ', STR_PAD_RIGHT);
    $linha .= substr(str_pad($this->ocorrenciaHeaderLote, 10, ' ', STR_PAD_RIGHT), 0, 10);

    return $linha;
  }

  public function registro() {

    $linha = substr(str_pad($this->banco, 3, '0', STR_PAD_LEFT), 0, 3);
    $linha .= str_pad($this->loteServicoLote, 4, '0', STR_PAD_LEFT);
    $linha .= substr($this->tipoRegistro, 0, 1);
    $linha .= str_pad($this->numSequencialArquivo, 5, '0', STR_PAD_LEFT);
    $linha .= substr($this->codSegmentoRegistro, 0, 1);
    $linha .= substr($this->tipoMovimento, 0, 1);
    $linha .= substr(str_pad($this->codInstrucaoMovimento, 2, '0', STR_PAD_LEFT), 0, 2);
    $linha .= substr(str_pad($this->codCamaraCentral, 3, '0', STR_PAD_LEFT), 0, 3);
    $linha .= substr(str_pad($this->codBancoFavorecido, 3, '0', STR_PAD_LEFT), 0, 3);
    $linha .= substr(str_pad($this->agenciaFavorecido, 5, '0', STR_PAD_LEFT), 0, 5);
    $linha .= substr($this->digAgenciaFavorecido, 0, 1);
    $linha .= substr(str_pad($this->contaFavorecido, 12, '0', STR_PAD_LEFT), 0, 12);
    $linha .= substr($this->digContaFavorecido, 0, 1);
    $linha .= substr($this->digAgenciaContaFavorecido, 0, 1);
    $linha .= substr(str_pad($this->nomeFavorecido, 30, ' ', STR_PAD_RIGHT), 0, 30);
    $linha .= substr(str_pad($this->numDocEmpresa, 20, ' ', STR_PAD_RIGHT), 0, 20); //verificar se pode ser a chapa do funcionario
    $linha .= substr(str_pad($this->dataPagto, 8, ' ', STR_PAD_RIGHT), 0, 8);
    $linha .= substr(str_pad($this->tipoMoeda, 3, ' ', STR_PAD_RIGHT), 0, 3);
    $linha .= substr(str_pad($this->qtdMoeda, 15, '0', STR_PAD_LEFT), 0, 15);
    $linha .= substr(str_pad($this->valorPagto, 15, '0', STR_PAD_LEFT), 0, 15);
    $linha .= substr(str_pad($this->numDocBanco, 20, ' ', STR_PAD_RIGHT), 0, 20);
    $linha .= substr(str_pad($this->dataRealEfetivacaoPagto, 8, ' ', STR_PAD_RIGHT), 0, 8);
    $linha .= substr(str_pad($this->valorRealEfetivacaoPagto, 15, ' ', STR_PAD_RIGHT), 0, 15);
    $linha .= substr(str_pad($this->informacao2, 40, ' ', STR_PAD_RIGHT), 0, 40);
    $linha .= substr(str_pad($this->complTipoServico, 2, ' ', STR_PAD_RIGHT), 0, 2);
    $linha .= substr(str_pad($this->codFinalidadeTed, 5, ' ', STR_PAD_RIGHT), 0, 5);
    $linha .= substr(str_pad($this->complFinalidadePagto, 2, ' ', STR_PAD_RIGHT), 0, 2);
    $linha .= str_pad('', 3, ' ', STR_PAD_RIGHT);
    $linha .= substr($this->avisoFavorecido, 0, 1);
    $linha .= substr(str_pad($this->ocorrenciaRegistro, 10, ' ', STR_PAD_RIGHT), 0, 10);

    return $linha;
  }

  public function trailerLote() {

    $linha = str_pad($this->banco, 3, '0', STR_PAD_LEFT);
    $linha .= str_pad($this->loteServicoLote, 4, '0', STR_PAD_LEFT);
    $linha .= substr($this->tipoRegistroTrailer, 0, 1);
    $linha .= str_pad('', 9, ' ', STR_PAD_LEFT);
    $linha .= str_pad($this->qtdRegistroLote, 6, '0', STR_PAD_LEFT);
    $linha .= str_pad($this->somatorioValores, 18, '0', STR_PAD_LEFT);
    $linha .= str_pad($this->somatorioQtdMoeda, 18, '0', STR_PAD_LEFT);
    $linha .= str_pad($this->numAvisoDebito, 6, ' ', STR_PAD_LEFT);
    $linha .= str_pad('', 165, ' ', STR_PAD_LEFT);
    $linha .= substr(str_pad($this->ocorrenciaTrailerLote, 10, ' ', STR_PAD_RIGHT), 0, 10);

    return $linha;
  }

  public function trailerArquivo() {

    $linha = str_pad($this->banco, 3, '0', STR_PAD_LEFT);
    $linha .= str_pad($this->loteServicoTrailerArquivo, 4, '0', STR_PAD_LEFT);
    $linha .= substr($this->tipoRegistroTrailerArquivo, 0, 1);
    $linha .= str_pad('', 9, ' ', STR_PAD_LEFT);
    $linha .= str_pad($this->qtdLotesArquivo, 6, '0', STR_PAD_LEFT);
    $linha .= str_pad($this->qtdRegistroArquivo, 6, '0', STR_PAD_LEFT);
    $linha .= str_pad($this->qtdContasArquivo, 6, '0', STR_PAD_LEFT);
    $linha .= str_pad('', 205, ' ', STR_PAD_LEFT);

    return $linha;
  }

  public function __destruct() {
    
  }

}
