<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "gms_playlist_detail_out".
 *
 * @property integer $id
 * @property integer $playlist_id
 * @property integer $video_id
 * @property integer $rating
 */
class GmsPlaylistDetailOut extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gms_playlist_detail_out';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['playlist_id', 'video_id', 'rating'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'playlist_id' => 'Playlist ID',
            'video_id' => 'Video ID',
            'rating' => 'Rating',
        ];
    }
}
