<?php

namespace frontend\models;

use common\models\Ewallet;
use common\models\Transactions;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class TransferSearchForm extends Model
{
    /**
     * @var string
     */

    /**
     * {@inheritdoc}
     */
    public $id;
    public $username;
    public $email;
    public $status;
    public $created_after;
    public $created_before;
    public $transaction_id;
    public $type;
    public $wallet_id;
    public $transaction_status;

    public function rules()
    {
        return [
            [['id', 'username', 'status', 'created_before', 'created_after', 'transaction_id', 'wallet_id', 'transaction_status', 'type'], 'safe'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email does not exist.'],
        ];
    }

    public function getUserWallet()
    {
        $walletData = [];
        $user_wallet = Ewallet::find()
            ->alias('e')->select(['e.wallet_id', 'u.username'])
            ->innerJoin('user u', 'u.id = e.user_id')
            ->andWhere(['u.deleted_at' => null])->all();

        foreach ($user_wallet as $userWallet) {
            $walletData[$userWallet->wallet_id] = $userWallet->username;
        }

        return $walletData;
    }

    public function getUserTransaction(){
        return Transactions::find()
        ->alias('t')->select([
                't.transaction_id',
                'e.wallet_id',
                'u.username',
                't.description',
                'amount',
                't.type',
                't.date_created',
                't.sender'
            ])
        ->innerJoin('ewallet e', 'e.wallet_id = t.wallet_id')
        ->innerJoin('user u', 'u.id = e.user_id')
        ->andWhere(['t.deleted_at' => null])
        ->andWhere(['u.id' => Yii::$app->user->getId()]);
    }

    public function getTransactionReportDataProvider($query)
    {
        $provider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['transaction_id', 'Amount', 'date_created'],
                'defaultOrder' => [
                    'date_created' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);
        return $provider;
    }

}