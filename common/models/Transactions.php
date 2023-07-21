<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "transaction".
 *
 * @property int $transaction_id
 * @property int $wallet_id
 * @property string $description
 * @property float|null $amount
 * @property string $date_created
 * @property string $type
 * @property string|null $deleted_at
 * @property int|null $sender
 * @property Ewallet $wallet
 */
class Transactions extends \yii\db\ActiveRecord
{
    public $username;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['wallet_id', 'description', 'type'], 'required'],
            [['wallet_id'], 'integer'],
            [['amount'], 'number'],
            [['date_created', 'deleted_at'], 'safe'],
            [['description'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 20],
            [['wallet_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ewallet::class, 'targetAttribute' => ['wallet_id' => 'wallet_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'transaction_id' => 'Transaction ID',
            'wallet_id' => 'Wallet ID',
            'description' => 'Description',
            'amount' => 'Amount',
            'date_created' => 'Date Created',
            'type' => 'Type',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * Gets query for [[Wallet]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWallet()
    {
        return $this->hasOne(Ewallet::class, ['wallet_id' => 'wallet_id']);
    }

    public static function findById($id)
    {
        return static::findOne(['transaction_id' => $id]);
    }
}