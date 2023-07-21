<?php

namespace frontend\models;

use common\models\Ewallet;
use common\models\Transactions;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


class TransactionReportForm extends Model
{
    /**
     * @var string
     */

    /**
     * {@inheritdoc}
     */
    public $transaction_id;
    public $description;
    public $amount;
    public $wallet_id;
    public $type;

    public function rules()
    {
        return [
            [['amount', 'wallet_id', 'type', 'description'], 'required'],
            [['transaction_id'],'safe'],
            ['amount', 'compare', 'compareValue' => 0, 'operator' => '>', 'message' => 'Amount must be greater than 0.'],
        ];
    }
    public function addNewRecord()
    {
        if (!$this->validate()) {
            return null;
        }

        $transaction = new Transactions();
        $transaction->wallet_id = $this->wallet_id;
        $transaction->description = $this->description;
        $transaction->amount = $this->amount;
        if ($this->type == "deduct") {
            $balance = Ewallet::findBalance($this->wallet_id);
            if ($balance < $this->amount) {
                $this->addError('amount', 'Insufficient balance.');
                Yii::$app->session->setFlash('error', 'Incorrect form submission ');
                return;
            }
        }

        $transaction->type = $this->type;
        return $transaction->save();
    }

    public function deleteTransaction()
    {
        $transaction = $this->getTransaction();
        $transaction->deleted_at = date("Y-m-d h:i:s",time());
        $transaction->update(false, ['deleted_at']);
    }

    public function getTransaction(){
        return Transactions::findById($this->transaction_id);
    }
}