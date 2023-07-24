<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\Ewallet;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $confirmPassword;
    public $role;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],

            ['confirmPassword', 'required'],
            ['confirmPassword', 'compare', 'compareAttribute' => 'password', 'message' => "Passwords don't match"],

            ['role', 'safe'],

        ];
    }


    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        if(!isset($this->role)){
            $user->role = "user";
        }else{
            $user->role = $this->role;
        }

        $user->generateAuthKey();
        $user->status = 10;
        $user->generateEmailVerificationToken();

        if ($user->save()) {
            $Ewallet = new Ewallet();
            $Ewallet->user_id = $user->id;
        }
        
        $authManager = Yii::$app->authManager;
        $authManager->assign($authManager->getRole($user->getRole()), $user->id);

        return $Ewallet->save();
    }



}