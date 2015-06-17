<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cnab
 *
 * @author denner.fernandes
 */
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class cnab extends MY_Controller {

  private $nome_cnab;
  private $xls;
  private $txt;
  private $perguntaEmpresa;
  private $bancoAtual;
  private $perguntaDataPagto;
  private $numeroFolha;
  private $numeroProcesso;
  private $numeroRegistros;
  private $numeroLog;
  private $valorRegistros = 0;
  private $valorFolha = 0;
  private $total = 0;
  private $msgError = NULL;
  private $numError = 10;
  private $logCnab = array();
  private $dadosEmpresa = array();
  private $dadosFilial = array();
  private $dadosBanco = array();
  private $dadosTipoOperacao = array();
  private $dadosContaBancaria = array();

  public function __construct() {
    parent::__construct();

    //LOAD dos MODELS
    $this->load->Model('Model_Empresa');
    $this->load->Model('Model_Filial');
    $this->load->Model('Model_Banco');
    $this->load->Model('Model_Tipo_Operacao');
    $this->load->Model('Model_Processo');
    $this->load->Model('Model_Base_Processo');
    $this->load->Model('Model_Cnab_Novo');
    $this->load->Model('Model_Funcionario');
    $this->load->Model('Model_Conta_Bancaria');
    $this->load->Model('Model_Folha');
    $this->load->Model('Model_Lancamento_Financeiro');
    $this->load->Model('cnab240');
    $this->load->Model('cnab200');
  }

  public function index() {
    try {

      //CARGA dos SELECTS
      $this->data['empresa'] = $this->Model_Empresa->getAll();
      $this->data['tipo_operacao'] = $this->Model_Tipo_Operacao->getAll();
      $this->data['menu_cnab'] = 'active';
      $this->data['menu_cnab_gerar'] = 'active';
      $this->data['breadcrumb'] = $this->breadcrumb(array('cnab', 'gerar'));
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
    }
    $this->MY_view('cnab/form', $this->data);
  }

  public function validar() {
    try {

      //CARGA dos SELECTS
      $this->data['empresa'] = $this->Model_Empresa->getAll();
      $this->data['erro'] = $this->msgError;

      $this->data['menu_cnab'] = 'active';
      $this->data['menu_cnab_validar'] = 'active';
      $this->data['breadcrumb'] = $this->breadcrumb(array('cnab', 'validar'));
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
    }
    $this->MY_view('cnab/validar', $this->data);
  }

  public function controll() {
    try {

      UTF8_ENABLED;
      $acao = FALSE;

      //Validar entradas
      $this->valida();

      //Set propriedade pergunta
      $this->setPerguntaEmpresa($this->POST[Model_Processo::EMPRESA]);
      $this->setPerguntaDataPagto(implode('', array_reverse(explode('-', $this->POST[Model_Processo::DATAPAG]))));

      //Set propriedades de dados
      $this->setDadosEmpresa($this->Model_Empresa->get($this->POST[Model_Processo::EMPRESA])[0]);
      $this->dadosFilial = $this->Model_Filial->get($this->POST[Model_Processo::FILIAL])[0];
      $this->dadosTipoOperacao = $this->Model_Tipo_Operacao->get($this->POST[Model_Processo::OPERACAO])[0];

      if ($this->POST['gerar'] == 'cnab') {
        $acao = $this->bootGerarCnab();
      } else if ($this->POST['gerar'] == 'xls') {
        $acao = $this->bootGerarExcel();
      } else {
        throw new Exception('Erro na escolha para gerar arquivo.');
      }

      if ($acao) {
        $this->setCnabBancos();
      } else {
        $this->setLogCnab(array(Model_Log::ACAO => 'Erro', Model_Log::VALOR => $this->total));
        //Escreve LOG
        $this->setLog($this->logCnab, 'cnab');
      }

      //Redireciona para pagina do resumo
      redirect('cnab/listar/' . $this->numeroProcesso);
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
    }
    redirect('cnab');
  }

  public function valideArquivo() {
    try {

      $this->setPerguntaEmpresa($this->POST[Model_Processo::EMPRESA]);
      $this->setDadosEmpresa($this->Model_Empresa->get($this->POST[Model_Processo::EMPRESA])[0]);
      $this->dadosFilial = $this->Model_Filial->get($this->POST[Model_Processo::FILIAL])[0];

      $this->numError = 1000;
      //Pega extensão do arquivo
      $xls = pathinfo($_FILES['xls']['name']);
      //Faz upload do arquivo
      $this->doUpload('xls');
      //Le arquivo
      $dados = $this->readXLS($xls['extension']);
      //Valida funcionario por chapa, cpf e duplicidade
      $this->validaFuncionario($dados);

      if (is_null($this->msgError)) {
        $this->session->set_flashdata('SUCESSO', 'Arquivo sem erros.');
      } else {
        //$this->sendEmail();
        $this->validar();
      }
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
      redirect('cnab/validar/');
    }
  }

  //---------------------------------- INTERFACES --------------------------------------------------------//

  private function cnab200() {

    $this->headerArquivoCnab200();
    $i = $this->registroCnab200();
    $this->footerArquivoCnab200($i);
    return TRUE;
  }

  private function cnab240() {

    $this->headerArquivoCnab240();
    $this->headerLoteCnab240();
    $this->registroCnab240();
    $this->trailerLoteCnab240();
    $this->trailerArquivoCnab240();
    return TRUE;
  }

  private function bootGerarCnab() {
    //Pega extensão do arquivo
    $xls = pathinfo($_FILES['xls']['name']);
    //Faz upload do arquivo
    $this->doUpload('xls');
    //Le arquivo
    $dados = $this->readXLS($xls['extension']);
    //Valida funcionario por chapa, cpf e duplicidade
    $info = $this->validaFuncionario($dados);
    //Cria Folha
    $this->setFolha();
    //Cria processo
    $this->setProcesso();
    //Popula tabela base_processo
    $this->setBaseProcesso($info);
    return TRUE;
  }

  private function bootGerarExcel() {

    //Faz upload do arquivo
    $this->doUpload('xls', 'txt');
    //Le arquivo
    $linhas = $this->readTxt();
    //Busca nas linhas do Cnab informacoes do funcionario
    $dados = $this->preparaLinhas($linhas);

    //Valida funcionario por chapa, cpf e duplicidade
    $info = $this->validaFuncionario($dados);
    //Cria arquivo .xlsx
    $this->writeXLSNovo($dados);
    //Cria Folha
    $this->setFolha();
    //Cria processo
    $this->setProcesso();
    //Popula tabela base_processo
    $this->setBaseProcesso($info);
    return TRUE;
  }

  //---------------------------------- FUNCTIONS DAS INTERFACES -------------------------------------------//

  private function setCnabBancos() {

    $bancos = $this->divideBancos();

    foreach ($bancos as $key => $value) {

      $this->setDadosBanco($this->Model_Banco->get($key)[0]);
      $this->setDadosContaBancaria($this->Model_Conta_Bancaria->get(
                      array(
                          Model_Conta_Bancaria::EMPRESA => $this->getPerguntaEmpresa(),
                          Model_Conta_Bancaria::BANCO => $key,
                      )
              )[0]
      );

      $this->setBancoAtual($key);

      if ($this->getDadosBanco()[Model_Banco::COD] == 237) {
        $this->cnab200();
      } else {
        $this->cnab240();
      }

      $this->finalizaCnab();
    }
  }

  private function finalizaCnab() {

    //Escreve arquivo cnab.txt
    $this->writeTxt();
    $this->setLogCnab(array(Model_Log::ACAO => 'Gerar Cnab'));
    //Escreve LOG
    $this->setLog($this->logCnab, 'cnab');
    //Gera lancamento financeiro
    $this->setLancamentoFinanceiro();
    $this->writeXLS();
  }

  private function divideBancos() {

    $baseProcesso = $this->Model_Base_Processo->get(array(Model_Base_Processo::PROCESSO => $this->getNumeroProcesso()));
    $countBancos = array();
    foreach ($baseProcesso as $key => $value) {
      $countBancos[] = $value[Model_Base_Processo::BANCO];
    }
    $bancos = array_count_values(array_replace($countBancos, array_fill_keys(array_keys($countBancos, null), '')));
    $dados = array();
    foreach ($bancos as $key1 => $value1) {
      foreach ($baseProcesso as $key2 => $value2) {
        if ($value2[Model_Base_Processo::BANCO] == $key1) {
          $dados[$key1][] = $value2;
        }
      }
    }
    return $dados;
  }

  private function headerArquivoCnab200() {

    $this->cnab200->codAgencia = $this->dadosContaBancaria[Model_Conta_Bancaria::AGENCIA];
    $this->cnab200->numRazaoCc = $this->dadosContaBancaria[Model_Conta_Bancaria::CC];
    $this->cnab200->contaEmpresa = $this->dadosContaBancaria[Model_Conta_Bancaria::CONTA];
    $this->cnab200->digContaEmpresa = $this->dadosContaBancaria[Model_Conta_Bancaria::DIGITOAG] ? $this->dadosContaBancaria[Model_Conta_Bancaria::DIGITOAG] : ' ';
    $this->cnab200->numBanco = $this->dadosContaBancaria[Model_Conta_Bancaria::CODEMPRESA];
    $this->cnab200->razaoEmpresa = $this->dadosEmpresa[Model_Empresa::NOME];
    $this->cnab200->dataGravacao = date('dmY');
    $this->cnab200->dataPagto = $this->getPerguntaDataPag();
    $this->cnab200->numSequencial = 1;

    $this->inserirLinha($this->cnab200->headerArquivo());
  }

  private function registroCnab200() {

    $base = $this->Model_Base_Processo->getByCriterio(
            array(
                'BP.' . Model_Base_Processo::PROCESSO => $this->numeroProcesso,
                'BP.' . Model_Base_Processo::BANCO => $this->getBancoAtual()
            )
    );

    $i = 2;
    foreach ($base as $key => $value) {

      if (!is_null($value[Model_Base_Processo::AGENCIA]) or !is_null($value[Model_Base_Processo::NOME])) {
        $linha = str_pad('1', 62);
        $linha .= str_pad($value[Model_Base_Processo::AGENCIA], 5, '0', STR_PAD_LEFT);
        $linha .= '07050' . str_pad($value[Model_Base_Processo::CONTA] . $value[Model_Base_Processo::DIGITO], 8, '0', STR_PAD_LEFT);
        $linha .= str_pad('', 2, ' ', STR_PAD_LEFT);
        $linha .= str_pad(substr($value[Model_Base_Processo::NOME], 0, 38), 38);
        $linha .= str_pad($value[Model_Base_Processo::CHAPA], 6, '0', STR_PAD_LEFT);

        $this->valorRegistros += str_replace(',', '.', $value[Model_Base_Processo::VALOR]);

        $valor = number_format(str_replace(',', '.', $value[Model_Base_Processo::VALOR]), 2, '.', '');
        $linha .= str_pad(str_replace('.', '', $valor), 13, '0', STR_PAD_LEFT) . '298';
        $linha .= str_pad('', 52, ' ', STR_PAD_LEFT);
        $linha .= str_pad($i, 6, '0', STR_PAD_LEFT);
        $i++;
        $this->inserirLinha($linha, $value[Model_Base_Processo::ID]);
      }
    }
    return $i;
  }

  private function footerArquivoCnab200($i) {

    $linha = '9' . str_pad(str_replace('.', '', number_format((float) $this->valorRegistros, 2, '.', '')), 13, '0', STR_PAD_LEFT);
    $linha .= str_pad('', 180, ' ');
    $linha .= str_pad($i, 6, '0', STR_PAD_LEFT);
    $this->inserirLinha($linha);

    $this->logCnab[Model_Log::VALOR] = number_format((float) $this->valorRegistros, 2, '.', '');
    settype($this->logCnab[Model_Log::VALOR], 'float');

    $this->setValorRegistros(0);
  }

  private function headerArquivoCnab240() {

    $this->cnab240->banco = $this->dadosBanco[Model_Banco::COD];
    $this->cnab240->tipoInscricaoEmpresa = 2;
    $this->cnab240->numInscricaoEmpresa = $this->dadosContaBancaria[Model_Conta_Bancaria::CODEMPRESA];
    $this->cnab240->codConvenioNoBanco = $this->dadosContaBancaria[Model_Conta_Bancaria::CONVENIO];
    $this->cnab240->agenciaEmpresa = $this->dadosContaBancaria[Model_Conta_Bancaria::AGENCIA];
    $this->cnab240->digAgenciaEmpresa = $this->dadosContaBancaria[Model_Conta_Bancaria::DIGITOAG];
    $this->cnab240->contaEmpresa = $this->dadosContaBancaria[Model_Conta_Bancaria::CONTA];
    $this->cnab240->digContaEmpresa = $this->dadosContaBancaria[Model_Conta_Bancaria::DIGITO];
    $this->cnab240->nomeEmpresa = $this->dadosEmpresa[Model_Empresa::NOME];
    $this->cnab240->nomeBanco = $this->dadosBanco[Model_Banco::NOME];

    $this->inserirLinha($this->cnab240->headerArquivo());
  }

  private function headerLoteCnab240() {

    $this->cnab240->banco = $this->dadosBanco[Model_Banco::COD];
    $this->cnab240->loteServicoLote = 1;
    $this->cnab240->tipoServico = 30;
    $this->cnab240->numInscricaoEmpresa = $this->dadosContaBancaria[Model_Conta_Bancaria::CODEMPRESA];
    $this->cnab240->codConvenioNoBanco = $this->dadosContaBancaria[Model_Conta_Bancaria::CONVENIO];
    $this->cnab240->agenciaEmpresa = $this->dadosContaBancaria[Model_Conta_Bancaria::AGENCIA];
    $this->cnab240->digAgenciaEmpresa = $this->dadosContaBancaria[Model_Conta_Bancaria::DIGITOAG];
    $this->cnab240->contaEmpresa = $this->dadosContaBancaria[Model_Conta_Bancaria::CONTA];
    $this->cnab240->digContaEmpresa = $this->dadosContaBancaria[Model_Conta_Bancaria::DIGITO];
    $this->cnab240->nomeEmpresa = $this->dadosEmpresa[Model_Empresa::NOME];
    $this->cnab240->logradouroEndereco = 'RUA SAO FRANCISCO XAVIER';
    $this->cnab240->numEndereco = '603';
    $this->cnab240->cidadeEndereco = 'RIO DE JANEIRO';
    $this->cnab240->cepEndereco = '20550';
    $this->cnab240->complCepEndereco = '011';
    $this->cnab240->ufEndereco = 'RJ';

    $this->inserirLinha($this->cnab240->headerLote());
  }

  private function registroCnab240() {

    $base = $this->Model_Base_Processo->getByCriterio(
            array(
                'BP.' . Model_Base_Processo::PROCESSO => $this->numeroProcesso,
                'BP.' . Model_Base_Processo::BANCO => $this->getBancoAtual()
            )
    );
    $i = 0;
    foreach ($base as $key => $value) {
      $i++;
      $this->cnab240->banco = $this->dadosBanco[Model_Banco::COD];
      $this->cnab240->loteServicoLote = 1;
      $this->cnab240->numSequencialArquivo = $i;
      $this->cnab240->codBancoFavorecido = $value[Model_Banco::COD];
      $this->cnab240->agenciaFavorecido = $value[Model_Base_Processo::AGENCIA];
      $this->cnab240->digAgenciaFavorecido = $value[Model_Base_Processo::DIGITOAG] ? $value[Model_Base_Processo::DIGITOAG] : ' ';
      $this->cnab240->contaFavorecido = $value[Model_Base_Processo::CONTA];
      $this->cnab240->digContaFavorecido = $value[Model_Base_Processo::DIGITO];
      $this->cnab240->nomeFavorecido = $value[Model_Base_Processo::NOME];
      //$this->cnab240->numDocEmpresa = $value[Model_Base_Processo::CHAPA];
      $this->cnab240->dataPagto = $this->getPerguntaDataPag();

      $this->valorRegistros += str_replace(',', '.', $value[Model_Base_Processo::VALOR]);

      $valor = $value[Model_Base_Processo::VALOR];

      $valor = number_format(str_replace(',', '.', $valor), 2, '.', '');

      $this->cnab240->valorPagto = str_replace('.', '', $valor);
      $this->inserirLinha($this->cnab240->registro(), $value[Model_Base_Processo::ID]);
    }
    $this->setNumeroRegistros($i);
  }

  private function trailerLoteCnab240() {

    $this->cnab240->banco = $this->dadosBanco[Model_Banco::COD];
    $this->cnab240->loteServicoLote = 1;
    $this->cnab240->qtdRegistroLote = $this->getNumeroRegistros() + 2;
    $this->cnab240->somatorioValores = number_format((float) $this->getValorRegistros(), 2, '', '');

    $this->logCnab[Model_Log::VALOR] = number_format((float) $this->getValorRegistros(), 2, '.', '');
    settype($this->logCnab[Model_Log::VALOR], 'float');

    $this->valorRegistros = 0;

    $this->inserirLinha($this->cnab240->trailerLote());
  }

  private function trailerArquivoCnab240() {

    $this->cnab240->banco = $this->dadosBanco[Model_Banco::COD];
    $this->cnab240->qtdLotesArquivo = 1;
    $this->cnab240->qtdRegistroArquivo = $this->getNumeroRegistros() + 4;
    $this->inserirLinha($this->cnab240->trailerArquivo());
  }

  private function setLancamentoFinanceiro() {
    try {
      $base = $this->Model_Cnab_Novo->getByCriterio(
              array(
                  'CN.' . Model_Cnab_Novo::PROCESSO => $this->numeroProcesso,
                  'CN.' . Model_Base_Processo::BANCO => $this->getBancoAtual()
              )
      );

      foreach ($base as $key => $value) {

        $dados[Model_Lancamento_Financeiro::ID] = $this->Model_Lancamento_Financeiro->autoincrement();
        $dados[Model_Lancamento_Financeiro::FILIAL] = $this->dadosFilial[Model_Filial::FILIAL];
        $dados[Model_Lancamento_Financeiro::PREFIXO] = 'GPE';
        $dados[Model_Lancamento_Financeiro::SEQUENCIAL] = $this->Model_Lancamento_Financeiro->getSequence();
        $dados[Model_Lancamento_Financeiro::PARCELA] = 1;
        $dados[Model_Lancamento_Financeiro::TIPO] = 'VL';
        $dados[Model_Lancamento_Financeiro::NATUREZA] = 210403;
        $dados[Model_Lancamento_Financeiro::DIRF] = 2;
        $dados[Model_Lancamento_Financeiro::CODRET] = NULL;
        $dados[Model_Lancamento_Financeiro::CODFORNECEDOR] = $this->dadosEmpresa[Model_Empresa::CODERP];
        $dados[Model_Lancamento_Financeiro::LOJA] = $this->dadosEmpresa[Model_Empresa::LOJA];
        $dados[Model_Lancamento_Financeiro::NOMEFORNECEDOR] = $this->dadosEmpresa[Model_Empresa::FANTASIA];
        $dados[Model_Lancamento_Financeiro::EMISSAO] = implode('', explode('-', $this->POST[Model_Processo::DATAPAG]));
        $dados[Model_Lancamento_Financeiro::VENCIMENTO] = implode('', explode('-', $this->POST[Model_Processo::DATAPAG]));
        $dados[Model_Lancamento_Financeiro::VALOR] = number_format((float) $value[Model_Base_Processo::VALOR], 2, '.', '');
        $dados[Model_Lancamento_Financeiro::HISTORICO] = $this->dadosTipoOperacao[Model_Tipo_Operacao::NOME];
        $dados[Model_Lancamento_Financeiro::RATEIO] = 'N';
        $dados[Model_Lancamento_Financeiro::ACRESCIMO] = 0;
        $dados[Model_Lancamento_Financeiro::DECRESCIMO] = 0;
        $dados[Model_Lancamento_Financeiro::CCUSTO] = $value[Model_Base_Processo::CCUSTO];
        $dados[Model_Lancamento_Financeiro::CONTA_ORCA] = '201001';
        $dados[Model_Lancamento_Financeiro::LOG] = $this->numeroLog;

        settype($dados[Model_Lancamento_Financeiro::VALOR], 'float');

        $acao = $this->Model_Lancamento_Financeiro->save($dados);
        if (!$acao) {
          throw new Exception('Erro ao gravar Lançamento Financeiro registro' . $dados[Model_Lancamento_Financeiro::ID]);
        }
      }
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getTraceAsString());
    }
  }

  //---------------------------------- HELPERS -----------------------------------------------------------//

  private function valida() {

    if (empty($this->POST[Model_Processo::EMPRESA])) {
      throw new Exception('Escolha uma Empresa.');
    }
    if (empty($this->POST[Model_Processo::FILIAL])) {
      throw new Exception('Escolha uma Filial.');
    }
    if (empty($this->POST[Model_Processo::OPERACAO])) {
      throw new Exception('Escolha um Tipo de Operação.');
    }
    if (empty($this->POST[Model_Processo::DATAPAG])) {
      throw new Exception('Escolha a data de pagamento.');
    }
    if (empty($_FILES['xls'])) {
      throw new Exception('Não foi enviado o arquivo .xls/.xlsx/.csv.');
    }
  }

  private function validaFuncionario($dados) {

    $info = $dadosFuncionario = $chapas = array();
    $this->msgError = NULL;
    $count = 0;
    foreach ($dados as $key => $value) {

      if ($value['A'] != 'CHAPA' && !is_null($value['A']) && !empty($value['A'])) {

        $chapa = isset($value['A']) ? str_pad((int) $value['A'], 6, "0", STR_PAD_LEFT) : '';
        $nome = str_replace('\'', '', $value['B']);
        $value['C'] = str_replace('R', '', str_replace('$', '', str_replace(',', '', $value['C'])));
        $valor = number_format((float) $value['C'], 2, '.', '');
        $cpf = str_replace('/', '', str_replace('-', '', str_replace('.', '', $value['D'])));

        $coligada = $this->getDadosEmpresa()[Model_Empresa::COLIGADA];

        if ($this->perguntaEmpresa == 1) {
          $filial = $this->Model_Funcionario->getFilialRM($coligada, $cpf)[0];
        } else {
          $filial = $this->Model_Funcionario->get(array(
                      Model_Funcionario::EMPRESA => $coligada,
                      Model_Funcionario::CPF => $cpf
                          )
                  )[0];
        }
        if (is_null($filial) || $filial === FALSE) {
          $count++;
          if ($count < $this->numError) {
            $this->msgError[] .= 'Erro ao encontrar filial do funcionário ' . $nome . '.';
          }
        } else {

          if (str_pad($this->dadosFilial[Model_Filial::FILIAL], 2, 0, STR_PAD_LEFT) != str_pad($filial[Model_Funcionario::FILIAL], 2, 0, STR_PAD_LEFT)) {
            $count++;
            if ($count < $this->numError) {
              $this->msgError[] .= 'Funcionário ' . $nome . ' não é da filial selecionada.';
            }
          }
        }

        if ($this->perguntaEmpresa == 1) {
          $info = $this->Model_Funcionario->getFuncionariosRM($coligada, $chapa, $cpf);
        } else {
          $info = $this->Model_Funcionario->get(array(
              Model_Funcionario::EMPRESA => $coligada,
              Model_Funcionario::CHAPA => $chapa,
              Model_Funcionario::CPF => $cpf
                  )
          );
        }

        if (is_null($info) || $info === FALSE) {
          $count++;
          if ($count < $this->numError) {
            $this->msgError[] .= 'A CHAPA e o CPF do funcionário ' . $nome . ' não correspondem ao cadastro.';
          }
        } else {
          $cpfs[] = $cpf;
          $info[0][Model_Base_Processo::VALOR] = $valor;
          $this->total += $valor;
          $dadosFuncionario[] = $info[0];
        }
      }
    }

    $cpfs = array_count_values($cpfs);
    foreach ($cpfs as $key => $value) {
      if ($value > 1) {
        $this->msgError[] .= 'O Funcionário com o CPF ' . $key . ' se repete ' . $value . ' vezes no arquivo.';
      }
    }

    foreach ($dadosFuncionario as $key => $value) {

      $dadosFuncionario[$key][Model_Funcionario::NOME] = str_replace('\'', '', $value[Model_Funcionario::NOME]);

      if (empty($value[Model_Funcionario::BANCO])) {
        $this->msgError[] .= 'O Funcionário ' . $value[Model_Funcionario::NOME] . ' não tem o número do banco cadastrado no sistema.';
      }
      $banco = $this->Model_Banco->get(array(Model_Banco::COD => $value[Model_Funcionario::BANCO]))[0][Model_Banco::ID];

      if (empty($banco)) {
        $count++;
        if ($count < $this->numError) {
          $this->msgError[] .= 'Banco ' . $value[Model_Funcionario::BANCO] . ' não está cadastrado no sistema CNAB.<br> Cadastre-o ou atualize os dados do funcionário ' . $value[Model_Funcionario::NOME] . ' para prosseguir.';
        }
      } else {
        $dadosFuncionario[$key][Model_Funcionario::BANCO] = $banco;
      }
    }

    if (!is_null($this->msgError)) {
      if ($this->numError == 10) {
        $erro = '';
        foreach ($this->msgError as $value) {
          $erro .= $value . '<br>';
        }
        throw new Exception($erro . 'Por favor, verifique o arquivo e tente novamente.');
      }
    } else {
      return $dadosFuncionario;
    }
  }

  private function preparaLinhas($dados) {

    $info = $campos = array();

    if (substr($dados[0], 76, 3) == 237) {
      $layout = 200;
    } else {
      $layout = 240;
    }

    for ($i = 0; $i < count($dados); $i++) {
      if ($i != 0 && $i != count($dados) - 1) {
        if ($layout == 200) {
          $banco = 237;
          $agencia = substr($dados[$i], 62, 5);
          $conta = substr($dados[$i], 72, 7);
          $digConta = substr($dados[$i], 79, 1);
          $valor = substr($dados[$i], 126, 11) . '.' . substr($dados[$i], 137, 2);
          $valor = number_format((float) $valor, 2, '.', '');

          settype($agencia, 'int');
          settype($conta, 'int');

          $info = $this->Model_Funcionario->getForExcel($banco, $agencia, NULL, $conta, $digConta);

          $campos[$i]['A'] = $info[0][Model_Funcionario::CHAPA];
          $campos[$i]['B'] = $info[0][Model_Funcionario::NOME];
          $campos[$i]['C'] = $valor;
          $campos[$i]['D'] = $info[0][Model_Funcionario::CPF];
        } else {
          if ($i != 1 && $i != count($dados) - 2) {
            $banco = substr($dados[$i], 0, 3);
            $agencia = substr($dados[$i], 23, 5);
            $digAgencia = substr($dados[$i], 28, 1);
            $conta = substr($dados[$i], 29, 12);
            $digConta = substr($dados[$i], 41, 1);
            $nome = substr($dados[$i], 43, 30);
            $valor = substr($dados[$i], 119, 13) . '.' . substr($dados[$i], 134, 2);
            $valor = number_format((float) $valor, 2, '.', '');

            if ($digAgencia = ' ') {
              $digAgencia = NULL;
            }

            if ($digConta = ' ') {
              $digConta = NULL;
            }

            settype($agencia, 'int');
            settype($conta, 'int');

            $info = $this->Model_Funcionario->getForExcel($banco, $agencia, $digAgencia, $conta, $digConta);

            $campos[$i]['A'] = $info[0][Model_Funcionario::CHAPA];
            $campos[$i]['B'] = $info[0][Model_Funcionario::NOME];
            $campos[$i]['C'] = $valor;
            $campos[$i]['D'] = $info[0][Model_Funcionario::CPF];
          }
        }
      }
    }
    return $campos;
  }

  private function doUpload($form = NULL, $tipo = 'xls') {

    $this->xls = $this->txt = '';

    $config['pasta'] = './arquivos/uploads/';

    $nome_arquivo = $_FILES[$form]['name'];
    $nome_final = date('Ymd-His') . '-' . $nome_arquivo;

    if ($tipo == 'xls') {
      $config['extensoes'] = array('xls', 'xlsx', 'csv');
      $this->xls = $config['pasta'] . $nome_final;
    } else {
      $config['extensoes'] = array('txt', 'rem');
      $this->txt = $config['pasta'] . $nome_final;
    }



    $config['erros'][0] = 'Não houve erro';
    $config['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
    $config['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
    $config['erros'][3] = 'O upload do arquivo foi feito parcialmente';
    $config['erros'][4] = 'Não foi feito o upload do arquivo';

    if ($_FILES[$form]['error'] != 0) {
      throw new Exception($config['erros'][$_FILES[$form]['error']]);
    }

    $extensao = strtolower(end(explode('.', $_FILES[$form]['name'])));

    if (array_search($extensao, $config['extensoes']) === false) {
      $ext = '';
      foreach ($config['extensoes'] as $it) {
        $ext .= $it . ' - ';
      }
      throw new Exception('Por favor, envie arquivos com a(s) seguinte(s) extensão (ões): ' . substr($ext, 0, -3));
    } else {
      if (!move_uploaded_file($_FILES[$form]['tmp_name'], $config['pasta'] . $nome_final)) {
        throw new Exception('Não foi possível enviar o arquivo, tente novamente');
      }
    }
  }

  private function setFolha() {

    $competencia = explode('-', $this->POST['competencia']);

    $folha = $this->Model_Folha->get(array(
        Model_Folha::EMPRESA => $this->POST[Model_Processo::EMPRESA],
        Model_Folha::FILIAL => $this->POST[Model_Processo::FILIAL],
        Model_Folha::MES => $competencia[1],
        Model_Folha::ANO => $competencia[0]
    ));

    $campos = array(Model_Processo::EMPRESA, Model_Processo::FILIAL);
    $dados = elements($campos, $this->POST);
    $dados[Model_Folha::ID] = $this->Model_Folha->autoincrement();
    $dados[Model_Folha::ANO] = $competencia[0];
    $dados[Model_Folha::MES] = $competencia[1];
    settype($dados[Model_Folha::ID], 'int');
    settype($dados[Model_Processo::EMPRESA], 'int');
    settype($dados[Model_Processo::FILIAL], 'int');
    settype($dados[Model_Folha::ANO], 'int');
    settype($dados[Model_Folha::MES], 'int');

    if (empty($folha)) {

      $acao = $this->Model_Folha->save($dados);

      if ($acao) {
        $this->numeroFolha = $dados[Model_Folha::ID];
      } else {
        throw new Exception('Erro ao criar folha.');
      }
    } else {
      $this->numeroFolha = $folha[0][Model_Folha::ID];
      $this->valorFolha = $folha[0][Model_Folha::VALOR];
    }
  }

  private function setProcesso() {

    $campos = array(Model_Processo::EMPRESA, Model_Processo::FILIAL, Model_Processo::OPERACAO);
    $dados = elements($campos, $this->POST);
    $dados[Model_Processo::ID] = $this->Model_Processo->autoincrement();
    $dados[Model_Processo::FOLHA] = $this->numeroFolha;
    $dados[Model_Processo::VALOR] = number_format((float) $this->getTotal(), 2, '.', '');
    $dados[Model_Processo::USUARIO] = $this->user_info[model_usuario::ID];
    settype($dados[Model_Processo::ID], 'int');
    settype($dados[Model_Processo::FOLHA], 'int');
    settype($dados[Model_Processo::EMPRESA], 'int');
    settype($dados[Model_Processo::FILIAL], 'int');
    settype($dados[Model_Processo::OPERACAO], 'int');
    settype($dados[Model_Processo::USUARIO], 'int');
    settype($dados[Model_Processo::VALOR], 'float');

    $acao = $this->Model_Processo->save($dados);
    $acao2 = $this->Model_Processo->setData($dados[Model_Processo::ID], date("d/m/Y", strtotime($this->POST[Model_Processo::DATAPAG])));

    if ($acao && $acao2) {
      $this->numeroProcesso = $dados[Model_Processo::ID];
    } else {
      throw new Exception('Erro ao criar processo.');
    }
    $valor = number_format((float) $this->valorFolha + $this->getTotal(), 2, '.', '');
    settype($valor, 'float');
    settype($this->numeroFolha, 'int');
    $acao3 = $this->Model_Folha->save(array(Model_Folha::VALOR => $valor), $this->numeroFolha);

    if (!$acao3) {
      throw new Exception('Erro ao criar processo.');
    }
  }

  private function writeTxt() {

    $nomeArquivo = $this->dadosTipoOperacao[Model_Tipo_Operacao::NOME] . '-';
    $nomeArquivo .= $this->dadosEmpresa[Model_Empresa::FANTASIA] . '-' . $this->dadosFilial[Model_Filial::NOME] . '-';
    $nomeArquivo .= $this->dadosBanco[Model_Banco::NOME] . '-';
    $nomeArquivo .= number_format((float) $this->logCnab[Model_Log::VALOR], 2, '', '') . '-' . date('Ymd-His');
    $nomeArquivo = $this->retiraAcentos(str_replace('/', '', $nomeArquivo));

    $ponteiro = fopen('./arquivos/cnabs/' . $nomeArquivo . '.txt', 'a');

    $this->setTxt('./arquivos/cnabs/' . $nomeArquivo . '.txt');

    $query = $this->Model_Cnab_Novo->get(
            array(
                Model_Cnab_Novo::PROCESSO => $this->getNumeroProcesso(),
                Model_Cnab_Novo::BANCO => $this->getBancoAtual(),
            )
    );
    $i = 1;
    foreach ($query as $row) {

      if (count($query) != $i) {
        $quebra = chr(13) . chr(10);
      } else {
        $quebra = '';
      }
      $linha = $row[Model_Cnab_Novo::LINHA] . $quebra;
      fwrite($ponteiro, $linha, strlen($linha));
      $i++;
    }

    fclose($ponteiro);

    $this->nome_cnab = $nomeArquivo . '.txt';
  }

  private function readTxt() {

    $ponteiro = fopen($this->txt, 'r');
    $dados = array();

    while (!feof($ponteiro)) {
      $dados[] = fgets($ponteiro);
    }

    fclose($ponteiro);

    return $dados;
  }

  private function readXLS($ext) {

    $this->load->library('phpexcel');

    $inputFileName = $this->xls;

    switch ($ext) {
      case 'xls':
        $objReader = new PHPExcel_Reader_Excel5();
        break;
      case 'xlsx':
        $objReader = new PHPExcel_Reader_Excel2007();
        break;
      case 'csv':
        $objReader = new PHPExcel_Reader_CSV();
        break;
//	$objReader = new PHPExcel_Reader_OOCalc();
//	$objReader = new PHPExcel_Reader_SYLK();
//	$objReader = new PHPExcel_Reader_Gnumeric();
      default:
        $objReader = new PHPExcel_Reader_Excel5();
        break;
    }

    $objPHPExcel = $objReader->load($inputFileName);

    return $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
  }

  private function writeXLS() {

    $this->load->library('phpexcel');
    $objPHPExcel = new PHPExcel();

    $objPHPExcel->getProperties()->setCreator($this->user_info[Model_Usuario::NOME])
            ->setLastModifiedBy($this->user_info[Model_Usuario::NOME])
            ->setTitle('Lançamento Financeiro n°:' . $this->numeroLog)
            ->setSubject('APP CNAB')
            ->setDescription('Programa que salva vidas')
            ->setCategory('Títulos');
    $objPHPExcel->getActiveSheet()->setTitle('Lancamento-Financeiro');

    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'E2_FILIAL')
            ->setCellValue('B1', 'E2_PREFIXO')
            ->setCellValue('C1', 'E2_NUM')
            ->setCellValue('D1', 'E2_PARCELA')
            ->setCellValue('E1', 'E2_TIPO')
            ->setCellValue('F1', 'E2_NATUREZ')
            ->setCellValue('G1', 'E2_DIRF')
            ->setCellValue('H1', 'E2_CODRET')
            ->setCellValue('I1', 'E2_FORNECE')
            ->setCellValue('J1', 'E2_LOJA')
            ->setCellValue('K1', 'E2_NOMFOR')
            ->setCellValue('L1', 'E2_EMISSAO')
            ->setCellValue('M1', 'E2_VENCTO')
            ->setCellValue('N1', 'E2_VALOR')
            ->setCellValue('O1', 'E2_HIST')
            ->setCellValue('P1', 'E2_RATEIO')
            ->setCellValue('Q1', 'E2_ACRESC')
            ->setCellValue('R1', 'E2_DECRESC')
            ->setCellValue('S1', 'E2_CCD')
            ->setCellValue('T1', 'E2_XCO');


    $base = $this->Model_Lancamento_Financeiro->get(array(Model_Lancamento_Financeiro::LOG => $this->numeroLog));

    $i = 1;
    foreach ($base as $key => $value) {
      $i++;
      $objPHPExcel->getActiveSheet()
              ->setCellValueByColumnAndRow(0, $i, str_pad($this->dadosFilial[Model_Filial::FILIAL], 2, 0, STR_PAD_LEFT))
              ->setCellValueByColumnAndRow(1, $i, $value[Model_Lancamento_Financeiro::PREFIXO])
              ->setCellValueByColumnAndRow(2, $i, $value[Model_Lancamento_Financeiro::SEQUENCIAL])
              ->setCellValueByColumnAndRow(3, $i, $value[Model_Lancamento_Financeiro::PARCELA])
              ->setCellValueByColumnAndRow(4, $i, $value[Model_Lancamento_Financeiro::TIPO])
              ->setCellValueByColumnAndRow(5, $i, $value[Model_Lancamento_Financeiro::NATUREZA])
              ->setCellValueByColumnAndRow(6, $i, $value[Model_Lancamento_Financeiro::DIRF])
              ->setCellValueByColumnAndRow(7, $i, $value[Model_Lancamento_Financeiro::CODRET])
              ->setCellValueByColumnAndRow(8, $i, $value[Model_Lancamento_Financeiro::CODFORNECEDOR])
              ->setCellValueByColumnAndRow(9, $i, $value[Model_Lancamento_Financeiro::LOJA])
              ->setCellValueByColumnAndRow(10, $i, $value[Model_Lancamento_Financeiro::NOMEFORNECEDOR])
              ->setCellValueByColumnAndRow(11, $i, $value[Model_Lancamento_Financeiro::EMISSAO])
              ->setCellValueByColumnAndRow(12, $i, $value[Model_Lancamento_Financeiro::VENCIMENTO])
              ->setCellValueByColumnAndRow(13, $i, number_format((float) $value[Model_Lancamento_Financeiro::VALOR], 2, '', ''))
              ->setCellValueByColumnAndRow(14, $i, $value[Model_Lancamento_Financeiro::HISTORICO])
              ->setCellValueByColumnAndRow(15, $i, $value[Model_Lancamento_Financeiro::RATEIO])
              ->setCellValueByColumnAndRow(16, $i, $value[Model_Lancamento_Financeiro::ACRESCIMO])
              ->setCellValueByColumnAndRow(17, $i, $value[Model_Lancamento_Financeiro::DECRESCIMO])
              ->setCellValueByColumnAndRow(18, $i, (string) str_pad(str_replace('.', '', $value[Model_Lancamento_Financeiro::CCUSTO]), 9, 0, STR_PAD_LEFT))
              ->setCellValueByColumnAndRow(19, $i, $value[Model_Lancamento_Financeiro::CONTA_ORCA]);
      $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->getNumberFormat()->setFormatCode('00');
      $objPHPExcel->getActiveSheet()->getStyle('J' . $i)->getNumberFormat()->setFormatCode('00');
      $objPHPExcel->getActiveSheet()->getStyle('S' . $i)->getNumberFormat()->setFormatCode('000000000');
    }

    $objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
    $objWriter->setDelimiter(';');
    $objWriter->setEnclosure('');
    $objWriter->setLineEnding("\r\n");
    $objWriter->setSheetIndex(0);

    $nomeArquivo = $this->dadosEmpresa[Model_Empresa::FANTASIA] . '-' . $this->dadosFilial[Model_Filial::NOME] . '-';
    $nomeArquivo .= $this->dadosBanco[Model_Banco::NOME] . '-';
    $nomeArquivo .= number_format((float) $this->logCnab[Model_Log::VALOR], 2, '', '') . '-' . date('Ymd-His') . '.csv';
    $nomeArquivo = $this->retiraAcentos(str_replace('/', '', $nomeArquivo));

    $objWriter->save('./arquivos/lanc_financ/' . $nomeArquivo);

    $acao = $this->Model_Log->save(array(Model_Log::LC => './arquivos/lanc_financ/' . $nomeArquivo), $this->numeroLog);
    if (!$acao) {
      throw new Exception('Erro ao gravar arquivo do Lançamento Financeiro em ' . $nomeArquivo);
    }
  }

  private function writeXLSNovo($dados) {

    $this->load->library('phpexcel');
    $objPHPExcel = new PHPExcel();

    $objPHPExcel->getProperties()->setCreator($this->user_info[Model_Usuario::NOME])
            ->setLastModifiedBy($this->user_info[Model_Usuario::NOME])
            ->setTitle('Leitura do CNAB:' . $this->numeroFolha)
            ->setSubject('APP CNAB')
            ->setDescription('Programa que salva vidas')
            ->setCategory('Leitura CNAB');
    /* $competencia = 'Competencia: ' . $this->dadosEmpresa[Model_Empresa::FANTASIA] . '-' . $this->dadosFilial[Model_Filial::NOME] . '-' . $this->POST['competencia'];
      $competencia = $this->retiraAcentos(str_replace('/', '', $competencia));
      $objPHPExcel->getActiveSheet()->setTitle($competencia); */

    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'CHAPA')
            ->setCellValue('B1', 'NOME')
            ->setCellValue('C1', 'VALOR')
            ->setCellValue('D1', 'CPF');

    $i = 1;
    foreach ($dados as $key => $value) {
      $i++;
      $objPHPExcel->getActiveSheet()
              ->setCellValueByColumnAndRow(0, $i, $value['A'])
              ->setCellValueByColumnAndRow(1, $i, $value['B'])
              ->setCellValueByColumnAndRow(2, $i, $value['C'])
              ->setCellValueByColumnAndRow(3, $i, $value['D']);
      $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->getNumberFormat()->setFormatCode('000000');
      $objPHPExcel->getActiveSheet()->getStyle('D' . $i)->getNumberFormat()->setFormatCode('00000000000');
    }

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

    $nomeArquivo = $this->dadosEmpresa[Model_Empresa::FANTASIA] . '-' . $this->dadosFilial[Model_Filial::NOME] . '-';
    $nomeArquivo .= date('Ymd-His') . '.xlsx';
    $nomeArquivo = $this->retiraAcentos(str_replace('/', '', $nomeArquivo));

    $objWriter->save('./arquivos/uploads/' . $nomeArquivo);

    $this->xls = './arquivos/uploads/' . $nomeArquivo;
  }

  private function setBaseProcesso($linha) {

    foreach ($linha as $key => $value) {

      $dados[Model_Base_Processo::ID] = $this->Model_Base_Processo->autoincrement();
      $dados[Model_Base_Processo::CHAPA] = $value[Model_Funcionario::CHAPA];
      $dados[Model_Base_Processo::VALOR] = $value[Model_Base_Processo::VALOR];
      $dados[Model_Base_Processo::PROCESSO] = $this->numeroProcesso;
      $dados[Model_Base_Processo::NOME] = $this->retiraAcentos($value[Model_Funcionario::NOME]);
      $dados[Model_Base_Processo::BANCO] = $value[Model_Funcionario::BANCO];
      $dados[Model_Base_Processo::AGENCIA] = $value[Model_Funcionario::AGENCIA];
      $dados[Model_Base_Processo::DIGITOAG] = $value[Model_Funcionario::DIGITOAG];
      $dados[Model_Base_Processo::CCUSTO] = $value[Model_Funcionario::CCUSTO];
      $dados[Model_Base_Processo::SECAO] = $value[Model_Funcionario::SECAO];
      $dados[Model_Base_Processo::SITUACAO] = $value[Model_Funcionario::SITUACAO];
      $dados[Model_Base_Processo::PERIODO] = $value[Model_Funcionario::PERIODO];
      $dados[Model_Base_Processo::CPF] = $value[Model_Funcionario::CPF];

      $contadig = explode('-', $value[Model_Funcionario::CONTA]);

      if (!isset($contadig[1])) {
        $dados[Model_Base_Processo::CONTA] = substr($value[Model_Funcionario::CONTA], 0, -1);
        $dados[Model_Base_Processo::DIGITO] = substr($value[Model_Funcionario::CONTA], -1, 1);
      } else {
        $dados[Model_Base_Processo::CONTA] = $contadig[0];
        $dados[Model_Base_Processo::DIGITO] = $contadig[1];
      }
      settype($dados[Model_Base_Processo::ID], 'int');
      settype($value[Model_Funcionario::BANCO], 'int');
      settype($dados[Model_Base_Processo::AGENCIA], 'int');
      settype($dados[Model_Base_Processo::CONTA], 'int');
      settype($dados[Model_Base_Processo::VALOR], 'float');

      $acao = $this->Model_Base_Processo->save($dados);

      if (!$acao) {
        throw new Exception('Erro ao ler linha ' . $key . ' arquivo excel.');
      }
    }
  }

  private function inserirLinha($linha, $ID = NULL) {
    try {
      $dados[Model_Cnab_Novo::ID] = $this->Model_Cnab_Novo->autoincrement();
      $dados[Model_Cnab_Novo::BASE] = $ID;
      settype($dados[Model_Cnab_Novo::ID], 'int');
      if (!is_null($ID)) {
        settype($dados[Model_Cnab_Novo::BASE], 'int');
      }
      $dados[Model_Cnab_Novo::PROCESSO] = $this->numeroProcesso;
      $dados[Model_Cnab_Novo::LINHA] = $linha;
      $dados[Model_Cnab_Novo::BANCO] = $this->bancoAtual;
      $acao = $this->Model_Cnab_Novo->save($dados);
      if (!$acao) {
        throw new Exception('Erro ao gravar linha' . $dados[Model_Cnab_Novo::ID]);
      }
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getTraceAsString());
    }
  }

  private function setLog($dados = '') {
    try {
      if (!empty($dados)) {
        $dados[Model_Log::ID] = $this->Model_Log->autoincrement();
        $dados[Model_Log::PROCESSO] = $this->numeroProcesso;
        $dados[Model_Log::USUARIO] = $this->user_info[Model_Usuario::ID];
        $dados[Model_Log::XLS] = $this->getXls();
        $dados[Model_Log::CNAB] = $this->getTxt();
        $dados[Model_Log::BANCO] = $this->getBancoAtual();
        settype($dados[Model_Log::ID], 'integer');
        settype($dados[Model_Log::PROCESSO], 'integer');
        settype($dados[Model_Log::USUARIO], 'integer');
        settype($dados[Model_Log::BANCO], 'integer');

        $acao = $this->Model_Log->save_log_canb($dados);

        if (!$acao) {
          throw new Exception('ERRO', 'Erro ao gravar log.');
        } else {
          $this->numeroLog = $dados[Model_Log::ID];
        }
      } else {
        throw new Exception('ERRO', 'Não foi possível ler os dados para o log.');
      }
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
      redirect('cnab');
    }
  }

  private function retiraAcentos($texto) {

    $texto = strtr(utf8_decode($texto), utf8_decode('ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ'), 'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy');

    return $texto;
  }

  private function sendEmail() {

    $this->load->library('My_PHPMailer');

    $mail = new PHPMailer();
    $mail->IsSMTP(); //Definimos que usaremos o protocolo SMTP para envio.
    $mail->CharSet = 'UTF-8';
    $mail->SMTPAuth = true; //Habilitamos a autenticação do SMTP. (true ou false)
    $mail->SMTPSecure = 'ssl'; //Estabelecemos qual protocolo de segurança será usado.
    $mail->Host = 'smtp.gmail.com'; //Podemos usar o servidor do gMail para enviar.
    $mail->Port = 465; //Estabelecemos a porta utilizada pelo servidor do gMail.
    $mail->Username = 'dennerfernandes777@gmail.com'; //Usuário do gMail
    $mail->Password = 'salamander'; //Senha do gMail
    $mail->SetFrom('denner.fernandes@grupompe.com.br'); //Quem está enviando o e-mail.
    $mail->AddReplyTo('sistemas@grupompe.com.br', 'noreply - App Cnab'); //Para que a resposta será enviada.
    $mail->Subject = 'Erro validação APP-CNAB'; //Assunto do e-mail.
    $file = explode('-', $this->xls);
    $erro = '<h2>Erros ao Validar arquivo ' . $file[count($file) - 1] . '</h2><br />';
    foreach ($this->msgError as $value) {
      $erro .= '<p>' . $value . '</p>';
    }
    $mail->Body = $erro;
    $mail->AltBody = $erro;
    $mail->AddAddress($this->user_info[Model_Usuario::EMAIL], $this->user_info[Model_Usuario::NOME]);

    /* Também é possível adicionar anexos. */
    //$mail->AddAttachment("images/phpmailer.gif");
    //$mail->AddAttachment("images/phpmailer_mini.gif");

    if (!$mail->Send()) {
      $this->session->set_flashdata('ERRO', 'ocorreu um erro durante o envio: ' . $mail->ErrorInfo);
    } else {
      $this->session->set_flashdata('SUCESSO', 'E-mail enviado com sucesso!');
    }
  }

  //---------------------------------- PUBLIC ------------------------------------------------------------//

  public function downloadCnab($aquivoNome, $tipo = 'cnab', $ext = 'txt') {
    try {

      set_time_limit(0);

      switch ($tipo) {
        case 'cnab':
          $arquivoLocal = './arquivos/cnabs/';
          break;
        case 'upload':
          $arquivoLocal = './arquivos/uploads/';
          break;
        case 'lc':
          $arquivoLocal = './arquivos/lanc_financ/';
          break;
      }

      if ($ext == 'txt') {
        $ext = 'text/plain';
      } else {
        $ext = 'application/vnd.ms-excel';
      }

      $arquivoLocal .= str_replace('%20', ' ', $aquivoNome);

      if (!file_exists($arquivoLocal)) {
        throw new Exception('Arquivo não existe.');
      }

      header('Content-Description: File Transfer');
      header('Content-Disposition: attachment; filename="' . basename($arquivoLocal) . '"');
      header('Content-Type: ' . $ext);
      header('Content-Transfer-Encoding: binary');
      header('Content-Length: ' . filesize($arquivoLocal));
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header('Pragma: public');
      header('Expires: 0');

      readfile($arquivoLocal);
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
      redirect('cnab');
    }
  }

  public function listar($ID) {
    try {

      $processo = array(Model_Cnab_Novo::PROCESSO => $ID);
      $this->data['cnab_novo'] = $this->Model_Cnab_Novo->get($processo);
      $this->data['base_processo'] = $this->Model_Base_Processo->getByCriterio($processo);
      $this->data['log'] = $this->Model_Log->get($ID);

      $this->data['menu_cnab'] = 'active';
      $this->data['breadcrumb'] = $this->breadcrumb(array('CNAB', 'Resumo'));
      $this->MY_view('cnab/listar', $this->data);
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
      redirect('cnab');
    }
  }

  //---------------------------------- GETTERS ------------------------------------------------------------//

  public function getPerguntaDataPag() {
    return $this->perguntaDataPagto;
  }

  public function getPerguntaEmpresa() {
    return $this->perguntaEmpresa;
  }

  public function getBancoAtual() {
    return $this->bancoAtual;
  }

  public function getLogCnab() {
    return $this->logCnab;
  }

  public function getXls() {
    return $this->xls;
  }

  public function getNumeroProcesso() {
    return $this->numeroProcesso;
  }

  public function getDadosEmpresa() {
    return $this->dadosEmpresa;
  }

  public function getDadosBanco() {
    return $this->dadosBanco;
  }

  public function getDadosContaBancaria() {
    return $this->dadosContaBancaria;
  }

  public function getNome_cnab() {
    return $this->nome_cnab;
  }

  public function getNumeroRegistros() {
    return $this->numeroRegistros;
  }

  public function getValorRegistros() {
    return $this->valorRegistros;
  }

  public function getTxt() {
    return $this->txt;
  }

  public function getTotal() {
    return $this->total;
  }

  //---------------------------------- SETTERS ------------------------------------------------------------//

  public function setPerguntaDataPagto($datapag) {
    $this->perguntaDataPagto = $datapag;
  }

  public function setPerguntaEmpresa($empresa) {
    $this->perguntaEmpresa = $empresa;
  }

  public function setBancoAtual($banco) {
    $this->bancoAtual = $banco;
  }

  public function setLogCnab($cnab) {
    if (is_array($cnab)) {
      foreach ($cnab as $key => $value) {
        $this->logCnab[$key] = $value;
      }
    } else {
      $this->logCnab = $cnab;
    }
  }

  public function setXls($xls) {
    $this->xls = $xls;
  }

  public function setNumeroProcesso($numeroProcesso) {
    $this->numeroProcesso = $numeroProcesso;
    ;
  }

  public function setDadosEmpresa($dadosEmpresa) {
    $this->dadosEmpresa = $dadosEmpresa;
  }

  public function setDadosBanco($dadosBanco) {
    $this->dadosBanco = $dadosBanco;
  }

  public function setDadosContaBancaria($dadosContaBancaria) {
    $this->dadosContaBancaria = $dadosContaBancaria;
    return $this;
  }

  public function setNome_cnab($nome_cnab) {
    $this->nome_cnab = $nome_cnab;
  }

  public function setNumeroRegistros($numeroRegistros) {
    $this->numeroRegistros = $numeroRegistros;
  }

  public function setValorRegistros($valorRegistros) {
    $this->valorRegistros = $valorRegistros;
  }

  public function setTxt($txt) {
    $this->txt = $txt;
  }

  public function setTotal($total) {
    $this->total = $total;
  }

}
