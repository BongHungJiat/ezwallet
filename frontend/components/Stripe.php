<?php
namespace app\components;
use common\models\StripeTransaction;
use Yii;
use yii\base\Component;
use yii\httpclient\Client;
use yii\helpers\ArrayHelper;
use stripe\Charge;
use yii\web\BadRequestHttpException;
use Stripe\PaymentIntent;

class Stripe extends Component
{

    public $secretKey;

    public function init()
    {
        parent::init();
        $this->secretKey = 'sk_test_51NUlWFHOSHSSNQywYmWVUv9uYXgKCSBXKAPE3tep8worlxcU4i3PxWYfs2GH1qGlywVW8yQdqoawiCPYemqc5pIn00v5sj7wZF';
    }

    public function createPaymentIntent($amount,$currency){
        \Stripe\Stripe::setApiKey($this->secretKey);

        try{
            $intent = PaymentIntent::create([
                'amount' => strval($amount),
                'currency' => $currency
            ]);
        }catch(\Exception $e){
            throw new BadRequestHttpException('Payment initiation failed: ' . $e->getMessage());
        }

        $stripe_transaction = new StripeTransaction;
        $stripe_transaction->intentId = $intent->id;
        $stripe_transaction->user_id = Yii::$app->user->getId();
        $stripe_transaction->save();
        
        return ['clientSecret' => $intent->client_secret];
    }

    public function verifyIntent($intentId){
        \Stripe\Stripe::setApiKey($this->secretKey);
        $paymentIntent = PaymentIntent::retrieve($intentId);
        if ($paymentIntent->status === 'succeeded') {
            return true;
        } else {
            return $paymentIntent->status;
        }
    }

}