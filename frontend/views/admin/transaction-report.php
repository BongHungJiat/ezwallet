<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\bootstrap4\Modal;
use kartik\select2\Select2;
use yii\bootstrap4\BootstrapAsset;
use yii\bootstrap4\BootstrapPluginAsset;

BootstrapAsset::register($this);
BootstrapPluginAsset::register($this);

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
$this->title = 'My Yii Application';
$this->context->layout = '../layouts/admin-main.php';
?>

<?php
Modal::begin([
    'id' => 'filterModal',
    'title' => 'Filter',
]);

?>
<?php $form = ActiveForm::begin(['id' => 'filter-form']); ?>
<div class="container">
    <div class="row">
        <?= $form->field($model, 'transaction_id', ['options' => ['class' => 'col-6']]) ?>
        <?= $form->field($model, 'wallet_id', ['options' => ['class' => 'col-6']]) ?>
        <?= $form->field($model, 'type', ['options' => ['class' => 'col-6']])->dropdownList(
            [
                'add' => 'Add',
                'deduct' => 'Deduct'
            ],
            ['prompt' => 'Select type']
        ) ?>
        <?= $form->field($model, 'created_after', ['options' => ['class' => 'col-6']])
            ->textInput(['type' => 'date', 'class' => 'form-control'])
            ?>
        <?= $form->field($model, 'created_before', ['options' => ['class' => 'col-6']])
            ->textInput(['type' => 'date', 'class' => 'form-control'])
            ?>
    </div>
    <?= Html::submitButton('Filter', ['class' => 'btn btn-primary mt-2', 'name' => 'filter-button']) ?>
</div>

<?php ActiveForm::end(); ?>
<?php

Modal::end();
?>

<!-- Add transaction form -->
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
        <?= $form->field($managementModel, 'description', ['options' => ['class' => 'col-6']]) ?>

        <?= $form->field($managementModel, 'wallet_id', ['options' => ['class' => 'col-6'],'labelOptions' => ['label' => 'User'],])
            ->widget(Select2::className(), [
                'data' => $wallet_username,
                // Replace $walletData with the actual data for the select options
                'options' => ['placeholder' => 'Select a wallet'],
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

        <?= $form->field($managementModel, 'type', ['options' => ['class' => 'col-6']])->dropdownList(
            [
                'add' => 'Add',
                'deduct' => 'Deduct'
            ],
            ['prompt' => 'Select type']
        ) ?>
    </div>
    <?= Html::submitButton('Add', ['class' => 'btn btn-primary mt-2', 'name' => 'add-button']) ?>
</div>
<?php
ActiveForm::end();
Modal::end();
?>

<!-- Delete user form -->
<?php $form = ActiveForm::begin(['id' => 'delete-form']); ?>
<?= $form->field($managementModel, 'transaction_id', ['options' => ['class' => 'col-6 invisible']])->textInput(['id' => 'delete-id']) ?>
<?= Html::submitButton('Delete', ['class' => 'btn btn-primary mt-2', 'name' => 'delete-button']) ?>
<?php ActiveForm::end(); ?>

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
                        <?= Html::a('Export to Excel', ['export-to-excel', 'format' => 'transaction-export'], ['class' => 'btn btn-primary']) ?>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#filterModal">
                            Filter
                        </button>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                            Add
                        </button>
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
                                    'attribute' => "Wallet ID",
                                    'value' => function ($model) {
                                                                return $model->wallet_id;
                                                            }
                                ],
                                [
                                    'attribute' => "username",
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
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

<?php
$this->registerJs("
    $('.delete-button').click(function () {
        var id = $(this).data('id');
        $('#delete-form').find('#delete-id').val(id);
        Swal.fire({
            title: 'Are you sure?',
            text: 'Are you sure you want to delete this transaction?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
        }).then((result) => {
            if (result.isConfirmed) {
                $('#delete-form').submit();
                Swal.fire('Transaction Deleted!', '', 'success');
            }
        })
    });
");
?>