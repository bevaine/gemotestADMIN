<?php

namespace console\controllers;

use common\components\helpers\ActiveSyncHelper;
use yii\console\Controller;

/**
 * Created by PhpStorm.
 * User: evgeny.dymchenko
 * Date: 22.08.2017
 * Time: 14:36
 */
class ActiveSyncController extends Controller
{
    public function actionTest ()
    {
        $ActiveSyncHelper = new ActiveSyncHelper();
        $ActiveSyncHelper->connectLDAP();
        exit;
        $ActiveSyncHelper->nurse = 1;
        $ActiveSyncHelper->department = 0;
        //$ActiveSyncHelper->typeLO = "SLO";
        $ActiveSyncHelper->lastName = "Тюленев";
        $ActiveSyncHelper->firstName = "Дмитрий";
        $ActiveSyncHelper->middleName = "Андреевич";
        $ActiveSyncHelper->operatorofficestatus = "Оператор";
        $ActiveSyncHelper->fullName = $ActiveSyncHelper->lastName." ".$ActiveSyncHelper->firstName." ".$ActiveSyncHelper->middleName;
        print_r($ActiveSyncHelper->checkUserNameAd());
    }
}