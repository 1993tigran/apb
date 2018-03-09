<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Backgrounds */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="backgrounds-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model_background_images, 'image[]')->widget(FileInput::classname(), [
        'options' => [
            'multiple' => true,
            'accept' => 'uploads/background-image*'
        ],
        'pluginOptions' => [
            'showUpload' => false
        ],

    ])->label('Background image'); ?>

    <div class="col-md-12">
    <?php if (!empty($background_images)):?>
        <?php foreach ($background_images as $image):?>
            <div class="col-md-3 edit-image-content" >
                <img  style="width: 100%" height="200" src="/uploads/background-image/<?=$image->image?>" alt="">
                <span  class="fa fa-trash" onclick="deleteBackgImageAjax(this)" data-id ="<?=$image->id?>" aria-hidden="true"></span>
            </div>
        <?php endforeach;?>
    <?php endif;?>
    </div>
    <br>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
