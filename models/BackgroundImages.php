<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "background_images".
 *
 * @property int $id
 * @property int $background_id
 * @property string $image
 * @property string $created_at
 *
 * @property Backgrounds $background
 */
class BackgroundImages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'background_images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image'], 'file', 'skipOnEmpty' => true, 'maxFiles'=>0],
            [['background_id'], 'integer'],
            [['created_at'], 'safe'],
            [['image'], 'string', 'max' => 255],
            [['background_id'], 'exist', 'skipOnError' => true, 'targetClass' => Backgrounds::className(), 'targetAttribute' => ['background_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'background_id' => 'Background ID',
            'image' => 'Image',
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
     * @param $ides
     * @return array
     */
    public function getBacBackgroundImages($ides)
    {
        $query = (new Query())
            ->select('*')
            ->from(self::tableName());
        foreach ($ides as $id) {
            $query = $query->orWhere(['background_id' => $id]);
        }
        $data = $query->all();

        return $data;
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getBackgroundImagesById($id)
    {
       return self::find()->where(['background_id' => $id])->all();
    }
}
