$(document).ready(function () {
    $("#cep").keyup(function () {
        searchCEP();
    });
    $("#uf").keyup(function () {
        searchLog();
    });
    $("#cidade").keyup(function () {
        searchLog();
    });
    $("#logradouro").keyup(function () {
        searchLog();
    });
    $("#matricula").blur(function () {
        $.ajax({type: "POST", url: "/functions/validamatricula.php", data: {"matricula": $("#matricula").val() + ""}})
                .done(function (data) {
                    if (data === "1") {
                        $("#matricula").parent().children("i").css({'color': 'black'});
                    } else {
                        $("#matricula").parent().children("i").css({'color': '#FF0000'});
                        alertUser("A matr&iacute;cula informada n&atilde;o &eacute; v&aacute;lida ou já está sendo usada, tente novamente.");
                        $("#matricula").focus();
                    }
                });
    });
    $("#senha").blur(function () {
        if ($(this).val().length < 8) {
            $("#senha").parent().children("i").css({'color': 'red'});
            alertUser("A Senha não pode ter menos que 8 caracteres.");
        } else {
            $("#senha").parent().children("i").css({'color': 'black'});
        }
    });
    $("#confsenha").blur(function () {
        if ($(this).val() !== $("#senha").val()) {
            $("#confsenha").parent().children("i").css({'color': 'red'});
            alertUser("A Senha e Confirmação não são iguais.");
        } else {
            $("#confsenha").parent().children("i").css({'color': 'black'});

        }
    });
    $("#cpf").blur(function () {
        var cpf = $("#cpf").val() + "";
        if (cpf.length === 11) {
            cpf = cpf.substring(0, 3) + "." + cpf.substring(3, 6) + "." + cpf.substring(6, 9) + "-" + cpf.substring(9, 11);
            $("#cpf").val(cpf);
            $("#cpf").parent().children("i").css({'color': 'black'});
        } else if (cpf.length === 14) {
            $("#cpf").parent().children("i").css({'color': 'black'});
        } else {
            $("#cpf").parent().children("i").css({'color': 'red'});
        }
    });
    $("#cpf").keypress(function () {
        var cpf = $("#cpf").val() + "";
        if (cpf.length === 3) {
            cpf = cpf + ".";
            $("#cpf").val(cpf);
        } else
        if (cpf.length === 7) {
            cpf = cpf + ".";
            $("#cpf").val(cpf);
        } else
        if (cpf.length === 11) {
            cpf = cpf + "-";
            $("#cpf").val(cpf);
        }
        if (cpf.length >= 14) {
            cpf = cpf.substring(0, 14);
            $("#cpf").val(cpf);
            $("#cpf").parent().children("i").css({'color': 'black'});
        }
    });
    $("#telefone").blur(function () {
        var regex = /^\(\d{2}\)\d{4,5}-\d{4}$/;
        var telefone = $("#telefone").val();
        if (regex.test(telefone)) {
            $("#telefone").parent().children("i").css({'color': 'black'});
        } else {
            $("#telefone").parent().children("i").css({'color': 'red'});
            $("#telefone").parent(".input").val("");
        }
    });
    $("#telefone").keypress(function (e) {
        var telefone = $("#telefone").val() + "";
        if (telefone.length === 1 && e.keyCode !== 8) {
            if ($.isNumeric(telefone)) {
                telefone = "(" + telefone;
                $("#telefone").val(telefone);
            }
        } else
        if (telefone.length === 3 && e.keyCode !== 8) {
            telefone = telefone + ")";
            $("#telefone").val(telefone);
        } else
        if (telefone.length === 8 && e.keyCode !== 8) {
            telefone = telefone + "-";
            $("#telefone").val(telefone);
        } else
        if (telefone.length === 13 && e.keyCode !== 8) {
            telefone = telefone.replace("(", "");
            telefone = telefone.replace(")", "");
            telefone = telefone.replace("-", "");
            telefone = "(" + telefone.substring(0, 2) + ")" + telefone.substring(2, 7) + "-" + telefone.substring(7, 11);
            $("#telefone").val(telefone);
            $("#telefone").parent().children("i").css({'color': 'black'});
        } else

        if (telefone.length > 13) {
            telefone = telefone.substring(0, 13);
            $("#telefone").val(telefone);
            $("#telefone").parent().children("i").css({'color': 'black'});
        }
    });
    $("#telefone").keyup(function (e) {
        var telefone = $("#telefone").val() + "";
        if (telefone.length === 13 && e.keyCode === 8) {
            telefone = telefone.replace("(", "");
            telefone = telefone.replace(")", "");
            telefone = telefone.replace("-", "");
            telefone = "(" + telefone.substring(0, 2) + ")" + telefone.substring(2, 6) + "-" + telefone.substring(6, 10);
            $("#telefone").val(telefone);
            $("#telefone").parent().children("i").css({'color': 'black'});
        }
    });
    $("#celular").blur(function () {
        var regex = /^\(\d{2}\)\d{4,5}-\d{4}$/;
        var celular = $("#celular").val();
        if (regex.test(celular)) {
            $("#celular").parent().children("i").css({'color': 'black'});
        } else {
            $("#celular").parent().children("i").css({'color': 'red'});
        }
    });
    $("#celular").keypress(function (e) {
        var celular = $("#celular").val() + "";
        if (celular.length === 1 && e.keyCode !== 8) {
            if ($.isNumeric(celular)) {
                celular = "(" + celular;
                $("#celular").val(celular);
            }
        } else
        if (celular.length === 3 && e.keyCode !== 8) {
            celular = celular + ")";
            $("#celular").val(celular);
        } else
        if (celular.length === 8 && e.keyCode !== 8) {
            celular = celular + "-";
            $("#celular").val(celular);
        } else
        if (celular.length === 13) {
            celular = celular.replace("(", "");
            celular = celular.replace(")", "");
            celular = celular.replace("-", "");
            celular = "(" + celular.substring(0, 2) + ")" + celular.substring(2, 6) + "-" + celular.substring(6, 10);
            $("#celular").val(celular);
            $("#celular").parent().children("i").css({'color': 'black'});
        }
        if (celular.length > 12) {
            celular = celular.replace("(", "");
            celular = celular.replace(")", "");
            celular = celular.replace("-", "");
            celular = "(" + celular.substring(0, 2) + ")" + celular.substring(2, 7) + "-" + celular.substring(7, 11);
            $("#celular").val(celular);
            $("#celular").parent().children("i").css({'color': 'black'});
        }
        if (celular.length > 13) {
            celular = celular.substring(0, 13);
            $("#celular").val(celular);
            $("#celular").parent().children("i").css({'color': 'black'});
        }
    });
    $("#celular").keyup(function (e) {
        var telefone = $("#celular").val() + "";
        if (telefone.length === 13 && e.keyCode === 8) {
            telefone = telefone.replace("(", "");
            telefone = telefone.replace(")", "");
            telefone = telefone.replace("-", "");
            telefone = "(" + telefone.substring(0, 2) + ")" + telefone.substring(2, 6) + "-" + telefone.substring(6, 10);
            $("#celular").val(telefone);
            $("#celular").parent().children("i").css({'color': 'black'});
        }
    });
});