<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use kartik\widgets\SideNav;
use yii\helpers\Url;


AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <!--<link rel="stylesheet" href="\frontend\web\css\font-awesome.min.css">-->
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'hamsterEat',
        'brandUrl' => Yii::$app->homeUrl,
        'innerContainerOptions' => ['class' => 'container-fluid'],
        'options' => [
            'class' => 'topnav navbar-fixed-top MainNav',
        ],
    ]);
    $menuItems = [
        ['label' => 'About', 'url' => ['/site/about']],
        ['label' => 'Contact', 'url' => ['/site/contact']],
        ['label' => '<span class="glyphicon glyphicon-shopping-cart"></span> Cart', 'url' => ['/cart/view-cart']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => '<span class="glyphicon glyphicon-user"></span> Signup', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => '<span class="glyphicon glyphicon-log-in"></span> Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = ['label' => '' . Yii::$app->user->identity->username . '', 'items' => [
                       ['label' => 'Profile', 'url' => ['/user/user-profile']],
					   ['label' => 'Cart', 'url' => ['/cart/view-cart']],
                        '<li class="divider"></li>',
                       ['label' => 'Logout ', 'url' => ['/site/logout'],'linkOptions' => ['data-method' => 'post']],
                    ]];
       // $menuItems[] = ['label' => 'Create Restaurant', 'url' => ['Restaurant/default/new-restaurant-location'],'visible'=>Yii::$app->user->can('restaurant manager')];
     
        // $menuItems[] = '<li>'
        //     . Html::beginForm(['/site/logout'], 'post')
        //     . Html::submitButton(
        //         'Logout (' . Yii::$app->user->identity->username . ')',
        //         ['class' => 'btn btn-link logout']
        //     )
        //     . Html::endForm()
        //     . '</li>';
        //     ['label' => 'My Profile', 'url' => ['/user/user-profile']];
            
    }
     
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => $menuItems,
        
    ]);
    NavBar::end();
    ?>

<div class="row">
    <div class="sidenav col-md-3" >
        <div class="navbar-left">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
            <span class="sr-only">Toggle navigation</span> Side Menu <i class="fa fa-bars"></i>
        </button>
        </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
    <?php echo SideNav::widget([
    'encodeLabels' => false,
    'options' => ['class' => 'in'],
    'items' => [
        ['label' => '<i class="glyphicon glyphicon-list-alt"></i> My Order', 'options' => ['class' => 'active'], 'items' => [
            ['label' => 'My Order'],
           
        ]],
        ['label' =>'<i class="fa fa-money"></i> My Account','icon' => '','options' => ['class' => 'active'], 'items' => [
            ['label' => 'Top up', 'url' => Url::to(['topup/index'])],
            ['label' => 'Withdraw Money', 'url' => Url::to(['withdraw/index'])],
        ]],
         ['label' => '<i class="glyphicon glyphicon-cog"></i> Member Settings','options' => ['class' => 'active'], 'items' => [
            ['label' => 'User Profile', 'url' => Url::to(['user/user-profile'])],
            ['label' => 'Discount Codes', 'url' => Url::to(['vouchers/index'])],
        ]],
        ['label' => '<i class="fa fa-comments"></i> Customer Service', 'options' => ['class' => 'active'], 'items' => [
           ['label' => 'Ticket', 'url' => Url::to(['ticket/index'])],
        ]],
         ['label' => 'Delivery Man', 'options' => ['class' =>'active'],'items'=>[
                        ['label' => 'Daily Sign In' , 'url' => Url::to(['/Delivery/daily-sign-in/index'])],
            ]
        ],
          ['label' => ' My Restaurant', 'options' => ['class' => 'active'], 'items' => [
            ['label' => 'View Own Restaurant', 'url' => Url::to(['Restaurant/default/view-restaurant'])],
            ['label' => 'Create New Restaurant', 'url' => Url::to(['Restaurant/default/new-restaurant-location'])],
         
           
        ]],
]]);     
?>
</div>
</div>




    

    <div class="container" style="width: 100%; height: 100%;">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <div class="content">
        <?= $content ?>
    </div>
    </div>
</div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; hamsterEat <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
