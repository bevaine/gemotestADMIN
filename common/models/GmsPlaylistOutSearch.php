<?php

namespace common\models;

use common\components\helpers\FunctionsHelper;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\GmsPlaylistOut;

/**
 * GmsPlaylistOutSearch represents the model behind the search form about `common\models\GmsPlaylistOut`.
 * @property string $device_name
 * @property string $sender_name
 * @property string $date_start_val
 * @property string $date_end_val
 */

class GmsPlaylistOutSearch extends GmsPlaylistOut
{
    public $device_name;
    public $sender_name;
    public $date_start_val;
    public $date_end_val;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'device_id', 'is_monday', 'is_tuesday', 'is_wednesday', 'is_thursday', 'is_friday', 'is_saturday', 'is_sunday', 'date_start', 'date_end', 'sender_id', 'region_id', 'active', 'group_id'], 'integer'],
            [['date_start_val', 'date_end_val', 'device_name', 'sender_name', 'name', 'jsonPlaylist', 'time_start', 'time_end'], 'safe'],
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
        $query = GmsPlaylistOut::find()
            ->joinWith("deviceModel")
            ->joinWith("senderModel");

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'update_at' => SORT_DESC
                ],
            ],
        ]);

        $sort = $dataProvider->getSort();
        $sort->attributes = array_merge($sort->attributes, [
            'sender_name' => [
                'asc' => ['gms_senders.sender_name' => SORT_ASC],
                'desc' => ['gms_senders.sender_name' => SORT_DESC]
            ],
            'device_name' => [
                'asc' => ['gms_devices.device' => SORT_ASC],
                'desc' => ['gms_devices.device' => SORT_DESC]
            ],
            'date_start_val' => [
                'asc' => ['date_start' => SORT_ASC],
                'desc' => ['date_start' => SORT_DESC]
            ],
            'date_end_val' => [
                'asc' => ['date_end' => SORT_ASC],
                'desc' => ['date_end' => SORT_DESC]
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
            'gms_playlist_out.id' => $this->id,
            'gms_playlist_out.region_id' => $this->region_id,
            'gms_playlist_out.active' => $this->active,
            'gms_playlist_out.group_id' => $this->group_id,
        ]);

        $query->andFilterWhere(['like', 'LOWER(gms_playlist_out.name)', strtolower($this->name)])
            ->andFilterWhere(['like', 'LOWER(gms_devices.name)', strtolower($this->device_name)])
            ->andFilterWhere(['like', 'LOWER(gms_senders.sender_name)', strtolower($this->sender_name)]);

        if (!empty($this->time_start)) {
            $query->andFilterWhere([
                '>=',
                'gms_playlist_out.time_start',
                FunctionsHelper::getTimeDate(strtotime($this->time_start))
            ]);
        }

        if (!empty($this->time_end)) {
            $query->andFilterWhere([
                '<=',
                'gms_playlist_out.time_end',
                FunctionsHelper::getTimeDate(strtotime($this->time_end))
            ]);
        }

        if ($this->date_start_val) {
            $query->andFilterWhere([
                '>=',
                'gms_playlist_out.date_start',
                FunctionsHelper::getTimeStart(strtotime($this->date_start_val))
            ]);
        }

        if ($this->date_end_val) {
            $query->andFilterWhere([
                '<=',
                'gms_playlist_out.date_end',
                FunctionsHelper::getTimeEnd(strtotime($this->date_end_val))
            ]);
        }

        return $dataProvider;
    }
}
