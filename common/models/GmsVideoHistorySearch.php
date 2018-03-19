<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\GmsVideoHistory;

/**
 * GmsVideoHistorySearch represents the model behind the search form about `common\models\GmsVideoHistory`.
 * @property string $pls_name;
 * @property integer $region_id;
 * @property string $sender_name;
 */
class GmsVideoHistorySearch extends GmsVideoHistory
{
    /**
     * @inheritdoc
     */
    public $region_id;
    public $sender_name;
    public $pls_name;

    public function rules()
    {
        return [
            [['id', 'pls_id', 'video_key', 'region_id'], 'integer'],
            [['sender_name', 'pls_name', 'device_id', 'created_at', 'last_at'], 'safe'],
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
        $query = GmsVideoHistory::find()
            ->from('gms_video_history t')
            ->joinWith('regionModel t1')
            ->joinWith('senderModel t2')
            ->joinWith('deviceModel t3')
            ->joinWith('playListOutModel t4')
            ->select('t.*, t1.*, t2.*, t3.*, t4.name, t4.id');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $sort = $dataProvider->getSort();
        $sort->attributes = array_merge($sort->attributes, [
            'region_id' => [
                'asc' => ['t1.region_name' => SORT_ASC],
                'desc' => ['t1.region_name' => SORT_DESC]
            ],
            'sender_name' => [
                'asc' => ['t2.sender_name' => SORT_ASC],
                'desc' => ['t2.sender_name' => SORT_DESC]
            ],
            'device_name' => [
                'asc' => ['t3.device' => SORT_ASC],
                'desc' => ['t3.device' => SORT_DESC]
            ],
            'pls_name' => [
                'asc' => ['t4.name' => SORT_ASC],
                'desc' => ['t4.name' => SORT_DESC]
            ],

            'created_at' => [
                'asc' => ['t.created_at' => SORT_ASC],
                'desc' => ['t.created_at' => SORT_DESC]
            ],
            'last_at' => [
                'asc' => ['t.last_at' => SORT_ASC],
                'desc' => ['t.last_at' => SORT_DESC]
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
            'video_key' => $this->video_key,
        ]);

        $query->andFilterWhere(['like', 't.created_at', $this->created_at]);

        $query->andFilterWhere(['t1.id' => $this->region_id])
            ->andFilterWhere(['like', 't2.sender1_name', $this->sender_name])
            ->andFilterWhere(['like', 't3.device', $this->device_id])
            ->andFilterWhere(['like', 'LOWER(t4.name)', strtolower($this->pls_name)]);

        return $dataProvider;
    }
}
