<?php

namespace frontend\models;

use common\models\Ewallet;
use Yii;
use common\models\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\db\Query;

class UserSearchForm extends Model
{
    /**
     * @var string
     */

    /**
     * {@inheritdoc}
     */

    public function getUserBalance()
    {
        $query = (new Query())
            ->select(['*'])
            ->from('ewallet e')
            ->innerJoin('user u', 'u.id = e.user_id')
            ->innerJoin('transaction t', 't.wallet_id = e.wallet_id')
            ->where(['u.id' => Yii::$app->user->identity->getId()])
            ->andWhere(['t.deleted_at' => null])
            ->orderBy(['t.date_created'=>SORT_DESC]);
        $result = $query->all();

        
        return $result;
    }

    public function getUserMonthlySpending()
    {
        $monthlySpending = (new Query())
            ->select(['COALESCE(SUM(CASE WHEN t.type = "deduct" THEN t.amount END),0) AS balance'])
            ->from('ewallet e')
            ->innerJoin('user u', 'u.id = e.user_id')
            ->innerJoin('transaction t', 't.wallet_id = e.wallet_id')
            ->where(['u.id' => Yii::$app->user->identity->getId()])
            ->andWhere(['MONTH(t.date_created)' => new Expression('MONTH(CURRENT_DATE())')])
            ->andWhere(['t.deleted_at' => null])
            ->groupBy('t.amount');

        $monthlySpending = $monthlySpending->scalar();
        return $monthlySpending;
    }

    public function getAllUser(){
        $user = User::find()->select(["*"])->all();
        return $user;
    }

    

}