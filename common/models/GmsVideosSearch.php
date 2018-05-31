<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\GmsVideos;
use common\components\helpers\FunctionsHelper;

/**
 * GmsVideosSearch represents the model behind the search form about `common\models\GmsVideos`.
 * @property $created_at_from
 * @property $created_at_to
 */

class GmsVideosSearch extends GmsVideos
{
    public $created_at_from;
    public $created_at_to;
    public $size;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'time', 'created_at'], 'integer'],
            [['name', 'type', 'file', 'created_at_from', 'created_at_to'], 'safe'],
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
        $query = GmsVideos::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC
                ],
            ],
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
            'time' => $this->time,
        ]);

        if ($this->created_at_from) {
            $query->andFilterWhere([
                '>=',
                'created_at',
                FunctionsHelper::getTimeStart(strtotime($this->created_at_from))
            ]);
        }

        if ($this->created_at_to) {
            $query->andFilterWhere([
                '<=',
                'created_at',
                FunctionsHelper::getTimeEnd(strtotime($this->created_at_to))
            ]);
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'file', $this->file]);

        $sort = $dataProvider->getSort();
        $sort->attributes = array_merge($sort->attributes, [
            'size' => [
                'asc' => ['height' => SORT_ASC],
                'desc' => ['height' => SORT_DESC]
            ],
        ]);
        $dataProvider->setSort($sort);

        return $dataProvider;
    }
}
