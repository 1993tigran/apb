<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ProjectsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'My projects';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="projects-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            'id',
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

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{images}{update}{delete}',
                'buttons' => [

                    'images' => function ($url) {
                        if (!empty($url)){
                            return Html::a('<i class="fa fa-image"></i>', $url, [
                                'title' => Yii::t('app', 'Images'),
                            ]);
                        }
                    },

                    'update' => function ($url) {
                        if (!empty($url)){
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                'title' => Yii::t('app', 'Update'),
                            ]);
                        }
                    },
                    'delete' => function ($url) {
                        if (!empty($url)){
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title' => Yii::t('app', 'Delete'),
                                'data-title' => 'Delete',
                                'data-method' => 'post',
                                'aria-label' => 'Delete',
                                'data-confirm' => 'Are you sure you want to delete this item?'
                            ]);
                        }
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {

                    if ($action === 'images') {
                        $url = \yii\helpers\Url::to(['/project-images-size-list/' . $model['id']]);
                        return $url;
                    }
                    if ($action === 'update') {
                        $url = \yii\helpers\Url::to(['/projects-update/' . $model['id']]);
                        return $url;
                    }
                    if ($action === 'delete') {
                        $url = \yii\helpers\Url::to(['/projects-delete/' . $model['id']]);
                        return $url;
                    }
                }
            ],
        ],
    ]); ?>
</div>
