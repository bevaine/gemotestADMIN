<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\InputOrderZabor;

/**
 * InputOrderZaborSearch represents the model behind the search form about `common\models\InputOrderZabor`.
 */
class InputOrderZaborSearch extends InputOrderZabor
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['aid'], 'integer'],
            [['OrderID', 'IsslCode', 'MSZabor', 'DateIns'], 'safe'],
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
            'DateIns' => $this->DateIns,
        ]);

        $query->andFilterWhere(['like', 'OrderID', $this->OrderID])
            ->andFilterWhere(['like', 'IsslCode', $this->IsslCode])
            ->andFilterWhere(['like', 'MSZabor', $this->MSZabor]);

        return $dataProvider;
    }
}
