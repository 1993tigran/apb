<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "images_size".
 *
 * @property int $id
 * @property string $title
 * @property int $width
 * @property int $height
 * @property string $created_at
 *
 * @property ProjectsImagesSize[] $projectsImagesSizes
 */
class ImagesSize extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'images_size';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'width','height'], 'required'],
            [['created_at'], 'safe'],
            [['title'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'width' => 'Width',
            'height' => 'Height',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectsImagesSizes()
    {
        return $this->hasMany(ProjectsImagesSize::className(), ['images_size' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectsImages()
    {
        return $this->hasMany(ProjectsImages::className(), ['images_size_id' => 'id']);
    }
}
