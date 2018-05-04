<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use common\components\helpers\FunctionsHelper;
use DateTime;
use DateTimeZone;
use yii\base\Exception;
use DateInterval;

/**
 * This is the model class for table "gms_devices".
 *
 * @property integer $id
 * @property string $sender_id
 * @property string $name
 * @property string $device
 * @property string $timezone
 * @property string $created_at
 * @property string $last_active_at
 * @property integer $region_id
 * @property integer $auth_status
 * @property integer $current_pls_id
 * @property GmsRegions $regionModel
 * @property GmsSenders $senderModel
 * @property GmsPlaylistOut $playListOutModel
 * @property GmsGroupDevices $groupDevices
 * @property array $treeDevices
 *
 */

class GmsDevices extends \yii\db\ActiveRecord
{
    CONST SCENARIO_ADD_DEVICE = 'addDevice';
    CONST SCENARIO_EDIT_DEVICE = 'editDevice';

    /**
     * @return array
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_ADD_DEVICE => ['device', 'created_at', 'last_active_at', 'auth_status'],
            self::SCENARIO_EDIT_DEVICE => ['name', 'device', 'created_at', 'last_active_at', 'auth_status', 'sender_id', 'region_id', 'current_pls_id', 'timezone'],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gms_devices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device'], 'required', 'on' => 'addDevice'],
            [['name', 'region_id', 'device', 'timezone'], 'required', 'on' => 'editDevice'],
            [['sender_id', 'region_id', 'auth_status', 'current_pls_id'], 'integer'],
            [['name', 'created_at', 'last_active_at', 'device', 'timezone'], 'string', 'max' => 255],
            ['auth_status', 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sender_id' => 'Отделение',
            'name' => 'Название',
            'device' => 'ID устройства',
            'created_at' => 'Создан',
            'last_active_at' => 'Активность',
            'region_id' => 'Регион',
            'auth_status' => 'Авторизация',
            'current_pls_id' => 'Плейлист',
            'sender_name' => 'Отделение',
            'current_pls_name' => 'Плейлист',
            'timezone' => 'Временная зона'
        ];
    }

    /**
     * @param null $auth
     * @return array|mixed
     */
    public static function getAuthStatus($auth = null) {
        if ($auth == null || $auth != 1) $auth = 0;
        return self::getAuthStatusArray()[$auth];
    }

    /**
     * @return null|string
     */
    public function getAuthGrid()
    {
        if (empty($this->region_id)
            || empty($this->timezone)
            || empty($this->device))
            return null;

        $value = $this->auth_status;
        if (!empty($value)) {
            $img_name = 'on.jpg';
            $title = "Заблокировать";
            $action = "deactivate";
        } else {
            $img_name = 'off.jpg';
            $title = "Авторизировать";
            $action = "activate";
        }
        return Html::a(
            Html::img('/img/'.$img_name),
            Url::to(["/GMS/gms-devices/".$action."/".$this->id]),
            ['title' => $title]
        );
    }

    /**
     * @return null
     */
    public function getTimeZoneGrid()
    {
        if (array_key_exists($this->timezone, FunctionsHelper::getTimeZonesList())) {
            return FunctionsHelper::getTimeZonesList()[$this->timezone];
        } else return null;
    }

    /**
     * @return null|string
     */
    public function getLastActiveGrid()
    {
        if (empty($this->last_active_at))
            return null;

        $value = $this->last_active_at;
        $img_name = 'icon-time3.jpg';

        try {
            $dt2 = new DateTime($value);
            $dt3 = new DateTime($value);
            $tz = $dt2->getTimezone();
            $dt1 = new DateTime('now', new DateTimeZone($tz->getName()));
            $dt2->add(new DateInterval('P1D')); // +1 день
            $dt3->add(new DateInterval('P2D')); // +2 дня
            if ($dt2 >= $dt1) {
                $img_name = 'icon-time1.jpg';
            } elseif ($dt3 >= $dt1) {
                $img_name = 'icon-time2.jpg';
            }
        } catch (Exception $e) {
            return null;
        }

        $html = date("Y-m-d H:i:s T", strtotime($this->last_active_at));
        $html .= "&#9;".Html::img('/img/'.$img_name, [
            "alt" => 'Последняя активность была '.$value,
            "title" => 'Последняя активность была '.$value
        ]);
        return $html;
    }

    /**
     * @return null|string
     */
    public function getStateGrid()
    {
        if (empty($this->auth_status))
            return null;

        $value = $this->auth_status;
        $name = GmsDevices::getAuthStatus($value);
        $value == 1 ? $class = 'success' : $class = 'danger';
        $html = Html::tag('span', Html::encode($name), ['class' => 'label label-' . $class]);
        return $html;
    }

    /**
     * @return array
     */
    static public function getAuthStatusArray() {
        return ['0' => 'Не авторизирован', '1' => 'Авторизирован'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegionModel()
    {
        return $this->hasOne(GmsRegions::className(), ['id' => 'region_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSenderModel()
    {
        return $this->hasOne(GmsSenders::className(), ['id' => 'sender_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayListOutModel()
    {
        return $this->hasOne(GmsPlaylistOut::className(), ['id' => 'current_pls_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupDevices()
    {
        return $this->hasOne(GmsGroupDevices::className(), ['device_id' => 'id']);
    }

    /**
     * @return array
     */
    static function getTreeDevices()
    {
        $out = [];
        $out2 = [];
        $findModel = self::find()->all();

        /** @var GmsDevices $model */
        foreach ($findModel as $model) {
            if (empty($model->name || empty($model->region_id))) continue;
            $out[$model->region_id][$model->sender_id][$model->id] = $model->name;
        }

        foreach ($out as $key => $region) {
            $findRegion = GmsRegions::findOne($key);
            if (!$findRegion) continue;
            $out = [
                'title' => $findRegion->region_name,
                'key' => (string)$key,
                'folder' => true,
                'expanded' => true
            ];
            if (is_array($region)) {
                foreach ($region as $key1 => $sender) {
                    $children_device = [];
                    if (is_array($sender)) {
                        foreach ($sender as $key2 => $device) {
                            $children_device[] = [
                                'title' => $device,
                                'key' => (string)$key2,
                                'folder' => false,
                                'expanded' => true,
                                'data' => [
                                    'parent_key' => !empty($key1) ? (string)$key.".".$key1 : (string)$key
                                ]
                            ];
                        }
                    }
                    if (!empty($key1)) {
                        $findSender = GmsSenders::findOne($key1);
                        $children_sender = [
                            'title' => $findSender->sender_name,
                            'key' => (string)$key.".".$key1,
                            'folder' => true,
                            'expanded' => true
                        ];
                        $children_sender['children'] = $children_device;
                        $out['children'][] = $children_sender;
                    } else {
                        $out['children'] = $children_device;
                    }
                }
            }
            $out2[] = $out;
        }
        return $out2;
    }

    /**
     * @inheritdoc
     */
    public static function getDeviceList()
    {
        $arr = self::find()
            ->orderBy(['name' => 'asc'])
            ->asArray()
            ->all();

        return ArrayHelper::map($arr,'id','name');
    }
}