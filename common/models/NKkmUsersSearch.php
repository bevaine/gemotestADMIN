<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\NKkmUsers;

/**
 * NKkmUsersSearch represents the model behind the search form of `common\models\NKkmUsers`.
 */
class NKkmUsersSearch extends NKkmUsers
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'kkm_id', 'user_id'], 'integer'],
            [['login', 'password', 'user_type'], 'safe'],
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
        $query = NKkmUsers::find()
            ->joinWith(['kkm'], false)
            ->joinWith(['logins'], false);
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
            'kkm_id' => $this->kkm_id,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'login', $this->login])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'user_type', $this->user_type]);

        return $dataProvider;
    }
}
