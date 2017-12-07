<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\NReturnOrder;

/**
 * NReturnOrderSearch represents the model behind the search form about `common\models\NReturnOrder`.
 * @property $date_from
 * @property $date_to
 */
class NReturnOrderSearch extends NReturnOrder
{
    public $date_from;
    public $date_to;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'parent_type', 'status', 'user_id', 'sync_with_lc_status'], 'integer'],
            [['date', 'date_from', 'date_to', 'order_num', 'kkm', 'last_update', 'sync_with_lc_date'], 'safe'],
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
        $query = NReturnOrder::find();

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
            'parent_id' => $this->parent_id,
            'parent_type' => $this->parent_type,
            'date' => $this->date,
            'status' => $this->status,
            'total' => $this->total,
            'user_id' => $this->user_id,
            'sync_with_lc_status' => $this->sync_with_lc_status,
            'last_update' => $this->last_update,
            'sync_with_lc_date' => $this->sync_with_lc_date,
        ]);

        if (isset($params["NReturnOrderSearch"]['date_from']) && isset($params["NReturnOrderSearch"]['date_to'])) {
            $query->andWhere(['>=', 'date', $params["NReturnOrderSearch"]['date_from']])
                ->andWhere(['<=', 'date', $params["NReturnOrderSearch"]['date_to']]);
        } else {
            if ($this->date_from && $this->date_to) {
                $query->andWhere(['>=', 'date', $this->date_from])
                    ->andWhere(['<=', 'date', $this->date_to]);
            }
        }

        $query->andFilterWhere(['like', 'order_num', $this->order_num])
            ->andFilterWhere(['like', 'kkm', $this->kkm]);

        return $dataProvider;
    }
}
