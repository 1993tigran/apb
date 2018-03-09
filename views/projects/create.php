<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Projects */

$this->title = 'New Project';
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="projects-create">
    <?= $this->render('_form', [
        'model' => $model,
        'background_categories' => $background_categories,
        'images_size' => $images_size,
    ]) ?>

</div>
