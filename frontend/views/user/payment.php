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
?>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!--  Main wrapper -->
        <div class="body-wrapper pt-0">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <!--  Row 1 -->
                    <div class="w-75">
                        <form id="payment-form">
                            <div id="link-authentication-element">
                                <!--Stripe.js injects the Link Authentication Element-->
                            </div>
                            <div id="payment-element">
                                <!--Stripe.js injects the Payment Element-->
                            </div>
                            <button id="submit" class="btn btn-primary mt-3">
                                <div class="spinner hidden" id="spinner"></div>
                                <span id="button-text">Pay now</span>
                            </button>
                            <div id="payment-message" class="hidden"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="https://js.stripe.com/v3/"></script>
<script>
    var client_secret = <?= json_encode($intent) ?>;
</script>

<?php
    $this->registerJsFile("../js/payment.js");
?>