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
<div class="row">
<div class="col-md-6">
    <h1><?= \yii\helpers\Html::encode($this->title) ?></h1>
</div>
<div align="right" class="col-md-6">
    <a  class="btn" href="/zipping/<?=$project_id?>".>Download zip</a>
</div>
</div>

<div class="row">
<div id="project-images-list">
    <?php foreach ($project_images as $project_image): ?>
        <dic class="col-md-3 project-image-item edit-image-content">
            <img class="project-image-view"
                 src="/uploads/images/<?= $project_id . '/' . $backgrounds_size->width . '_' . $backgrounds_size->height . '/' . $project_image->name ?>"
                 alt="">
            <span class="fa fa-trash" onclick="deleteProjectImageAjax(this)" data-project-id="<?=$project_id;?>" data-id="<?=$project_image->id;?>" data-size-width="<?=$backgrounds_size->width;?>" data-size-height="<?=$backgrounds_size->height;?>"  aria-hidden="true"></span>
        </dic>
    <?php endforeach; ?>

</div>
</div>
<div class="row pagination-content" align="center">
    <?=\yii\widgets\LinkPager::widget(['pagination' => $pages]);?>
</div>
