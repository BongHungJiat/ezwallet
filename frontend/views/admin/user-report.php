<?php

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
$this->context->layout = '../layouts/admin-main.php';
GridViewAsset::register($this);
?>

<!-- Filter result form -->
<?php
Modal::begin([
	'id' => 'filterModal',
	'title' => 'Filter',
]);
$form = ActiveForm::begin([
	'id' => 'filter-form',
	'method' => 'get',
]);
?>
<div class="container">
	<div class="row">
		<?= $form->field($model, 'id', ['options' => ['class' => 'col-6']]) ?>
		<?= $form->field($model, 'username', ['options' => ['class' => 'col-6']]) ?>
		<?= $form->field($model, 'email', ['options' => ['class' => 'col-6']]) ?>
		<?= $form->field($model, 'status', ['options' => ['class' => 'col-6']])->dropdownList(
			[
				9 => 'Inactive',
				10 => 'Active'
			],
			['prompt' => 'Select Status']
		); ?>
		<?= $form->field($model, 'created_after', ['options' => ['class' => 'col-6']])
			->textInput(['type' => 'date', 'class' => 'form-control'])
			?>
		<?= $form->field($model, 'created_before', ['options' => ['class' => 'col-6']])
			->textInput(['type' => 'date', 'class' => 'form-control'])
			?>
	</div>
	<?= Html::submitButton('Filter', ['class' => 'btn btn-primary mt-2', 'name' => 'filter-button']) ?>
</div>
<?php
ActiveForm::end();
Modal::end();
?>

<!-- Update user profile form -->
<?php
Modal::begin([
	'id' => 'editModal',
	'title' => 'Edit Profile',
]);
$form = ActiveForm::begin(['id' => 'update-form']);
?>
<div class="container">
	<div class="row">
		<?= $form->field($userModel, 'username', ['options' => ['class' => 'col-6']])->textInput(['id' => 'edit-username']) ?>
		<?= $form->field($userModel, 'email', ['options' => ['class' => 'col-6']])->textInput(['id' => 'edit-email']) ?>
		<?= $form->field($userModel, 'password', ['options' => ['class' => 'col-6 mt-2']])->passwordInput()->label('Password', ['id' => 'edit-password']) ?>
		<?= $form->field($userModel, 'id', ['options' => ['class' => 'col-6 invisible']])->textInput(['id' => 'edit-id']) ?>
	</div>
	<?= Html::submitButton('Update', ['class' => 'btn btn-primary mt-2', 'name' => 'update-button']) ?>
</div>
<?php
ActiveForm::end();
Modal::end();
?>

<!-- Add user form -->
<?php
Modal::begin([
	'id' => 'addModal',
	'title' => 'Add User',
]);
$form = ActiveForm::begin(['id' => 'add-form']);
?>
<div class="container">
	<div class="row">
		<?= $form->field($userModel, 'username')->textInput(['autofocus' => true]) ?>
		<?= $form->field($userModel, 'email') ?>
		<?= $form->field($userModel, 'password')->passwordInput() ?>
	</div>
	<?= Html::submitButton('Add', ['class' => 'btn btn-primary mt-2', 'name' => 'add-button']) ?>
</div>
<?php
ActiveForm::end();
Modal::end();
?>

<!-- Delete user form -->
<?php $form = ActiveForm::begin(['id' => 'delete-form']); ?>
<?= $form->field($userModel, 'id', ['options' => ['class' => 'col-6 invisible']])->textInput(['id' => 'delete-id']) ?>
<?= Html::submitButton('Delete', ['class' => 'btn btn-primary mt-2', 'name' => 'delete-button']) ?>
<?php ActiveForm::end(); ?>

<?php
$this->registerJs("
		$(document).ready(function () {
			$('.edit-button').click(function () {
				var id = $(this).data('id');
				var username = $(this).data('username');
				var email = $(this).data('email');
	
				$('#editModal').find('#edit-username').val(username);
				$('#editModal').find('#edit-email').val(email);
				$('#editModal').find('#edit-id').val(id);
			});
	
	
			$('.delete-button').click(function () {
				var id = $(this).data('id');
				$('#delete-form').find('#delete-id').val(id);
				Swal.fire({
					title: 'Are you sure?',
					text: 'Are you sure you want to delete this user?',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: 'Yes',
				}).then((result) => {
					if (result.isConfirmed) {
						$('#delete-form').submit();
						Swal.fire('User Deleted!', '', 'success');
					}
				})
			});
		});
		");
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
							<?= Html::a('Export to Excel', ['export-to-excel', 'format' => '_gridview'], ['class' => 'btn btn-primary']) ?>
							<button type="button" class="btn btn-primary ms-2" data-toggle="modal"
								data-target="#addModal">
								Add
							</button>
							<button type="button" class="btn btn-primary ms-2" data-toggle="modal"
								data-target="#filterModal">
								Filter
							</button>
						</div>
						<?= GridView::widget([
							'dataProvider' => $sql,
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
																		'data-username' => $model->username,
																		'data-email' => $model->email,
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