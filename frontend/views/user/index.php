<?php

use phpDocumentor\Reflection\DocBlock\Description;
use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\grid\GridViewAsset;
use yii\bootstrap4\Modal;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
$this->title = 'My Yii Application';
$balance = 0;
foreach ($data as $transaction) {
  if ($transaction['type'] == 'deduct') {
    $balance -= $transaction['amount'];
  } else {
    $balance += $transaction['amount'];
  }
}
?>


<?php
Modal::begin([
  'id' => 'reloadModal',
  'options' => ['tabindex' => false],
  'title' => 'Add Transaction',
]);
$form = ActiveForm::begin([
  'id' => 'reload-form',
  'method' => 'get'
]);

?>
<div class="container">
  <div class="row">

    <?= $form->field($reloadModal, 'amount', ['options' => ['class' => 'col-6']])
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
  <?= Html::submitButton('Reload', ['class' => 'btn btn-primary mt-2', 'name' => 'reload-button']) ?>
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
            <div class="row">
              <div class="col-lg-4">
                <!-- Yearly Breakup -->
                <div class="card">
                  <div class="card-body">
                    <div class="row alig n-items-start">
                      <div class="col-8">
                        <h5 class="card-title mb-9 fw-semibold"> E-Wallet Balance </h5>
                        <h4 class="fw-semibold mb-3">RM
                          <?php echo $balance ?>
                        </h4>
                        <button type="button" class="btn btn-primary ms-2" data-toggle="modal"
                          data-target="#reloadModal">
                          Reload
                        </button>
                      </div>
                    </div>
                  </div>
                  <div id="earning"></div>
                </div>
              </div>
              <div class="col-lg-4">
                <!-- Monthly Earnings -->
                <div class="card">
                  <div class="card-body">
                    <div class="row alig n-items-start">
                      <div class="col-8">
                        <h5 class="card-title mb-9 fw-semibold">Monthly Expenses</h5>
                        <h4 class="fw-semibold mb-3">RM
                          <?php echo $monthlySpending ?>
                        </h4>
                      </div>
                    </div>
                  </div>
                  <div id="earning"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-12 d-flex align-items-stretch">
          <div class="card w-100">
            <div class="card-body p-4">
              <h5 class="card-title fw-semibold mb-4">Recent Transactions</h5>
              <div class="table-responsive">
                <table class="table text-nowrap mb-0 align-middle">
                  <thead class="text-dark fs-4">
                    <tr>
                      <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Id</h6>
                      </th>
                      <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Description</h6>
                      </th>
                      <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Amount</h6>
                      </th>
                      <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Date</h6>
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    for ($i = 0; $i < 4 && $i < count($data); $i++) {
                      ?>
                      <tr>
                        <td class="border-bottom-0">
                          <h6 class="fw-semibold mb-0">
                            <?php echo $data[$i]['transaction_id'] ?>
                          </h6>
                        </td>
                        <td class="border-bottom-0">
                          <span class="fw-semibold mb-1">
                            <?php echo $data[$i]['description'] ?>
                          </span>
                        </td>
                        <td class="border-bottom-0">
                          <p class="mb-0 fw-normal">
                            <?php echo $data[$i]['type'] == "add" ? "+" : "-" ?> RM
                            <?php echo $data[$i]['amount'] ?>
                          </p>
                        </td>
                        <td class="border-bottom-0">
                          <h6 class="fw-semibold mb-0 fs-4">
                            <?php echo DateTime::createFromFormat('Y-m-d H:i:s', $data[$i]['date_created'])->format("d-m-Y g:i A") ?>
                          </h6>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>

  </div>
  </div>
  </div>
</body>