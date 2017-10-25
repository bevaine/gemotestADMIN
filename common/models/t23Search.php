<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\t23;

/**
 * t23Search represents the model behind the search form about `common\models\t23`.
 */
class t23Search extends t23
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q7', 'q8', 'q9', 'q10', 'q11', 'q12', 'q13', 'q14', 'q15', 'q16', 'q17', 'q18', 'q19', 'q20', 'q21', 'q22', 'q23'], 'safe'],
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
        $query = t23::find();

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
        $query->andFilterWhere(['like', 'q1', $this->q1])
            ->andFilterWhere(['like', 'q2', $this->q2])
            ->andFilterWhere(['like', 'q3', $this->q3])
            ->andFilterWhere(['like', 'q4', $this->q4])
            ->andFilterWhere(['like', 'q5', $this->q5])
            ->andFilterWhere(['like', 'q6', $this->q6])
            ->andFilterWhere(['like', 'q7', $this->q7])
            ->andFilterWhere(['like', 'q8', $this->q8])
            ->andFilterWhere(['like', 'q9', $this->q9])
            ->andFilterWhere(['like', 'q10', $this->q10])
            ->andFilterWhere(['like', 'q11', $this->q11])
            ->andFilterWhere(['like', 'q12', $this->q12])
            ->andFilterWhere(['like', 'q13', $this->q13])
            ->andFilterWhere(['like', 'q14', $this->q14])
            ->andFilterWhere(['like', 'q15', $this->q15])
            ->andFilterWhere(['like', 'q16', $this->q16])
            ->andFilterWhere(['like', 'q17', $this->q17])
            ->andFilterWhere(['like', 'q18', $this->q18])
            ->andFilterWhere(['like', 'q19', $this->q19])
            ->andFilterWhere(['like', 'q20', $this->q20])
            ->andFilterWhere(['like', 'q21', $this->q21])
            ->andFilterWhere(['like', 'q22', $this->q22])
            ->andFilterWhere(['like', 'q23', $this->q23]);

        return $dataProvider;
    }


}
