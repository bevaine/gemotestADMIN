<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RepPeriodLabGemotest;

/**
 * RepPeriodLabGemotestSearch represents the model behind the search form of `common\models\RepPeriodLabGemotest`.
 */
class RepPeriodLabGemotestSearch extends RepPeriodLabGemotest
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'test_period', 'user_id'], 'integer'],
            [['date_start', 'date_end', 'sender_id', 'login', 'pass', 'date_active', 'deleted', 'contract'], 'safe'],
            [['reward'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = RepPeriodLabGemotest::find();

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
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'date_active' => $this->date_active,
            'reward' => $this->reward,
            'test_period' => $this->test_period,
            'deleted' => $this->deleted,
            'user_id' => $this->user_id,
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => str_replace('ЛД', '', $this->contract),
        ]);

        $query->andFilterWhere(['like', 'sender_id', $this->sender_id])
            ->andFilterWhere(['like', 'login', $this->login])
            ->andFilterWhere(['like', 'pass', $this->pass]);

        return $dataProvider;
    }
}
