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
        $field = [
            'aid',
            'OrderID',
            'IsslCode',
            'MSZabor',
            'DateIns',
            'last_name',
            'first_name',
            'middle_name'
        ];

        $query = InputOrderZabor::find()
            ->joinWith('hrPublicEmployee')
            ->select('InputOrderIsklIsslMSZabor.*, last_name, first_name, middle_name')
            ->groupBy($field);

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

        if (!isset($params['sort'])) {
            $params['sort'] = '-DateIns';
        }


        $dataProvider = new SqlDataProvider([
            'sql' => $query->createCommand()->getRawSql(),
            'db' => 'GemoTestDB',
            'sort' => [
                'attributes' => $field
            ],
        ]);

        return $dataProvider;
    }
}
