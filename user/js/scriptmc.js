$(document).ready(function () {
    $("a.edit").click(function () {
        var td = $(this).parent().parent().children("td");
        var input = td.children("input");
        if (td.length != 0) {
            if (input.attr("disabled")) {
                if (input.attr("id") === "dataNasc") {
                    input.attr("type", "date");
                }
                if (input.attr("id") === "uf") {
                    $("#cidade").attr("disabled", false);
                    $("#cidade").css({
                        "border": "1px solid #717171",
                        "background": "#FFF"
                    });
                    $("#logradouro").attr("disabled", false);
                    $("#logradouro").css({
                        "border": "1px solid #717171",
                        "background": "#FFF"
                    });
                    $("#help").parent().parent().removeAttr("hidden");
                    $(this).css({
                        "color": "green",
                        "display": "none",
                        "border-bottom": "1px dotted green"
                    });
                    $("#cep").parent().parent().children("td").children(".edit").css({"display": "none"});
                }
                input.attr("disabled", false);
                input.css({
                    "border": "1px solid #717171",
                    "background": "#FFF"
                });
                $(this).css({
                    "color": "green",
                    "border-bottom": "1px dotted green"
                });
                var cancel = td.children(".cancel");
                cancel.css({"display": "inline"});
            } else {
                if (input.attr("id") === "dataNasc") {
                    input.attr("type", "text");
                }
                if (input.attr("id") === "uf") {
                    $("#cidade").attr("disabled", true);
                    $("#cidade").css({
                        "border": "none",
                        "background": "none"
                    });
                    $("#logradouro").attr("disabled", true);
                    $("#logradouro").css({
                        "border": "none",
                        "background": "none"
                    });
                    $("#cep").parent().parent().children("td").children(".edit").css({"display": "inline"});
                }
                input.attr("disabled", true);
                input.css({
                    "border": "none",
                    "background": "none"
                });
                var cancel = td.children(".cancel");
                cancel.css({"display": "none"});
                alteraReg(input[0], $(this));
                if (input.attr("id") === "uf") {
                    alteraReg($("#cidade")[0]);
                    alteraReg($("#logradouro")[0]);
                    alteraReg($("#uf")[0]);
                    alteraReg($("#bairro")[0]);
                    alteraReg($("#cep")[0]);
                }
            }
        }
    });
    $("input[type=text], input[type=email]").keyup(function () {
        $(this).attr("size", "" + ($(this).val()).length);
    });
    $("a.cancel").click(function () {
        var td = $(this).parent().parent().children("td");
        var input1 = td.children("input")[0];
        var input2 = td.children("input")[1];
        if (input1.id === "edd") {
            input1.type = "text";
        }
        if (input1.id === "uf") {
            $("#cidade").parent().children("input").val();
            $("#cidade").attr("disabled", true);
            $("#cidade").css({
                "border": "none",
                "background": "none"
            });
            $("#cidade").val($("#cidade").parent().children("input")[1].value);
            $("#logradouro").attr("disabled", true);
            $("#logradouro").css({
                "border": "none",
                "background": "none"
            });
            $("#logradouro").val($("#logradouro").parent().children("input")[1].value);
            $("#help").parent().parent().css({"display": "none"});
            $("#help").html("Ao alterar qualquer um dos itens desbloqueados, será feito uma busca automática pelo CEP.");
            $("#cep").parent().parent().children("td").children(".edit").css({"display": "inline"});
        }
        input1.disabled = true;
        input1.style.border = "none";
        input1.style.background = "none";
        input1.size = input2.size;
        input1.value = input2.value;
        var cancel = td.children(".cancel");
        cancel.css({"display": "none"});
        td.children("a.edit").css({
            "color": "#B5684D",
            "border-bottom": "1px dotted #B5684D",
            "display": "inline"
        });
    });

    $("#cep").keyup(function () {
        searchCEP();
    });

    $("#celular").blur(function () {
        var regex = /^\(\d{2}\)\d{4,5}-\d{4}$/;
        var celular = $("#celular").val();
        if (regex.test(celular)) {
            $("#celular").css({'background': '#fff'});
        } else {
            $("#celular").css({'background': '#ffb0b0'});
        }
    });

    $("a.edits").click(function () {
        $.ajax({type: "POST", url: "/functions/alterarcadastro.php", data: {"colAlt": "senha"}})
                .done(function (data) {
                    if (data === "1") {
                        alert("Foi enviado um email para alteração da senha.");
                    } else {
                        alert("Houve um erro na solicitação de alteração, tente novamente.");
                    }
                });
    });
});
function alteraReg(input, a) {
    var value = input.value;
    var name = input.id;
    $.ajax({type: "POST", url: "/functions/alterarcadastro.php", data: {"colAlt": "alu_" + name, "valueAlt": value + ""}})
            .done(function (data) {
                var td = a.parent().parent().children("td");
                if (data === "1") {
                    alert("Alteração realizada com sucesso.");
                    a.css({
                        "color": "#B5684D",
                        "border-bottom": "1px dotted #B5684D"
                    });
                    var cancel = td.children(".cancel");
                    cancel.css({"display": "none"});
                } else {
                    alert("Houve um erro na solicitação de alteração, tente novamente.");
                }
            });
}
function alteraReg(input) {
    var value = input.value;
    var name = input.id;
    $.ajax({type: "POST", url: "/functions/alterarcadastro.php", data: {"colAlt": "alu_" + name, "valueAlt": value + ""}})
            .done(function (data) {
                alert(data);
            });
}
function procuracep(cep) {
    $("#cep").val(cep);
    searchCEP();
    $("#help").css({"display": "none"});
    $("#help").parent().parent().css({"display": "none"});
    $("#help").html("Ao alterar qualquer um dos itens desbloqueados, será feito uma busca automática pelo CEP.");
}