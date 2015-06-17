<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cnab200
 *
 * @author denner.fernandes
 */
class cnab200 extends MY_Model {

  /**
   * 
   * Header
   * 
   * */
  public $indRegistroHeader = 0;
  public $codRemessa = 1;
  public $literal1 = 'REMESSA';
  public $codServico = '03';
  public $literal2 = 'CREDITO C/C';
  public $codAgencia;
  public $numRazaoCc;
  public $agenciaEmpresa;
  public $contaEmpresa;
  public $digContaEmpresa = ' ';
  public $numCrec = ' ';
  public $numBanco;
  public $razaoEmpresa;
  public $codBanco = 237;
  public $nomeBanco = 'BRADESCO';
  public $dataGravacao;
  public $densidadeGravacao = '01600';
  public $unDensidadeGravacao = 'BPI';
  public $dataPagto;
  public $indMoeda = ' ';
  public $indSeculo = 'N';
  public $numSequencial;

  /**
   * 
   * Registro
   * 
   * */
  public $indRegistro = 1;
  public $agenciaFuncionario;
  public $razaoContaFuncionario = '07050';
  public $contaFuncionario;
  public $digContaFuncionario = ' ';
  public $nomeFuncionario;
  public $chapaFuncionario;
  public $valorPagtoFuncionario;
  public $indTipoServico = 298;

  /**
   * 
   * Trailler
   * 
   * */
  public $indRegistroTrailler = 9;
  public $valorTotal;

  public function headerArquivo() {

    $linha = substr($this->indRegistroHeader, 0, 1);
    $linha .= substr($this->codRemessa, 0, 1);
    $linha .= substr(str_pad($this->literal1, 7, ' ', STR_PAD_RIGHT), 0, 7);
    $linha .= substr(str_pad($this->codServico, 2, '0', STR_PAD_LEFT), 0, 2);
    $linha .= substr(str_pad($this->literal2, 15, ' ', STR_PAD_RIGHT), 0, 15);
    $linha .= substr(str_pad($this->codAgencia, 5, '0', STR_PAD_LEFT), 0, 5);
    $linha .= substr(str_pad($this->numRazaoCc, 5, '0', STR_PAD_LEFT), 0, 5);
    $linha .= substr(str_pad($this->contaEmpresa, 7, '0', STR_PAD_LEFT), 0, 7);
    $linha .= substr($this->digContaEmpresa, 0, 1);
    $linha .= substr($this->numCrec, 0, 1);
    $linha .= str_pad('', 1, ' ', STR_PAD_RIGHT);
    $linha .= substr(str_pad($this->numBanco, 5, '0', STR_PAD_LEFT), 0, 5);
    $linha .= substr(str_pad($this->razaoEmpresa, 25, ' ', STR_PAD_RIGHT), 0, 25);
    $linha .= substr(str_pad($this->codBanco, 3, '0', STR_PAD_LEFT), 0, 3);
    $linha .= substr(str_pad($this->nomeBanco, 15, ' ', STR_PAD_RIGHT), 0, 15);
    $linha .= substr(str_pad($this->dataGravacao, 8, ' ', STR_PAD_RIGHT), 0, 8);
    $linha .= substr(str_pad($this->densidadeGravacao, 5, '0', STR_PAD_LEFT), 0, 5);
    $linha .= substr(str_pad($this->unDensidadeGravacao, 3, ' ', STR_PAD_RIGHT), 0, 3);
    $linha .= substr(str_pad($this->dataPagto, 8, ' ', STR_PAD_RIGHT), 0, 8);
    $linha .= substr($this->indMoeda, 0, 1);
    $linha .= substr($this->indSeculo, 0, 1);
    $linha .= str_pad('', 74, ' ', STR_PAD_RIGHT);
    $linha .= substr(str_pad($this->numSequencial, 6, '0', STR_PAD_LEFT), 0, 6);

    return $linha;
  }

  public function registro() {

    $linha = substr($this->indRegistro, 0, 1);
    $linha .= str_pad('', 61, ' ', STR_PAD_RIGHT);
    $linha .= substr(str_pad($this->agenciaFuncionario, 5, '0', STR_PAD_LEFT), 0, 5);
    $linha .= substr(str_pad($this->razaoContaFuncionario, 5, '0', STR_PAD_LEFT), 0, 5);
    $linha .= substr(str_pad($this->contaFuncionario, 7, '0', STR_PAD_LEFT), 0, 7);
    $linha .= substr($this->digContaFuncionario, 0, 1);
    $linha .= str_pad('', 2, ' ', STR_PAD_RIGHT);
    $linha .= substr(str_pad($this->nomeFuncionario, 38, ' ', STR_PAD_RIGHT), 0, 38);
    $linha .= substr(str_pad($this->chapaFuncionario, 6, '0', STR_PAD_LEFT), 0, 6);
    $linha .= substr(str_pad($this->valorPagtoFuncionario, 15, '0', STR_PAD_LEFT), 0, 15);
    $linha .= substr(str_pad($this->indTipoServico, 3, '0', STR_PAD_LEFT), 0, 3);
    $linha .= str_pad('', 52, ' ', STR_PAD_RIGHT);
    $linha .= substr(str_pad($this->numSequencial, 6, '0', STR_PAD_LEFT), 0, 6);

    return $linha;
  }

  public function trailerArquivo() {

    $linha = substr($this->indRegistroTrailler, 0, 1);
    $linha .= substr(str_pad($this->valorTotal, 15, '0', STR_PAD_LEFT), 0, 15);
    $linha .= str_pad('', 180, ' ', STR_PAD_RIGHT);
    $linha .= substr(str_pad($this->numSequencial, 6, '0', STR_PAD_LEFT), 0, 6);

    return $linha;
  }

}
