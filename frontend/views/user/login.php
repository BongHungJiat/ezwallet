<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
$this->title = 'My Yii Application';

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
?>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-6 col-lg-3 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <h3 class="text-center">Login</h3>

                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>
                <div class="form-group">
                  <?= Html::submitButton('Login', ['class' => 'btn btn-primary w-100', 'name' => 'login-button']) ?>
                </div>
                <div class="d-flex flex-column align-items-center justify-content-center">
                  <p class="fs-4 mb-0 fw-bold">New to EZWallet?</p>
                  <a class="text-primary fw-bold ms-2" href="./signup">Create an account</a>
                </div>
                <?php ActiveForm::end(); ?>



              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>