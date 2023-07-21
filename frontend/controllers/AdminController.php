<?php

namespace frontend\controllers;

use common\models\LoginForm;
use common\models\User;
use frontend\models\ContentManagementForm;
use frontend\models\ReportForm;
use frontend\models\SignupForm;
use frontend\models\TransactionReportForm;
use frontend\models\TransactionReportSearchForm;
use frontend\models\UserManagementForm;
use frontend\models\UserManagementSearch;
use frontend\models\UserReportSearchForm;
use frontend\models\UserSearchForm;
use yii;
use yii\db\Exception;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Site controller
 */
class AdminController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'transaction-report', 'user-report', 'logout', 'content-management', 'export-to-excel'],
                        'allow' => false,
                        'roles' => ['user'],
                        'denyCallback' => function ($rule, $action) {
                            if (Yii::$app->user->identity->role == "user") {
                                return $action->controller->redirect(['user/index']);
                            }
                        }
                    ],
                    [
                        'actions' => ['index', 'transaction-report', 'user-report', 'logout', 'content-management', 'export-to-excel'],
                        'allow' => true,
                        'roles' => ['admin'],
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
        if (!Yii::$app->user->isGuest) {
            return $this->render('index');
        }
    }

    public function actionUserReport()
    {
        $model = new UserReportSearchForm();
        $userModal = new UserManagementForm();

        //TODO
        // wrap everything in try and catch after begin transaction

        if (Yii::$app->request->get('filter-button') !== null) {
            $model->load(Yii::$app->request->get());
        }

        $userModal->load(Yii::$app->request->post());
        $db = Yii::$app->db->beginTransaction();
    
        if (Yii::$app->request->post('update-button') !== null) {
            try {
                $userModal->updateUser();
                $db->commit();
            } catch (Exception $e) {
                $db->rollback();
                Yii::$app->session->setFlash('error', Yii::t('app', $e->getMessage()));
            }
        }

        if (Yii::$app->request->post('delete-button') !== null) {
            try {
                $userModal->deleteUser();
                $db->commit();
            } catch (Exception $e) {
                $db->rollback();
                Yii::$app->session->setFlash('error', Yii::t('app', $e->getMessage()));
            }
        }

        if (Yii::$app->request->post('add-button') !== null) {
            try {
                $userModal->addUser();
                $db->commit();
            } catch (Exception $e) {
                $db->rollback();
                Yii::$app->session->setFlash('error', Yii::t('app', $e->getMessage()));
            }
        }

        return $this->render('user-report', ['sql' => $model->filterReport($model->getUserReportQuery()), 'model' => $model, 'userModel' => $userModal]);
    }

    public function actionTransactionReport()
    {
        $model = new TransactionReportSearchForm();
        $managementModel = new TransactionReportForm();
        $db = Yii::$app->db->beginTransaction();
        if (Yii::$app->request->post('filter-button') !== null) {
            $model->load(Yii::$app->request->post());
            return $this->render('transaction-report', ['provider' => $model->filterTransactionReport(), 'model' => $model, 'managementModel' => $managementModel, 'wallet_username' => $model->getUserWallet()]);
        }

        $managementModel->load(Yii::$app->request->post());
        if (Yii::$app->request->post('add-button') !== null) {
            try {
                $managementModel->addNewRecord();
                $db->commit();
            } catch (Exception $e) {
                $db->rollback();
                Yii::$app->session->setFlash('error', Yii::t('app', $e->getMessage()));
            }
            return $this->render('transaction-report', ['provider' => $model->getTransactionReportDataProvider($model->getTransactionReportQuery()), 'model' => $model, 'managementModel' => $managementModel, 'wallet_username' => $model->getUserWallet()]);
        }

        if (Yii::$app->request->post('delete-button') !== null) {
            try {
                $managementModel->deleteTransaction();
                $db->commit();
            } catch (Exception $e) {
                $db->rollback();
                Yii::$app->session->setFlash('error', Yii::t('app', $e->getMessage()));
            }
            return $this->render('transaction-report', ['provider' => $model->getTransactionReportDataProvider($model->getTransactionReportQuery()), 'model' => $model, 'managementModel' => $managementModel, 'wallet_username' => $model->getUserWallet()]);
        }

        return $this->render('transaction-report', ['provider' => $model->getTransactionReportDataProvider($model->getTransactionReportQuery()), 'model' => $model, 'managementModel' => $managementModel, 'wallet_username' => $model->getUserWallet()]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['admin/']);
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['admin/']);
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionExportToExcel($format)
    {
        if ($format == "transaction-export") {
            $model = new TransactionReportSearchForm;
            $dataProvider = $model->getTransactionReportDataProvider($model->getTransactionReportQuery());
        } else {
            $model = new UserReportSearchForm;
            $model->load(Yii::$app->request->get());
            $dataProvider = $model->getUserReportDataProvider($model->getUserReportQuery());
        } //throw new \Exception(var_export($query->all(),1),1);

        $content = $this->renderPartial('excel/' . $format, ['dataProvider' => $dataProvider]);

        $response = Yii::$app->response;
        $response->format = Response::FORMAT_RAW;
        $response->headers->add('Content-Type', 'application/vnd.ms-excel');
        $response->headers->add('Content-Disposition', 'attachment; filename="export.xls"');
        $response->content = $content;
        $response->send();
        Yii::$app->end();
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['/user/login']);
    }


    public function actionContentManagement()
    {
        $contentModel = new ContentManagementForm();
        $db = Yii::$app->db->beginTransaction();

        $contentModel->load(Yii::$app->request->post());
        if (Yii::$app->request->post('add-button') !== null) {
            try {
                $contentModel->addPosting();
                $db->commit();
            } catch (Exception $e) {
                $db->rollback();
                Yii::$app->session->setFlash('error', Yii::t('app', $e->getMessage()));
            }
        }

        if (Yii::$app->request->post('edit-button') !== null) {
            try {
                $contentModel->editPosting();
                $db->commit();
            } catch (Exception $e) {
                $db->rollback();
                Yii::$app->session->setFlash('error', Yii::t('app', $e->getMessage()));
            }
        }

        if (Yii::$app->request->post('delete-button') !== null) {
            try {
                $contentModel->deletePosting();
                $db->commit();
            } catch (Exception $e) {
                $db->rollback();
                Yii::$app->session->setFlash('error', Yii::t('app', $e->getMessage()));
            }
        }

        return $this->render('content-management', ['model' => $contentModel, 'sql' => $contentModel->getPostingDataProvider($contentModel->getPosting())]);
    }

}