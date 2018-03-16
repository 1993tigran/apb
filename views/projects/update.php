<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Projects */

$this->title = 'Update Projects';
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="projects-create">
    
    <?= $this->render('_form', [
        'model' => $model,
        'background_categories' => $background_categories,
        'selected_project_back' => $selected_project_back,
        'select_images' => $select_images,
    ]) ?>

</div>
