<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ewallet".
 *
 * @property int $wallet_id
 * @property int $user_id
 * @property int $status
 * @property string $date_created
 *
 * @property Transaction[] $transactions
 * @property User $user
 */
class Ewallet extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */


    public $balance;
    public $created_at;
    public $transaction_id;
    public $username;
    public $description;
    public $amount;
    public $user_status;
    public $transaction_status;
    public $transaction_date;
    public $date_created;
    public $id;
    public $email;

    public static function tableName()
    {
        return 'ewallet';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'status'], 'integer'],
            [['date_created'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'wallet_id' => 'Wallet ID',
            'user_id' => 'User ID',
            'status' => 'Status',
            'date_created' => 'Date Created',
        ];
    }

    /**
     * Gets query for [[Transactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::class, ['wallet_id' => 'wallet_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function findBalance($id){
        return Ewallet::find()
        ->alias('e')->select(['COALESCE(SUM(amount),0) as amount'])
        ->innerJoin('transaction t', 't.wallet_id = e.wallet_id')
        ->groupBy("e.wallet_id")
        ->andWhere(['e.wallet_id'=>$id])->scalar();
    }
    
    public static function findWalletByUserId($id){
        return Ewallet::find()
        ->alias('e')->select(['e.wallet_id'])
        ->andWhere(['e.user_id'=>$id])->scalar();
    }

    public static function findUsernameByWalletId($id){
        return User::find()
        ->alias('u')->select(['u.username'])
        ->innerJoin('ewallet e','e.user_id = u.id')
        ->andWhere(['e.wallet_id'=>$id])->scalar();
    }
}
