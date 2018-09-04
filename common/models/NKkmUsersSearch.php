<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\NKkmUsers;

/**
 * NKkmUsersSearch represents the model behind the search form of `common\models\NKkmUsers`.
 * @property string $sender_key
 * @property string $number
 * @property string $name_gs
 * @property string $fio
 * @property string $kkm_name
 */

class NKkmUsersSearch extends NKkmUsers
{
    public $sender_key;
    public $number;
    public $name_gs;
    public $kkm_name;
    public $fio;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'kkm_id', 'user_id'], 'integer'],
            [['kkm_name', 'number', 'name_gs', 'sender_key', 'login', 'password', 'user_type'], 'safe'],
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
            ->joinWith(['erpUsers'], false);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'sender_key' => SORT_DESC
                ],
            ],
        ]);

        $sort = $dataProvider->getSort();
        $sort->attributes = array_merge($sort->attributes, [
            'name_gs' => [
                'asc' => ['Logins.[Name]' => SORT_ASC],
                'desc' => ['Logins.[Name]' => SORT_DESC]
            ],
            'number' => [
                'asc' => ['n_kkm.number' => SORT_ASC],
                'desc' => ['n_kkm.number' => SORT_DESC]
            ],
            'kkm_name' => [
                'asc' => ['n_kkm.[name]' => SORT_ASC],
                'desc' => ['n_kkm.[name]' => SORT_DESC]
            ],
            'sender_key' => [
                'asc' => ['n_kkm.sender_key' => SORT_ASC],
                'desc' => ['n_kkm.sender_key' => SORT_DESC]
            ],
        ]);
        $dataProvider->setSort($sort);

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

        $query->andFilterWhere(['like', 'lower(n_kkm_users.login)', mb_strtolower($this->login, 'UTF-8')])
            ->andFilterWhere(['like', 'n_kkm_users.password', $this->password])
            ->andFilterWhere(['like', 'n_kkm_users.user_type', $this->user_type])
            ->andFilterWhere(['like', 'lower(Logins.[Name])', mb_strtolower($this->name_gs, 'UTF-8')])
            ->andFilterWhere(['like', 'n_kkm.number', $this->number])
            ->andFilterWhere(['like', 'n_kkm.[name]', $this->kkm_name])
            ->andFilterWhere(['n_kkm.sender_key' => $this->sender_key]);

        return $dataProvider;
    }
}
