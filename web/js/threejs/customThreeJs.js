/**
 * Created by Admin on 2/20/2018.
 */

var container, stats;
var group;
var camera, controls, scene, renderer;
window.requestAbort = false;
var strDownloadMime = "image/octet-stream";
var mmInPx = 3.779528;
var previewCount = 0;
var previewProject = function (data, backgrounds, generate) {

    // 1 mm = 3.779528 px; 1 px = 0.264583 mm
    var threeJsTimeOut = setTimeout(function () {
        $("#container").removeClass('loader');
        $("#container").css('background-color', '#74d0db');

        window.camera_pox = data.zomMax;
        window.bac = backgrounds;
        var heightSize = 0;
        window.index = 0;
        var texture;

        countSaveImages(data,backgrounds);

        $(".time-round").html('(' + timeRound + ' IMAGES)');
        $(".time-round-generate").html(timeRound);

        function init() {
            if (generate) {
                var widthDiv = data.projectMaxSize['width'];
                var heightDiv = data.projectMaxSize['height'];
            } else {
                var widthDiv = $('#project-view').innerWidth();
                var heightDiv = window.innerHeight - heightSize;
            }

            camera = new THREE.PerspectiveCamera(50, widthDiv / heightDiv, 0.1, 1000);

            camera.position.set(0, 0, window.camera_pox);
            // camera.position.set( current_object.e_left, current_object.e_center, current_object.e_right );

            controls = new THREE.OrbitControls(camera);

            controls.addEventListener('change', render);

            scene = new THREE.Scene();

            if (!generate) {
                scene.background = texture;
            }

            geometry = new THREE.BoxGeometry(data.width, data.height, data.depth); //7, 10, 1.2, 4, 4, 1  20, 15, 5

            var map_1 = THREE.ImageUtils.loadTexture(data.rightImg);
            map_1.anisotropy = 16;
            material1 = new THREE.MeshPhongMaterial({map: map_1});
            var map_2 = THREE.ImageUtils.loadTexture(data.backImg);
            map_2.anisotropy = 16;
            material2 = new THREE.MeshPhongMaterial({map: map_2});

            var map_3 = THREE.ImageUtils.loadTexture(data.leftImg);
            map_3.anisotropy = 16;
            material3 = new THREE.MeshPhongMaterial({map: map_3});

            var map_4 = THREE.ImageUtils.loadTexture(data.frontImg);
            map_4.anisotropy = 16;
            material4 = new THREE.MeshPhongMaterial({map: map_4}); // main visible

            var map_5 = THREE.ImageUtils.loadTexture(data.topImg);
            map_5.anisotropy = 16;
            material5 = new THREE.MeshPhongMaterial({map: map_5}); // top bottom

            var map_6 = THREE.ImageUtils.loadTexture(data.bottomImg);
            map_6.anisotropy = 16;
            material6 = new THREE.MeshPhongMaterial({map: map_6}); // top bottom

            materials = [
                material1,
                material3,
                material5,
                material6,
                material4,
                material2
            ];

            meshFaceMaterial = new THREE.MeshFaceMaterial(materials);

            mesh = new THREE.Mesh(geometry, meshFaceMaterial);
            mesh.updateMatrix();
            mesh.position.z = 0;
            scene.add(mesh);
            // group.add(mesh);


            var lightIntensity = '.' + data.lightIntensity;
            var environLightIntensity = '.' + data.environLightIntensity;
            light = new THREE.PointLight(0xffffff, lightIntensity);
            // light.position.set(50, 60, 100);
            //
            // scene.add(light);

            window.lightx = data.lightx;
            window.lighty = data.lighty;
            window.lightz = data.lightz;
            window.x = 0;
            window.y = 0;
            if (previewCount == 0) {

                var lightInterval = setInterval(function () {
                    light.position.set(window.x, window.y, window.lightz);
                    window.x++;
                    window.y++;
                    if (x == window.lightx) {
                        x = -window.lightx;
                    }
                    if (y == window.lighty) {
                        y = -window.lighty;
                    }
                    scene.add(light);
                }, 1000);
            }
            light_b = new THREE.AmbientLight(0xbbbbbb, environLightIntensity);
            scene.add(light_b);

            // renderer

            renderer = new THREE.WebGLRenderer({antialias: true, alpha: true, preserveDrawingBuffer: true});
            renderer.setClearColor(0x000000, 0);
            renderer.setSize(widthDiv, heightDiv);

            container = document.getElementById('container');
            container.appendChild(renderer.domElement);
            if (!generate) {
                window.addEventListener('resize', onWindowResize, false);
            }


            if (generate) {
                var pathArray = data.pathname.split('/')
                var projectId = pathArray[pathArray.length - 1];

                if (data.projectsIdes) {
                    var projectsIdes = data.projectsIdes;
                }
                window.saveImagesCount = 0;
                var imageSaveInterval = setInterval(function () {
                    if (!window.requestAbort) {

                        var strMime = "image/png";
                        var imgData = renderer.domElement.toDataURL(strMime);
                        var data = {
                            'projectId': projectId,
                            'imgData': imgData,
                        }

                        if (saveImagesCount <= timeRound) {
                            saveImagesCount = saveImagesCount + backgrounds.length;
                            if (saveImagesCount >= timeRound){
                                $(".saved-images-count").html(timeRound);
                            }else {
                                $(".saved-images-count").html(saveImagesCount);
                            }
                            $.ajax({
                                type: "POST",
                                url: '/save-images-ajax',
                                async: true,
                                cache: true,
                                data: data,
                                success: function (res) {

                                }
                            });
                        } else {
                            if (projectsIdes && projectsIdes.length > 0) {
                                clearInterval(imageSaveInterval);
                                generateQueueProjects(projectsIdes);
                            }else {
                                window.location.replace("/projects");
                            }
                        }
                    }
                }, 1000);
            }
        }


        window.flag = true;

        window.zommMin = data.zommMin;
        window.zomMax = data.zomMax;

        texture = new THREE.Color(0x74d0db);


        if (previewCount == 0) {
            if (generate == true) {
                // setInterval(function () {
                //     texture = new THREE.TextureLoader().load(window.bac[window.index])
                //     if (window.index == window.bac.length - 1) {
                //         window.index = 0;
                //     } else {
                //         window.index++;
                //     }
                //     scene.background = texture;
                //
                // }, 4000);
            }


            // if (generate == true) {
            var zoomInterval = setInterval(function () {
                camera.position.set(0, 0, window.camera_pox);
                window.camera_pox--;
                if (window.camera_pox == window.zommMin) {
                    window.camera_pox = window.zomMax;
                }
            }, 500);
            // }
        }
        window.verticalRot = data.verticalRot * 2 * Math.PI / 360;
        window.horizontalRot = data.horizontalRot * 2 * Math.PI / 360;
        window.verticalFlag = false;
        window.horizontalFlag = false;

        function animate() {

            requestAnimationFrame(animate);
            // mesh.rotation.z += 180 / Math.PI * 0.0002;
            // if (generate == true){
            if (verticalFlag) {
                mesh.rotation.x -= 180 / Math.PI * 0.0001;
                if (mesh.rotation.x < 0) {
                    window.verticalFlag = false;
                }
            }
            if (!verticalFlag) {
                mesh.rotation.x += 180 / Math.PI * 0.0001;
                if (mesh.rotation.x > verticalRot) {
                    window.verticalFlag = true;
                }
            }

            if (horizontalFlag) {
                mesh.rotation.y -= 180 / Math.PI * 0.0001;
                if (mesh.rotation.y < 0) {
                    window.horizontalFlag = false;
                }
            }
            if (!horizontalFlag) {
                mesh.rotation.y += 180 / Math.PI * 0.0001;
                if (mesh.rotation.y > horizontalRot) {
                    window.horizontalFlag = true;
                }
            }
            // }

            controls.update();
            render();
        }

        function onWindowResize() {
            var widthDiv = $('#project-view').innerWidth();
            var heightDiv = window.innerHeight - heightSize;
            camera.aspect = widthDiv / heightDiv;
            camera.updateProjectionMatrix();
            renderer.setSize(widthDiv, heightDiv);
            render();
        }

        function render() {
            renderer.render(scene, camera);
        }

        init();
        if (previewCount == 0) {
            animate();
        }
        previewCount++;
    }, 3000)
};