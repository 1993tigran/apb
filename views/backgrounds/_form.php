<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Backgrounds */
/* @var $form yii\widgets\ActiveForm */
$this->registerCssFile('@web/css/cropper.css',['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile('@web/js/cropper.js',['position' => \yii\web\View::POS_HEAD]);
?>

<div class="backgrounds-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'width')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'height')->textInput(['maxlength' => true]) ?>

    <div class="col-md-12">
        <label for="Front Img">Background image</label>
        <div class="file btn btn-lg btn-primary">
            Upload
            <input disabled="disabled" id="backImgId" type='file' data-width="" data-height="" data-name="back-img" data-id="back-img" multiple  onchange="readBackgroundURL(this);" />
        </div>
        <br>
        <div  id="content"></div>
        <br>
    </div>

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
        <br>
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
