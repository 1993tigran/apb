/**
 * Created by Admin on 2/19/2018.
 */
$(document).ready(function () {
    var w = 0;
    var h = 0;
    var d = 0;

    if( $('#box-width input').val() > 0 && $('#box-height input').val() > 0 && $('#box-depth input').val() > 0){
        $(".images-box-content").hide();
    }

    $('#box-width input').blur(function () {
        w = $(this).val();
        $('.images-box input').each(function (key, val) {
            $(val).attr("data-width", w);
        })
        ImageBoxControl(w, h, d)
    });

    $('#box-height input').blur(function () {
        h = $(this).val();
        $('.images-box input').each(function (key, val) {
            $(val).attr("data-height", h);
        })
        ImageBoxControl(w, h, d)
    });

    $('#box-depth input').blur(function () {
        d = $(this).val();
        $('.images-box input').each(function (key, val) {
            $(val).attr("data-depth", d);
        })
        ImageBoxControl(w, h, d)
    });

    $(".generate-btn").attr('disabled','disabled');
    $(".preview").on('click', function () {
        $("#form-project input,textarea,select").blur();
        setTimeout(function () {
            if ($("#form-project").find('.has-error').length <= 0 ){
                var backgroundIdes = $("#select-back").val();
                if (backgroundIdes) {
                    $("#container").html(' ');
                    $("#container").css('background', 'transparent');
                    $("#container").addClass('loader');
                    getBackgrounds(backgroundIdes);
                }
            }
        },1000);
    });

    $("#form-project input,textarea,select").change(function () {
        if ($("#form-project").find('.has-error').length <= 0 ){
            $(".generate-btn").attr('disabled','disabled');
        }
    });

    $("#backgrounds-width").change(function () {
        checkBackgroundSize();
    });
    $("#backgrounds-height").change(function () {
        checkBackgroundSize();
    });

    $(".go_back").on('click',function () {
        $("#container").html(' ');
        $("#container").css('background', 'transparent')
        $("#container").addClass('loader');
    })

    // $(".images-box-content").hide();
})

