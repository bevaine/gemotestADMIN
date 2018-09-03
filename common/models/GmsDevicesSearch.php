<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\GmsDevices;

/**
 * GmsDevicesSearch represents the model behind the search form about `common\models\GmsDevices`.
 * @property $created_at_from
 * @property $created_at_to
 * @property $last_active_at_from
 * @property $last_active_at_to
 * @property $sender_name
 * @property $current_pls_name
 */
class GmsDevicesSearch extends GmsDevices
{
    public $created_at_from;
    public $created_at_to;
    public $last_active_at_from;
    public $last_active_at_to;
    public $sender_name;
    public $current_pls_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'region_id', 'auth_status', 'current_pls_id'], 'integer'],
            [['sender_name', 'created_at', 'created_at_from', 'created_at_to', 'last_active_at_from', 'last_active_at_to', 'current_pls_name', 'sender_id', 'name', 'device', 'timezone'], 'safe'],
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
     * @param $params
     * @param null $param
     * @return ActiveDataProvider
     */
    public function search($params, $param = null)
    {
        $query = GmsDevices::find();

        $query->joinWith('senderModel')
            ->joinWith('playListOutModel');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'last_active_at' => SORT_DESC,
                ]
            ],
        ]);

        $sort = $dataProvider->getSort();
        $sort->attributes = array_merge($sort->attributes, [
            'sender_name' => [
                'asc' => ['gms_senders.sender_name' => SORT_ASC],
                'desc' => ['gms_senders.sender_name' => SORT_DESC]
            ],
            'current_pls_name' => [
                'asc' => ['gms_playlist_out.name' => SORT_ASC],
                'desc' => ['gms_playlist_out.name' => SORT_DESC]
            ],
        ]);
        $dataProvider->setSort($sort);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'gms_devices.region_id' => $this->region_id,
            'auth_status' => $this->auth_status,
            'timezone' => $this->timezone,
            'gms_senders.sender_key' => $this->sender_id,
        ]);

        if ($param == 'auth') {
            $query->andWhere([
                'auth_status' => 1
            ]);
        } else {
            $query->andWhere([
                'OR',
                ['!=', 'auth_status', 1],
                ['auth_status' => null]
            ]);
        }

        $query->andFilterWhere(['like', 'LOWER(gms_devices.name)', strtolower($this->name)])
            ->andFilterWhere(['like', 'LOWER(device)', strtolower($this->device)])
            ->andFilterWhere(['like', 'LOWER(gms_playlist_out.name)', strtolower($this->current_pls_name)]);

        if ($this->created_at_from) {
            $query->andFilterWhere(['>=', 'created_at', date('Y-m-d 00:00:00', strtotime($this->created_at_from))]);
        }
        if ($this->created_at_to) {
            $query->andFilterWhere(['<=', 'created_at', date('Y-m-d 23:59:59', strtotime($this->created_at_to))]);
        }

        if ($this->last_active_at_from) {
            $query->andFilterWhere(['>=', 'last_active_at', date('Y-m-d 00:00:00', strtotime($this->last_active_at_from))]);
        }
        if ($this->last_active_at_to) {
            $query->andFilterWhere(['<=', 'last_active_at', date('Y-m-d 23:59:59', strtotime($this->last_active_at_to))]);
        }

        return $dataProvider;
    }
}
