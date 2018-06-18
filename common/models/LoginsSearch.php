<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * LoginsSearch represents the model behind the search form about `common\models\Logins`.
 * @property string $last_name
 * @property string $first_name
 * @property string $middle_name
 * @property string $ad_login
 * @property string $AD_position
*/

class LoginsSearch extends Logins
{
    public $ad_login;
    public $DateBlocked;
    public $AD_position;
    public $last_name;
    public $first_name;
    public $middle_name;

        /**
     * @return null|object
     */
    public static function getDb() {
        return Yii::$app->get('GemoTestDB');
    }

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
     * @param $params
     * @return SqlDataProvider
     */
    public function search($params)
    {
        $params['LoginsSearch'] = array_map(
            'trim',
            $params['LoginsSearch']
        );
        $this->load($params);

        $query = Logins::find()
            ->joinWith(['adUsersMany'], false)
            ->joinWith(['adUserAccountsMany'], false)
            ->joinWith(['directorFlo'], false)
            ->joinWith(['directorFloSender'], false)
            ->select('Logins.*, 
                n_ad_Users.*,
                DirectorFlo.id as directorID, 
                DirectorFloSender.sender_key as directorKey,
                n_ad_Useraccounts.ad_login as loginAD, 
                n_ad_Useraccounts.ad_pass as passAD'
            );

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
            //'Key' => $this->Key,
        ]);

        if (!empty($this->Key)) {
            $query->andWhere('([Logins].[Key] = :key) OR ([DirectorFloSender].[sender_key] = :key)', [':key' => $this->Key]);
        }

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

        $query->andFilterWhere(['like', '[Logins].[Login]', $this->Login])
            ->andFilterWhere(['like', 'Name', $this->Name])
            ->andFilterWhere(['like', 'Email', $this->Email])
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

        $dataProvider = new SqlDataProvider([
            'sql' => $query->createCommand()->getRawSql(),
            'db' => 'GemoTestDB',
            'sort' => [
                'attributes' => [
                    'aid' => ['default' => SORT_ASC],
                    'Key' => ['default' => SORT_ASC],
                    'UserType' => ['default' => SORT_ASC],
                    'Name' => ['default' => SORT_ASC],
                    'Login' => ['default' => SORT_ASC],
                    'last_name' => ['default' => SORT_ASC],
                    'first_name' => ['default' => SORT_ASC],
                    'middle_name' => ['default' => SORT_ASC],
                    'AD_position' => ['default' => SORT_ASC],
                    'ad_login' => ['default' => SORT_ASC],
                ],
            ],
        ]);

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
