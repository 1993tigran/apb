<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Projects */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="projects-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php $path = '/uploads/projects_image/'?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            [
                'attribute'=>'front_img',
                'value'=>  $path.$model->front_img,
                'format' => ['image',['width'=>'200']],
            ],
            [
                'attribute'=>'back_img',
                'value'=> $path.$model->back_img,
                'format' => ['image',['width'=>'200']],
            ],
            [
                'attribute'=>'top_img',
                'value'=> $path.$model->top_img,
                'format' => ['image',['width'=>'200']],
            ],
            [
                'attribute'=>'bottom_img',
                'value'=> $path.$model->bottom_img,
                'format' => ['image',['width'=>'200']],
            ],
            [
                'attribute'=>'left_img',
                'value'=> $path.$model->left_img,
                'format' => ['image',['width'=>'200']],
            ],
            [
                'attribute'=>'right_img',
                'value'=> $path.$model->right_img,
                'format' => ['image',['width'=>'200']],
            ],
            'box_width',
            'box_height',
            'box_depth',
            'vertical_rot',
            'horizontal_rot',
            'zomm_min',
            'zom_max',
            'created_at',
        ],
    ]) ?>

</div>
