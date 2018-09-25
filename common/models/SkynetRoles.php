<?php

namespace common\models;

use Yii;
use common\models\Permissions;
use yii\db\Exception;
use yii\log\Logger;

/**
 * This is the model class for table "skynet_roles".
 *
 * @property int $id
 * @property string $name
 * @property string $structure_json
 * @property string $tables_json
 * @property string $info_json
 * @property array $permission
 * @property integer $type
 */
class SkynetRoles extends \yii\db\ActiveRecord
{
    public $permission;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'skynet_roles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'structure_json', 'tables_json', 'info_json'], 'string'],
            [['type'], 'integer'],
            ['permission', 'each', 'rule' => ['string']],
            ['name', 'required', 'when' => function ($model) {
                return $model->type == 7;
                }, 'whenClient' => 'function (attribute, value) {
                    return $("#skynet-type option:selected").val() === 7;
                }', 'message' => 'Название роли обязательно к заполнению']
            ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название роли',
            'structure_json' => 'Structure Json',
            'tables_json' => 'Tables Json',
            'info_json' => 'Info Json',
            'type' => 'Тип учетной записи'
        ];
    }

    /**
     * @param $permissions
     * @return bool
     */
    public function addPermissions($permissions)
    {
        $arrRows = [];
        $rowInsert = [];

        if (!isset($permissions)
            || !is_array($permissions))
            return false;

        foreach ($permissions as $permission) {
            $rowInsert[] = [$this->id, $permission];
            $arrRows[] = $permission;
        }
        if (empty($arrRows) || empty($rowInsert))
            return false;

        try {
            Permissions::deleteAll(['department' => $this->id]);
            Yii::$app->db->createCommand()->batchInsert(
                Permissions::tableName(),
                ['department', 'permission'],
                $rowInsert
            )->execute();

        } catch (Exception $e) {
            Yii::getLogger()->log([
                'addPermissions->batchInsert'=>$e->getMessage()
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }
        return true;
    }
}
