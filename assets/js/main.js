/* Author:
 denner.fernandes - denners777@hotmail.com
 */

var CODLOCAL = 'http://intranet.grupompe.com.br/cnab/';

$(document).ready(function () {

  $('.tooltips').tooltip();
  $('.datatable').dataTable({
    "aLengthMenu": [[10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, "All"]],
    "iDisplayLength": 100
  });
  $(".datatable").tablecloth({
    theme: "stats",
    //bordered: true,
    //condensed: true,
    striped: true,
    //sortable: true,
    clean: true,
    cleanElements: "th td",
    customClass: "table table-hover"
  });




//  $('#empresa_cnab').on("change", function(e) {
//    var $ID = $('#empresa_cnab').val();
//    $.post(CODLOCAL + 'filial/selectFilial/' + $ID, function(data) {
//      $('#filial').html(data);
//    });
//  });

});
function visualizarLancamentoFinanceiro($url, $ID) {

  $.ajax({
    type: 'POST',
    url: $url,
    async: true,
    data: {id: $ID},
    success: function (msg) {
      $('.lancfinanc').html(msg);

    }
  });
}
function deletar($action, $direct) {
  bootbox.confirm("Deseja realmente deletar este registro?", function () {
    overlay(true);
    $.post($action, function (data) {
      $(location).attr('href', $direct);
    });
  });
}

function overlay($in) {
  if ($in) {
    $('.overlay').fadeIn('slow');
  } else {
    $('.overlay').fadeOut('slow');
  }
}

function conferir($element) {

  $($element).toggle('slow');
}

function gerarExcel($dados) {
  overlay(true);
  $.post({
    type: 'POST',
    url: CODLOCAL + 'relatorio/imprimirRelatorio/',
    data: {dados: $dados}
  }).done(function (msg) {
    console.log("Data Saved: " + msg);
  }).fail(function () {
    alert("error");
  });
  overlay(false);
}
function relatorio_analitico($this, $empreendimento, $filial, $ano, $mes, $periodo) {

  if ($($this).hasClass('fa-plus')) {

    overlay(true);

    $('.dataTables_wrapper').find('.fa-minus').removeClass('fa-minus').addClass('fa-plus');
    $('.dataTables_wrapper').find('.analitico').remove();

    $($this).parents('tr').after('<tr class="analitico"><td colspan="11" class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i></td></tr>');
    $.ajax({
      type: 'POST',
      url: CODLOCAL + 'relatorio/relatorio_analitico/',
      async: true,
      data: {
        empreendimento: $empreendimento,
        filial: $filial,
        ano: $ano,
        mes: $mes,
        periodo: $periodo
      },
      success: function ($return) {

        $('.analitico td').html($return);
        $($this).removeClass('fa-plus').addClass('fa-minus');
        overlay(false);

      }
    });

  } else {
    $($this).removeClass('fa-minus').addClass('fa-plus');
    $('.dataTables_wrapper').find('.analitico').remove();
  }
}

var levaModal = function (e) {
  var empresa = $(e).data('empresa');
  var arquivo = $(e).data('arquivo');
  var file = $(e).data('file');
  var folha = $(e).data('folha');

  $('#baixarCnab .empresa').val(empresa);
  $('#baixarCnab .arquivo').val(arquivo);
  $('#baixarCnab .cnab').val(file);
  $('#baixarCnab .folha').val(folha);
}

var validaEmpresa = function () {
  overlay(true);

  $.ajax({
    type: 'POST',
    url: CODLOCAL + 'atendimento/graficos/getProprietario',
    data: {
      fila: $fila,
      datade: $datade,
      dataate: $dataate,
      responsavel: $('#responsavel').val(),
      grafico: $grafico
    },
    success: function (obj) {
      overlay(false);
    }
  }).fail(function () {
    show_stack_bar_top("error", "Erro", "Não há registros")
  });
}

$('#empresa_cnab').on("change", function (e) {
  var $ID = $('#empresa_cnab').val();
  $.post(CODLOCAL + 'relatorio/getFilial/' + $ID, function (data) {
    $('#filial').html(data);
  });
});

$('#todos_checkbox').click(function (event) {
  if (this.checked) {
    $('.checkbox_baixar').each(function () {
      this.checked = true;
      $('.texto_todos').text('Desmarcar todos');
    });
  } else {
    $('.checkbox_baixar').each(function () {
      this.checked = false;
      $('.texto_todos').text('Marcar todos');
    });
  }

});