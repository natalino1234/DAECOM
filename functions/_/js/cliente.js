$(document).ready(function() {
    $('.CPF').mask('000.000.000-00', { reverse: true });
    $('.money').mask("#####.##0,00", { reverse: true });
    $("#formCrud").validate({
        rules: {
            nmCliente: {
                required: true,
                minlength: 5
            },
            cpfCliente: {
                required: true,
                maxLength: 11
            },
            rgCliente: {
                required: true
            },
            emailCliente: {
                required: true,
                email: true
            },
            senhaCliente: {
                required: true
            }
        },
        messages: {
            nmCliente: {
                required: "Você deve preencher o nome",
                minlength: "O nome deve ter mais que 5 caracteres"
            },
            cpfCliente: {
                required: "Você deve preencher o CPF"
            },
            rgCliente: {
                required: true,
            },
            emailCliente: {
                required: "Preencha seu e-mail de contato",
                email: "Digite um e-mail válido"
            },
            senhaCliente: {
                required: true
            }
        }
    });

});