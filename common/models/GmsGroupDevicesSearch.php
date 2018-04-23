<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\GmsGroupDevices;

/**
 * GmsGroupDevicesSearch represents the model behind the search form of `common\models\GmsGroupDevices`.
 */
class GmsGroupDevicesSearch extends GmsGroupDevices
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'device_id', 'group_id'], 'integer'],
            [['group_name'], 'safe'],
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
        $query = GmsGroupDevices::find()
            ->select(['group_name', 'group_id'])
            ->distinct();

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
            'group_id' => $this->group_id,
        ]);

        $query->andFilterWhere(['ilike', 'group_name', $this->group_name]);

        return $dataProvider;
    }
}
