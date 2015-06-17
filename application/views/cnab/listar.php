<div class="container">
  <div class="row">
    <div class="col-lg-12">
      <div class="well clearfix">
        <div class="row">
          <dl class="dl-horizontal col-lg-7 col-xs-6">
            <dt>EMPRESA:</dt>
            <dd><?php echo $log[0]['EMPRESA']; ?></dd>
            <dt>FILIAL:</dt>
            <dd><?php echo $log[0]['FILIAL']; ?></dd>
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
              </dl>
              <dl class="col-xs-4">
                <dt class="text-muted">CNAB GERADO:</dt>
                <dd>
                  <div class="btn-group hidden-xs">
                    <a class="btn btn-default tooltips" onclick="conferir('.cnabnovo<?php echo $row['NUMBANCO']; ?>');" title="Visualizar CNAB">
                      <span class="glyphicon glyphicon-eye-open"></span> CNAB
                    </a>
                    <?php
                    $cnab = explode('/', $row[Model_Log::CNAB]);
                    $pos = count($cnab);
                    ?>
                    <a class="btn btn-primary tooltips" href="<?php echo base_url('cnab/downloadCnab/' . $cnab[$pos - 1] . '/cnab/txt'); ?>" title="Download CNAB">
                      <span class="glyphicon glyphicon-download-alt"></span> CNAB
                    </a>
                    <?php
                    $lc = explode('/', $row[Model_Log::LC]);
                    $posicao = count($lc);
                    ?>
                    <a class="btn btn-success tooltips" href="<?php echo base_url('cnab/downloadCnab/' . $lc[$posicao - 1] . '/lc/xlsx'); ?>" title="Download da Arquivo de Importação">
                      <span class="glyphicon glyphicon-download-alt"></span> PROTHEUS
                    </a>
                  </div>
                  <div class="btn-group visible-xs">
                    <a class="btn btn-default tooltips" onclick="conferir('.cnabnovo<?php echo $row['NUMBANCO']; ?>');" title="Visualizar CNAB">
                      <span class="glyphicon glyphicon-eye-open"></span>
                    </a>
                    <?php
                    $cnab = explode('/', $row[Model_Log::CNAB]);
                    $pos = count($cnab);
                    ?>
                    <a class="btn btn-primary tooltips" href="<?php echo base_url('cnab/downloadCnab/' . $cnab[$pos - 1] . '/cnab/txt'); ?>" title="Download CNAB">
                      <span class="glyphicon glyphicon-download-alt"></span>
                    </a>
                    <?php
                    $lc = explode('/', $row[Model_Log::LC]);
                    $posicao = count($lc);
                    ?>
                    <a class="btn btn-success tooltips" href="<?php echo base_url('cnab/downloadCnab/' . $lc[$posicao - 1] . '/lc/xlsx'); ?>" title="Download da Arquivo de Importação">
                      <span class="glyphicon glyphicon-download-alt"></span>
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
</div> <!-- /container --> 