<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\GmsPlaylistOut;

/**
 * GmsPlaylistOutSearch represents the model behind the search form about `common\models\GmsPlaylistOut`.
 */
class GmsPlaylistOutSearch extends GmsPlaylistOut
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'device_id', 'date_play', 'start_time_play', 'end_time_play', 'isMonday', 'isTuesday', 'isWednesday', 'isThursday', 'isFriday', 'isSaturday', 'isSunday', 'timeStart', 'timeEnd', 'dateStart', 'dateEnd', 'sender_id', 'region_id', 'created_at', 'active'], 'integer'],
            [['file', 'jsonPlaylist'], 'safe'],
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
        $query = GmsPlaylistOut::find();

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
            'device_id' => $this->device_id,
            'date_play' => $this->date_play,
            'start_time_play' => $this->start_time_play,
            'end_time_play' => $this->end_time_play,
            'isMonday' => $this->isMonday,
            'isTuesday' => $this->isTuesday,
            'isWednesday' => $this->isWednesday,
            'isThursday' => $this->isThursday,
            'isFriday' => $this->isFriday,
            'isSaturday' => $this->isSaturday,
            'isSunday' => $this->isSunday,
            'timeStart' => $this->timeStart,
            'timeEnd' => $this->timeEnd,
            'dateStart' => $this->dateStart,
            'dateEnd' => $this->dateEnd,
            'sender_id' => $this->sender_id,
            'region_id' => $this->region_id,
            'created_at' => $this->created_at,
            'active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'file', $this->file])
            ->andFilterWhere(['like', 'jsonPlaylist', $this->jsonPlaylist]);

        return $dataProvider;
    }
}
