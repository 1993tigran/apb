<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2/23/2018
 * Time: 11:45 AM
 */
$this->registerJsFile('@web/js/threejs/build/three.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('@web/js/threejs/lib/OrbitControls.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('@web/js/threejs/customThreeJs.js', ['position' => \yii\web\View::POS_END]);

$this->title = 'Project Image Generate';

?>
<?php ?>

<div id="project-view">
    <a class="go_back" data-confirm="Are you sure you want to cancel this process?" href="/projects-edit/<?= $id; ?>">
        < Go back - Images:(<span class="time-round-generate">0</span>/<span class="saved-images-count">0</span>)
    </a>
    <div id="container" class="loader"></div>
    <div class="containers">
        <?php foreach ($projects_images_size as $key => $project_image_size): ?>
            <div id="container_<?= $key; ?>" data-name="renderer_<?= $key; ?>" data-width="<?= $project_image_size['width'];  ?>"
                 data-height="<?= $project_image_size['height']; ?>" style="display: none"></div>
        <?php endforeach;; ?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var projectId = <?=$id;?>;
//       var imagesSizeMax = {
//           'maxImageWidth':<?//=$projectsImagesSizeMax['width']?>//,
//           'maxImageHeight':<?//=$projectsImagesSizeMax['height']?>
//        }
//        getGenerateImageSize(projectId);
//        $(window).bind("load", function() {
            projectImageGenerate()
//        });
    })

</script>