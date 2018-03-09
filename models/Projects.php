<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "projects".
 *
 * @property int $id
 * @property string $title
 * @property string $front_img
 * @property string $back_img
 * @property string $top_img
 * @property string $bottom_img
 * @property string $left_img
 * @property string $right_img
 * @property int $box_width
 * @property int $box_height
 * @property int $box_depth
 * @property int $vertical_rot
 * @property int $horizontal_rot
 * @property int $zomm_min
 * @property int $light_x
 * @property int $light_y
 * @property int $light_z
 * @property int $light_intensity
 * @property int $environ_light_intensity
 * @property string $created_at
 *
 * @property ProjectsBackgrounds[] $projectsBackgrounds
 */
class Projects extends \yii\db\ActiveRecord
{

    public $images_size;
    public $background_ides;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'projects';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['images_size', 'background_ides'], 'required'],
            [['light_x', 'light_y','light_z','light_intensity','environ_light_intensity'], 'required'],
            [['title', 'front_img', 'back_img', 'top_img', 'bottom_img', 'left_img', 'right_img','box_width', 'box_height', 'box_depth', 'vertical_rot', 'horizontal_rot', 'zomm_min', 'zom_max'], 'required'],
            [['box_width', 'box_height', 'box_depth', 'vertical_rot', 'horizontal_rot', 'zomm_min', 'zom_max'], 'integer'],
            [['created_at'], 'safe'],
            [['title', 'front_img', 'back_img', 'top_img', 'bottom_img', 'left_img', 'right_img'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Project Title',
            'front_img' => 'Front Img',
            'back_img' => 'Back Img',
            'top_img' => 'Top Img',
            'bottom_img' => 'Bottom Img',
            'left_img' => 'Left Img',
            'right_img' => 'Right Img',
            'box_width' => 'Box Width',
            'box_height' => 'Box Height',
            'box_depth' => 'Box Depth',
            'vertical_rot' => 'Vertical Rotation',
            'horizontal_rot' => 'Horizontal Rotation',
            'zomm_min' => 'Zoom Min',
            'zom_max' => 'Zoom Max',
            'created_at' => 'Created At',
            'background_ides' => 'Background categories',
            'images_size' => 'Images size',
            'light_x' => 'Light x',
            'light_y' => 'Light y',
            'light_z' => 'Light z',
            'light_intensity' => 'Light Intensity',
            'environ_light_intensity' => 'Environ Light Intensity',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectsBackgrounds()
    {
        return $this->hasMany(ProjectsBackgrounds::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectsImagesSize()
    {
        return $this->hasMany(ProjectsImagesSize::className(), ['project_id' => 'id']);
    }
}
