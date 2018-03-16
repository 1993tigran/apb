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

<div id="project-view">
    <?php if (!empty($projects_Ides)):?>
        <a class="go_back"  href="/queue-list">
            < Go back - Projects:(<span class="count-project-to-generate">0</span>/<span class="count-generate-project">0</span>)  Images:(<span class="time-round-generate">0</span>/<span class="saved-images-count">0</span>)
        </a>
    <?php else:?>
        <a class="go_back" data-confirm="Are you sure you want to cancel this process?" href="/projects-edit/<?= $id; ?>">
            < Go back - Images:(<span class="time-round-generate">0</span>/<span class="saved-images-count">0</span>)
        </a>
    <?php endif;?>
    <div id="container" class="loader"></div>
</div>
<script type="text/javascript">
    $(document).ready(function () {

        var projectsIdes = <?=$projects_Ides?>;

        if(projectsIdes){
            generateQueueProjects(projectsIdes);
        }else {
            projectImageGenerate();
        }


        $(".go_back").click(function () {
            window.requestAbort = true;
        })
    })

</script>