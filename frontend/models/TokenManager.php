<?php

namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;


class TokenManager extends Model
{
    // Token Duration
    private $uration = 1800;
    private $secretKey = "meatenschlappen";

    public $user;
    public $iat;
    public $eat;

    public function generateToken($id)
    {
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];
        $header = json_encode($header);


        $payload = [
            'id' => $id,
            'iat' => time(),
            'eat' => time() + 1800
        ];
        $payload = json_encode($payload);

        $header = $this->base64url_encode($header);
        $payload = $this->base64url_encode($payload);


        $signature = hash_hmac('sha256', $header . '.' . $payload, $this->secretKey);

        return $header . '.' . $payload . '.' . $signature;
    }

    function verifyToken($token)
    {
        list($encodedHeader, $encodedPayload, $encodedSignature) = explode('.', $token);
        $signature = hash_hmac('sha256', $encodedHeader . '.' . $encodedPayload, $this->secretKey);
        $payload = json_decode(base64_decode($encodedPayload));

        if ($encodedSignature !== $signature) {
            return false;
        }

        if($payload->eat < time()){
            return false;
        }

        $this->user = User::findIdentity($payload->id);
        $this->iat = $payload->iat;
        $this->eat = $payload->eat;

        return true;
    }


    private function base64url_decode($data)
    {
        $decoded = strtr($data, '-_', '+/');
        $decoded = base64_decode($decoded, true);

        return $decoded;
    }

    private function base64url_encode($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

}