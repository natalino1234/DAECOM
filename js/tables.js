$(document).ready(function () {
    $(document).on("click", ".remLine", function () {
        var tr = $(this).parent().parent();
        tr.remove();
    });
    $(".addLine").click(function () {
        var tr = $(this).parent().parent();
        var linha = "<tr><td>" +
                "<div class='input'>" +
                "<i class='fa fa-calendar'></i>" +
                "<input type='date' name='dia[]' id='domhi' required>" +
                "</div>" +
                "<td>" +
                "<div class='input'>" +
                "<i class='fa fa-clock-o'></i>" +
                "<input type='time' name='hi[]' id='domhf' required>" +
                "</div>" +
                "<td>" +
                "<div class='input'>" +
                "<i class='fa fa-clock-o'></i>" +
                "<input type='time' name='hf[]' id='domhf' required>" +
                "</div>" +
                "<td>" +
                "<div class='input submit remLine tooltips-r'>" +
                "<i class='fa fa-minus'></i>" +
                "<button></button>" +
                "<span>Remover Horário</span>" +
                "</div>";
        $(linha).insertAfter(tr);
    });
});