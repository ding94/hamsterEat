<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Modal;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use kartik\widgets\SideNav;
use yii\helpers\Url;
use common\models\Rmanager;
use frontend\models\Deliveryman;
use common\models\Restaurant;
use frontend\assets\NotificationAsset;
use frontend\assets\UserAsset;

AppAsset::register($this);
NotificationAsset::register($this);
UserAsset::register($this);
?>
<style>
    span.badge{
        background-color:#404040;
        margin-left: 2px;
        margin-bottom:5px;
    }
    #cart{
        line-height:33px;
    }
    #cart1{
        line-height:33px;
    }
    #feedback-modal .modal-content{
        width:800px;
        margin-left: -230px;
        margin-top: 100px;
        height: 620px;
    }
    </style>
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
<?php Modal::begin([
            'header' => '<h2 class="modal-title">Feedback</h2>',
            'id'     => 'feedback-modal',
            'size'   => 'modal-sm',
            //'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
    
    Modal::end() ?>
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
        ['label' => 'Guide', 'url' => ['/site/faq']],
        //   ['label' => '<span id="cart" class="glyphicon glyphicon-shopping-cart"><span class="badge">'.Yii::$app->view->params['number'].'</span></span> ', 'url' => ['/cart/view-cart']],
    ];
    
    if (Yii::$app->user->isGuest) {
          $menuItems[] = ['label' => '<span id ="cart1" class="glyphicon glyphicon-shopping-cart"></span> ', 'url' => ['/cart/view-cart']];
        $menuItems[] = ['label' => '<span class="glyphicon glyphicon-user"></span> Signup', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => '<span class="glyphicon glyphicon-log-in"></span> Login', 'url' => ['/site/login']];
    }
       

     else {
          $menuItems[] = ['label' => '<span id="cart" class="glyphicon glyphicon-shopping-cart"><span class="badge">'.Yii::$app->view->params['number'].'</span></span> ', 'url' => ['/cart/view-cart']];
        /*if (Rmanager::find()->where('uid=:id',[':id'=>Yii::$app->user->identity->id])->one()) {
            $restaurant = Restaurant::find()->where('Restaurant_Manager=:rm',[':rm'=>Yii::$app->user->identity->username])->all();
            $menuItems[] = ['label' => '<span class="glyphicon glyphicon-home"></span> Restaurants',

            ];
            foreach ($restaurant as $k => $each) {
            $menuItems[2]['items'][$k] = ['label' => $each['Restaurant_Name'],'url' => ['/Restaurant/default/restaurant-details','rid'=>$each['Restaurant_ID']]];
            $menuItems[2]['items'][$k+count($restaurant)] = '<li class="divider"></li>';
            }
        }*/
       
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
        $menuItems[end($keys)]['items'][] = ['label' => '<h4 class="menu-title">View All</h4>','url' => ['/notification/index']];
        $menuItems[] = ['label' => '' . Yii::$app->user->identity->username . '', 'items' => [
                       ['label' => 'Profile', 'url' => ['/user/user-profile'] ,'options'=> ['class'=>'list-user'],],
                        '<li class="divider"></li>',
                    ]];
         $keys = array_keys($menuItems);
        if (Rmanager::find()->where('uid=:id',[':id'=>Yii::$app->user->identity->id])->one()) {
                $menuItems[end($keys)]['items'][] =['label' => 'Restaurants ', 'url' => ['/Restaurant/restaurant/restaurant-service'],'linkOptions' => ['data-method' => 'post']];
                $menuItems[end($keys)]['items'][] = '<li class="divider"></li>';
        }
        if (Deliveryman::find()->where('User_id=:id',[':id'=>Yii::$app->user->identity->id])->one()){
                $menuItems[end($keys)]['items'][] =['label' => 'Delivery Orders', 'url' => ['/order/deliveryman-orders'],'linkOptions' => ['data-method' => 'post']];
                $menuItems[end($keys)]['items'][] = '<li class="divider"></li>';
        }
        $menuItems[end($keys)]['items'][] = ['label' => 'Logout ', 'url' => ['/site/logout'],'linkOptions' => ['data-method' => 'post']];

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
</div>

    <div class="container page-wrap">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <div class="container vertical-divider">
            <ul id="nav" class="nav nav-default">
                <li role="presentation"><label class="label-btn"><i class="fa fa-user fa-lg"></i>&nbsp;<?php echo Html::a('Profile',['/user/user-profile'])?></label></li>
                <li role="presentation"><label class="label-btn"><i class="fa fa-money fa-lg"></i>&nbsp;<?php echo Html::a('Balance',['/user/userbalance'])?></label></li>
                <li role="presentation"><label class="label-btn"><i class="fa fa-cutlery fa-lg"></i>&nbsp;<?php echo Html::a('Order',['/order/my-orders'])?></label></li>
                <li role="presentation"><label class="label-btn"><i class="fa fa-comment fa-lg"></i>&nbsp;<?php echo Html::a('Messages',['/ticket/index'])?></label></li>
                <li role="presentation"><label id="voucher" class="label-btn"><i class="fa fa-comment fa-lg"></i>&nbsp;<?php echo Html::a('Vouchers',['/vouchers/index'])?></label></li>
            </ul>
        </div>
        <div class="content">
            <?= $content ?>
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
                    <li><?php echo Html::a('Feedback', Url::to(['/site/feed-back', 'link'=>Yii::$app->request->url]), ['data-toggle'=>'modal','data-target'=>'#feedback-modal']) ?></li>
                    <li><?php echo Html::a('About Us' ,['site/about']) ?></li>
                    <li><?php echo Html::a('Guide' ,['site/faq']) ?></li>
                    <li><a href="../HomeCookedDelicacies/Help.php">Help</a></li>
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
                 <a target="_blank" href="https://www.facebook.com" class="btn btn-social-icon btn-facebook" title="Facebook"><span class="fa fa-facebook"></span></a>
                 <a target="_blank" href="https://plus.google.com" class="btn btn-social-icon btn-google" title="Google +"><span class="fa fa-google"></span></a>
                 <a target="_blank" href="https://www.instagram.com" class="btn btn-social-icon btn-instagram" title="Instagram"><span class="fa fa-instagram"></span></a>
                 </center>               
            </div>

		<!-- </div> -->
</div>
		
	</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>