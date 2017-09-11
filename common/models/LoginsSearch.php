<?php

namespace common\models;

use phpDocumentor\Reflection\Types\Null_;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Logins;
use yii\helpers\ArrayHelper;

/**
 * LoginsSearch represents the model behind the search form about `common\models\Logins`.
 * @property Operators $operators
 * @property NAdUsers $adUsers
*/

class LoginsSearch extends Logins
{
    public $last_name;
    public $first_name;
    public $middle_name;
    public $AD_position;
    public $ad_login;
    public $DateBlocked;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['aid', 'IsOperator', 'IsAdmin', 'OpenExcel', 'EngVersion', 'IsDoctor', 'UserType', 'InputOrder', 'PriceID', 'CanRegister', 'InputOrderRM', 'OrderEdit', 'MedReg', 'goscontract', 'FizType', 'clientmen', 'mto', 'mto_editor', 'show_preanalytic', 'parentAid', 'GarantLetter'], 'integer'],
            [['ad_login','last_name', 'first_name', 'middle_name', 'AD_position', 'Login', 'Pass', 'Name', 'Email', 'Key', 'Logo', 'LogoText', 'LogoText2', 'LogoType', 'LogoWidth', 'TextPaddingLeft', 'tbl', 'CACHE_Login', 'LastLogin', 'DateBeg', 'DateEnd', 'block_register', 'last_update_password', 'role'], 'safe'],
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
        //$query = Logins::find()
        $query = Logins::find()
            //->with('adUsersNew');
            //->select('n_ad_Users.*')
            ->join('FULL JOIN', 'n_ad_Users','[Logins].[aid] = [n_ad_Users].[gs_id] AND [Logins].[UserType] = [n_ad_Users].[gs_usertype]')
            ->join('LEFT JOIN', 'n_ad_Useraccounts', '[n_ad_Users].[gs_key] = [n_ad_Useraccounts].[gs_id] AND [n_ad_Users].[last_name] = [n_ad_Useraccounts].[last_name] AND [n_ad_Users].[first_name] = [n_ad_Useraccounts].[first_name] AND [n_ad_Users].[middle_name] = [n_ad_Useraccounts].[middle_name]');

        //$query = Logins::find()->with('n_ad_Users','n_ad_Useraccounts');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        //$query->where(['IS NOT', '[Logins].[aid]', null]);

        // grid filtering conditions
        $query->andFilterWhere([
            'aid' => $this->aid,
            'IsOperator' => $this->IsOperator,
            'IsAdmin' => $this->IsAdmin,
            'OpenExcel' => $this->OpenExcel,
            'EngVersion' => $this->EngVersion,
            'IsDoctor' => $this->IsDoctor,
            'UserType' => $this->UserType,
            'InputOrder' => $this->InputOrder,
            'PriceID' => $this->PriceID,
            'CanRegister' => $this->CanRegister,
            'InputOrderRM' => $this->InputOrderRM,
            'OrderEdit' => $this->OrderEdit,
            'MedReg' => $this->MedReg,
            'goscontract' => $this->goscontract,
            'FizType' => $this->FizType,
            'clientmen' => $this->clientmen,
            'mto' => $this->mto,
            'mto_editor' => $this->mto_editor,
            'LastLogin' => $this->LastLogin,
            'DateBeg' => $this->DateBeg,
            'last_update_password' => $this->last_update_password,
            'show_preanalytic' => $this->show_preanalytic,
            'parentAid' => $this->parentAid,
            'GarantLetter' => $this->GarantLetter,
        ]);

        if ($this->DateEnd == 1) {
            $query->andWhere(['OR', ['>', 'DateEnd', date("Y-m-d G:i:s:000", time())], ['DateEnd' => NULL]]);
        } elseif ($this->DateEnd == 2) {
            $query->andFilterWhere(['<=', 'DateEnd', date("Y-m-d G:i:s:000", time())]);
        }

        if ($this->block_register == 1) {
            $query->andWhere(['OR', ['>', 'block_register', date("Y-m-d G:i:s:000", time())], ['block_register' => NULL]]);
        } elseif ($this->block_register == 2) {
            $query->andFilterWhere(['<=', 'block_register', date("Y-m-d G:i:s:000", time())]);
        }

        $query->andFilterWhere(['like', 'Login', $this->Login])
            ->andFilterWhere(['like', 'Pass', $this->Pass])
            ->andFilterWhere(['like', 'Name', $this->Name])
            ->andFilterWhere(['like', 'Email', $this->Email])
            ->andFilterWhere(['like', 'Key', $this->Key])
            ->andFilterWhere(['like', 'Logo', $this->Logo])
            ->andFilterWhere(['like', 'LogoText', $this->LogoText])
            ->andFilterWhere(['like', 'LogoText2', $this->LogoText2])
            ->andFilterWhere(['like', 'LogoType', $this->LogoType])
            ->andFilterWhere(['like', 'LogoWidth', $this->LogoWidth])
            ->andFilterWhere(['like', 'TextPaddingLeft', $this->TextPaddingLeft])
            ->andFilterWhere(['like', 'tbl', $this->tbl])
            ->andFilterWhere(['like', 'CACHE_Login', $this->CACHE_Login])
            ->andFilterWhere(['like', 'role', $this->role])
            ->andFilterWhere(['like', 'n_ad_Users.last_name', $this->last_name])
            ->andFilterWhere(['like', 'n_ad_Users.first_name', $this->first_name])
            ->andFilterWhere(['like', 'n_ad_Users.middle_name', $this->middle_name])
            ->andFilterWhere(['like', 'n_ad_Users.AD_position', $this->AD_position])
            ->andFilterWhere(['like', 'n_ad_Useraccounts.ad_login', $this->ad_login]);

        return $dataProvider;
    }

    /**
     * @param bool $id
     * @return array|mixed
     */
    public static function getListName($id = false)
    {
        $modules = [];
        foreach (self::find() as $model) {
            $modules[$model->ID] = $model->Name;
        }
        return $id !== false && isset($modules[$id]) ? ArrayHelper::getValue($modules, $id) : $modules;
    }
}
