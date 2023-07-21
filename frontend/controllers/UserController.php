<?php

namespace frontend\controllers;

use common\models\Ewallet;
use frontend\controllers\api\AccessController;
use frontend\models\ContentManagementForm;
use frontend\models\ReloadForm;
use frontend\models\TokenManager;
use frontend\models\TransferForm;
use frontend\models\TransferSearchForm;
use frontend\models\UserSearchForm;
use yii;
use yii\db\Exception;
use yii\web\Controller;
use frontend\models\SignupForm;
use common\models\LoginForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\httpclient\Client;

/**
 * Site controller
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $reloadModal = new ReloadForm;
        $model = new UserSearchForm;

        if ($reloadModal->load(Yii::$app->request->get())) {
            return $this->redirect(['user/payment?amount=' . $reloadModal->amount]);
        }

        if (!Yii::$app->user->isGuest) {
            return $this->render('index', [
                'data' => $model->getUserBalance(),
                'monthlySpending' => $model->getUserMonthlySpending(),
                'reloadModal' => $reloadModal
            ]);
        }


        return $this->redirect(['user/login']);

    }

    public function actionPayment()
    {
        if (Yii::$app->request->get("amount") == null) {
            return $this->redirect("user/");
        }
        $amount = Yii::$app->request->get("amount");
        $amount = str_replace(',', '.', $amount);
        $amountCents = (int) (floatval($amount) * 100);

        return $this->render('payment', ['intent' => Yii::$app->stripe->createPaymentIntent($amountCents, 'myr')]);
    }

    public function actionPaymentSuccess()
    {
        $access = new TokenManager;
        $reload = new ReloadForm;
        $token = $access->generateToken(Yii::$app->user->getId());

        if(Yii::$app->stripe->verifyIntent(Yii::$app->request->get("payment_intent")) !== true){
            return $this->renderPartial('payment-failed',['error_msg'=>"Payment has not been made"]);
        }

        if ($reload->reload($token, Yii::$app->request->get("amount"),Yii::$app->request->get("payment_intent")) !== true) {
            return $this->renderPartial('payment-failed',['error_msg'=>$reload->reload($token, Yii::$app->request->get("amount"),Yii::$app->request->get("payment_intent"))]);
        }
        return $this->renderPartial('payment-success');
    }

    public function actionAboutUs()
    {
        $model = new ContentManagementForm;
        return $this->render('about-us', ['model' => $model->getPosting()->all()]);
    }

    public function actionTransfer()
    {
        $model = new TransferSearchForm();
        $managementModel = new TransferForm();


        if (Yii::$app->request->post('add-button') !== null) {
            $managementModel->load(Yii::$app->request->post());
            $managementModel->addNewRecord();
        }

        return $this->render('transfer', [
            'provider' => $model->getTransactionReportDataProvider($model->getUserTransaction()),
            'model' => $model,
            'managementModel' => $managementModel,
            'wallet_username' => $model->getUserWallet()
        ]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (Yii::$app->user->identity->role == "user") {
                return $this->redirect(['user/']);
            } else {
                return $this->redirect(['admin/']);
            }
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        $db = Yii::$app->db->beginTransaction();
        if ($model->load(Yii::$app->request->post())) {
            try {
                if ($model->signup()) {
                    $db->commit();
                    return $this->redirect(['user/login']);
                }
            } catch (Exception $e) {
                $db->rollback();
                Yii::$app->session->setFlash('error', Yii::t('app', $e->getMessage()));
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}