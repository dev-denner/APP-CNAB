<div class="container">
  <div class="row">
    <div class="col-lg-12">
      <div class="well clearfix">
        <div class="row">
          <dl class="dl-horizontal col-lg-7 col-xs-6">
            <dt>FOLHA:</dt>
            <dd><?php echo str_pad($log[0][Model_Folha::ID], 7, 0, STR_PAD_LEFT); ?></dd>
            <dt>EMPRESA:</dt>
            <dd><?php echo $log[0]['EMPRESA']; ?></dd>
            <dt>PROCESSO:</dt>
            <dd><?php echo str_pad($log[0][Model_Log::PROCESSO], 11, 0, STR_PAD_LEFT); ?></dd>
            <dt>USUÁRIO:</dt>
            <dd><?php echo $log[0][Model_Usuario::NOME]; ?></dd>
            <dt>T. OPERAÇÃO:</dt>
            <dd><?php echo $log[0]['OPERACAO']; ?></dd>
          </dl>
          <dl class="dl-horizontal col-lg-5 col-xs-6">
            <dt>COMPETÊNCIA:</dt>
            <dd><?php echo str_pad($log[0]['COMPETENCIA'], 7, 0, STR_PAD_LEFT); ?></dd>
            <dt>DATA PAGTO:</dt>
            <dd><?php echo $log[0]['DATAPAG']; ?></dd>
            <dt>VALOR TOTAL:</dt>
            <dd><?php echo 'R$ ' . number_format(str_replace(',', '.', $log[0]['VALOR']), 2, ',', '.'); ?></dd>
            <br />
            <dt class="text-muted">FUNCIONÁRIOS (LOG):</dt>
            <dd>
              <a class="btn tooltips btn-default btn-xs" onclick="conferir('.confere2');" title="Visualizar Funcionários do XLS Enviado">
                <span class="glyphicon glyphicon-eye-open"></span> VISUALIZAR
              </a>
            </dd>
            <dt class="text-muted">ARQ. ENVIADO (BASE):</dt>
            <dd>
              <?php
              $xlsx = explode('/', $log[0][Model_Log::XLS]);
              $pos = count($xlsx);
              ?>
              <a href="<?php echo base_url('cnab/downloadCnab/' . $xlsx[$pos - 1] . '/upload/xls'); ?>" class="btn tooltips btn-success btn-xs" title="Download do Arquivo XLS Enviado">
                <span class="glyphicon glyphicon-download-alt"></span> DOWNLOAD
              </a>
            </dd>
          </dl>
        </div>
      </div>
      <hr />
      <div class="panel panel-default">
        <div class="row">
          <div class="panel-heading">
            <h4 class="panel-title text-center">
              <span class="fa-stack fa-lg">
                <i class="fa fa-circle-o fa-stack-2x"></i>
                <i class="fa fa-file fa-stack-1x"></i>
              </span> CNABs Gerados
            </h4>
          </div>
          <?php
          foreach ($log as $row) :
            ?>
            <dl class="dl-horizontal col-xs-4">
              <dt>ID:</dt>
              <dd><?php echo str_pad($row[Model_Log::ID], 11, 0, STR_PAD_LEFT); ?></dd>
              <dt>DATA:</dt>
              <dd><?php echo $row['DATA']; ?></dd>
            </dl>
            <dl class="dl-horizontal col-xs-4">
              <dt>BANCO:</dt>
              <dd><?php echo $row['BANCO']; ?></dd>
              <dt>TOTAL:</dt>
              <dd><?php echo 'R$ ' . number_format(str_replace(',', '.', $row['TOTAL']), 2, ',', '.'); ?></dd>
              <dt>TOTAL BAIXADO:</dt>
              <dd><?php echo 'R$ ' . number_format(str_replace(',', '.', $row['total_baixado']), 2, ',', '.'); ?></dd>
            </dl>
            <dl class="col-xs-4">
              <dt class="text-muted">CNAB GERADO:</dt>
              <dd>
                <div class="btn-group">
                  <a class="btn btn-default btn-xs tooltips" onclick="conferir('.cnabnovo<?php echo $row['NUMBANCO']; ?>');" title="Visualizar CNAB">
                    <span class="glyphicon glyphicon-eye-open"></span> CNAB
                  </a>
                  <?php
                  $cnab = explode('/', $row[Model_Log::CNAB]);
                  $pos = count($cnab);
                  ?>
                  <a class="btn btn-primary btn-xs tooltips" onclick="levaModal(this);" data-empresa="<?php echo $log[0]['IDEMPRESA']; ?>" data-arquivo="<?php echo $row[Model_Log::ID]; ?>" data-file="<?php echo 'cnab/downloadCnab/' . $cnab[$pos - 1] . '/cnab/txt'; ?>" data-folha="<?php echo $log[0][Model_Folha::ID]; ?>" title="Download CNAB" data-toggle="modal" data-target="#baixarCnab">
                    <span class="glyphicon glyphicon-download-alt"></span> CNAB
                  </a>
                </div>
                <div class="btn-group">
                  <button class="btn btn-default btn-xs tooltips" onclick="visualizarLancamentoFinanceiro('<?php echo base_url('cnab/visualizarLancamentoFinanceiro/'); ?>', <?php echo $row[Model_Log::ID]; ?>)" title="Visualizar Lançamento Financeiro">
                    <span class="glyphicon glyphicon-eye-open"></span> CC / EO
                  </button>
                  <?php
                  $lc = explode('/', $row[Model_Log::LC]);
                  $posicao = count($lc);
                  ?>
                  <a class="btn btn-success btn-xs  tooltips" href="<?php echo base_url('cnab/downloadCnab/' . $lc[$posicao - 1] . '/lc/xlsx'); ?>" title="Download da Arquivo de Importação">
                    <span class="glyphicon glyphicon-download-alt"></span> PROTHEUS
                  </a>
                </div>
                <div class="btn-group">
                  <a class="btn btn-warning btn-xs tooltips" href="<?php echo base_url('cnab/baixar_pagamento/' . $row[Model_Log::ID]); ?>" title="Confirmar Pagamento">
                    <span class="glyphicon glyphicon-fire"></span> BAIXAR
                  </a>
                </div>
              </dd>
            </dl>
            <div style="clear: both"></div>
            <?php
          endforeach;
          ?>

        </div>
      </div>

      <div class="confere2">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover table-condensed datatable">
            <thead>
              <tr>
                <th>FILIAL</th>
                <th>CHAPA</th>
                <th>NOME</th>
                <th width="120">CPF</th>
                <th>VALOR</th>
                <th>BANCO</th>
                <th>AG - DIG.</th>
                <th>C/C - DIG.</th>
                <th>C. CUSTO</th>
                <th>P. PAGTO</th>
                <th>SITUACAO</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($base_processo as $row):
                if (!empty($row[Model_Base_Processo::NOME])):
                  ?>
                  <tr>
                    <td><?php echo str_pad($row[Model_Base_Processo::FILIAL], 4, 0, STR_PAD_LEFT); ?></td>
                    <td><?php echo $row[Model_Base_Processo::CHAPA]; ?></td>
                    <td><?php echo $row[Model_Base_Processo::NOME]; ?></td>
                    <td><?php echo $row[Model_Base_Processo::CPF]; ?></td>
                    <td><?php echo 'R$' . number_format(str_replace(',', '.', $row[Model_Base_Processo::VALOR]), 2, ',', '.'); ?></td>
                    <td><?php echo $row[Model_Banco::NOME]; ?></td>
                    <td><?php echo str_pad($row[Model_Base_Processo::AGENCIA], 4, 0, STR_PAD_LEFT) . ' ' . $row[Model_Base_Processo::DIGITOAG]; ?></td>
                    <td><?php echo str_pad($row[Model_Base_Processo::CONTA], 6, 0, STR_PAD_LEFT) . ' ' . $row[Model_Base_Processo::DIGITO]; ?></td>
                    <td><?php echo $row[Model_Base_Processo::CCUSTO]; ?></td>
                    <td><?php echo $row[Model_Base_Processo::PERIODO]; ?></td>
                    <td><?php echo $row[Model_Base_Processo::SITUACAO]; ?></td>
                  </tr>
                  <?php
                endif;
              endforeach;
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <?php
  foreach ($log as $row) :
    ?>
    <div class="confere cnabnovo<?php echo $row['NUMBANCO']; ?>">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>Processo: <?php echo $cnab_novo[0][Model_Cnab_Novo::PROCESSO] ?></th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($cnab_novo as $row2):
              if ($row2[Model_Cnab_Novo::BANCO] == $row['NUMBANCO']):
                ?>
                <tr>
                  <td><xmp><?php echo $row2[Model_Cnab_Novo::LINHA] ?></xmp></td>
              </tr>
              <?php
            endif;
          endforeach;
          ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php
  endforeach;
  ?>
  <div class="lancfinanc">

  </div>
</div> <!-- /container --> 

<!-- Modal -->
<div class="modal fade" id="baixarCnab" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open_multipart('cnab/downloadByEmpresa', 'class="form-horizontal" target="_new"'); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Pagar por qual empresa?</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="empresa_cnab" class="col-sm-4 control-label">Empresa</label>
          <div class="col-sm-8">
            <select name="<?php echo Model_Processo::EMPRESA; ?>" id="empresa_cnab" class="form-control">
              <option value="">Escolha a Empresa</option>
              <?php foreach ($empresa as $row): ?>
                <option value="<?php echo strtoupper($row[Model_Empresa::ID]) ?>"><?php echo $row[Model_Empresa::COLIGADA], ' - ', $row[Model_Empresa::NOME] ?></option>
              <?php endforeach; ?>
            </select>
            <input type="hidden" name="empresa" class="empresa" value="<?php echo $log[0]['IDEMPRESA']; ?>" />
            <input type="hidden" name="arquivo" class="arquivo" value="" />
            <input type="hidden" name="cnab" class="cnab" value="" />
            <input type="hidden" name="folha" class="folha" value="" />
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary">Download</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>