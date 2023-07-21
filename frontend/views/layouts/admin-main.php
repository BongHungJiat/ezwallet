<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\web\JqueryAsset;
use yii\bootstrap4\NavBar;
use kartik\sidenav\SideNav;


AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
  <meta charset="<?= Yii::$app->charset ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <?php $this->registerCsrfMetaTags() ?>
  <title>
    <?= Html::encode($this->title) ?>
  </title>
  <?php $this->head() ?>
  <?php $this->registerCssFile("../css/styles.min.css"); ?>
  <?php $this->registerCssFile("../css/styles.css"); ?>
</head>

<body class="d-flex flex-column h-100">
  <?php $this->beginBody() ?>

  <header>
    <?php
    // NavBar::begin([
    //     'brandLabel' => Yii::$app->name,
    //     'brandUrl' => Yii::$app->homeUrl,
    //     'options' => [
    //         'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
    //     ],
    // ]);
    // $menuItems = [
    //     ['label' => 'Home', 'url' => ['/site/index']],
    //     ['label' => 'About', 'url' => ['/site/about']],
    //     ['label' => 'Contact', 'url' => ['/site/contact']],
    // ];
    // if (Yii::$app->user->isGuest) {
    //     $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
    //     $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    // } else {
    //     $menuItems[] = '<li>'
    //         . Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
    //         . Html::submitButton(
    //             'Logout (' . Yii::$app->user->identity->username . ')',
    //             ['class' => 'btn btn-link logout']
    //         )
    //         . Html::endForm()
    //         . '</li>';
    // }
    // echo Nav::widget([
    //     'options' => ['class' => 'navbar-nav ml-auto'],
    //     'items' => $menuItems,
    // ]);
    // NavBar::end();
    if ($this->context->getView()->context->action->id !== 'login' && $this->context->getView()->context->action->id !== 'signup') { ?>

      <?= Alert::widget() ?>
      <aside class="left-sidebar">

        <!-- Sidebar scroll-->
        <div>
          <div class="d-flex flex-column align-items-center justify-content-around">
            <h4 class="mt-3">Welcome</h4>
            </br>
            <p>
              <?php
              if (!Yii::$app->admin->isGuest) {
                echo Yii::$app->admin->identity->username;

              } else {
                echo "Guests";
              }
              ?>
            </p>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
              <i class="ti ti-x fs-8"></i>
            </div>
          </div>
          <!-- Sidebar navigation-->
          <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">Home</span>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link" href="/admin" aria-expanded="false">
                  <span>
                    <i class="ti ti-layout-dashboard"></i>
                  </span>
                  <span class="hide-menu">Dashboard</span>
                </a>
              </li>
              <?php
              if (Yii::$app->admin->isGuest) {
                ?>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="./login" aria-expanded="false">
                    <span>
                      <i class="ti ti-layout-dashboard"></i>
                    </span>
                    <span class="hide-menu">Login</span>
                  </a>
                </li>
                <?php
              } else {
                ?>
                <li class="sidebar-item">
                  <?php echo Html::a('
                  <span>
                    <i class="ti ti-layout-dashboard"></i>
                  </span>
                  <span class="hide-menu">Logout</span>', ['admin/logout'], ['data-method' => 'post', 'class' => 'sidebar-link']); ?>

                </li>
                <?php
              }
              ?>

            </ul>
          </nav>
          <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->
      </aside>
      <!--  Sidebar End -->
    <?php } ?>
  </header>

  <main role="main" class="flex-shrink-0">
    <?= Breadcrumbs::widget([
      'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    <?= $content ?>
  </main>

  <footer class="footer mt-auto py-3 text-muted">
    <div class="container">
      <p class="float-left">&copy;
        <?= Html::encode(Yii::$app->name) ?>
        <?= date('Y') ?>
      </p>
      <p class="float-right">
        <?= Yii::powered() ?>
      </p>
    </div>
    <?php
    $this->registerCssFile("../js/app.min.js");
    $this->registerCssFile("../js/dashboard.js");
    $this->registerCssFile("../js/sidebarmenu.js");
    $this->registerJsFile("@web/js/jquery.min.js");
    $this->registerJsFile("@web/js/sweetalert.js");
    ?>

  </footer>


  <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage();