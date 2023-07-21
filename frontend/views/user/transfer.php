<?php

use common\models\User;
use kartik\select2\Select2;
use phpDocumentor\Reflection\DocBlock\Description;
use yii\bootstrap4\Alert;
use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\grid\GridViewAsset;
use yii\bootstrap4\Modal;


/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
$this->title = 'My Yii Application';
GridViewAsset::register($this);
?>


<?php
Modal::begin([
    'id' => 'addModal',
    'options' => ['tabindex' => false],
    'title' => 'Add Transaction',
]);
$form = ActiveForm::begin(['id' => 'add-form']);
?>
<div class="container">
    <div class="row">

        <?= $form->field($managementModel, 'wallet_id', [
            'options' => ['class' => 'col-6'],
            'labelOptions' => ['label' => 'User'],
        ])->widget(Select2::className(), [
                    'data' => $wallet_username,
                    'options' => ['placeholder' => 'Select a user'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'theme' => 'bootstrap4',
                    ],
                ]); ?>

        <?= $form->field($managementModel, 'amount', ['options' => ['class' => 'col-6']])
            ->widget(\yii\widgets\MaskedInput::class, [
                'clientOptions' => [
                    'alias' => 'numeric',
                    'groupSeparator' => ',',
                    'digits' => 2,
                    'digitsOptional' => false,
                    'rightAlign' => false,
                    'autoGroup' => true,
                    'autoUnmask' => true,
                    'unmaskAsNumber' => true,
                    'removeMaskOnSubmit' => true,
                ],
                'options' => ['class' => 'form-control']
            ]) ?>
    </div>
    <?= Html::submitButton('Transfer', ['class' => 'btn btn-primary mt-2', 'name' => 'add-button']) ?>
</div>
<?php
ActiveForm::end();
Modal::end();
?>


<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!--  Main wrapper -->
        <div class="body-wrapper pt-0">
            <div class="container-fluid">
                <!--  Row 1 -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary ms-2" data-toggle="modal"
                                data-target="#addModal">
                                Create new Transfer
                            </button>
                        </div>
                        <?= GridView::widget([
                            'dataProvider' => $provider,
                            'columns' => [
                                [
                                    'label' => 'Transaction ID',
                                    'attribute' => "transaction_id",
                                    'value' => function ($model) {
                                                                return $model->transaction_id;
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
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>