$(document).ready(function () {
    $(document).on("mouseenter", ".tooltips-r i,.tooltips-l i,.tooltips-u i,.tooltips-b i", function () {
        $(this).parent().children("span").fadeIn();
    });
    $(document).on("mouseleave", ".tooltips-r,.tooltips-l,.tooltips-u,.tooltips-b", function () {
        $(this).children("span").fadeOut();
    });
});