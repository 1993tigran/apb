<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\Projects */
/* @var $form yii\widgets\ActiveForm */


$this->registerCssFile('@web/css/cropper.css',['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile('@web/js/cropper.js',['position' => \yii\web\View::POS_HEAD]);

$this->registerJsFile('@web/js/threejs/build/three.js',['position' => \yii\web\View::POS_END]);
$this->registerJsFile('@web/js/threejs/lib/OrbitControls.js',['position' => \yii\web\View::POS_END]);
$this->registerJsFile('@web/js/threejs/customThreeJs.js',['position' => \yii\web\View::POS_END]);
?>
<?php $form = ActiveForm::begin([
    'id' => 'form-project',
]); ?>
<div class="row">

    <div class="col-md-6 project-form">
        <div class="projects-form">
            <div class="row">
                <div class="col-md-6">
                    <h1><?= Html::encode($this->title) ?></h1>
                </div>
                <div class="col-md-6" align="right">
                    <br>

                    <button type="button" class="btn btn-primary preview">Preview</button>
                </div>
            </div>
            <br>
            <?= $form->field($model, 'title')
                ->textInput([
                    'placeholder' => 'Project Title',
            ])->label(false) ?>

            <?= $form->field($model, 'background_ides',[])->widget(Select2::classname(), [
                'data' => $background_categories,
                'options' => [
                    'id' => 'select-back',
                    'value' => !empty($selected_project_back)?$selected_project_back:'',
                    'placeholder' => 'Select background categories ...',
                    'multiple' => true,
                ],
            ])->label('Background categories');
            ?>

            <?= $form->field($model, 'images_size')->widget(Select2::classname(), [
                'data' => $images_size,
                'options' => [
                    'id' => 'images-size',
                    'value' => !empty($selected_images_size)?$selected_images_size:'',
                    'placeholder' => 'Select size ...',
                    'multiple' => true,
                ],
            ])->label('Images size');
            ?>
            <br>
            <div class="row">
                <div class="col-md-4" id="box-width">
                    <?= $form->field($model, 'box_width')
                        ->textInput([
                            'id' => 'box_width',
                            'placeholder' => 'Box width(mm)'
                        ])->label(false); ?>
                </div>
                <div class="col-md-4" id="box-height">
                    <?= $form->field($model, 'box_height')
                        ->textInput([
                            'id' => 'box_height',
                            'placeholder' => 'Box wheight(mm)'
                        ])
                        ->label(false); ?>
                </div>
                <div class="col-md-4" id="box-depth">
                    <?= $form->field($model, 'box_depth')
                        ->textInput([
                            'id' => 'box_depth',
                            'placeholder' => 'Box depth(mm)'
                        ])
                        ->label(false); ?>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'vertical_rot')
                        ->textInput([
                             'id' => 'vertical_rot',
                            'placeholder' => 'Vertical rotation(max angle)'
                        ])
                        ->label(false); ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'horizontal_rot')
                        ->textInput([
                             'id' => 'horizontal_rot',
                            'placeholder' => 'Horizontal rotation(max angle)'
                        ])
                        ->label(false); ?>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'zomm_min')
                        ->textInput([
                            'id' => 'zomm_min',
                            'placeholder' => 'Zoom minimal ratio(%)'
                        ])
                        ->label(false) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'zom_max')
                        ->textInput([
                            'id' => 'zom_max',
                            'placeholder' => 'Zoom maximal ratio(%)'
                        ])
                        ->label(false) ?>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'light_x')
                        ->textInput([
                            'id' => 'light_x',
                            'placeholder' => 'Light x'
                        ])
                        ->label(false); ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'light_y')
                        ->textInput([
                            'id' => 'light_y',
                            'placeholder' => 'Light y'
                        ])
                        ->label(false); ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'light_z')
                        ->textInput([
                            'id' => 'light_z',
                            'placeholder' => 'Light z'
                        ])
                        ->label(false) ?>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'light_intensity')
                        ->textInput([
                            'id' => 'light_intensity',
                            'placeholder' => 'Light Intensity(0-9)'
                        ])
                        ->label(false) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'environ_light_intensity')
                        ->textInput([
                            'id' => 'environ_light_intensity',
                            'placeholder' => 'Environ Light Intensity(0-9)'
                        ])
                        ->label(false) ?>
                </div>
            </div>


        </div>
        <br>
        <div class="images-box">

            <div class="row">
                <div class="col-md-4">
                    <div class="images-box-content"></div>
                    <label for="Front Img">Front Img</label>
                    <input type='file' data-name="front" data-id="front-img" onchange="readURL(this);" />
                    <div  id="front-img-content">
                        <?php if (!empty($select_images['front_img'])):?>
                            <img id="front-img-image" style="width: 100%" class="img-thumbnail" src="<?=$select_images['front_img'];?>">
                        <?endif;?>
                    </div>
                    <button  data-id="front-img" type="button" id="front-img-crop" class="crop-button" onclick="crop(this)">Crop</button>
                    <input id="front-img" type="hidden" value="<?=!empty($select_images['front_img'])?$select_images['front_img']:$select_images['front_img'];?>" name="Projects[front_img]" />
                </div>
                <div class="col-md-4">
                    <div class="images-box-content"></div>
                    <label for="back Img">Back Img</label>
                    <input type='file' data-name="back" data-id="back-img" onchange="readURL(this);" />
                    <div  id="back-img-content">
                        <?php if (!empty($select_images['back_img'])):?>
                            <img id="back-img-image" style="width: 100%" class="img-thumbnail" src="<?=$select_images['back_img'];?>">
                        <?endif;?>
                    </div>
                    <button  data-id="back-img" type="button" id="back-img-crop" class="crop-button" onclick="crop(this)">Crop</button>
                    <input id="back-img" type="hidden" value="<?=!empty($select_images['back_img'])?$select_images['back_img']:$select_images['back_img'];?>" name="Projects[back_img]" />
                </div>
                <div class="col-md-4">
                    <div class="images-box-content"></div>
                    <label for="top Img">Top Img</label>
                    <input type='file' data-name="top" data-id="top-img" name="Projects[top_img]" onchange="readURL(this);" />
                    <div  id="top-img-content">
                        <?php if (!empty($select_images['top_img'])):?>
                            <img id="top-img-image" style="width: 100%" class="img-thumbnail" src="<?=$select_images['top_img'];?>">
                        <?endif;?>
                    </div>
                    <button  data-id="top-img" type="button" id="top-img-crop" class="crop-button" onclick="crop(this)">Crop</button>
                    <input id="top-img" type="hidden" value="<?=!empty($select_images['top_img'])?$select_images['top_img']:$select_images['top_img'];?>" name="Projects[top_img]" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="images-box-content"></div>
                    <label for="bottom Img">Bottom Img</label>
                    <input type='file' data-name="bottom" data-id="bottom-img" name="Projects[bottom_img]" onchange="readURL(this);" />
                    <div  id="bottom-img-content">
                        <?php if (!empty($select_images['bottom_img'])):?>
                            <img id="bottom-img-image" style="width: 100%" class="img-thumbnail" src="<?=$select_images['bottom_img'];?>">
                        <?endif;?>
                    </div>
                    <button  data-id="bottom-img" type="button" id="bottom-img-crop" class="crop-button" onclick="crop(this)">Crop</button>
                    <input id="bottom-img" type="hidden" value="<?=!empty($select_images['bottom_img'])?$select_images['bottom_img']:$select_images['bottom_img'];?>" name="Projects[bottom_img]" />
                </div>
                <div class="col-md-4">
                    <div class="images-box-content"></div>
                    <label for="left Img">left Img</label>
                    <input type='file' data-name="left" data-id="left-img" name="Projects[left_img]" onchange="readURL(this);" />
                    <div  id="left-img-content">
                        <?php if (!empty($select_images['left_img'])):?>
                            <img id="left-img-image" style="width: 100%" class="img-thumbnail" src="<?=$select_images['left_img'];?>">
                        <?endif;?>
                    </div>
                    <button  data-id="left-img" type="button" id="left-img-crop" class="crop-button" onclick="crop(this)">Crop</button>
                    <input id="left-img" type="hidden" value="<?=!empty($select_images['left_img'])?$select_images['left_img']:$select_images['left_img'];?>" name="Projects[left_img]" />
                </div>
                <div class="col-md-4">
                    <div class="images-box-content"></div>
                    <label for="right Img">Right Img</label>
                    <input type='file' data-name="right" data-id="right-img" name="Projects[right_img]" onchange="readURL(this);" />
                    <div  id="right-img-content">
                        <?php if (!empty($select_images['right_img'])):?>
                            <img id="right-img-image" style="width: 100%" class="img-thumbnail" src="<?=$select_images['right_img'];?>">
                        <?endif;?>
                    </div>
                    <button  data-id="right-img" type="button" id="right-img-crop" class="crop-button" onclick="crop(this)">Crop</button>
                    <input id="right-img" type="hidden" value="<?=!empty($select_images['right_img'])?$select_images['right_img']:$select_images['right_img'];?>" name="Projects[right_img]" />
                </div>
            </div>

        </div>
    </div>
    <div class="col-md-6 project-view" id="project-view">
        <div class="row">
            <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <span class="nav_menu_preview">Preview</span>
                    <span nav_menu_preview_generate>
                        <button type="submit" class="btn btn-success generate-btn">GENERATE <em class="time-round"></em></button>
                    </span>
                </div>
            </div>
        </div>

        <div id="container" style="overflow:hidden;"></div>
    </div>
</div>
<?php ActiveForm::end(); ?>

