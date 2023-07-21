<?php

namespace frontend\models;

use common\models\Ewallet;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;
use Yii;
use yii\db\Exception;

/**
 * UserManagementSearch represents the model behind the search form of `common\models\User`.
 */
class UserManagementForm extends User
{
    /**
     * {@inheritdoc}
     */

    public $id;
    public $username;
    public $email;
    public $password;
    public $delete;
    public function rules()
    {
        return [
            // combine rules together
            ['id', 'safe'],

            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'validateUniqueUsername'],
            [['username', 'email'], 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'validateUniqueEmail'],

            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],

            [['role', 'delete'], 'safe']
        ];
    }
    public function validateUniqueUsername($attribute, $params)
    {
        $user = $this->getUser();
        if ($user && $user->username === $this->$attribute) {
            return;
        }

        $existingUser = User::findOne(['username' => $this->$attribute]);
        if ($existingUser) {
            $this->addError($attribute, 'This username has already been taken.');
        }
    }

    public function validateUniqueEmail($attribute, $params)
    {
        $user = $this->getUser();
        if ($user && $user->email === $this->$attribute) {
            return;
        }

        $existingUser = User::findOne(['email' => $this->$attribute]);
        if ($existingUser) {
            $this->addError($attribute, 'This email address has already been taken.');
        }
    }

    public function updateUser()
    {
        // TODO
        // update the updated_at column as well
        $user = $this->getUser();

        if ($user == null) {
            Yii::$app->session->setFlash('error', 'Oops, something went wrong');
            return;
        }

        if (!$this->validate()) {
            Yii::$app->session->setFlash('error', 'Incorrect form submission ');
            return;
        }

        $user->username = $this->username;
        $user->email = $this->email;
        if(!empty($this->password)){
            $user->setPassword($this->password);
        }
        $user->updated_at = time();

        $user->update(false, ['username', 'email', 'password_hash','updated_at']);
        return;
    }

    protected function getUser()
    {
        return User::findIdentity($this->id) ?? null;
    }

    public function deleteUser()
    {
        $user = $this->getUser();
        $user->deleted_at = time();
        $user->update(false, ['deleted_at']);
    }

    public function addUser()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->role = $this->role ?? "user";
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