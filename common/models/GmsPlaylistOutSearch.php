<?php

namespace common\models;

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
            [['id', 'device_id', 'is_monday', 'is_tuesday', 'is_wednesday', 'is_thursday', 'is_friday', 'is_saturday', 'is_sunday', 'date_start', 'date_end', 'sender_id', 'region_id', 'active'], 'integer'],
            [['date_start_val', 'date_end_val', 'device_name', 'sender_name', 'name', 'jsonPlaylist'], 'safe'],
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
                    'created_at' => SORT_DESC
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
            'gms_playlist_out.device_id' => $this->device_id,
            'gms_playlist_out.is_monday' => $this->is_monday,
            'gms_playlist_out.is_tuesday' => $this->is_tuesday,
            'gms_playlist_out.is_wednesday' => $this->is_wednesday,
            'gms_playlist_out.is_thursday' => $this->is_thursday,
            'gms_playlist_out.is_friday' => $this->is_friday,
            'gms_playlist_out.is_saturday' => $this->is_saturday,
            'gms_playlist_out.is_sunday' => $this->is_sunday,
            'gms_playlist_out.time_start' => $this->time_start,
            'gms_playlist_out.time_end' => $this->time_end,
            'gms_playlist_out.date_start' => $this->date_start,
            'gms_playlist_out.date_end' => $this->date_end,
            'gms_playlist_out.sender_id' => $this->sender_id,
            'gms_playlist_out.region_id' => $this->region_id,
            'gms_playlist_out.created_at' => $this->created_at,
            'gms_playlist_out.active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'jsonPlaylist', $this->jsonPlaylist])
            ->andFilterWhere(['like', 'gms_devices.device', $this->device_name])
            ->andFilterWhere(['like', 'gms_senders.sender_name', $this->sender_name]);


        $end_date = mktime(
            date("H", 23),
            date("i", 59),
            date("s", 59),
            date("m", strtotime($this->date_end_val)),
            date("d", strtotime($this->date_end_val)),
            date("Y", strtotime($this->date_end_val))
        );

        if ($this->date_start_val) {
            $query->andFilterWhere(['>=', 'date_start', strtotime($this->date_start_val)]);
        }

        if ($this->date_end_val) {
            $query->andFilterWhere(['<=', 'date_end', $end_date]);
        }

        return $dataProvider;
    }
}
