<?php

namespace console\controllers;

use common\models\User;
use frontend\models\SignupForm;
use Yii;
use yii\console\Controller;

class AdminController extends Controller
{
    public function actionCreateAdmin($username,$email,$password)
    {
        // Confirm password no need
        $model = new SignupForm();
        $data = [
            'SignupForm' => [
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'role' => 'admin',
            ],
        ];
    
        if ($model->load($data) && $model->signup()) {
            echo "Admin user created successfully.";
        } else {
            $errors = $model->getFirstErrors();
            echo "Error creating admin user:\n";
            foreach ($errors as $attribute => $error) {
                echo "- $attribute: $error\n";
            }
        }
    }

}