<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\NAdUsers;

/**
 * NAdUsersSearch represents the model behind the search form about `common\models\NAdUsers`.
 */
class NAdUsersSearch extends NAdUsers
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'gs_id', 'gs_key', 'gs_usertype', 'allow_gs', 'active', 'AD_active', 'auth_ldap_only'], 'integer'],
            [['last_name', 'first_name', 'middle_name', 'AD_name', 'AD_position', 'AD_email', 'table_number', 'subdivision', 'create_date', 'last_update', 'gs_email', 'AD_login'], 'safe'],
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
        $query = NAdUsers::find();

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
            'ID' => $this->ID,
            'create_date' => $this->create_date,
            'last_update' => $this->last_update,
            'gs_id' => $this->gs_id,
            'gs_key' => $this->gs_key,
            'gs_usertype' => $this->gs_usertype,
            'allow_gs' => $this->allow_gs,
            'active' => $this->active,
            'AD_active' => $this->AD_active,
            'auth_ldap_only' => $this->auth_ldap_only,
        ]);

        $query->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'middle_name', $this->middle_name])
            ->andFilterWhere(['like', 'AD_name', $this->AD_name])
            ->andFilterWhere(['like', 'AD_position', $this->AD_position])
            ->andFilterWhere(['like', 'AD_email', $this->AD_email])
            ->andFilterWhere(['like', 'table_number', $this->table_number])
            ->andFilterWhere(['like', 'subdivision', $this->subdivision])
            ->andFilterWhere(['like', 'gs_email', $this->gs_email])
            ->andFilterWhere(['like', 'AD_login', $this->AD_login]);

        return $dataProvider;
    }
}
