<?php
//use  yii\helpers\Html;
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2/23/2018
 * Time: 11:45 AM
 */

$this->title = 'Project Images List';
?>
<?php ?>
<h1><?= \yii\helpers\Html::encode($this->title) ?></h1>
<div id="project-images-list">
    <?php foreach ($project_images as $project_image): ?>
        <dic class="col-md-4 project-image-item">
            <img class="project-image-view"
                 src="/uploads/images/<?= $project_id . '/' . $images_size->width . '_' . $images_size->height . '/' . $project_image->name ?>"
                 alt="">
        </dic>
    <?php endforeach; ?>
</div>
