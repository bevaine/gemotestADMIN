<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Franchazy;

/**
 * FranchazySearch represents the model behind the search form about `common\models\Franchazy`.
 */
class FranchazySearch extends Franchazy
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['AID', 'Active', 'IsOperator', 'IsAdmin', 'LogoType', 'LogoWidth', 'TextPaddingLeft', 'OpenExcel', 'EngVersion', 'InputOrder', 'PriceID', 'CanRegister', 'InputOrderRM'], 'integer'],
            [['Login', 'Pass', 'Name', 'Email', 'Key', 'BlankText', 'BlankName', 'Logo', 'LogoText', 'LogoText2', 'OpenActive', 'ReestrUslug', 'LCN', 'Li_cOrg'], 'safe'],
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
        $query = Franchazy::find();

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
            'AID' => $this->AID,
            'Active' => $this->Active,
            'IsOperator' => $this->IsOperator,
            'IsAdmin' => $this->IsAdmin,
            'LogoType' => $this->LogoType,
            'LogoWidth' => $this->LogoWidth,
            'TextPaddingLeft' => $this->TextPaddingLeft,
            'OpenExcel' => $this->OpenExcel,
            'EngVersion' => $this->EngVersion,
            'InputOrder' => $this->InputOrder,
            'PriceID' => $this->PriceID,
            'CanRegister' => $this->CanRegister,
            'InputOrderRM' => $this->InputOrderRM,
            'OpenActive' => $this->OpenActive,
        ]);

        $query->andFilterWhere(['like', 'Login', $this->Login])
            ->andFilterWhere(['like', 'Pass', $this->Pass])
            ->andFilterWhere(['like', 'Name', $this->Name])
            ->andFilterWhere(['like', 'Email', $this->Email])
            ->andFilterWhere(['like', 'Key', $this->Key])
            ->andFilterWhere(['like', 'BlankText', $this->BlankText])
            ->andFilterWhere(['like', 'BlankName', $this->BlankName])
            ->andFilterWhere(['like', 'Logo', $this->Logo])
            ->andFilterWhere(['like', 'LogoText', $this->LogoText])
            ->andFilterWhere(['like', 'LogoText2', $this->LogoText2])
            ->andFilterWhere(['like', 'ReestrUslug', $this->ReestrUslug])
            ->andFilterWhere(['like', 'LCN', $this->LCN])
            ->andFilterWhere(['like', 'Li_cOrg', $this->Li_cOrg]);

        return $dataProvider;
    }
}
