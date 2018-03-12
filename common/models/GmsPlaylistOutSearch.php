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
            [['id', 'device_id', 'is_monday', 'is_tuesday', 'is_wednesday', 'is_thursday', 'is_friday', 'is_saturday', 'is_sunday', 'time_start', 'time_end', 'date_start', 'date_end', 'sender_id', 'region_id', 'created_at', 'active'], 'integer'],
            [['name', 'jsonPlaylist'], 'safe'],
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
            'is_monday' => $this->is_monday,
            'is_tuesday' => $this->is_tuesday,
            'is_wednesday' => $this->is_wednesday,
            'is_thursday' => $this->is_thursday,
            'is_friday' => $this->is_friday,
            'is_saturday' => $this->is_saturday,
            'is_sunday' => $this->is_sunday,
            'time_start' => $this->time_start,
            'time_end' => $this->time_end,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
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
