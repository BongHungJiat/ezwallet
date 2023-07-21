<?php

namespace frontend\models;

use common\models\Ewallet;
use common\models\Transactions;
use common\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class TransferForm extends Model
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
    public $amount;
    public $transaction_status;

    public function rules()
    {
        return [
            [['amount', 'wallet_id'], 'required'],
            [['transaction_id'],'safe'],
            ['amount', 'compare', 'compareValue' => 0, 'operator' => '>', 'message' => 'Amount must be greater than 0.'],
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

    public function getUserTransaction()
    {
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

    public function addNewRecord()
    {
        if (!$this->validate()) {
            return null;
        }

        //deduct money from tranferer
        $transaction = new Transactions();
        $currentUserWallet = Ewallet::findWalletByUserId(Yii::$app->user->id);
        if($this->wallet_id == $currentUserWallet){
            $this->addError('wallet_id', "Can't transfer to yourself");
            Yii::$app->session->setFlash('error', "Can't transfer to yourself");
            return;
        }

        $balance  = Ewallet::findBalance($currentUserWallet);
        if ($balance < $this->amount) {
            $this->addError('amount', 'Insufficient balance.');
            Yii::$app->session->setFlash('error', 'Insufficient balance.');
            return;
        }

        $transaction->wallet_id = $currentUserWallet;
        $transaction->sender = Yii::$app->user->getId();
        $transaction->description = "Transferred " . $this->amount . " to " . Ewallet::findUsernameByWalletId($this->wallet_id);
        $transaction->amount = $this->amount;
        $transaction->type = 'deduct';
        $transaction->save();

        //add money to transferee
        $transaction = new Transactions();
        $transaction->wallet_id = $this->wallet_id;
        $transaction->sender = Yii::$app->user->getId();
        $transaction->description = "Receive " . $this->amount . " from " . User::findIdentity(Yii::$app->user->getId())->username;
        $transaction->amount = $this->amount;
        $transaction->type = 'add';
        return $transaction->save();
    }

}