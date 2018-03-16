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
    <ul class="list-group col-md-12">
        <?php foreach ($images_sizes as $images_size): ?>
            <a href="/project-images-list/<?= $project_id . '/' . $images_size->background_id; ?>">
                <li a class="list-group-item">
                    <?= $images_size->background->width . ' x ' . $images_size->background->height; ?>
                </li>
            </a>
        <?php endforeach; ?>
    </ul>
</div>
