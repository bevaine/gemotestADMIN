<?php
/**
 * Created by PhpStorm.
 * User: evgeny.dymchenko
 * Date: 31.08.2017
 * Time: 14:59
 */

namespace common\models;

use yii\base\Model;
use Yii;

class AddDocForm extends Model
{
    /**
     * @return null|object
     */
    public static function getDb() {
        return Yii::$app->get('GemoTestDB');
    }
}