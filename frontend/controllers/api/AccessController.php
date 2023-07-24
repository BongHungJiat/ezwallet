<?php

namespace frontend\controllers\api;

use common\models\LoginForm;
use common\models\User;
use frontend\models\TokenManager;
use frontend\models\UserReportSearchForm;
use Yii;
use yii\filters\auth\QueryParamAuth;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\rest\Controller;

class AccessController extends Controller
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'authenticator' => [
                'class' => QueryParamAuth::className(),
                'except' => [
                    'index'
                ],
            ]
        ]);
    }

    public function actionIndex()
    {
        $model = new LoginForm();
        $model->username = Yii::$app->request->get('username');
        $model->password = Yii::$app->request->get('password');

        if (!$model->login()) {
            return [
                'status' => 'error',
                'message' => 'Login failed',
            ];
        }

        $tokenManager = new TokenManager();

        return [
            'status' => 'success',
            'message' => 'Access Token Generated',
            'access_token' => $tokenManager->generateToken(Yii::$app->user->getId()),
        ];
    }
}