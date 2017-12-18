<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\NReturnWithoutItem;

/**
 * NReturnWithoutItemSearch represents the model behind the search form about `common\models\NReturnWithoutItem`.
 * @property $date_from
 * @property $date_to
 */

class NReturnWithoutItemSearch extends NReturnWithoutItem
{
    public $date_from;
    public $date_to;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'pay_type', 'user_aid'], 'integer'],
            [['order_num', 'date', 'date_from', 'date_to' , 'kkm', 'z_num', 'comment', 'path_file', 'base', 'code_1c'], 'safe'],
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
        $query = NReturnWithoutItem::find();

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
            'total' => $this->total,
            'pay_type' => $this->pay_type,
            'user_aid' => $this->user_aid,
        ]);

        $query->andFilterWhere(['like', 'order_num', $this->order_num])
            ->andFilterWhere(['like', 'kkm', $this->kkm])
            ->andFilterWhere(['like', 'z_num', $this->z_num])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'path_file', $this->path_file])
            ->andFilterWhere(['like', 'base', $this->base])
            ->andFilterWhere(['like', 'code_1c', $this->code_1c]);

        if ($this->date_from) {
            $query->andFilterWhere(['>=', 'date', date('Y-m-d 00:00:00', strtotime($this->date_from))]);
        }
        if ($this->date_to) {
            $query->andFilterWhere(['<=', 'date', date('Y-m-d 23:59:59', strtotime($this->date_to))]);
        }

        return $dataProvider;
    }
}
