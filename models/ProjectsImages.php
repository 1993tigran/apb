<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "projects_images".
 *
 * @property int $id
 * @property int $project_id
 * @property int $images_size_id
 * @property string $name
 * @property string $created_at
 *
 * @property Projects $project
 */
class ProjectsImages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'projects_images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'name','images_size_id'], 'required'],
            [['project_id','images_size_id'], 'integer'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Projects::className(), 'targetAttribute' => ['project_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => 'Project ID',
            'images_size_id' => 'Images Size Id',
            'name' => 'Name',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Projects::className(), ['id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImagesSize()
    {
        return $this->hasOne(ImagesSize::className(), ['id' => 'images_size_id']);
    }

    public static function getProjectsImagesByIdes($project_id, $size_id)
    {
        return self::find()->where(['project_id' => $project_id])->andWhere(['images_size_id' => $size_id]);
    }

}
