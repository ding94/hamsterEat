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
use iutbay\yii2fontawesome\FontAwesome as FA;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet">
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
        'brandLabel' => Html::img('@web/SysImg/Logo.png'),
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
        $menuItems[] = ['label' => '<span class="glyphicon glyphicon-user"></span> Signup', 'url' => ['/site/ruser']];
        $menuItems[] = ['label' => '<span class="glyphicon glyphicon-log-in"></span> Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = ['label' => '' . Yii::$app->user->identity->username . '', 'items' => [
                       ['label' => 'Profile', 'url' => ['/user/user-profile']],
                        '<li class="divider"></li>',
                       ['label' => 'Logout ', 'url' => ['/site/logout'],'linkOptions' => ['data-method' => 'post']],
                    ]];
        
       //  $menuItems = ['label' => 'Create Restaurant', 'url' => ['Restaurant/default/new-restaurant-location'],'visible'=>Yii::$app->user->can('restaurant manager')];
      
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
	 </div>

<!--<div class="container-fluid" ">-->

        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <!--<div class="container">-->
            <?= $content ?>
       <!-- </div>-->

<!--
<footer class="footer ">
    <div class="container">
        <p class="pull-left">&copy; hamsterEat <?= date('Y') ?></p>

       
    </div>
</footer>-->
	<!--Footer-->
	<footer id="Footer" class="container-fluid footer">
		<!--Footer First Row-->
		<div class="row">
			<div id="Box1" class = "col-sm-3 col-xs-12">
				<h3 id="footertitle">HamsterEat</h3>
				<hr>
				<ul id="linklist" class="list-unstyled">
					<li><a href="../HomeCookedDelicacies/">Home</a></li>
					<li><a href="../HomeCookedDelicacies/ChooseUpgrade.php">Join Us</a></li>
					<li><a href="../web/index.php?r=site/about">About Us</a></li>
					<li><a href="../HomeCookedDelicacies/Help.php">Help</a></li>
					<li><a href="../HomeCookedDelicacies/">Login</a></li>
					<li><a href="../HomeCookedDelicacies/Registration.php">Signup</a></li>
				</ul>
				
			</div>

			<div id="Box2" class = "col-sm-3 col-xs-12">
				<h3>Contact Us</h3>
				<hr>
                <ul id="linklist" class="list-unstyled">
                    <li><a href="../web/index.php?r=site/contact">Contact</a></li>
                </ul>
				<p>Tel. 1700-818-315</p>
				<p>Email. cs@sgshop.com.my</p>
				<a href="mailto:cs@sgshop.com.my" target="_blank" class="btn btn-primary">Email Us</a>
			</div>
			
			<div id="Box3" class = "col-sm-3 col-xs-12">
				<h3>Follow | Get in Touch</h3>
				<hr>
				 <center>
				 <a target="_blank" href="https://twitter.com" class="btn btn-social-icon btn-twitter"><span class="fa fa-twitter"></span></a>
				 <a target="_blank" href="https://www.facebook.com" class="btn btn-social-icon btn-facebook"><span class="fa fa-facebook"></span></a>
				 <a target="_blank" href="https://plus.google.com" class="btn btn-social-icon btn-google"><span class="fa fa-google"></span></a>
				 <a target="_blank" href="https://www.instagram.com" class="btn btn-social-icon btn-instagram"><span class="fa fa-instagram"></span></a>
				 </center>				 
			</div>

		</div>

		
	</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
