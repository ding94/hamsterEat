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
use common\models\Rmanager;
use common\models\Restaurant;


AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <link rel="shortcut icon" type="image/png" href="SysImg/Icon.png">
        <?= Alert::widget(['options'=>[
        'style'=>'position:fixed;
                    top:80px;
                    right:25%;
                    width:50%;
                    z-index:5000;',
   ],]);?>
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
            'id' => 'uppernavbar'
        ],
    ]);
    $menuItems = [
        ['label' => 'About', 'url' => ['/site/about']],
        ['label' => '<span class="glyphicon glyphicon-shopping-cart"></span> Cart', 'url' => ['/cart/view-cart']],
    ];
    
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => '<span class="glyphicon glyphicon-user"></span> Signup', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => '<span class="glyphicon glyphicon-log-in"></span> Login', 'url' => ['/site/login']];
    }
       

     else {
        if (Rmanager::find()->where('uid=:id',[':id'=>Yii::$app->user->identity->id])->one()) {
            $restaurant = Restaurant::find()->where('Restaurant_Manager=:rm',[':rm'=>Yii::$app->user->identity->username])->all();
            $menuItems[] = ['label' => '<span class="glyphicon glyphicon-home"></span> Restaurants',

            ];
            foreach ($restaurant as $k => $each) {
            $menuItems[2]['items'][$k] = ['label' => $each['Restaurant_Name'],'url' => ['/Restaurant/default/restaurant-details','rid'=>$each['Restaurant_ID']]];
            $menuItems[2]['items'][$k+count($restaurant)] = '<li class="divider"></li>';
            }
        }
       
        $menuItems[] = ['label' => '<span class=""> <i class="fa fa-bell"></i>'.Yii::$app->view->params['countNotic'].'</span>'];
        $keys = array_keys($menuItems);

        if(empty(Yii::$app->view->params['notication']))
        {
            $menuItems[end($keys)]['items'][] = ['label' => '<h4 class="item-title">Empty Notication</h4>'];
        }
        else
        {
            $menuItems[end($keys)]['items'][] = ['label' => '<h4 class="menu-title">Notifications</h4>'];

            $menuItems[end($keys)]['items'][] = '<li class="divider"></li>';
            foreach(Yii::$app->view->params['notication'] as $i=> $notic)
            {

                $menuItems[end($keys)]['items'][] = ['label' => '<h4 class="item-title">'.Yii::$app->view->params['listOfNotic'][$i]['description'].'</h4>'];
                foreach($notic as $data)
                {
                    $ago = Yii::$app->formatter->asRelativeTime($data['created_at']);
                    if($data['type'] == 1)
                    {
                        $url = ["order/restaurant-orders",'rid' => $data['rid']];
                    }
                    else
                    {
                         $url = [Yii::$app->view->params['listOfNotic'][$i]['url']];
                    }
                   
                    $menuItems[end($keys)]['items'][] = ['label' => '<h4 class="item-info">'.$data['description'].' from <span class="right">'.$ago.'</span></h4>','url' => $url];
                }
            }
        }
        $menuItems[end($keys)]['items'][] = '<li class="divider"></li>';
        $menuItems[end($keys)]['items'][] = ['label' => '<h4 class="menu-title">View All</h4>','url' => ['notification/index']];
        $menuItems[] = ['label' => '' . Yii::$app->user->identity->username . '', 'items' => [
                       ['label' => 'Profile', 'url' => ['/user/user-profile']],
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

<div class="container1">
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
            ['label' => 'My Order','url' => Url::to(['/order/my-orders'])],
            ['label' => 'Order History','url' => Url::to(['/order/my-order-history'])],
        ]],
        ['label' =>'<i class="fa fa-money"></i> My Account','icon' => '','options' => ['class' => 'active'], 'items' => [
             ['label' => 'Account Balance', 'url' => Url::to(['/user/userbalance'])],
			['label' => 'Account History', 'url' => Url::to(['/topup-history/index'])],
           
        ]],
         ['label' => '<i class="glyphicon glyphicon-cog"></i> Member Settings','options' => ['class' => 'active'], 'items' => [
            ['label' => 'User Profile', 'url' => Url::to(['/user/user-profile'])],
            ['label' => 'Change Password', 'url' => Url::to(['/user/changepassword'])],
            ['label' => 'Discount Codes', 'url' => Url::to(['/vouchers/index'])],
        ]],
        ['label' => '<i class="fa fa-comments"></i> Customer Service', 'options' => ['class' => 'active'], 'items' => [
           ['label' => 'Ticket', 'url' => Url::to(['/ticket/index'])],
        ]],
         ['label' => 'Delivery Man', 'options' => ['class' =>'active'],
          'items'=>[
                        ['label' => 'Daily Sign In' , 'url' => Url::to(['/Delivery/daily-sign-in/index'])],
                        ['label' => 'Delivery Orders' , 'url' => Url::to(['/order/deliveryman-orders'])],
                        ['label' => 'Delivery Orders History' , 'url' => Url::to(['/order/deliveryman-order-history'])],
                    ],
             'visible'=> Yii::$app->user->can('rider'), 
        ],
          ['label' => ' My Restaurant', 'options' => ['class' => 'active'], 'items' => [
            ['label' => 'View Own Restaurant', 'url' => Url::to(['/Restaurant/default/view-restaurant'])],
            ['label' => 'Create New Restaurant', 'url' => Url::to(['/Restaurant/default/new-restaurant-location'])],
            ['label' => 'Manage Restaurant', 'url' => Url::to(['/Restaurant/restaurant/restaurant-service'])],
           ],
             'visible'=> Yii::$app->user->can('restaurant manager'), 
           ],
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

<!--<footer class="footer navbar-fixed-bottom">
    <div class="container">
        <p class="pull-left">&copy; hamsterEat <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>-->
	<!--Footer-->
	<footer id="Footer" class="footer">
        <div class="container-fluid">
		<!--Footer First Row-->
<!-- 		<div class="row"> -->
			<div id="Box1" class = "col-sm-3 col-xs-12">
                <h3 id="footertitle">HamsterEat</h3>
                <hr>
                <ul id="linklist" class="list-unstyled">
                    
                    <li><?php echo Html::a('About Us' ,['site/about']) ?></li>
                    <li><a href="../HomeCookedDelicacies/Help.php">Help</a></li>
                    <li><?php echo Html::a('Login' ,['site/login']) ?></li>
                    <li><?php echo Html::a('Signup' ,['site/ruser']) ?></li>
                </ul>
                
            </div>

            <div id="Box2" class = "col-sm-3 col-xs-12">
                <h3>Contact Us</h3>
                <hr>
                <ul id="linklist" class="list-unstyled">
                    <li> <?php echo Html::a('Contact' ,['site/contact']) ?></li>
                </ul>
                <p>Tel. 1700-818-315</p>

                <p>Email. support@hamsterEat.my</p>
                <a href="mailto:support@hamsterEat.my" target="_blank" class="btn btn-primary">Email Us</a>

            </div>
            
            <div id="Box3" class = "col-sm-3 col-xs-12">
                <h3>Follow | Get in Touch</h3>
                <hr>
                 <center>
                 <a target="_blank" href="https://www.facebook.com" class="btn btn-social-icon btn-facebook"><span class="fa fa-facebook"></span></a>
                 <a target="_blank" href="https://plus.google.com" class="btn btn-social-icon btn-google"><span class="fa fa-google"></span></a>
                 <a target="_blank" href="https://www.instagram.com" class="btn btn-social-icon btn-instagram"><span class="fa fa-instagram"></span></a>
                 </center>               
            </div>

		<!-- </div> -->
</div>
		
	</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
