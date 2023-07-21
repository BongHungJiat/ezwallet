<?php

namespace frontend\models;

use common\models\Ewallet;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class UserReportSearchForm extends Model
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

    public $wallet_id;
    public $transaction_status;

    public function rules()
    {
        return [
            [['id', 'username', 'status', 'created_before', 'created_after', 'transaction_id', 'wallet_id', 'transaction_status'], 'safe'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email does not exist.'],
        ];
    }

    public function getUserReportQuery()
    {
        return Ewallet::find()
            ->alias('e')->select([
                    'u.id',
                    'u.username',
                    'u.email',
                    'e.wallet_id',
                    'COALESCE(SUM(CASE WHEN t.type = "add" THEN t.amount ELSE -t.amount END),0) AS balance',
                    'u.status as user_status',
                    'u.created_at',
                ])
            ->innerJoin('user u', 'e.user_id = u.id')
            ->leftJoin('transaction t', 't.wallet_id = e.wallet_id')
            ->groupBy(['e.wallet_id'])
            ->andWhere(['u.deleted_at' => null])
            ->andWhere(['t.deleted_at' => null])
            ->andWhere(['u.role' => 'user']);
    }

    public function getUserReportDataProvider($query)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['id', 'Balance', 'created_at'],
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);
        return $dataProvider;
    }

    public function filterReport()
    {
        $query = $this->getUserReportQuery();

        $query->andFilterWhere(['=', 'u.id', $this->id]);
        $query->andFilterWhere(['like', 'username', $this->username]);
        $query->andFilterWhere(['like', 'email', $this->email]);
        $query->andFilterWhere(['u.status' => $this->status]);

        if (!empty($this->created_after)) {
            $query->andWhere(['>=', 'created_at', strtotime($this->created_after)]);
        }
        if (!empty($this->created_before)) {
            $query->andWhere(['<=', 'created_at', strtotime($this->created_before)]);
        }

        return $this->getUserReportDataProvider($query);
    }

}