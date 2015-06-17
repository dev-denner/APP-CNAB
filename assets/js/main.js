/* Author:
 denner.fernandes - denners777@hotmail.com
 */

var LOCAL = 'http://192.168.1.54/cnab/';

$(document).ready(function() {

  $('.tooltips').tooltip();
  $('.datatable').dataTable();
  
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
  

  $('#empresa_cnab').on("change", function(e) {
    var $ID = $('#empresa_cnab').val();
    $.post(LOCAL + 'filial/selectFilial/' + $ID, function(data) {
      $('#filial').html(data);
    });
  });
});


function deletar($action, $direct) {
  bootbox.confirm("Deseja realmente deletar este registro?", function() {
    $.post($action, function(data) {
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