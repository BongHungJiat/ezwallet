<?php

namespace console\controllers;

use common\models\User;
use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionIndex()
    {

    }

    public function actionCreateAdminRole()
    {
        echo "Creating admin role...\n";
        $role = Yii::$app->authManager->createRole('admin');
        $role->description = 'Admin';
        Yii::$app->authManager->add($role);
        echo "Admin role created!";
    }

    public function actionCreateUserRole()
    {
        echo "Creating user role...\n";
        $role = Yii::$app->authManager->createRole('user');
        $role->description = 'User';
        Yii::$app->authManager->add($role);
        echo "User role created!";
    }

    public function actionCreateSubadminRole()
    {
        echo "Creating sub-admin role...\n";
        $role = Yii::$app->authManager->createRole('subadmin');
        $role->description = 'Subadmin';
        Yii::$app->authManager->add($role);
        echo "Sub-admin role created!";
    }


    public function actionRoleSetup()
    {
        echo "Checking through all user accounts and assignning roles...\n";
        $users = User::find()
            ->alias('u')->select(['*'])
            ->leftJoin("auth_assignment a", "a.user_id = u.id")
            ->all();

        foreach ($users as $user) {
            $authManager = Yii::$app->authManager;
            $authManager->assign($authManager->getRole($user->getRole()), $user->id);
        }
        echo "Process completed successfully!\n";
    }
}