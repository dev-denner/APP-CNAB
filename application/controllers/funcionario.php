<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of funcionario
 *
 * @author denner.fernandes
 */
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class funcionario extends MY_Controller {

  private $dadosEmpresa = array();
  private $dadosBanco = array();
  private $msgError = NULL;

  public function __construct() {
    parent::__construct();
    $this->load->Model('Model_Funcionario');
    $this->load->Model('Model_Empresa');
    $this->load->Model('Model_Banco');
    $this->load->Model('Model_Filial');
  }

  public function index() {
    $this->data['menu_admin'] = 'active';
    $this->data['menu_funcionario'] = 'active';
    $this->data['breadcrumb'] = $this->breadcrumb(array('Admin', 'funcionario'));
    $this->data['funcionario'] = $this->Model_Funcionario->getAll();
    $this->MY_view('funcionario/listar', $this->data);
  }

  public function importar() {
    $this->data['menu_admin'] = 'active';
    $this->data['menu_funcionario'] = 'active';
    $this->data['breadcrumb'] = $this->breadcrumb(array('Admin', 'funcionario', 'importar'));
    $this->data['empresa'] = $this->Model_Empresa->getAll();
    $this->MY_view('funcionario/importar', $this->data);
  }

  public function processarImportacao() {
    try {
//Validar entradas
      $this->valida();

      $this->setDadosEmpresa($this->Model_Empresa->get($this->POST[Model_Funcionario::EMPRESA])[0]);
      $this->setDadosBanco($this->Model_Banco->getAll());

      $xls = pathinfo($_FILES['file']['name']);

      $file = $this->doUpload('file');

      $dados = $this->readXLS($xls['extension'], $file);

      $this->validaFuncionario($dados);
      
      redirect('funcionario');
    } catch (Exception $exc) {
      $this->session->set_flashdata('ERRO', $exc->getMessage());
      redirect('funcionario/importar');
    }
  }

  private function valida() {

    if (empty($this->POST[Model_Funcionario::EMPRESA])) {
      throw new Exception('Escolha uma Empresa.');
    }
    if (empty($_FILES['file'])) {
      throw new Exception('Não foi enviado nenhum arquivo.');
    }
  }

  private function doUpload($form = NULL) {

    $config['pasta'] = './arquivos/funcionarios/';

    $nome_arquivo = $_FILES[$form]['name'];
    $nome_final = date('YmdHis') . '-' . $nome_arquivo;

    $config['extensoes'] = array('xls', 'xlsx', 'csv');

    $file = $config['pasta'] . $nome_final;

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
      if (!move_uploaded_file($_FILES[$form]['tmp_name'], $file)) {
        throw new Exception('Não foi possível enviar o arquivo, tente novamente');
      } else {
        return $file;
      }
    }
  }

  private function readXLS($ext, $file) {

    $this->load->library('phpexcel');

    $inputFileName = $file;

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

  private function validaFuncionario($dados) {

    $info = $dadosFuncionario = $chapas = array();

    foreach ($dados as $key => $value) {

      $flagBanco = $flagConta = FALSE;
      $chapa = $nome = $eo = $banco = $agencia = $agenciadig = $conta = $contadig = $cpf = $situacao = $periodo = $coligada = '';
      $dado = array();

      if ($value['A'] != 'CHAPA' && !is_null($value['A'])) {
        $chapa = isset($value['A']) ? str_pad((int) $value['A'], 6, "0", STR_PAD_LEFT) : '';
        $nome = str_replace('\'', '', $value['B']);
        $eo = str_pad($value['C'], 6, "0", STR_PAD_LEFT);
        $banco = $value['D'];
        $agencia = $value['E'];
        $agenciadig = $value['F'];

        settype($value['G'], 'string');
        $value['G'] = trim($value['G']);
        if (!is_null($value['G']) && isset($value['G'][1])) {
          $aux = explode('-', $value['G']);
          if (isset($aux[1])) {
            $conta = $aux[0];
            $contadig = $aux[1];
          } else {
            $conta = substr($value['G'], 0, -1);
            $contadig = substr($value['G'], -1, 1);
          }
        } else {
          $flagConta = TRUE;
        }
        $cpf = str_replace('/', '', str_replace('-', '', str_replace('.', '', $value['H'])));
        $situacao = $value['I'];
        $periodo = $value['J'];
        $filial = $value['L'];

        $coligada = $this->getDadosEmpresa()[Model_Empresa::ID];

        foreach ($this->getDadosBanco() as $key => $value) {
          if ($banco == str_pad($value[Model_Banco::COD], 3, '0', STR_PAD_LEFT)) {
            $flagBanco = TRUE;
          }
        }

        if (!$flagBanco) {
          $this->msgError .= 'O banco ' . $banco . ' não está cadastrado no sistema CNAB. Funcionário ' . $nome . ' - ' . $chapa . ' não foi importado. <br>';
        }
        if ($flagConta) {
          $this->msgError .= 'A conta corrente do funcionário ' . $nome . ' - ' . $chapa . ' não foi inserida no arquivo enviado. Ele não foi importado.<br>';
        }

        if ($flagBanco === TRUE && $flagConta === FALSE) {
          $dado[Model_Funcionario::EMPRESA] = $coligada;
          $dado[Model_Funcionario::CHAPA] = $chapa;
          $dado[Model_Funcionario::NOME] = trim($nome);
          $dado[Model_Funcionario::BANCO] = $banco;
          $dado[Model_Funcionario::AGENCIA] = $agencia;
          $dado[Model_Funcionario::DIGITOAG] = trim($agenciadig);
          $dado[Model_Funcionario::CONTA] = $conta;
          $dado[Model_Funcionario::DIGITO] = trim($contadig);
          $dado[Model_Funcionario::CCUSTO] = $eo;
          $dado[Model_Funcionario::CPF] = $cpf;
          $dado[Model_Funcionario::SITUACAO] = trim($situacao);
          $dado[Model_Funcionario::PERIODO] = trim($periodo);
          $dado[Model_Funcionario::FILIAL] = str_pad(trim($filial), 2, 0, STR_PAD_LEFT);

          settype($dado[Model_Funcionario::EMPRESA], 'int');
          settype($dado[Model_Funcionario::AGENCIA], 'int');
          settype($dado[Model_Funcionario::CONTA], 'int');
          settype($dado[Model_Funcionario::BANCO], 'int');
          settype($dado[Model_Funcionario::DIGITOAG], 'string');
          settype($dado[Model_Funcionario::CCUSTO], 'string');

          $info = $this->Model_Funcionario->get(
                  array(
                      Model_Funcionario::EMPRESA => $coligada,
                      Model_Funcionario::CPF => $cpf,
                  )
          );
          //var_dump($dado); exit;
          if (empty($info)) {
            $dado[Model_Funcionario::ID] = $this->Model_Funcionario->autoincrement();
            settype($dado[Model_Funcionario::ID], 'int');
            $acao = $this->Model_Funcionario->save($dado);
          } else {
            $acao = $this->Model_Funcionario->save($dado, array(
                Model_Funcionario::EMPRESA => $coligada,
                Model_Funcionario::CPF => $cpf
                    )
            );
          }

          if (!$acao) {
            $this->msgError .= 'Ocorreu um erro ao importar o funcionário ' . $nome . ' - ' . $chapa . '<br>';
          }
        }
      }
    }
    if (!is_null($this->msgError)) {
      $this->session->set_flashdata('ERRO', $this->msgError);
    } else {
      $this->session->set_flashdata('SUCESSO', 'Funcionários importados com sucesso.');
    }
  }

  public function getDadosEmpresa() {
    return $this->dadosEmpresa;
  }

  public function getDadosBanco() {
    return $this->dadosBanco;
  }

  public function setDadosEmpresa($dadosEmpresa) {
    $this->dadosEmpresa = $dadosEmpresa;
  }

  public function setDadosBanco($dadosBanco) {
    $this->dadosBanco = $dadosBanco;
  }

}
