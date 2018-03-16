<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Projects;

/**
 * ProjectsSearch represents the model behind the search form of `app\models\Projects`.
 */
class ProjectsSearch extends Projects
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'box_width', 'box_height', 'box_depth', 'vertical_rot', 'horizontal_rot', 'zomm_min', 'zom_max'], 'integer'],
            [['title', 'front_img', 'back_img', 'top_img', 'bottom_img', 'left_img', 'right_img', 'created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params,$queue)
    {
        $query = Projects::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'box_width' => $this->box_width,
            'box_height' => $this->box_height,
            'box_depth' => $this->box_depth,
            'vertical_rot' => $this->vertical_rot,
            'horizontal_rot' => $this->horizontal_rot,
            'zomm_min' => $this->zomm_min,
            'zom_max' => $this->zom_max,
            'queue' => $queue,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'front_img', $this->front_img])
            ->andFilterWhere(['like', 'back_img', $this->back_img])
            ->andFilterWhere(['like', 'top_img', $this->top_img])
            ->andFilterWhere(['like', 'bottom_img', $this->bottom_img])
            ->andFilterWhere(['like', 'left_img', $this->left_img])
            ->andFilterWhere(['like', 'right_img', $this->right_img])
            ->andFilterWhere(['like', 'created_at', $this->created_at]);

        return $dataProvider;
    }
}
