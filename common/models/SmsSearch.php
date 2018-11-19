<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Sms;

/**
 * SmsSearch represents the model behind the search form of `common\models\Sms`.
 */
class SmsSearch extends Sms
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['priority', 'attempt', 'provider_id', 'bounce_reason', 'attempts_get_status'], 'integer'],
            [['orderNum', 'status', 'client_id', 'phone', 'message', 'tz', 'provider_sms_id', 'deliver_sm', 'callback'], 'safe'],
            [['enqueued'], 'boolean'],
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
        $query = Sms::find();

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
            'priority' => $this->priority,
            'enqueued' => $this->enqueued,
            'attempt' => $this->attempt,
            'provider_id' => $this->provider_id,
            'bounce_reason' => $this->bounce_reason,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'attempts_get_status' => $this->attempts_get_status,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['ilike', 'client_id', $this->client_id])
            ->andFilterWhere(['ilike', 'phone', $this->phone])
            ->andFilterWhere(['ilike', 'message', $this->message])
            ->andFilterWhere(['ilike', 'tz', $this->tz])
            ->andFilterWhere(['ilike', 'provider_sms_id', $this->provider_sms_id])
            ->andFilterWhere(['ilike', 'deliver_sm', $this->deliver_sm])
            ->andFilterWhere(['ilike', 'callback', $this->callback])
            ->andFilterWhere(['ilike', 'message', $this->orderNum]);

        return $dataProvider;
    }
}
