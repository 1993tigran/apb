<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\ProjectsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="projects-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'front_img') ?>

    <?= $form->field($model, 'back_img') ?>

    <?= $form->field($model, 'top_img') ?>

    <?php // echo $form->field($model, 'bottom_img') ?>

    <?php // echo $form->field($model, 'left_img') ?>

    <?php // echo $form->field($model, 'right_img') ?>

    <?php // echo $form->field($model, 'box_width') ?>

    <?php // echo $form->field($model, 'box_height') ?>

    <?php // echo $form->field($model, 'box_depth') ?>

    <?php // echo $form->field($model, 'vertical_rot') ?>

    <?php // echo $form->field($model, 'horizontal_rot') ?>

    <?php // echo $form->field($model, 'zomm_min') ?>

    <?php // echo $form->field($model, 'zom_max') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
