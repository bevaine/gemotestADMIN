<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\NPay;

/**
 * NPaySearch represents the model behind the search form about `\common\models\NPay`.
 */
class NPaySearch extends NPay
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
            [['id', 'base_doc_id', 'base_doc_type', 'patient_id', 'login_id', 'login_type', 'pay_type', 'discount_id', 'cito_factor', 'printlist', 'free_pay', 'pay_type_original'], 'integer'],
            [['date', 'order_num', 'order_data', 'base_doc_date', 'patient_fio', 'patient_phone', 'patient_birthday', 'login_key', 'login_fio', 'sender_id', 'sender_name', 'discount_card', 'discount_name', 'app_version', 'kkm', 'z_num'], 'safe'],
            [['cost', 'discount_percent', 'bonus', 'discount_total', 'total', 'bonus_balance'], 'number'],
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
        $query = NPay::find();

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
            'order_data' => $this->order_data,
            'base_doc_id' => $this->base_doc_id,
            'base_doc_type' => $this->base_doc_type,
            'base_doc_date' => $this->base_doc_date,
            'patient_id' => $this->patient_id,
            'patient_birthday' => $this->patient_birthday,
            'login_id' => $this->login_id,
            'login_type' => $this->login_type,
            'pay_type' => $this->pay_type,
            'cost' => $this->cost,
            'discount_id' => $this->discount_id,
            'discount_percent' => $this->discount_percent,
            'bonus' => $this->bonus,
            'discount_total' => $this->discount_total,
            'total' => $this->total,
            'cito_factor' => $this->cito_factor,
            'bonus_balance' => $this->bonus_balance,
            'printlist' => $this->printlist,
            'free_pay' => $this->free_pay,
            'pay_type_original' => $this->pay_type_original,
        ]);

        $query->andFilterWhere(['like', 'order_num', $this->order_num])
            ->andFilterWhere(['like', 'patient_fio', $this->patient_fio])
            ->andFilterWhere(['like', 'patient_phone', $this->patient_phone])
            ->andFilterWhere(['like', 'login_key', $this->login_key])
            ->andFilterWhere(['like', 'login_fio', $this->login_fio])
            ->andFilterWhere(['like', 'sender_id', $this->sender_id])
            ->andFilterWhere(['like', 'sender_name', $this->sender_name])
            ->andFilterWhere(['like', 'discount_card', $this->discount_card])
            ->andFilterWhere(['like', 'discount_name', $this->discount_name])
            ->andFilterWhere(['like', 'app_version', $this->app_version])
            ->andFilterWhere(['like', 'kkm', $this->kkm])
            ->andFilterWhere(['like', 'z_num', $this->z_num]);

        return $dataProvider;
    }
}
