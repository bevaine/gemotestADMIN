<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MedReturnOrder;

/**
 * MedReturnOrderSearch represents the model behind the search form about `common\models\MedReturnOrder`.
 */
class MedReturnOrderSearch extends MedReturnOrder
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order_id', 'status', 'user_id', 'is_virtual', 'pay_type', 'pay_type_original', 'is_freepay'], 'integer'],
            [['date', 'kkm', 'z_num'], 'safe'],
            [['total'], 'number'],
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
        $query = MedReturnOrder::find();

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
            'date' => $this->date,
            'order_id' => $this->order_id,
            'status' => $this->status,
            'total' => $this->total,
            'user_id' => $this->user_id,
            'is_virtual' => $this->is_virtual,
            'pay_type' => $this->pay_type,
            'pay_type_original' => $this->pay_type_original,
            'is_freepay' => $this->is_freepay,
        ]);

        $query->andFilterWhere(['like', 'kkm', $this->kkm])
            ->andFilterWhere(['like', 'z_num', $this->z_num]);

        return $dataProvider;
    }
}
