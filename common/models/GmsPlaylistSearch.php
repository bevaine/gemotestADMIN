<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\GmsPlaylist;

/**
 * GmsPlaylistSearch represents the model behind the search form about `common\models\GmsPlaylist`.
 * @property string $sender_name
 */
class GmsPlaylistSearch extends GmsPlaylist
{
    public $sender_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'region', 'created_at', 'updated_at', 'sender_id'], 'integer'],
            [['name', 'file', 'sender_name'], 'safe'],
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
        $query = GmsPlaylist::find();
        $query->joinWith('senderModel');



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
            'type' => $this->type,
            'region' => $this->region,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'file', $this->file])
            ->andFilterWhere(['like', 'sender_name', $this->sender_name]);

        //print_r($query);
        return $dataProvider;
    }
}