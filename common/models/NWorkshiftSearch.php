<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\NWorkshift;

/**
 * NWorkshiftSearch represents the model behind the search form about `common\models\NWorkshift`.
 * @property $close_date_from
 * @property $close_date_to
 * @property $open_date_from
 * @property $open_date_to
 */

class NWorkshiftSearch extends NWorkshift
{
    public $close_date_from;
    public $close_date_to;
    public $open_date_from;
    public $open_date_to;
    
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
            [['id', 'user_aid', 'error_check_count', 'error_check_return_count'], 'integer'],
            [['sender_key', 'kkm', 'z_num', 'open_date', 'close_date', 'close_date_from', 'close_date_to', 'open_date_from', 'open_date_to', 'sender_key_close', 'file_name', 'code_1c'], 'safe'],
            [['not_zero_sum_start', 'not_zero_sum_end', 'amount_cash_register', 'error_check_total_cash', 'error_check_total_card', 'error_check_return_total_cash', 'error_check_return_total_card'], 'number'],
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
        $query = NWorkshift::find();

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
            'user_aid' => $this->user_aid,
            'not_zero_sum_start' => $this->not_zero_sum_start,
            'not_zero_sum_end' => $this->not_zero_sum_end,
            'amount_cash_register' => $this->amount_cash_register,
            'error_check_count' => $this->error_check_count,
            'error_check_total_cash' => $this->error_check_total_cash,
            'error_check_total_card' => $this->error_check_total_card,
            'error_check_return_count' => $this->error_check_return_count,
            'error_check_return_total_cash' => $this->error_check_return_total_cash,
            'error_check_return_total_card' => $this->error_check_return_total_card,
        ]);

        if ($this->open_date_from) {
            $query->andFilterWhere(['>=', 'open_date', date('Y-m-d 00:00:00', strtotime($this->open_date_from))]);
        }
        if ($this->open_date_to) {
            $query->andFilterWhere(['<=', 'open_date', date('Y-m-d 23:59:59', strtotime($this->open_date_to))]);
        }

        if ($this->close_date_from) {
            $query->andFilterWhere(['>=', 'close_date', date('Y-m-d 00:00:00', strtotime($this->close_date_from))]);
        }
        if($this->close_date_to) {
            $query->andFilterWhere(['<=', 'close_date', date('Y-m-d 23:59:59', strtotime($this->close_date_to))]);
        }

        $query->andFilterWhere(['like', 'sender_key', $this->sender_key])
            ->andFilterWhere(['like', 'kkm', $this->kkm])
            ->andFilterWhere(['like', 'z_num', $this->z_num])
            ->andFilterWhere(['like', 'sender_key_close', $this->sender_key_close])
            ->andFilterWhere(['like', 'file_name', $this->file_name])
            ->andFilterWhere(['like', 'code_1c', $this->code_1c]);

        return $dataProvider;
    }
}
