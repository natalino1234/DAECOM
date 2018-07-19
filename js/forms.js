$(document).ready(function () {
    $('.inputfile').each(function ()
    {
        var $input = $(this),
                $label = $input.next('label'),
                labelVal = $label.html();

        $input.on('change', function (e)
        {
            var fileName = '';
            if (this.files && this.files.length > 1)
                fileName = (this.getAttribute('data-multiple-caption') || '').replace('{count}', this.files.length);
            else if (e.target.value)
                fileName = e.target.value.split('\\').pop();

            if (fileName)
                $label.html(fileName);
            else
                $label.html(labelVal);
            var img = $(".image-preview");
            if (!img.is(":hidden")) {
                img.fadeOut();
            }
            var reader = new FileReader();
            var file = e.target.files[0];
            reader.onload = function (e) {
                // get loaded data and render thumbnail.
                setTimeout(function () {
                    document.getElementById("image").src = e.target.result;
                }, 1000);

            };
            reader.readAsDataURL(file);
            setTimeout(function () {
                img.fadeIn();
            }, 2000);
        });

        // Firefox bug fix
        $input
                .on('focus', function () {
                    $input.addClass('has-focus');
                })
                .on('blur', function () {
                    $input.removeClass('has-focus');
                });
    });
    $("input").mouseenter(function () {
        var classList = $(this).parent().attr('class').split(/\s+/);
        var tem = false;
        $.each(classList, function (index, item) {
            if (item.indexOf("tooltips") > -1) {
                tem = true;
            }
        });
        if (tem) {
            $(this).parent().children("span").fadeIn();
        }
    }).mouseleave(function () {
        var classList = $(this).parent().attr('class').split(/\s+/);
        var tem = false;
        $.each(classList, function (index, item) {
            if (item.indexOf("tooltips") > -1) {
                tem = true;
            }
        });
        if (tem) {
            $(this).parent().children("span").fadeOut();
        }
    });
});