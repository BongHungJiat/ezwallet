<?php

namespace frontend\controllers\api;

use common\models\User;
use frontend\models\TokenManager;
use frontend\models\TransactionReportForm;
use frontend\models\TransactionReportSearchForm;
use frontend\models\UserReportSearchForm;
use Yii;
use yii\filters\auth\QueryParamAuth;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\rest\Controller;
use yii\web\Response;
use yii\helpers\Json;

class TransactionController extends Controller
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'authenticator' => [
                'class' => QueryParamAuth::className(),
                'except' => [
                    'index',
                    'reload'
                ],
            ]
        ]);
    }

    public function actionIndex()
    {
        // Validate token
        // Before action
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

        //get data
        $model = new TransactionReportSearchForm;
        $results = $model->getTransactionReportQuery()->all();

        $jsonData = [];
        foreach ($results as $result) {
            $data = [
                'transaction_id' => $result->transaction_id,
                'wallet_id' => $result->wallet_id,
                'username' => $result->username,
                'description' => $result->description,
                'amount' => $result->amount,
                'type' => $result->type,
                'date_created' => $result->date_created,
            ];

            // Add the data to the JSON array
            $jsonData[] = $data;
        }

        return $jsonData;
    }

    public function actionReload()
    {
        $tokenManager = new TokenManager();
        if (!$tokenManager->verifyToken(Yii::$app->request->post('access_token'))) {
            return [
                'status' => 'error',
                'message' => 'Invalid or expired access token',
            ];
        }

        if (Yii::$app->request->post('wallet_id') == null) {
            return [
                'status' => 'error',
                'message' => 'Missing field wallet_id',
            ];
        }

        if (Yii::$app->request->post('amount') == null) {
            return [
                'status' => 'error',
                'message' => 'Missing field amount',
            ];
        }

        $transactionModel = new TransactionReportForm;
        $transactionModel->wallet_id = Yii::$app->request->post('wallet_id');
        $transactionModel->description = "Reloaded RM" . Yii::$app->request->post('amount');
        $transactionModel->amount = Yii::$app->request->post('amount');
        $transactionModel->type = "add";
        $transactionModel->addNewRecord();

    }
}