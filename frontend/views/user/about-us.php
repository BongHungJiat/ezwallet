<?php


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
                <!--  Row 1 -->
                <div class="row">
                    <div class="col-lg-12">
                        <?php
                        foreach ($model as $post) {
                            ?>
                            <div class="row justify-content-center">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h1>
                                                <?php echo $post->title ?>
                                            </h1>
                                            <span>By <?php echo $post->username ?></span>
                                            <span>At <?php echo date_format(date_create($post->date_created),"d/m/y g:i A") ?></span>
                                            <div class="mb-3"></div>
                                            <?php echo $post->html ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
</body>