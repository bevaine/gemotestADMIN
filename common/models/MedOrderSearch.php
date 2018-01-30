<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MedOrder;

/**
 * MedOrderSearch represents the model behind the search form about `common\models\MedOrder`.
 */
class MedOrderSearch extends MedOrder
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'patient_id', 'user_id', 'office_id', 'status', 'guarantee_letter', 'erp_order_id', 'create_user_id', 'discount_type'], 'integer'],
            [['date', 'discount_name', 'representative', 'workshift_id', 'guarantee_letter_file_path', 'guarantee_letter_file_name', 'create_employee_guid'], 'safe'],
            [['discount'], 'number'],
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
        $query = MedOrder::find();

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
            'patient_id' => $this->patient_id,
            'user_id' => $this->user_id,
            'office_id' => $this->office_id,
            'status' => $this->status,
            'discount' => $this->discount,
            'guarantee_letter' => $this->guarantee_letter,
            'erp_order_id' => $this->erp_order_id,
            'create_user_id' => $this->create_user_id,
            'discount_type' => $this->discount_type,
        ]);

        $query->andFilterWhere(['like', 'discount_name', $this->discount_name])
            ->andFilterWhere(['like', 'representative', $this->representative])
            ->andFilterWhere(['like', 'workshift_id', $this->workshift_id])
            ->andFilterWhere(['like', 'guarantee_letter_file_path', $this->guarantee_letter_file_path])
            ->andFilterWhere(['like', 'guarantee_letter_file_name', $this->guarantee_letter_file_name])
            ->andFilterWhere(['like', 'create_employee_guid', $this->create_employee_guid]);

        return $dataProvider;
    }
}
