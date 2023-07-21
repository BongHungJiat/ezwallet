<?php
use yii\grid\GridView;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'label' => 'Transaction ID',
            'attribute' => "transaction_id",
            'value' => function ($model) {
                return $model->transaction_id;
            }
        ],
        [
            'attribute' => "Wallet ID",
            'value' => function ($model) {
                return $model->wallet_id;
            }
        ],
        [
            'attribute' => "Username",
            'value' => function ($model) {
                return $model->username;
            }
        ],
        [
            'attribute' => "Description",
            'value' => function ($model) {
                return $model->description;
            }
        ],
        [
            'attribute' => "Amount",
            'value' => function ($model) {
                return $model->amount;
            }
        ],
        [
            'attribute' => "Type",
            'value' => function ($model) {
                return $model->type;
            }
        ],
        [
            'label' => 'Date',
            'attribute' => "date_created",
            'value' => function ($model) {
                return $model->date_created;
            }
        ],

    ],
]) ?>
?>