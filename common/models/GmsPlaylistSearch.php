<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\GmsPlaylist;

/**
 * GmsPlaylistSearch represents the model behind the search form about `common\models\GmsPlaylist`.
 * @property string $sender_name
 * @property string $group_name
 * @property string $device_name
 * @property string $created_at_from
 * @property string $created_at_to
 */
class GmsPlaylistSearch extends GmsPlaylist
{
    public $sender_name;
    public $group_name;
    public $device_name;
    public $created_at_from;
    public $created_at_to;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'region', 'sender_id'], 'integer'],
            [['name', 'file', 'sender_name', 'group_name', 'device_name', 'created_at_from', 'created_at_to'], 'safe'],
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
        $query = GmsPlaylist::find()
            ->joinWith('senderModel')
            ->joinWith('groupDevicesModel')
            ->joinWith('deviceModel');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $sort = $dataProvider->getSort();
        $sort->attributes = array_merge($sort->attributes, [
            'sender_name' => [
                'asc' => ['gms_senders.sender_name' => SORT_ASC],
                'desc' => ['gms_senders.sender_name' => SORT_DESC]
            ],
            'group_name' => [
                'asc' => ['gms_group_devices.group_name' => SORT_ASC],
                'desc' => ['gms_group_devices.group_name' => SORT_DESC]
            ],
            'device_name' => [
                'asc' => ['gms_devices.device' => SORT_ASC],
                'desc' => ['gms_devices.device' => SORT_DESC]
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
            'type' => $this->type,
            'region' => $this->region,
        ]);

        $query->andFilterWhere(['like', 'LOWER(gms_playlist.name)', strtolower($this->name)])
            ->andFilterWhere(['like', 'LOWER(gms_devices.name)', strtolower($this->device_name)])
            ->andFilterWhere(['like', 'LOWER(gms_senders.sender_name)', strtolower($this->sender_name)])
            ->andFilterWhere(['like', 'LOWER(gms_group_devices.group_name)', strtolower($this->group_name)]);

        if ($this->created_at_from) {
            $query->andFilterWhere([
                '>=',
                'gms_playlist.created_at',
                GmsPlaylistOut::getTimeStart(strtotime($this->created_at_from))
            ]);
        }

        if ($this->created_at_to) {
            $query->andFilterWhere([
                '<=',
                'gms_playlist.created_at',
                GmsPlaylistOut::getTimeEnd(strtotime($this->created_at_to))
            ]);
        }

        //print_r($query);
        return $dataProvider;
    }
}
