<?php

use phpDocumentor\Reflection\DocBlock\Description;

/** @var yii\web\View $this */
$this->title = 'My Yii Application';
$this->context->layout = '../layouts/admin-main.php';
?>


<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <!--  Main wrapper -->
        <div class="body-wrapper pt-0">
            <div class="container-fluid">
                <!--  Row 1 -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                        <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <a href="/admin/content-management">
                                            <h3 class="text-center">CMS</h3>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <a href="/admin/user-report">
                                            <h3 class="text-center">User Report</h3>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <a href="/admin/transaction-report">
                                            <h3 class="text-center">Transaction Report</h3>
                                        </a>
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