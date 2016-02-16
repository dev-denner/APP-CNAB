<div class="container">

    <?php echo form_open_multipart('cnab/controll', 'class="form-horizontal" onsubmit="overlay(true);"'); ?>
    <fieldset class="col-lg-6 center col-lg-offset-3">
        <legend>Defina os parâmetros para gerar o CNAB</legend>

        <div class="form-group">
            <label for="empresa_cnab" class="col-sm-4 control-label">Empresa</label>
            <div class="col-sm-8">
                <select name="<?php echo Model_Processo::EMPRESA; ?>" id="empresa_cnab" class="form-control" required>
                    <option value="">Defina a Empresa</option>
                    <?php foreach ($empresa as $row): ?>
                        <option value="<?php echo strtoupper($row[Model_Empresa::ID]) ?>"><?php echo $row[Model_Empresa::COLIGADA], ' - ', $row[Model_Empresa::NOME] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="tipo_operacao" class="col-sm-4 control-label">Tipo de Pagamento</label>
            <div class="col-sm-8">
                <select name="<?php echo Model_Processo::OPERACAO; ?>" id="tipo_operacao" class="form-control" required>
                    <option value="">Defina o Tipo de Pagamento</option>
                    <?php foreach ($tipo_operacao as $row): ?>
                        <option value="<?php echo $row[Model_Tipo_Operacao::ID] ?>"><?php echo $row[Model_Tipo_Operacao::NOME] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="competencia" class="col-sm-4 control-label">Competência</label>
            <div class="col-sm-8">
                <input type="month" name="competencia" id="competencia" class="form-control" required maxlength="4" />
            </div>
        </div>

        <div class="form-group">
            <label for="data_pagto" class="col-sm-4 control-label">Data Prevista de Pagamento</label>
            <div class="col-sm-8">
                <input type="date" name="<?php echo Model_Processo::DATAPAG; ?>" id="data_pagto" class="form-control" required />
            </div>
        </div>

        <div class="form-group">
            <label for="gerar1" class="col-sm-4 control-label">O que deseja fazer?</label>
            <div class="col-sm-8">
                <div class="radio radio-inline">
                    <label>
                        <input type="radio" name="gerar" id="gerar1" value="cnab" checked>
                        Gerar novo CNAB
                    </label>
                </div>
                <div class="radio radio-inline">
                    <label>
                        <input type="radio" name="gerar" id="gerar2" value="xls">
                        Ler CNAB
                    </label>
                </div>
                <div class="checkbox checkbox-inline">
                    <label>
                        <input type="checkbox" name="layout240" id="layout240" value="bradesco">
                        Bradesco 240
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="nome_arquivo" class="col-sm-4 control-label">Nome do Arquivo</label>
            <div class="col-sm-8">
                <input type="text" name="nome_arquivo" id="nome_arquivo" class="form-control" />
            </div>
        </div>

        <div class="form-group">
            <label for="xls" class="col-sm-4 control-label">Enviar Arquivo</label>
            <div class="col-sm-8">
                <input type="file" name="xls" id="xls" class="form-control" required />
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-6 col-sm-6">
                <button type="submit" class="btn btn-primary btn-lg">Processar</button>
            </div>
        </div>
    </fieldset>
</form>

</div> <!-- /container --> 