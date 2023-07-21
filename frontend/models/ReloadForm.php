<?php

namespace frontend\models;

use common\models\Ewallet;
use common\models\StripeTransaction;
use common\models\Transactions;
use common\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\httpclient\Client;

class ReloadForm extends Model
{
    public $amount;

    public function rules()
    {
        return [
            [['amount'], 'required'],
            [['amount'], 'compare', 'compareValue' => 10, 'operator' => '>','message' => 'The minimum reload is RM 10.'],
        ];
    }
    

    public function reload($token, $amount,$intent_id)
    {
        $stripe_transaction = StripeTransaction::findTransaction($intent_id);

        // check transaction does exist
        if($stripe_transaction == null){
            return "This transaction does not exist";
        }

        // check transaction has already been made or not
        if($stripe_transaction->status != 0){
            return "This transaction has already been completed";
        }

        $stripe_transaction->status = 1;
        $stripe_transaction->update(false, ['status']);

        $client = new Client;
        // Set the API endpoint URL
        $url = 'http://ezwallet.test/api/transaction/reload';

        // Set the POST data
        $data = [
            'access_token' => $token,
            'wallet_id' => Ewallet::findWalletByUserId(Yii::$app->user->getId()),
            'amount' => $amount,
        ];

        // Send the POST request
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($url)
            ->setData($data)
            ->send();

        // Check if the request was successful
        if ($response->isOk) {
            return true;
        } else {
            $error = $response->content;
            throw new \Exception(var_export($error, 1), 1);
        }
    }
}