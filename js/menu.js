$(document).ready(function () {
    $(location).attr('href');
    //pure javascript
    var pathname = window.location.pathname;
    var itensMenu = $(".sidebar-nav a");
    var element = undefined;
    for (var i = 0; i < itensMenu.length; i++) {
        if (itensMenu[i].pathname !== "") {
            if (itensMenu[i].pathname === pathname) {
                element = itensMenu[i].pathname;
                break;
            }
        }
    }
    // to show it in an alert window
    $(".sidebar-nav a[href=\"" + element + "\"]").css({background: "#ca7900"});
    var list = $(".sidebar-nav a[href=\"" + element + "\"]").parent().parent();
    if (list !== undefined) {
        if (list.is(":hidden")) {
            list.parent().children("a").css({background: '#31536b'});
            list.animate({height: 'show'});
        }
    }
    $(".sidebar-nav li").click(function () {
        var ul = $(this).children("ul");
        if (ul !== undefined) {
            if (ul.is(":hidden")) {
                $(this).css({background: '#31536b'});
                ul.animate({height: 'show'});
            } else {
                $(this).css({background: ''});
                ul.animate({height: 'hide'});
            }
        }
    });
    $(".ancora").click(function (event) {
        event.preventDefault();
        $('html,body').animate({scrollTop: $(this.hash).offset().top}, 800);
    });
});