<?php

namespace frontend\controllers\api;

use common\models\User;
use frontend\models\TokenManager;
use frontend\models\UserReportSearchForm;
use Yii;
use yii\filters\auth\QueryParamAuth;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\rest\Controller;
use yii\web\Response;
use yii\helpers\Json;

class UserController extends Controller
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
        $tokenManager = new TokenManager();
        if (!$tokenManager->verifyToken(Yii::$app->request->get('access_token'))) {
            return [
                'status' => 'error',
                'message' => 'Invalid or expired access token',
            ];
        }

        if ($tokenManager->user->role != "admin") {
            return [
                'status' => 'error',
                'message' => 'Insufficient permission',
            ];
        }

        $model = new UserReportSearchForm;
        $results = $model->getUserReportQuery()->all();

        $jsonData = [];
        foreach ($results as $result) {
            $data = [
                'user_id' => $result->id,
                'username' => $result->username,
                'email' => $result->email,
                'wallet_id' => $result->wallet_id,
                'balance' => $result->balance,
                'status' => $result->user_status == 10 ? "active" : "inactive",
                'created_at' => date("Y-m-d H:i:s", $result->created_at),
            ];

            // Add the data to the JSON array
            $jsonData[] = $data;
        }
        return $jsonData;
    }
}