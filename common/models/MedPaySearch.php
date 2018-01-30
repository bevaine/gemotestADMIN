<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MedPay;

/**
 * MedPaySearch represents the model behind the search form about `common\models\MedPay`.
 * @property $date_from
 * @property $date_to
 */
class MedPaySearch extends MedPay
{
    public $date_from;
    public $date_to;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order_id', 'patient_id', 'user_id', 'pay_type', 'printlist', 'free_pay', 'base_doc_type', 'base_doc_id', 'is_virtual', 'pay_type_original'], 'integer'],
            [['date', 'date_from', 'date_to', 'patient_fio', 'patient_phone', 'patient_birthday', 'user_username', 'office_id', 'office_name', 'user_fio', 'kkm', 'z_num'], 'safe'],
            [['cost', 'discount_total', 'total'], 'number'],
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
        $query = MedPay::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'date' => SORT_DESC
                ]
            ],
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
            'patient_id' => $this->patient_id,
            'patient_birthday' => $this->patient_birthday,
            'user_id' => $this->user_id,
            'pay_type' => $this->pay_type,
            'cost' => $this->cost,
            'discount_total' => $this->discount_total,
            'total' => $this->total,
            'printlist' => $this->printlist,
            'free_pay' => $this->free_pay,
            'base_doc_type' => $this->base_doc_type,
            'base_doc_id' => $this->base_doc_id,
            'is_virtual' => $this->is_virtual,
            'pay_type_original' => $this->pay_type_original,
        ]);

        $query->andFilterWhere(['like', 'patient_fio', $this->patient_fio])
            ->andFilterWhere(['like', 'patient_phone', $this->patient_phone])
            ->andFilterWhere(['like', 'user_username', $this->user_username])
            ->andFilterWhere(['like', 'office_id', $this->office_id])
            ->andFilterWhere(['like', 'office_name', $this->office_name])
            ->andFilterWhere(['like', 'user_fio', $this->user_fio])
            ->andFilterWhere(['like', 'kkm', $this->kkm])
            ->andFilterWhere(['like', 'z_num', $this->z_num]);

        if ($this->date_from) {
            $query->andFilterWhere(['>=', 'date', date('Y-m-d 00:00:00', strtotime($this->date_from))]);
        }
        if ($this->date_to) {
            $query->andFilterWhere(['<=', 'date', date('Y-m-d 23:59:59', strtotime($this->date_to))]);
        }

        return $dataProvider;
    }
}
