<?php

use budyaga\cropper\Widget;
use \yii\bootstrap\ActiveForm;
use\yii\bootstrap\Html;

/* @var $this yii\web\View */

$this->title = '';
?>
<div class="site-index">

    <?php $form = ActiveForm::begin(['id' => 'form-profile']); ?>
    <?= $form->field($model, 'photo')->widget(Widget::className(), [
        'uploadUrl' => \yii\helpers\Url::toRoute('/test/upload'),
//        'uploadUrl' => null,
    ]) ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>


