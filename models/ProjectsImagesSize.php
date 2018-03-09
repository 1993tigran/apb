<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "projects_images_size".
 *
 * @property int $id
 * @property int $project_id
 * @property int $images_size
 *
 * @property ImagesSize $imagesSize
 * @property Projects $project
 */
class ProjectsImagesSize extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'projects_images_size';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'images_size'], 'required'],
            [['project_id', 'images_size'], 'integer'],
            [['images_size'], 'exist', 'skipOnError' => true, 'targetClass' => ImagesSize::className(), 'targetAttribute' => ['images_size' => 'id']],
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
            'images_size' => 'Images Size',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImagesSize()
    {
        return $this->hasOne(ImagesSize::className(), ['id' => 'images_size']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Projects::className(), ['id' => 'project_id']);
    }

    public static function getProjectsImagesSize($id)
    {
        $query = (new Query())
            ->select(['pi.project_id','is.*'])
            ->from(self::tableName().' pi')
            ->where(['pi.project_id' => $id])
            ->leftJoin(ImagesSize::tableName().' is', 'is.id = pi.images_size')
            ->orderBy(['is.width' => SORT_DESC])
            ->all();
        return $query;
    }

    public static function gatProjectImagesWithSize($project_id)
    {
       return self::find()->where(['project_id' => $project_id])->with('imagesSize')->all();
    }

    public static function getProjectsImagesSizeByProjectId($project_id)
    {
        return ProjectsImagesSize::find()->where(['project_id' => $project_id])->all();

    }
}
