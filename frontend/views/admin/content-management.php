<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\grid\GridViewAsset;
use yii\bootstrap4\Modal;
use dosamigos\ckeditor\CKEditor;

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */

/** @var yii\web\View $this */
$this->title = 'My Yii Application';
$this->context->layout = '../layouts/admin-main.php';
?>


<!-- add post form -->
<?php
Modal::begin([
    'id' => 'addModal',
    'size' => 'modal-xl',
    'options' => ['tabindex' => false],
    'title' => 'Add Post',
]);
$form = ActiveForm::begin(['id' => 'add-form']);
?>
<div class="container">
    <div class="row">
        <?= $form->field($model, 'title')->textInput() ?>
        <?= $form->field($model, 'html')->widget(CKEditor::className(), [
            'options' => ['rows' => 6],
            'preset' => 'advanced',
            'clientOptions' => ['readOnly' => false]
        ]) ?>
    </div>
    <?= Html::submitButton('Add', ['class' => 'btn btn-primary mt-2', 'name' => 'add-button']) ?>
</div>
<?php
ActiveForm::end();
Modal::end();
?>


<!-- edit post form -->
<?php
Modal::begin([
    'id' => 'editModal',
    'size' => 'modal-xl',
    'options' => ['tabindex' => false],
    'title' => 'Edit Post',
]);
$form = ActiveForm::begin(['id' => 'edit-form']);
?>
<div class="container">
    <div class="row">
        <?= $form->field($model, 'id', ['options' => ['class' => 'col-6 invisible']])->textInput(['id' => 'id']) ?>
        <?= $form->field($model, 'title')->textInput(['id' => 'edit-title']) ?>
        <?= $form->field($model, 'html')->widget(CKEditor::className(), [
            'options' => ['rows' => 6, 'id' => 'edit-html'],
            'preset' => 'advanced',
            'clientOptions' => ['readOnly' => false],
        ]) ?>
    </div>
    <?= Html::submitButton('Save', ['class' => 'btn btn-primary mt-2', 'name' => 'edit-button']) ?>
</div>
<?php
ActiveForm::end();
Modal::end();
?>



<!-- Delete post form -->
<?php $form = ActiveForm::begin(['id' => 'delete-form']); ?>
<?= $form->field($model, 'id', ['options' => ['class' => 'col-6 invisible']])->textInput(['id' => 'id']) ?>
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
                        <button type="button" class="btn btn-primary ms-2" data-toggle="modal" data-target="#addModal">
                            Add
                        </button>
                        <?= GridView::widget([
                            'dataProvider' => $sql,
                            'columns' => [
                                [
                                    'label' => 'Post ID',
                                    'attribute' => 'id',
                                    'value' => function ($model) {
                                                                return $model->id;
                                                            }
                                ],
                                [
                                    'attribute' => 'Admin Name',
                                    'value' => function ($model) {
                                                                return $model->username;
                                                            }
                                ],
                                [
                                    'attribute' => 'Title',
                                    'value' => function ($model) {
                                                                return $model->title;
                                                            }
                                ],
                                [
                                    'attribute' => 'updated_at',
                                    'value' => function ($model) {
                                                                return $model->updated_at;
                                                            }
                                ],
                                [
                                    'attribute' => 'date_created',
                                    'value' => function ($model) {
                                                                return $model->date_created;
                                                            }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{edit} {delete}',
                                    'buttons' => [
                                        'edit' => function ($url, $model, $key) {
                                                                    return Html::button('Edit', [
                                                                        'class' => 'btn btn-primary edit-button',
                                                                        'data-toggle' => 'modal',
                                                                        'data-target' => '#editModal',
                                                                        'data-id' => $model->id,
                                                                        'data-title' => $model->title,
                                                                        'data-html' => $model->html,
                                                                    ]);
                                                                },
                                        'delete' => function ($url, $model, $key) {
                                                                    return Html::button('Delete', [
                                                                        'class' => 'btn btn-danger delete-button',
                                                                        'data-id' => $model->id,
                                                                    ]);
                                                                },
                                    ],
                                ],
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<?php
$this->registerJs("
                $.fn.modal.Constructor.prototype.enforceFocus = function () {
                    modal_this = this
                    $(document).on('focusin.modal', function (e) {
                        if (modal_this.\$element[0] !== e.target && !modal_this.\$element.has(e.target).length
                            && !\$(e.target.parentNode).hasClass('cke_dialog_ui_input_select')
                            && !\$(e.target.parentNode).hasClass('cke_dialog_ui_input_text')) {
                            modal_this.\$element.focus()
                        }
                    })
                };

                $('.edit-button').click(function () {
                    var id = $(this).data('id');
                    var title = $(this).data('title');
                    var html = $(this).data('html');
                    var editor = CKEDITOR.instances['edit-html'];

                    $('#editModal').find('#id').val(id);
                    $('#editModal').find('#edit-title').val(title);
                    editor.setData(html);
                });

                $('.delete-button').click(function () {
                    var id = $(this).data('id');
                    $('#delete-form').find('#id').val(id);
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Are you sure you want to delete this posting?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#delete-form').submit();
                            Swal.fire('Posting Deleted!', '', 'success');
                        }
                    })
                });
            ");
?>
