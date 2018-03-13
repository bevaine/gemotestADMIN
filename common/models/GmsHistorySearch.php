<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\GmsHistory;

/**
 * GmsHistorySearch represents the model behind the search form about `common\models\GmsHistory`.
 * @property string $pls_name
 */

class GmsHistorySearch extends GmsHistory
{
    public $pls_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'pls_id', 'device_id', 'created_at', 'status'], 'integer'],
            [['pls_name','log_text'], 'safe'],
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
        $query = GmsHistory::find()->joinWith('playlistOutModel');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $sort = $dataProvider->getSort();
        $sort->attributes = array_merge($sort->attributes, [
            'pls_name' => [
                'asc' => ['gms_playlist_out.name' => SORT_ASC],
                'desc' => ['gms_playlist_out.name' => SORT_DESC]
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
            'pls_id' => $this->pls_id,
            'device_id' => $this->device_id,
            'created_at' => $this->created_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'gms_playlist_out.name', $this->pls_name]);

        return $dataProvider;
    }
}
