<?php

namespace api\modules\gms\controllers;

use common\models\GmsDevices;
use yii\web\ForbiddenHttpException;
use yii\rest\ActiveController;
use yii\filters\VerbFilter;
use api\helpers\ResponseObject;
use yii;

/**
 * Country Controller API
 *
 * @author Budi Irawan <deerawan@gmail.com>
 */
class PlaylistController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = 'common\models\GmsPlaylistOut';

    /**
     * @return array
     */
    public function actions()
    {
        return [];
    }

    /**
     * @param null $dev
     * @param null $pls
     * @return ResponseObject
     * @throws ForbiddenHttpException
     */
    public function actionView($dev = null, $pls = null)
    {
        $data = [];
        $response = new ResponseObject();

        if (!$modelDevices = GmsDevices::findOne(['device' => $dev])) {
            $modelDevices = new GmsDevices();
            $modelDevices->scenario = 'addDevice';
            $modelDevices->device = $dev;
            $modelDevices->auth_status = 0;
            $modelDevices->created_at = time();
        } else
            $modelDevices->scenario = 'editDevice';

        $modelDevices->last_active_at = time();

        if (!$modelDevices->save()) {
            Yii::getLogger()->log([
                'actionView:$modelDevices->save()' => $modelDevices->getErrors()
            ], 1, 'binary');
        }

        if (empty($modelDevices->auth_status)) {
            throw new ForbiddenHttpException('The requested page does not exist.');
        }

        if ($modelDevices->current_pls_id == $pls) {
            $data[] = ['playlist' => 1];
        }

        $response->addData($data);
        return $response;
    }
}


