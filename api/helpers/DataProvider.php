<?php

namespace api\helpers;

use yii\data\ArrayDataProvider;

/**
 * Class DataProvider
 * @package api\helpers
 */
class DataProvider
{
    /**
     * @param array $data
     * @param int $size
     * @param int $offset
     * @return array
     */
    public static function getArrayProvider($data = [], $size = 100, $offset = 0)
    {
        $provider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'offset' => $offset,
                'pageSize' => $size,
            ],
            'sort' => [
                'attributes' => ['id'],
            ],
        ]);

        $result = $provider->getModels();

        return $result;
    }
} 