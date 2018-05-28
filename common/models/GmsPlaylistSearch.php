<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\GmsPlaylist;
use yii\data\SqlDataProvider;

/**
 * GmsPlaylistSearch represents the model behind the search form about `common\models\GmsPlaylist`.
 * @property string $sender_name
 * @property string $group_id
 * @property string $device_name
 * @property string $created_at_from
 * @property string $created_at_to
 * @property string $playlist
 */
class GmsPlaylistSearch extends GmsPlaylist
{
    public $sender_name;
    public $group_id;
    public $region_id;
    public $device_name;
    public $created_at_from;
    public $created_at_to;
    public $playlist;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'region_id', 'group_id', 'sender_id'], 'integer'],
            [['name', 'file', 'sender_name', 'device_name', 'created_at_from', 'created_at_to', 'playlist'], 'safe'],
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
     * @param $action
     * @return SqlDataProvider
     */
    public function search($params, $action)
    {
        $this->load($params);

        $query = GmsPlaylist::find();
        if ($action == 'group')
        {
            $query->joinWith('groupDevicesModel')
                ->distinct()
                ->select([
                    'gms_playlist.id',
                    'gms_playlist.type',
                    'gms_playlist.name',
                    'gms_playlist.group_id',
                    'group_name' => 'gms_group_devices.group_name',
                ])
                ->where([
                    'not',
                    ['gms_group_devices.group_name' => null]
                ])
                ->andFilterWhere([
                    'gms_playlist.group_id'=> $this->group_id
                ]);

            $order = [
                'group_id' => [
                    'asc' => ['gms_group_devices.group_name' => SORT_ASC],
                    'desc' => ['gms_group_devices.group_name' => SORT_DESC]
                ],
            ];

        } elseif ($action == 'device')
        {
            $query->joinWith('deviceModel')
                ->distinct()
                ->select([
                    'gms_playlist.id',
                    'gms_playlist.type',
                    'gms_playlist.name',
                    'gms_playlist.device_id',
                    'device_name' => 'gms_devices.name'
                ])
                ->where([
                    'not',
                    ['gms_playlist.device_id' => null]
                ])
                ->andFilterWhere([
                    'like',
                    'LOWER(gms_devices.name)',
                    mb_strtolower($this->device_name)
                ]);

            $order = [
                'device_name' => [
                    'asc' => ['gms_devices.name' => SORT_ASC],
                    'desc' => ['gms_devices.name' => SORT_DESC]
                ],
            ];

        } else {
            $query
                ->joinWith('senderModel')
                ->joinWith('regionModel')
                ->distinct()
                ->select([
                    'gms_playlist.id',
                    'gms_playlist.type',
                    'gms_playlist.name',
                    'gms_playlist.region',
                    'gms_playlist.sender_id',
                    'gms_regions.region_name',
                    'gms_senders.sender_name',
                ])
                ->where([
                    'not',
                    ['gms_regions.region_name' => null]])
                ->orWhere([
                    'not',
                    ['gms_senders.sender_name' => null]])
                ->andFilterWhere([
                    'like',
                    'LOWER(gms_senders.sender_name)',
                    mb_strtolower($this->sender_name)
                ])
                ->andFilterWhere([
                    'gms_playlist.region' => $this->region_id
                ]);

            $order = [
                'region_id' => [
                    'asc' => ['gms_regions.region_name' => SORT_ASC],
                    'desc' => ['gms_regions.region_name' => SORT_DESC]
                ],
                'sender_name' => [
                    'asc' => ['gms_senders.sender_name' => SORT_ASC],
                    'desc' => ['gms_senders.sender_name' => SORT_DESC]
                ],
            ];
        }

        $order = array_merge($order, [
            'type' => [
                'asc' => ['gms_playlist.type' => SORT_ASC],
                'desc' => ['gms_playlist.type' => SORT_DESC]
            ],
            'playlist' => [
                'asc' => ['gms_playlist.name' => SORT_ASC],
                'desc' => ['gms_playlist.name' => SORT_DESC]
            ],
        ]);

        $query->andFilterWhere([
            'like',
            'LOWER(gms_playlist.name)',
            mb_strtolower($this->playlist)
        ]);

        $query->andFilterWhere(['type' => $this->type]);

        $dataProvider = new SqlDataProvider([
            'sql' => $query->createCommand()->getRawSql(),
            'sort' => [
                'defaultOrder' => $order
            ],
        ]);

        $sort = $dataProvider->getSort();
        $sort->attributes = array_merge($sort->attributes, $order);

        $dataProvider->setSort($sort);
        return $dataProvider;
    }
}
