<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Backgrounds */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Backgrounds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="backgrounds-view">



    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <h1><?= Html::encode($this->title) ?></h1>
    <hr>
        <div class="row">
            <?php foreach ($model->backgroundImages as $image):?>
                <div class="col-md-3" style="padding: 10px">
                    <img  style="width: 100%" height="200" src="/uploads/background-image/<?=$image->image?>" alt="">
                </div>
            <?php endforeach;?>
        </div>

</div>
