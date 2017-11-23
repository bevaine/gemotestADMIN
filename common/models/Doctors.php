<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "Doctors".
 *
 * @property integer $AID
 * @property integer $Active
 * @property string $Name
 * @property string $LastName
 * @property string $DateLastUpdate
 * @property string $DateIns
 * @property string $CACHE_DocID
 * @property string $Pass
 * @property string $Email
 * @property string $Phone
 * @property string $b2
 * @property string $b3
 * @property string $b4
 * @property string $b8
 * @property string $b9
 * @property string $b10
 * @property string $b11
 * @property string $b12
 * @property string $b13
 * @property string $b14
 * @property string $b15
 * @property string $b16
 * @property string $b18
 * @property string $b19
 * @property string $b20
 * @property string $b21
 * @property string $b22
 * @property integer $InputOrder
 * @property integer $InputOrderRM
 * @property integer $CanRegister
 * @property string $LPU_Name
 * @property string $LPU_Adr
 * @property integer $IsCons
 * @property integer $Is_Cons
 * @property integer $isDel
 * @property string $login
 * @property Logins $logins
 */

class Doctors extends \yii\db\ActiveRecord
{
    /**
     * @return null|object
     */
    public static function getDb()
    {
        return Yii::$app->get('GemoTestDB');
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Doctors';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Active', 'InputOrder', 'InputOrderRM', 'CanRegister', 'IsCons', 'Is_Cons', 'isDel'], 'integer'],
            [['Name', 'LastName', 'CACHE_DocID', 'Pass', 'Email', 'Phone', 'b2', 'b3', 'b4', 'b8', 'b9', 'b10', 'b11', 'b12', 'b13', 'b14', 'b15', 'b16', 'b18', 'b19', 'b20', 'b21', 'b22', 'LPU_Name', 'LPU_Adr', 'login'], 'string'],
            [['DateLastUpdate', 'DateIns'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'AID' => 'Aid',
            'Active' => 'Active',
            'Name' => 'Name',
            'LastName' => 'Last Name',
            'DateLastUpdate' => 'Date Last Update',
            'DateIns' => 'Date Ins',
            'CACHE_DocID' => 'Cache  Doc ID',
            'Pass' => 'Pass',
            'Email' => 'Email',
            'Phone' => 'Phone',
            'b2' => 'B2',
            'b3' => 'B3',
            'b4' => 'B4',
            'b8' => 'B8',
            'b9' => 'B9',
            'b10' => 'B10',
            'b11' => 'B11',
            'b12' => 'B12',
            'b13' => 'B13',
            'b14' => 'B14',
            'b15' => 'B15',
            'b16' => 'B16',
            'b18' => 'B18',
            'b19' => 'B19',
            'b20' => 'B20',
            'b21' => 'B21',
            'b22' => 'B22',
            'InputOrder' => 'Input Order',
            'InputOrderRM' => 'Input Order Rm',
            'CanRegister' => 'Can Register',
            'LPU_Name' => 'Lpu  Name',
            'LPU_Adr' => 'Lpu  Adr',
            'IsCons' => 'Is Cons',
            'Is_Cons' => 'Is  Cons',
            'isDel' => 'Is Del',
            'login' => 'Login',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogins()
    {
        return $this->hasMany(Logins::className(), ['key' => 'CACHE_DocID'])
            ->andOnCondition('[Logins].[UserType] = 5');
    }

     /**
     * @return array
     */
    public static function getDoctorsList()
    {
        $options = [];
        $values = [];

        $query = Doctors::find()
            ->joinWith(['logins'], false, 'FULL JOIN')
            ->select('Doctors.*,Logins.aid as GsID, Logins.UserType')
            ->where(['Is_Cons' => 4]);

        $dataProvider = new SqlDataProvider([
            'sql' => $query->createCommand()->getRawSql(),
            'db' => 'GemoTestDB',
            'sort' => [
                'attributes' => [
                    'DateIns' =>[
                        'default' => SORT_DESC,
                        'asc' => ['DateIns' => SORT_ASC],
                        'desc' => ['DateIns' => SORT_DESC],
                    ],
                ],
            ],
            'pagination' => false,
        ]);

        /** @var Doctors $model */
        foreach ($dataProvider->getModels() as $model) {
            $FIO = '';
            $disable = false;
            $color = '#2da83d';
            if (!empty($model['LastName'])) $FIO .= $model['LastName'];
            if (!empty($model['Name'])) $FIO .= " ".$model['Name'];
            if (!empty($model['GsID'])) {
                $disable = true;
                $FIO .= " - уже добавлен";
                $color =  '#d4282e';
            }
            $values[$model['CACHE_DocID']] = $FIO;
            $options[$model['CACHE_DocID']] = [
                'disabled'=>$disable,
                'label' => $FIO,
                'style'=>'color:'.$color.';font-weight:bold'
            ];
        }

        if (!empty($options) && !empty($values)) {
            krsort($options);
            krsort($values);
            return [
                'arrOptions' =>
                    ['options' => $options],
                'arrValues' =>
                    $values
            ];
        } else return null;
    }
}