function searchCEP() {
    if ($("#cep").val().length === 8) {
        var cep = $("#cep").val().replace(/\D/g, '');

        //Express�o regular para validar o CEP.
        var validacep = /^[0-9]{8}$/;

        //Valida o formato do CEP.
        if (validacep.test(cep)) {

            //Preenche os campos com "..." enquanto consulta webservice.
            $("#logradouro").val("...");
            $("#bairro").val("...");
            $("#cidade").val("...");
            $("#uf").val("...");

            //Consulta o webservice viacep.com.br/
            $.getJSON("//viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

                if (!("erro" in dados)) {
                    //Atualiza os campos com os valores da consulta.
                    $("#logradouro").val(dados.logradouro);
                    $("#bairro").val(dados.bairro);
                    $("#cidade").val(dados.localidade);
                    $("#uf").val(dados.uf);
                    $("#help").css({
                        "display": "none",
                        "color": "black"

                    });
                    $("#cep").parent(".input").css({
                        "color": "black"
                    });
                    $("#help").css({
                        "background": "",
                        "color": "red"
                    });
                } //end if.
                else {
                    //CEP pesquisado n�o foi encontrado.
                    limpa_formulario_cep();
                    $("#help").css({
                        "display": "block",
                        "color": "red"
                    });
                    $("#help").html("CEP n&atilde;o encontrado.");
                    $("#cep").parent(".input").css({
                        "color": "red"

                    });
                }
            });
        } else {
            //cep � inv�lido.
            limpa_formulario_cep();
            $("#help").css({
                "display": "block",
                "color": "red"

            });
            $("#help").html("Formato de CEP inv�lido.");
            $("#cep").parent(".input").css({
                "color": "red"

            });
        }
    } else {
        limpa_formulario_cep();
    }
}

function limpa_formulario_cep() {
    $("#logradouro").val("");
    $("#bairro").val("");
    $("#cidade").val("");
    $("#uf").val("");
}

function replaceSpecialChars(str) {
    str = str.replace(/[������]/, "A");
    str = str.replace(/[������]/, "a");
    str = str.replace(/[����]/, "o");
    str = str.replace(/[����]/, "O");
    str = str.replace(/[���]/, "u");
    str = str.replace(/[���]/, "u");
    str = str.replace(/[����]/, "E");
    str = str.replace(/[���]/, "e");
    str = str.replace(/[�]/, "C");
    str = str.replace(/[�]/, "c");
    return str;
}

function searchLog() {
    var err = false;
    var msgc = "";
    var msge = "";
    if ($("#logradouro").val().length >= 8) {
        var count = 0;
        if ($("#cidade").val() === "") {
            err = true;
            count++;
            msgc += "CIDADE";
            $("#cidade").parent(".input").css({
                "color": "red"
            });
        } else {
            $("#cidade").parent(".input").css({
                "color": "black"
            });
        }
        if ($("#uf").val() === "") {
            err = true;
            count++;
            msge += "SIGLA DO ESTADO";
            $("#uf").parent(".input").css({
                "color": "red"

            });
        } else {
            $("#uf").parent(".input").css({
                "color": "black"
            });
        }
        if (err) {
            var msg = "";
            if (count > 1) {
                msg = "Preencha os campos de " + msgc + " e de " + msge;
            } else {
                msg = "Preencha o campo de " + msgc + msge;
            }
            $("#help").html(msg + ".");
            $("#help").css({
                "background": "#ffb0b0",
                "color": "red"
            });
            $("#help").fadeIn();
        } else {
            $("#help").css({
                "color": "black"
            });
            var cidade = replaceSpecialChars($("#cidade").val());
            var estado = $("#uf").val();
            var logradouro = $("#logradouro").val();
            $.getJSON("https://viacep.com.br/ws/" + estado + "/" + cidade + "/" + logradouro + "/json/", function (dados) {
                if (!("erro" in dados)) {
                    //Atualiza os campos com os valores da consulta.
                    var dado = "<div class='title'>Escolha uma op&ccedil;&atilde;o para atualizar o formul&aacute;rio</div><div id='scroll'>";
                    var size = 0;
                    while (size < dados.length) {
                        var sel = dados[size];
                        dado += "<div class='option' onclick=\"procuracep(" + sel.cep.replace("-", "") + ")\"><div id='opcep'>" + sel.cep + "</div> - <div id='oplog'>" + sel.logradouro + "</div>"
                                + " - <div id='opbai'>" + sel.bairro + "</div> - <div id='opcid'>" + sel.localidade + "</div> - <div id='opuf'>" + sel.uf + "</div>"
                                + "</div>";
                        size++;
                    }
                    dado += "</div>";
                    $("#help").html(dado);
                    $("#help").css({
                        "background": ""
                    });
                    $("#help").fadeIn();
                } //end if.
                else {
                    $("#help").css({
                        "color": "red"
                    });
                    $("#help").html("Verifique se a Cidade e Estado est�o corretos, e se o logradouro est� com o que deseja correto.");
                    $("#cep").parent(".input").css({
                        "color": "red"
                    });
                    $("#help").fadeIn();
                }
            });
        }
    } else {
        $("#help").fadeOut();
    }
}
function procuracep(cep) {
    $("#cep").val(cep);
    searchCEP();
    $("#help").fadeOut();
}