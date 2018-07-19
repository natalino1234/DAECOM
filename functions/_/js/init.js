(function ($) {
    $(function () {

        $('.carousel').carousel({ dist: 150 });
        $('.button-collapse').sideNav();
        $('.modal').modal();
        Materialize.updateTextFields();

    }); // end of document ready
})(jQuery); // end of jQuery name space

$(document).ready(function () {
    $('select').material_select();
    $('.tooltipped').tooltip({ delay: 50 });
    $('.datepicker').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 15,
        today: 'Hoje',
        clear: 'Limpar',
        close: 'Ok',
        format: 'dd/mm/yyyy',
        closeOnSelect: false // Close upon selecting a date,
    });
    $('#btnExc,#btnAlterar,#btnexc, #btnalt').click(function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        $("#modal1").modal('open');
    });
    $('#btnSim').click(function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        $('#formCrud').submit();
    });
    $('#btnSalvar').click(function(e){
        $('#formCrud').submit();
    });
});


