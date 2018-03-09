<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "backgrounds".
 *
 * @property int $id
 * @property string $title
 * @property string $created_at
 *
 * @property BackgroundImages[] $backgroundImages
 * @property ProjectsBackgrounds[] $projectsBackgrounds
 */
class Backgrounds extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'backgrounds';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['created_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
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
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBackgroundImages()
    {
        return $this->hasMany(BackgroundImages::className(), ['background_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectsBackgrounds()
    {
        return $this->hasMany(ProjectsBackgrounds::className(), ['background_id' => 'id']);
    }

    public static function getBackgroundWithImages($id){
       return self::find()->where(['id' => $id])->with('backgroundImages')->one();
    }
}
