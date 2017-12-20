<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\NEncashment;

/**
 * NEncashmentSearch represents the model behind the search form about `common\models\NEncashment`.
 * @property $date_from
 * @property $date_to
 */
class NEncashmentSearch extends NEncashment
{
    public $date_from;
    public $date_to;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['sender_key', 'user_aid', 'receipt_number', 'receipt_file', 'date', 'date_from', 'date_to', 'code_1c'], 'safe'],
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
        $query = NEncashment::find();

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
            'total' => $this->total,
            'date' => $this->date,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'sender_key', $this->sender_key])
            ->andFilterWhere(['like', 'user_aid', $this->user_aid])
            ->andFilterWhere(['like', 'receipt_number', $this->receipt_number])
            ->andFilterWhere(['like', 'receipt_file', $this->receipt_file])
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
