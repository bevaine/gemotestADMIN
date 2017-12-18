<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BranchStaff;

/**
 * BranchStaffSearch represents the model behind the search form about `common\models\BranchStaff`.
 * @property $date_from
 * @property $date_to
 */

class BranchStaffSearch extends BranchStaff
{
    public $date_from;
    public $date_to;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'prototype'], 'integer'],
            [['first_name', 'middle_name', 'last_name', 'date_from', 'date_to', 'guid', 'sender_key', 'date', 'personnel_number'], 'safe'],
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
    public function search($params)
    {
        $query = BranchStaff::find();

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
            'prototype' => $this->prototype,
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'middle_name', $this->middle_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'guid', $this->guid])
            ->andFilterWhere(['like', 'sender_key', $this->sender_key])
            ->andFilterWhere(['like', 'personnel_number', $this->personnel_number]);

        if ($this->date_from) {
            $query->andFilterWhere(['>=', 'date', date('Y-m-d 00:00:00', strtotime($this->date_from))]);
        }
        if ($this->date_to) {
            $query->andFilterWhere(['<=', 'date', date('Y-m-d 23:59:59', strtotime($this->date_to))]);
        }

        return $dataProvider;
    }
}
