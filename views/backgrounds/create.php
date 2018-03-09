<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Backgrounds */

$this->title = 'Create Backgrounds';
$this->params['breadcrumbs'][] = ['label' => 'Backgrounds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="backgrounds-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'model_background_images' => $model_background_images,
        'background_images' => $background_images,
    ]) ?>

</div>
