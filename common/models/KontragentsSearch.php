<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Kontragents;

/**
 * KontragentsSearch represents the model behind the search form about `common\models\Kontragents`.
 */
class KontragentsSearch extends Kontragents
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['AID', 'LoginsAID', 'isDelete', 'PayType', 'Type', 'rmGroup', 'inoe', 'cito', 'goscontract', 'flNoDiscCard', 'hide_price', 'lab', 'price_supplier', 'sampling_of_biomaterial', 'use_ext_num'], 'integer'],
            [['Name', 'Key', 'ShortName', 'BlankText', 'BlankName', 'Blanks', 'Li_cOrg', 'LCN', 'ReestrUslug', 'RegionID', 'dt_off_discount', 'dt_off_auto_discount', 'dt_off_discount_card', 'code_1c', 'contract_number', 'contract_name', 'contractor_name', 'contract_date', 'date_update', 'payment', 'ext_num_mask', 'salt'], 'safe'],
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
        $query = Kontragents::find();

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
            'LoginsAID' => $this->LoginsAID,
            'isDelete' => $this->isDelete,
            'PayType' => $this->PayType,
            'Type' => $this->Type,
            'rmGroup' => $this->rmGroup,
            'inoe' => $this->inoe,
            'cito' => $this->cito,
            'goscontract' => $this->goscontract,
            'dt_off_discount' => $this->dt_off_discount,
            'flNoDiscCard' => $this->flNoDiscCard,
            'dt_off_auto_discount' => $this->dt_off_auto_discount,
            'dt_off_discount_card' => $this->dt_off_discount_card,
            'hide_price' => $this->hide_price,
            'lab' => $this->lab,
            'contract_date' => $this->contract_date,
            'date_update' => $this->date_update,
            'price_supplier' => $this->price_supplier,
            'sampling_of_biomaterial' => $this->sampling_of_biomaterial,
            'use_ext_num' => $this->use_ext_num,
        ]);

        $query->andFilterWhere(['like', 'Name', $this->Name])
            ->andFilterWhere(['like', 'Key', $this->Key])
            ->andFilterWhere(['like', 'ShortName', $this->ShortName])
            ->andFilterWhere(['like', 'BlankText', $this->BlankText])
            ->andFilterWhere(['like', 'BlankName', $this->BlankName])
            ->andFilterWhere(['like', 'Blanks', $this->Blanks])
            ->andFilterWhere(['like', 'Li_cOrg', $this->Li_cOrg])
            ->andFilterWhere(['like', 'LCN', $this->LCN])
            ->andFilterWhere(['like', 'ReestrUslug', $this->ReestrUslug])
            ->andFilterWhere(['like', 'RegionID', $this->RegionID])
            ->andFilterWhere(['like', 'code_1c', $this->code_1c])
            ->andFilterWhere(['like', 'contract_number', $this->contract_number])
            ->andFilterWhere(['like', 'contract_name', $this->contract_name])
            ->andFilterWhere(['like', 'contractor_name', $this->contractor_name])
            ->andFilterWhere(['like', 'payment', $this->payment])
            ->andFilterWhere(['like', 'ext_num_mask', $this->ext_num_mask])
            ->andFilterWhere(['like', 'salt', $this->salt]);

        return $dataProvider;
    }
}
