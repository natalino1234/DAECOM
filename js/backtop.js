$(document).ready(function () {
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $("#toTop").fadeIn("slow");
        } else {
            $("#toTop").fadeOut("slow");
        }
    });
    $("#toTop i").mouseenter(function () {
        $("#toTop span").fadeIn(300);
    }).mouseleave(function () {
        $("#toTop span").fadeOut(300);
    });
});