<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\GmsVideoHistory;
use yii\data\SqlDataProvider;

/**
 * GmsVideoHistorySearch represents the model behind the search form about `common\models\GmsVideoHistory`.
 * @property string $pls_name
 * @property integer $region_id
 * @property string $sender_name
 * @property string $video_name
 */
class GmsVideoHistorySearch extends GmsVideoHistory
{
    /**
     * @inheritdoc
     */
    public $region_id;
    public $sender_name;
    public $pls_name;
    public $date_from;
    public $date_to;
    public $sender_id;
    public $video_name;

    public function rules()
    {
        return [
            [['id', 'pls_id', 'video_key', 'region_id', 'type'], 'integer'],
            [['date_from', 'video_name', 'date_to', 'sender_name', 'pls_name', 'device_id', 'created_at'], 'safe'],
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
     * @return SqlDataProvider
     */
    public function search($params)
    {
        $this->load($params);

        $query = GmsVideoHistory::find()
            ->from('gms_video_history t')
            ->joinWith('regionModel t1')
            ->joinWith('senderModel t2')
            ->joinWith('playListOutModel t3')
            ->joinWith('videoModel t4')
            ->joinWith('deviceModel t5')
            ->select("t.*, t.id hv_id, t.created_at start_at, t.id vh_id, t.type type_pls, t1.*, t2.*, t3.name pls_name, t4.*, t4.name video_name, t4.id video_key, t5.id dev_id");

        // grid filtering conditions
        $query->andFilterWhere(['t.id' => $this->id])
            ->andFilterWhere(['t.type' => $this->type]);

        $query->andFilterWhere(['like', 't.created_at', $this->created_at])
              ->andFilterWhere(['like', 't.device_id', $this->device_id]);


        $query->andFilterWhere(['t1.id' => $this->region_id])
            ->andFilterWhere(['like', 't2.sender_name', $this->sender_name])
            ->andFilterWhere(['like', 'LOWER(t3.name)', strtolower($this->pls_name)])
            ->andFilterWhere(['like', 'LOWER(t4.name)', strtolower($this->video_name)]);

        if ($this->date_from) {
            $query->andFilterWhere(['>=', 't.created_at', date('Y-m-d 00:00:00 P', strtotime($this->date_from))]);
            $query->andFilterWhere(['>=', 't.last_at', date('Y-m-d 00:00:00 P', strtotime($this->date_from))]);
        }

        if ($this->date_to) {
            $query->andFilterWhere(['<=', 't.created_at', date('Y-m-d 23:59:59 P', strtotime($this->date_to))]);
            $query->andFilterWhere(['<=', 't.last_at', date('Y-m-d 23:59:59 P', strtotime($this->date_to))]);
        }

        $dataProvider = new SqlDataProvider([
            'sql' => $query->createCommand()->rawSql,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ],
        ]);

        $sort = $dataProvider->getSort();
        $sort->attributes = array_merge($sort->attributes, [
            'id' => [
                'asc' => ['vh_id' => SORT_ASC],
                'desc' => ['vh_id' => SORT_DESC],
            ],
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
            'type' => [
                'asc' => ['t.type' => SORT_ASC],
                'desc' => ['t.type' => SORT_DESC]
            ],
            'pls_name' => [
                'asc' => ['pls_name' => SORT_ASC],
                'desc' => ['pls_name' => SORT_DESC]
            ],
            'device_id' => [
                'asc' => ['t.device_id' => SORT_ASC],
                'desc' => ['t.device_id' => SORT_DESC]
            ],
            'start_at' => [
                'asc' => ['t.created_at' => SORT_ASC],
                'desc' => ['t.created_at' => SORT_DESC]
            ],
            'last_at' => [
                'asc' => ['t.last_at' => SORT_ASC],
                'desc' => ['t.last_at' => SORT_DESC]
            ],
            'date_at' => [
                'asc' => ['t.created_at' => SORT_ASC],
                'desc' => ['t.created_at' => SORT_DESC]
            ],
            'video_name' => [
                'asc' => ['t4.name' => SORT_ASC],
                'desc' => ['t4.name' => SORT_DESC]
            ],
        ]);

        $dataProvider->setSort($sort);

        return $dataProvider;
    }
}
