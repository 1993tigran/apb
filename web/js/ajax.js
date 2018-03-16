/**
 * Created by Admin on 2/20/2018.
 */

var getBackgrounds = function (backgroundIdes) {

    $.ajax({
        method: "GET",
        url: "/get-backgrounds-ajax",
        data: {backgroundIdes: backgroundIdes},
        success: function (data) {
            if (data) {
                $(".generate-btn").removeAttr('disabled');
                window.generate = false;
                var backgrounds = [];
                $(data).each(function (key, val) {
                    backgrounds.push(val.image);
                });
                var data = {
                    'frontImg': $("#front-img-image").attr('src'),
                    'backImg': $("#back-img-image").attr('src'),
                    'topImg': $("#top-img-image").attr('src'),
                    'bottomImg': $("#bottom-img-image").attr('src'),
                    'leftImg': $("#left-img-image").attr('src'),
                    'rightImg': $("#right-img-image").attr('src'),
                    'width': $("#box_width").val(),
                    'height': $("#box_height").val(),
                    'depth': $("#box_depth").val(),
                    'verticalRot': $("#vertical_rot").val(),
                    'horizontalRot': $("#horizontal_rot").val(),
                    'zommMin': $("#zomm_min").val(),
                    'zomMax': $("#zom_max").val(),
                    'lightx': $("#light_x").val(),
                    'lighty': $("#light_y").val(),
                    'lightz': $("#light_z").val(),
                    'lightIntensity': $("#light_intensity").val(),
                    'environLightIntensity': $("#environ_light_intensity").val(),
                };

                previewProject(data, backgrounds, generate);
            }

        }
    })
};

var deleteBackgImageAjax = function (data) {
    var id = $(data).data('id');
    $.ajax({
        method: "GET",
        url: "/delete-background-image-ajax",
        data: {id: id},
        success: function (respons) {
            if (respons) {
                $(data).parent().remove();
            }
        }
    })
}

var deleteProjectImageAjax = function (data) {
    var id = $(data).data('id');
    var sizeWidth = $(data).data('size-width');
    var sizeHeight = $(data).data('size-height');
    var projectId = $(data).data('project-id');
    $.ajax({
        method: "GET",
        url: "/delete-project-image-ajax",
        data: {id: id, sizeWidth: sizeWidth, sizeHeight: sizeHeight, projectId: projectId},
        success: function (respons) {
            if (respons) {
                $(data).parent().remove();
            }
        }
    })
}

var projectImageGenerate = function () {
    var pathname = window.location.pathname;
    $.ajax({
        method: "GET",
        url: pathname,
        success: function (respons) {
            if (respons) {
                window.generate = true;
                var backgrounds = respons.backgrounds;
                var data = {
                    'frontImg': respons.data.front_img,
                    'backImg': respons.data.back_img,
                    'topImg': respons.data.top_img,
                    'bottomImg': respons.data.bottom_img,
                    'leftImg': respons.data.left_img,
                    'rightImg': respons.data.right_img,
                    'width': respons.data.box_width,
                    'height': respons.data.box_height,
                    'depth': respons.data.box_depth,
                    'verticalRot': respons.data.vertical_rot,
                    'horizontalRot': respons.data.horizontal_rot,
                    'zommMin': respons.data.zomm_min,
                    'zomMax': respons.data.zom_max,
                    'lightx': respons.data.light_x,
                    'lighty': respons.data.light_y,
                    'lightz': respons.data.light_z,
                    'lightIntensity': respons.data.light_intensity,
                    'environLightIntensity': respons.data.environ_light_intensity,
                    'projectMaxSize': respons.projectMaxSize,
                    'pathname': pathname,
                };
                previewProject(data, backgrounds, generate);

            }
        },
    })
}

var queueFlag = 0;
var generateQueueProjects = function (projectsIdes) {

    if (projectsIdes.length > 0 && projectsIdes.length > queueFlag) {

        $("#container").css('background', 'transparent');
        $("#container").html(" ");
        $("#container").addClass('loader');
        $(".time-round-generate").html('0');
        $(".saved-images-count").html('0');
        $(".count-project-to-generate").html(projectsIdes.length);
        $(".count-generate-project").html(queueFlag+1);
        var pathname = window.location.pathname + '/' + projectsIdes[queueFlag];
        $.ajax({
            method: "GET",
            url: pathname,
            success: function (respons) {
                if (respons) {
                    window.generate = true;
                    var backgrounds = respons.backgrounds;
                    var data = {
                        'frontImg': respons.data.front_img,
                        'backImg': respons.data.back_img,
                        'topImg': respons.data.top_img,
                        'bottomImg': respons.data.bottom_img,
                        'leftImg': respons.data.left_img,
                        'rightImg': respons.data.right_img,
                        'width': respons.data.box_width,
                        'height': respons.data.box_height,
                        'depth': respons.data.box_depth,
                        'verticalRot': respons.data.vertical_rot,
                        'horizontalRot': respons.data.horizontal_rot,
                        'zommMin': respons.data.zomm_min,
                        'zomMax': respons.data.zom_max,
                        'lightx': respons.data.light_x,
                        'lighty': respons.data.light_y,
                        'lightz': respons.data.light_z,
                        'lightIntensity': respons.data.light_intensity,
                        'environLightIntensity': respons.data.environ_light_intensity,
                        'projectMaxSize': respons.projectMaxSize,
                        'pathname': pathname,
                        'projectsIdes': projectsIdes,
                    };
                    $("#container").html("");
                    previewProject(data, backgrounds, generate);

                }
            },
        })
    }else {
        window.location.replace("/projects");
    }
    queueFlag++;
}

// var getGenerateImageSize = function (projectId) {
//     $.ajax({
//         method: "GET",
//         url: '/generate-image-size-ajax',
//         data: {id:projectId},
//         success: function(respons){
//             console.log('respons',respons)
//             if(respons){
//                 projectImageGenerate(respons)
//             }
//         },
//     })
// }