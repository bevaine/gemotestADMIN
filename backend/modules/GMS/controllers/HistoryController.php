<?php
/**
 * Created by PhpStorm.
 * User: evgeny.dymchenko
 * Date: 22.03.2018
 * Time: 18:56
 */

namespace app\modules\GMS\controllers;

use yii\web\Controller;

class HistoryController extends Controller
{
    /**
     * Lists all GmsPlaylist models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}