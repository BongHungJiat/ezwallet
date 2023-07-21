<?php

use yii\grid\GridView;

GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'label' => 'User ID',
            'attribute' => 'id',
            'value' => function ($model) {
                return $model->id;
            }
        ],
        [
            'attribute' => 'Username',
            'value' => function ($model) {
                return $model->username;
            }
        ],
        [
            'attribute' => 'Email',
            'value' => function ($model) {
                return $model->email;
            }
        ],
        [
            'attribute' => 'Wallet ID',
            'value' => function ($model) {
                return $model->wallet_id;
            }
        ],
        [
            'attribute' => 'Balance',
            'value' => function ($model) {
                return $model->balance;
            }
        ],
        [
            'attribute' => 'status',
            'value' => function ($model) {
                if ($model->user_status == 10) {
                    return 'Active';
                } else {
                    return 'Inactive';
                }
            }
        ],
        [
            'attribute' => 'created_at',
            'value' => function ($model) {
                // throw new \Exception(var_export($model,1),1);
                return date("Y-m-d H:i:s", $model->created_at);
            }
        ],
    ],
]);

?>