<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Backgrounds */

$this->title = 'Update Backgrounds';
$this->params['breadcrumbs'][] = ['label' => 'Backgrounds', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="backgrounds-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'model_background_images' => $model_background_images,
        'background_images' => $background_images,
    ]) ?>

</div>
