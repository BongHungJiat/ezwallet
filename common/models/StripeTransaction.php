<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "stripe_transaction".
 *
 * @property string $intentId
 * @property int $user_id
 * @property int $status
 *
 * @property User $user
 */
class StripeTransaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stripe_transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['intentId', 'user_id'], 'required'],
            [['user_id', 'status'], 'integer'],
            [['intentId'], 'string', 'max' => 100],
            [['intentId'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'intentId' => 'Intent ID',
            'user_id' => 'User ID',
            'status' => 'Status',
        ];
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

    public static function findTransaction($intent_id){
        return static::findOne(['intentId' => $intent_id]);
    }
}
