$(document).ready(function() {
    $('#btAdd').click(function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var object = $(this);
        var exercicioId = $('#exercicio').val();
        var repeticoes = $('#repeticoes').val() * 1;
        var qtd = $('#quantidade').val();
        $.post('auxtreino.php', { 'exercicio': exercicioId, 'qtd': qtd, 'type': 'add', 'repeticoes': repeticoes })
            .done(function(response) {
                $('#subData').html(response);

            })
            .fail(function() {
                alert('Something Went Wrog ....');
            });
        return false;
    });
    
});