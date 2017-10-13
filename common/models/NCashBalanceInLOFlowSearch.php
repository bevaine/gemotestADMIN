<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\NCashBalanceInLOFlow;

/**
 * NCashBalanceInLOFlowSearch represents the model behind the search form about `common\models\NCashBalanceInLOFlow`.
 */
class NCashBalanceInLOFlowSearch extends NCashBalanceInLOFlow
{

    /**
     * @return null|object
     */
    public static function getDb() {
        return Yii::$app->get('GemoTestDB');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cashbalance_id', 'workshift_id'], 'integer'],
            [['sender_key', 'date', 'operation', 'operation_id'], 'safe'],
            [['total', 'balance'], 'number'],
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
        $query = NCashBalanceInLOFlow::find();

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
            'cashbalance_id' => $this->cashbalance_id,
            'total' => $this->total,
            'date' => $this->date,
            'balance' => $this->balance,
            'workshift_id' => $this->workshift_id,
        ]);

        $query->andFilterWhere(['like', 'sender_key', $this->sender_key])
            ->andFilterWhere(['like', 'operation', $this->operation])
            ->andFilterWhere(['like', 'operation_id', $this->operation_id]);

        return $dataProvider;
    }
}
