<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of relatorio
 *
 * @author denner.fernandes
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Relatorio extends MY_Controller {

    private $nomeArquivo = 'Relatório';

    public function __construct() {
        parent::__construct();
        $this->load->Model('Model_Empresa');
        $this->load->Model('Model_Banco');
        $this->load->Model('Model_Tipo_Operacao');
        $this->load->Model('Model_Relatorio');
    }

    public function index() {
        $this->session->set_flashdata('ERRO', 'Opção não permitida.');
        redirect('dashboard');
    }

    public function arquivos() {
        try {

            $this->data['empresa'] = $this->Model_Empresa->getAll();
            $this->data['tipo_operacao'] = $this->Model_Tipo_Operacao->getAll();
            $this->data['banco'] = $this->Model_Banco->getAll();
            $this->data['menu_relatorio'] = 'active';
            $this->data['menu_arquivos'] = 'active';
            $this->data['breadcrumb'] = $this->breadcrumb(array('Relatório', 'arquivos'));

            if ($this->POST) {
                $campos = array(Model_Empresa::ID, Model_Tipo_Operacao::ID, Model_Banco::ID, 'competencia');
                $dados = elements($campos, $this->POST);
                $this->data['relatorio'] = $this->Model_Relatorio->getArquivos($dados);
                $this->data['perguntas'] = $dados;
            }
        } catch (Exception $exc) {
            $this->session->set_flashdata('ERRO', $exc->getMessage());
        }
        $this->MY_view('relatorio/arquivos', $this->data);
    }

    public function liquido_folha() {
        try {
            $this->data['breadcrumb'] = $this->breadcrumb(array('Relatório', 'liquido_folha'));
            $this->data['periodo'] = $this->Model_Relatorio->getPeriodo();
            $this->data['menu_relatorio'] = 'active';
            $this->data['menu_liquido_folha'] = 'active';

            if ($this->POST) {
                $campos = array('periodo', 'competencia');
                $dados = elements($campos, $this->POST);
                $this->data['relatorio'] = $this->Model_Relatorio->get_liquido_folha($dados);
                $this->data['perguntas'] = json_encode($dados);
            }
            $this->MY_view('relatorio/liquido_folha', $this->data);
        } catch (Exception $exc) {
            $this->session->set_flashdata('ERRO', $exc->getMessage());
        }
    }

    public function relatorio_analitico() {
        try {

            if ($this->is_ajax()) {
                $campos = array('empreendimento', 'filial', 'ano', 'mes', 'periodo');
                $dados = elements($campos, $this->POST);
                $relatorio = $this->Model_Relatorio->get_liquido_folha_analitico($dados);
                echo $this->make_table($relatorio);
            }
        } catch (Exception $exc) {
            echo 'Ocorreu algum erro';
        }
    }

    public function controle_pagamento() {
        try {
            if ($this->POST) {
                $campos = array(Model_Empresa::ID, 'filial', 'gestor', 'cc_de', 'cc_ate', 'chapa', Model_Tipo_Operacao::ID, 'competencia', 'periodo');
                $dados = elements($campos, $this->POST);
                var_dump($dados);
                exit;
                $this->data['relatorio'] = $this->Model_Relatorio->getControlePagamento($dados);
                $this->data['perguntas'] = json_encode($dados);
            }

            $this->data['periodo'] = $this->Model_Relatorio->getPeriodo();
            $this->data['gestor'] = $this->Model_Relatorio->getGestor();
            $this->data['empresa'] = $this->Model_Empresa->getAll();
            $this->data['tipo_operacao'] = $this->Model_Tipo_Operacao->getAll();

            $this->data['menu_relatorio'] = 'active';
            $this->data['menu_controle_pagamento'] = 'active';
            $this->data['breadcrumb'] = $this->breadcrumb(array('Relatório', 'controle_pagamento'));
            $this->MY_view('relatorio/controle_pagamento', $this->data);
        } catch (Exception $exc) {
            $this->session->set_flashdata('ERRO', $exc->getMessage());
        }
    }

    public function getFilial($empresa) {
        try {
            if (is_numeric($empresa)) {
                $this->load->Model('Model_Empresa');
                $coligada = $this->Model_Empresa->get($empresa)[0][Model_Empresa::COLIGADA];
                $filial = $this->Model_Relatorio->getFilial($coligada);
                $retorno = '';
                if (is_array($filial)) {
                    foreach ($filial as $row => $value) {
                        $retorno .= '<option value="' . $value['CODFILIAL'] . '">' . $value['CODFILIAL'] . ' - ' . $value['NOMEFANTASIA'] . '</option>';
                    }
                } else {
                    $retorno = '<option value="">Não há filiais cadastradas para esta empresa.</option>';
                }
                echo $retorno;
            } else {
                throw new Exception('ERRO!!|Acesso não permitido a função.');
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function imprimirRelatorio($dados = NULL) {

        if (isset($this->POST)) {
            $dados = $this->POST['dados'];
        }

        $dados = json_decode($dados);

        $this->load->library('PHPExcel');
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()->setCreator($this->user_info[Model_Usuario::NOME])
                ->setLastModifiedBy($this->user_info[Model_Usuario::NOME])
                ->setTitle('Relatorio de Arquivos Gerados:')
                ->setSubject('APP CNAB')
                ->setDescription('Programa que salva vidas')
                ->setCategory('Relatorios');
        $objPHPExcel->getActiveSheet()->setTitle('Arquivos Gerados');

        $alpha = 'ABCDEFGHIJKLMNOPQRSTUVXZ';
        $i = 0;

        foreach ($dados as $key => $value) {
            $i++;
            $j = 0;
            foreach ($value as $key2 => $value2) {
                if (1 == $i) {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alpha[$j] . $i, $key2);
                    $k = $i + 1;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alpha[$j] . $k, $value2);
                } else {
                    $k = $i + 1;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alpha[$j] . $k, $value2);
                }
                $j++;
            }
        }
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

        header("HTTP/1.1 200 OK");
        header("Pragma: public");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $this->nomeArquivo . ' - ' . date('Ymd-His') . '.xlsx"');
        header("Content-Transfer-Encoding: binary");
        //header('Cache-Control: max-age=1');

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
    }

    public function imprimirRelatorioAnalitico() {
        try {

            $POST = json_decode($this->POST['dados']);
            foreach ($POST as $key => $value) {
                $this->POST[$key] = $value;
            }

            $dados = array('periodo' => $this->POST['periodo']);

            $competencia = explode('-', $this->POST['competencia']);
            $dados['ano'] = $competencia[0];
            $dados['mes'] = $competencia[1];

            $relatorio = $this->Model_Relatorio->get_liquido_folha_analitico($dados);

            $this->nomeArquivo = 'Relatório Analítico';
            $this->imprimirRelatorio($this->POST['dados'] = json_encode($relatorio));
            echo 'ok';
        } catch (Exception $exc) {
            echo 'Ocorreu algum erro';
        }
    }

    private function make_table($dados) {

        $return = '<table class="table table-striped table-bordered table-hover table-condensed">
        <thead>
          <tr>
            <!--<th>EMPREENDIMENTO</th>-->
            <th>FILIAL</th>
            <th>SEÇÃO</th>
            <th>NOME SEÇÃO</th>
            <th>ANO</th>
            <th>MÊS</th>
            <th>PERIODO</th>
            <th>BB</th>
            <th>BRADESCO</th>
            <th>CITY</th>
            <th>SANTANDER</th>
            <th>TOTAL</th>
          </tr>
        </thead>
        <tbody>';

        foreach ($dados as $row) {

            //$class = $row['NM_EMPDMT'] == 'NÃO SE APLICA' ? 'class="danger"' : '';
            $class = '';
            $return .= '<tr ' . $class . '>';
            //$return .= '<td>' . $row['NM_EMPDMT'] . '</td>';
            $return .= '<td>' . str_pad($row['CD_FILIAL'], 2, '0', STR_PAD_LEFT) . '</td>';
            $return .= '<td>' . $row['CD_SECAO'] . '</td>';
            $return .= '<td>' . $row['NM_SECAO'] . '</td>';
            $return .= '<td>' . $row['NU_ANO'] . '</td>';
            $return .= '<td>' . str_pad($row['NU_MES'], 2, '0', STR_PAD_LEFT) . '</td>';
            $return .= '<td>' . str_pad($row['NU_PERIODO'], 2, '0', STR_PAD_LEFT) . '</td>';
            $return .= '<td>R$ ' . number_format(str_replace(',', '.', $row['BB']), 2, ',', '.') . '</td>';
            $return .= '<td>R$ ' . number_format(str_replace(',', '.', $row['BRADESCO']), 2, ',', '.') . '</td>';
            $return .= '<td>R$ ' . number_format(str_replace(',', '.', $row['CITY']), 2, ',', '.') . '</td>';
            $return .= '<td>R$ ' . number_format(str_replace(',', '.', $row['SANTANDER']), 2, ',', '.') . '</td>';
            $return .= '<td>R$ ' . number_format(str_replace(',', '.', $row['TOTAL']), 2, ',', '.') . '</td>';
            $return .= '</tr>';
        }

        return $return;
    }

    public function __destruct() {
        
    }

}
