<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\InputOrderZabor;

/**
 * InputOrderZaborSearch represents the model behind the search form about `common\models\InputOrderZabor`.
 * @property $date_from
 * @property $date_to
 */

class InputOrderZaborSearch extends InputOrderZabor
{
    public $date_from;
    public $date_to;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['aid'], 'integer'],
            [['OrderID', 'IsslCode', 'MSZabor', 'DateIns', 'date_from', 'date_to'], 'safe'],
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
        $query = InputOrderZabor::find();

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
            'aid' => $this->aid,
        ]);

        $query->andFilterWhere(['like', 'OrderID', $this->OrderID])
            ->andFilterWhere(['like', 'IsslCode', $this->IsslCode])
            ->andFilterWhere(['like', 'MSZabor', $this->MSZabor]);

        if ($this->date_from) {
            $query->andFilterWhere(['>=', 'DateIns', date('Y-m-d 00:00:00', strtotime($this->date_from))]);
        }
        if ($this->date_to) {
            $query->andFilterWhere(['<=', 'DateIns', date('Y-m-d 23:59:59', strtotime($this->date_to))]);
        }

        return $dataProvider;
    }
}
