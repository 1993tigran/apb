/**
 * Created by Admin on 2/20/2018.
 */
//
function ImageBoxControl(w, h, d) {

    if (w > 0 && h > 0 && d > 0) {
        $(".images-box-content").hide();
    } else {
        $(".images-box-content").show();
    }
}
//
function getRoundedCanvas(sourceCanvas) {
    var canvas = document.createElement('canvas');
    var context = canvas.getContext('2d');
    var width = sourceCanvas.width;
    var height = sourceCanvas.height;

    canvas.width = width;
    canvas.height = height;
    context.beginPath();
    context.rect(width / 2, height / 2, Math.min(width, height) / 2, 0, 2 * Math.PI);
    context.drawImage(sourceCanvas, 0, 0, width, height);
    isCropped = true;
    return canvas;
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        var id = $(input).data('id');
        var name = $(input).data('name');
        var width = $(input).data('width');
        var height = $(input).data('height');
        var depth = $(input).data('depth');

        if (name == 'front' || name == 'back') {
            var width = width;
            var height = height;
        }
        if (name == 'top' || name == 'bottom') {
            var width = width;
            var height = depth;
        }

        if (name == 'left' || name == 'right') {
            var width = depth;
            var height = height;
        }

        reader.onload = function (e) {
            $('#' + id + '-content').html('<img id="' + id + '"  class="img-thumbnail" src="' + e.target.result + '" alt="Picture">');
            $('#' + id + '-crop').css('display', 'block');
            croppable = false;

            $('#' + id + '').cropper({
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.8,
                restore: false,
                guides: false,
                highlight: false,
                cropBoxMovable: false,
                cropBoxResizable: false,
                aspectRatio: width / height,
                minCropBoxWidth: width,
                minCropBoxHeight: height,
                minContainerHeight: 300,
                ready: function () {
                    croppable = true;
                }

            });
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function crop(data) {

    var id = $(data).data('id');

    var croppedCanvas;
    var roundedCanvas;

    if (!croppable) {
        return;
    }

    // Crop
    croppedCanvas = $('#' + id + '').cropper('getCroppedCanvas');

    // Round
    roundedCanvas = getRoundedCanvas(croppedCanvas);

    $('#' + id + '-crop').css('display', 'none');
    // Show
    $('#' + id + '-content').html('<img id="' + id + '-image" style="width: 100%"; class="img-thumbnail" src="' + roundedCanvas.toDataURL() + '">');
    $('#' + id).val(roundedCanvas.toDataURL());
}


function toDataURL(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.onload = function () {
        var reader = new FileReader();
        reader.onloadend = function () {
            callback(reader.result);
        }
        reader.readAsDataURL(xhr.response);
    };
    xhr.open('GET', url);
    xhr.responseType = 'blob';
    xhr.send();
}

function cropBack(data) {

    var id = $(data).data('id');
    var croppedCanvas;
    var roundedCanvas;

    if (!croppable) {
        return;
    }

    // Crop
    croppedCanvas = $('#' + id + '').cropper('getCroppedCanvas');

    // Round
    roundedCanvas = getRoundedCanvas(croppedCanvas);

    $('.' + id + '-crop').css('display', 'none');
    // Show
    $('.' + id + '-content').html('<img id="' + id + '-image" style="width: 100%"; class="img-thumbnail" src="' + roundedCanvas.toDataURL() + '">');
    $('.' + id).val(roundedCanvas.toDataURL());
}

var backFlug = 0;
function readBackgroundURL(input) {
    if (input.files) {

        var id = $(input).data('id');
        var name = $(input).data('name');
        var width = $(input).data('width');
        var height = $(input).data('height');

        $(input.files).each(function (key, val) {
            var reader = new FileReader();
            reader.readAsDataURL(input.files[key]);
            reader.onload = function (e) {
                $('#content').append('<div  class="col-md-3 back-img-' + key + backFlug + '-col"></div>');
                $('.back-img-' + key + backFlug + '-col').append('<div  class="back-img-' + key + backFlug + '-content"></div>');

                $('.back-img-' + key + backFlug + '-col').append('<button  class="back-img-' + key + backFlug + '-crop" data-id="back-img-' + key + backFlug + '"  type="button" class="crop-button" onclick="cropBack(this)">Crop</button>');

                $('.back-img-' + key + backFlug + '-col').append('<input name="BackgroundImages[image][]" class="back-img-' + key + backFlug + '" type="hidden" value="" />');

                $('.back-img-' + key + backFlug + '-content').append('<img id="back-img-' + key + backFlug + '"  class="img-thumbnail" src="' + e.target.result + '" alt="Picture">');

                $('.back-img-' + key + backFlug + '-crop').css('display', 'block');

                croppable = false;

                $('#back-img-' + key + backFlug + '').cropper({
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 1,
                    restore: false,
                    guides: false,
                    highlight: false,
                    cropBoxMovable: false,
                    cropBoxResizable: false,
                    aspectRatio: width / height,
                    minCropBoxWidth: width,
                    minCropBoxHeight: height,
                    minContainerHeight: 300,
                    ready: function () {
                        croppable = true;
                    }

                });
            };
        })

    }
    backFlug++;
}

var checkBackgroundSize = function () {
    if ($("#backgrounds-height").val() > 0 && $("#backgrounds-width").val() > 0) {
        var width = $("#backgrounds-width").val();
        var height = $("#backgrounds-height").val();
        $("#backImgId").attr('data-width', width);
        $("#backImgId").attr('data-height', height);
        $("#backImgId").removeAttr('disabled');
    } else {
        $("#backImgId").attr('disabled', 'disabled');
    }
};
checkBackgroundSize();


var countSaveImages = function (data,backgrounds) {
    var timeRoundVertical = data.verticalRot * 20 / 360;
    var timeHorizontalRot = data.horizontalRot * 20 / 360;
    var timeRot;
    if (timeHorizontalRot > timeRoundVertical) {
        timeRot = timeHorizontalRot
    } else if (timeHorizontalRot < timeRoundVertical) {
        timeRot = timeRoundVertical
    } else {
        timeRot = timeHorizontalRot;
    }
    var timeZoom = (data.zomMax - data.zommMin) * 60 / 120;

    window.timeRound = Math.round(backgrounds.length * timeRot * timeZoom);
}
