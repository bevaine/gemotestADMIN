<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\DoctorSpec;

/**
 * DoctorSpecSearch represents the model behind the search form about `common\models\DoctorSpec`.
 */
class DoctorSpecSearch extends DoctorSpec
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['AID', 'SpetialisationID', 'Active', 'GroupID'], 'integer'],
            [['Name', 'LastName', 'Fkey'], 'safe'],
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
        $query = DoctorSpec::find()
            ->joinWith('spec');

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

        $query->select([
            'Name',
            'LastName',
            'GroupID',
            'SpetialisationID'
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'AID' => $this->AID,
            'SpetialisationID' => $this->SpetialisationID,
            'Active' => $this->Active,
            'GroupID' => $this->GroupID,
        ]);

        $query->andFilterWhere(['like', 'Name', $this->Name])
            ->andFilterWhere(['like', 'LastName', $this->LastName])
            ->andFilterWhere(['like', 'Fkey', $this->Fkey]);

        $query->groupBy([
            'Name',
            'LastName',
            'GroupID',
            'SpetialisationID'
        ]);

        return $dataProvider;
    }
}
