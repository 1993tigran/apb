<?php
//use  yii\helpers\Html;
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2/23/2018
 * Time: 11:45 AM
 */

$this->title = 'Project Images Size List';
?>
<?php ?>
<h1><?= \yii\helpers\Html::encode($this->title) ?></h1>
<div id="project-images-list">
    <ul class="list-group col-md-6">
        <?php foreach ($model_images_size as $model_images_size):?>
        <li class="list-group-item">
            <a href="/project-images-list/<?=$project_id.'/'.$model_images_size->imagesSize->id;?>">
            <?=$model_images_size->imagesSize->width.' x '.$model_images_size->imagesSize->height;?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
