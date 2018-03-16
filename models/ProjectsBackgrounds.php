<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "projects_backgrounds".
 *
 * @property int $id
 * @property int $project_id
 * @property int $background_id
 * @property string $created_at
 *
 * @property Backgrounds $background
 * @property Projects $project
 */
class ProjectsBackgrounds extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'projects_backgrounds';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'background_id'], 'required'],
            [['project_id', 'background_id'], 'integer'],
            [['created_at'], 'safe'],
            [['background_id'], 'exist', 'skipOnError' => true, 'targetClass' => Backgrounds::className(), 'targetAttribute' => ['background_id' => 'id']],
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
            'background_id' => 'Background ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBackground()
    {
        return $this->hasOne(Backgrounds::className(), ['id' => 'background_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Projects::className(), ['id' => 'project_id']);
    }

    public function getProjectsBackgrounds()
    {
        return $this->hasMany(BackgroundImages::className(), ['background_id' => 'background_id']);
    }

    public static function getProjectsBackgroundsByProjectId($project_id)
    {
        return self::find()->where(['project_id' => $project_id])->all();
    }

    public static function getProjectsWithBackgrounds($id)
    {
        return self::find()->where(['project_id' => $id])->with('projectsBackgrounds')->all();
    }

    public static function getProjectsBackgroundSize($id)
    {
        return self::find()->where(['project_id' => $id])->with('background')->all();
    }

    public static function getProjectsBackgroundMaxSize($id)
    {
        $query = (new Query())
            ->select(['pb.project_id','b.*'])
            ->from(self::tableName().' pb')
            ->where(['pb.project_id' => $id])
            ->leftJoin(Backgrounds::tableName().' b', 'b.id = pb.background_id')
            ->orderBy(['b.width' => SORT_DESC])
            ->one();
        return $query;
    }

    public static function getBackgroundsWithImages($project_id)
    {
        return self::find()
            ->where(['project_id' => $project_id])
            ->with('background')
            ->with('projectsBackgrounds')
            ->all();
    }
}
