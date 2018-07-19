function alertUser(mensagem) {
    tempo = mensagem.split(" ").length * 1000;
    $("#alert .mensagem").html(mensagem);
    $("#alert").fadeIn(500);
    setTimeout(function () {
        $("#alert").fadeOut(500);
    }, tempo);
}
function executarAJAX(element, url, dados, success, error, redirect,funct) {
    $.ajax({type: "POST", url: url, data: dados})
            .done(function (data) {
                var r = JSON.parse(data);
                if (r["houveErro"] === 0) {
                    alertUser(success);
                    if (redirect !== undefined) {
                        location.href = redirect;
                    }
                    if(funct !== undefined){
                        funct(element);
                    }
                } else {
                    alertUser(r["erro"]);
                }

            });
}
$(document).ready(function () {
    $("#alert .fa-times-circle").click(function () {
        $("#alert").fadeOut(500);
    });
});