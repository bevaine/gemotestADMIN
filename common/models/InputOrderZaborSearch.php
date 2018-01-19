<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\InputOrderZabor;
use yii\data\SqlDataProvider;

/**
 * InputOrderZaborSearch represents the model behind the search form about `common\models\InputOrderZabor`.
 * @property $date_from
 * @property $date_to
 * @property BranchStaff $branchStaffFIO
 * @property $last_name
 * @property $first_name
 * @property $middle_name
 */

class InputOrderZaborSearch extends InputOrderZabor
{
    public $date_from;
    public $date_to;
    public $last_name;
    public $first_name;
    public $middle_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['aid'], 'integer'],
            [['last_name', 'first_name', 'middle_name', 'OrderID', 'IsslCode', 'MSZabor', 'DateIns', 'date_from', 'date_to'], 'safe'],
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

//        $subQuery = BranchStaff::find()
//            ->distinct()
//            ->select(['guid', 'first_name', 'last_name', 'middle_name'])
//            ->where(['is not', 'guid', null]);
//
//        $query = InputOrderZabor::find()
//            ->select('InputOrderIsklIsslMSZabor.*, vf.*')
//            ->leftJoin(
//                ['vf' =>
//                    '('.$subQuery
//                        ->prepare(Yii::$app->db->queryBuilder)
//                        ->createCommand()
//                        ->rawSql.')'
//                    ],
//                "MSZabor=CAST(vf.[guid] AS varchar(100))"
//            );

        $query = InputOrderZabor::find()
            ->joinWith('hrPublicEmployee')
            ->select('InputOrderIsklIsslMSZabor.*, hr_public_employee.*');

        $query->andFilterWhere([
            'aid' => $this->aid,
        ]);

        $query->andFilterWhere(['like', 'OrderID', $this->OrderID])
            ->andFilterWhere(['like', 'IsslCode', $this->IsslCode])
            ->andFilterWhere(['like', 'MSZabor', $this->MSZabor])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'middle_name', $this->middle_name])
            ->andFilterWhere(['like', 'first_name', $this->first_name]);

        if ($this->date_from) {
            $query->andFilterWhere(['>=', 'DateIns', date('Y-m-d 00:00:00', strtotime($this->date_from))]);
        }
        if ($this->date_to) {
            $query->andFilterWhere(['<=', 'DateIns', date('Y-m-d 23:59:59', strtotime($this->date_to))]);
        }

        $dataProvider = new SqlDataProvider([
            'sql' => $query->createCommand()->getRawSql(),
            'db' => 'GemoTestDB',
            'sort' => [
                'attributes' => [
                    'aid' => ['default' => SORT_ASC],
                    'OrderID' => ['default' => SORT_ASC],
                    'IsslCode' => ['default' => SORT_ASC],
                    'MSZabor' => ['default' => SORT_ASC],
                    'DateIns' => ['default' => SORT_ASC],
                    'last_name' => ['default' => SORT_ASC],
                    'first_name' => ['default' => SORT_ASC],
                    'middle_name' => ['default' => SORT_ASC],
                ],
            ],
        ]);

        return $dataProvider;
    }
}
