<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MedAppointment;

/**
 * MedAppointmentSearch represents the model behind the search form about `common\models\MedAppointment`.
 */
class MedAppointmentSearch extends MedAppointment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order_id', 'patient_id', 'doctor_id', 'user_id', 'office_id', 'nurse_id'], 'integer'],
            [['date', 'doctor_guid', 'nurse_guid'], 'safe'],
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
        $query = MedAppointment::find();

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
            'order_id' => $this->order_id,
            'date' => $this->date,
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'user_id' => $this->user_id,
            'office_id' => $this->office_id,
            'nurse_id' => $this->nurse_id,
        ]);

        $query->andFilterWhere(['like', 'doctor_guid', $this->doctor_guid])
            ->andFilterWhere(['like', 'nurse_guid', $this->nurse_guid]);

        return $dataProvider;
    }
}
