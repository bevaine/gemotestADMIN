<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "DirectorFloSender".
 *
 * @property integer $id
 * @property integer $director_id
 * @property string $sender_key
 * @property string $fullName
 * @property DirectorFlo $directorFlo
 * @property Franchazy $floName
 */
class DirectorFloSender extends \yii\db\ActiveRecord
{
    /**
     * @return null|object
     */
    public static function getDb() {
        return Yii::$app->get('GemoTestDB');
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'DirectorFloSender';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['director_id', 'sender_key'], 'required'],
            [['director_id'], 'integer'],
            [['sender_key'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'director_id' => 'Director ID',
            'sender_key' => 'Sender Key',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirectorFlo()
    {
        return $this->hasOne(DirectorFlo::class, ['id' => 'director_id']);
    }

    /**
     * @param null $key
     * @return null
     */
    public static function checkGD($key = null)
    {
        $out = null;
        if (!is_null($key)) {
            $findModel = self::findOne([
                'sender_key' => $key
            ]);
            if (isset($findModel->fullName)) {
                $out['gd'] = $findModel->fullName;
            }
        }
        return !empty($out) ? $out : false;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->directorFlo
            ? $this->directorFlo->last_name
            ." ".$this->directorFlo->first_name
            ." ".$this->directorFlo->middle_name
            : null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFloName()
    {
        return $this->hasOne(Franchazy::class, ['Key' => 'sender_key']);
    }

    /**
     * @return array
     */
    public static function getGdFloList()
    {
        $options = [];
        $values = [];

        $object = Logins::find()
            ->joinWith('directorFlo')
            ->where(['UserType' => '8'])
            ->orderBy(['Name' => 'asc'])
            ->all();

        /** @var Logins $model */
        foreach ($object as $model)
        {
            $disable = false;
            $style = 'color:#02723f';
            $FIO = $model->Name;
            if (!empty($model->directorFlo)) {
                $disable = true;
                $FIO .= ' - уже назначен: ' . $model->directorFlo->fullName;
                $style =  'color:#ec1c24;font-weight:bold';
            }
            $values[$model->Key] = $FIO;
            $options[$model->Key] = [
                'disabled' => $disable,
                'label' => $FIO,
                'style' => $style
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
