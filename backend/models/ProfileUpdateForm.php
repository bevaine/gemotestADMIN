<?php
namespace backend\models;

use yii\base\Model;
use common\models\User;
use yii\db\ActiveQuery;
use Yii;

class ProfileUpdateForm extends Model
{
    public $email;

    /**
     * @var User
     */
    private $_user;

    public function __construct(User $user, $config = [])
    {
        $this->_user = $user;
        $this->email = $user->email;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'email'],
            [
                'email',
                'unique',
                'targetClass' => User::className(),
                'message' => Yii::t('app', 'ERROR_EMAIL_EXISTS'),
                'filter' => ['<>', 'id', $this->_user->id],
            ],
            ['email', 'string', 'max' => 255],
        ];
    }

    public function update()
    {
        if ($this->validate()) {
            $user = $this->_user;
            $user->email = $this->email;
            return $user->save();
        } else {
            return false;
        }
    }
}