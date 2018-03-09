<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\BackgroundsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Backgrounds';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="backgrounds-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Album', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'title',
            [
                'header' => 'Created at',
                'format' => 'html',
                'value' => function ($data) {
                    return date("Y-m-d", strtotime($data['created_at']));
                },

                'filter' => \yii\jui\DatePicker::widget([
                    'attribute' => 'created_at',
                    'options' => ['class' => 'form-control'],
                    'model' => $searchModel,
                    'dateFormat' => 'yyyy-MM-dd',
                ]),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
