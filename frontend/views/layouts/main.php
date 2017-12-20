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
use yii\helpers\Json;
use common\models\Rmanagerlevel;
use common\models\Rmanager;
use frontend\models\Deliveryman;
use common\models\Restaurant;
use common\models\Order\Orderitem;
use yii\bootstrap\Modal;
use frontend\assets\NotificationAsset;
use common\models\Company\Company;
use common\models\Order\Orders;

AppAsset::register($this);
NotificationAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="SysImg/Icon.png">
    <?= Alert::widget(['options'=>[
        'style'=>'position:fixed;
                top:80px;
                right:25%;
                width:50%;
                z-index:5000;',
    ],]);?> 
    <div id="system-messages">
        
    </div>
    <?= Html::csrfMetaTags() ?>
    <!--<link rel="stylesheet" href="\frontend\web\css\font-awesome.min.css">-->
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php Modal::begin([
            'header' => '<h2 class="modal-title">Feedback</h2>',
            'id'     => 'feedback-modal',
            'size'   => 'modal-md',
            //'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
    
    Modal::end() ?>

<?php Modal::begin([
            'header' => '<h2 class="modal-title">Login</h2>',
            'id'     => 'login-modal',
            'size'   => 'modal-md',
            //'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
    
    Modal::end() ?>

    <?php Modal::begin([
            'header' => '<h2 class="modal-title">Placed Orders</h2>',
            'id'     => 'add-modal',
            'size'   => 'modal-lg',
            'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
    
    Modal::end() ?>


<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Html::img('@web/SysImg/Logo.png' ,['id'=>'logo']),

        'brandUrl' => Yii::$app->homeUrl,
        'innerContainerOptions' => ['class' => 'container'],
        'options' => [
            'class' => 'topnav navbar-fixed-top MainNav',
            'id' => 'uppernavbar'
        ],
    ]);
   /* $menuItems = [
        ['label' => 'About', 'url' => ['/site/about']],
        ['label' => 'Guide', 'url' => ['/site/faq']],
       
         //['label' => '<span class="glyphicon glyphicon-shopping-cart"><span class="badge">'.Yii::$app->view->params['number'].'</span></span> ', 'url' => ['/cart/view-cart']],
    ];*/
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => '<span class="glyphicon glyphicon-shopping-cart cart"></span> Cart ', 'url' => ['/cart/view-cart']];
        $menuItems[] = ['label' => '<span class="glyphicon glyphicon-user"></span> Signup', 'url' => ['/site/ruser']];
        $menuItems[] = ['label' => '<span class="glyphicon glyphicon-log-in"></span> Login', 'url' => ['/site/login-popup'],'linkOptions'=>['data-toggle'=>'modal','data-target'=>'#login-modal']]; 
    } else {
        $menuItems[] = ['label' => '<span class="glyphicon glyphicon-shopping-cart cart"><span class="badge">'.Yii::$app->view->params['number'].'</span></span>', 'url' => ['/cart/view-cart']];
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

                $menuItems[end($keys)]['items'][] = ['label' => '<h4 class="item-title">'.Yii::$app->view->params['listOfNotic'][$i]['description'].'</h4>' ];
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
        $rmanager = Rmanager::find()->where('uid=:id',[':id'=>Yii::$app->user->identity->id])->one();
        $menuItems[end($keys)]['items'][] = '<li class="divider"></li>';
        $menuItems[end($keys)]['items'][] = ['label' => '<h4 class="menu-title">View All</h4>','url' => ['/notification/index']];
        if (!empty($rmanager)) {
            $menuItems[] = ['label' => '<span class="glyphicon glyphicon-list-alt">'];
            $key = array_keys($menuItems);
            $lvl = Rmanagerlevel::find()->where('User_Username=:u',[':u'=>$rmanager['User_Username']])->all();

            $count = 0;
            foreach ($lvl as $k => $level) {
                $restaurant = Restaurant::find()->where('Restaurant_ID=:rid',[':rid'=>$level['Restaurant_ID']])->one();
                $orderitem = Orderitem::find()->where('Restaurant_ID=:id AND OrderItem_Status=:s',[':id'=>$level['Restaurant_ID'],':s'=>'Pending'])->joinwith(['food'])->count();
                if ($orderitem > 0) {
                    $menuItems[end($key)]['items'][] = ['label'=>$restaurant['Restaurant_Name'].'('.$orderitem.')','url'=>['/Restaurant/restaurant/cooking-detail','rid'=>$level['Restaurant_ID']]];
                    $menuItems[end($key)]['items'][] = '<li class="divider"></li>';
                }
                $count += $orderitem;
            }
            if ($count <= 0){
                $menuItems[end($key)]['items'][] = ['label'=>'Empty Orders'];
                $menuItems[end($key)]['items'][] = '<li class="divider"></li>';
            }
        }
        $menuItems[] = ['label' => '<span class="glyphicon glyphicon-user"></span><span class="username"> ' . Yii::$app->user->identity->username . '</span>', 'items' => [
                       ['label' => 'Profile', 'url' => ['/user/user-profile']],
                        '<li class="divider"></li>',
                       ]];

         $keys = array_keys($menuItems);
        if (!empty($rmanager)) {
                $menuItems[end($keys)]['items'][] =['label' => 'Restaurants ', 'url' => ['/Restaurant/restaurant/restaurant-service'],'linkOptions' => ['data-method' => 'post']];
                $menuItems[end($keys)]['items'][] = '<li class="divider"></li>';
        }
        if (Deliveryman::find()->where('User_id=:id',[':id'=>Yii::$app->user->identity->id])->one()){
                $menuItems[end($keys)]['items'][] =['label' => 'Delivery Orders', 'url' => ['/order/deliveryman-orders'],'linkOptions' => ['data-method' => 'post']];
                $menuItems[end($keys)]['items'][] = '<li class="divider"></li>';
        }
        /*if ($company = Company::find()->where('owner_id=:id',[':id'=>Yii::$app->user->identity->id])->one()) {
            $menuItems[end($keys)]['items'][] =['label' => 'Company', 'url' => ['/company/index']];
            $menuItems[end($keys)]['items'][] = '<li class="divider"></li>';
        }*/
        $menuItems[end($keys)]['items'][] = ['label' => 'Logout ', 'url' => ['/site/logout'],'linkOptions' => ['data-method' => 'post']];
                    //var_dump($menuItems);exit;
        
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
        'options' => ['class' => 'navbar-nav navbar-right dropdown'],
        'encodeLabels' => false,
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>
	 </div>

     <nav id="bottom-navbar">
            <div>
                <ul>
                    <?php if(Yii::$app->user->isGuest){ ?>
                    <li><?php echo Html::a('<span class="glyphicon glyphicon-shopping-cart cart"><span class="badge">'.Yii::$app->view->params['number'].'</span></span>',['/cart/view-cart']);?></li>
                    <li><?php echo Html::a('<span class=""><i class="fa fa-bell"></i>'.Yii::$app->view->params['countNotic'].'</span>',['/notification/index']);?></li>
                    <li><?php echo Html::a('<span class="glyphicon glyphicon-log-in"></span>',['/site/login-popup'],['data-toggle'=>'modal','data-target'=>'#login-modal']);?></li>
                    <?php } elseif(Rmanager::find()->where('uid=:id',[':id'=>Yii::$app->user->identity->id])->one()) { ?>
                    <li><?php echo Html::a('<span class="glyphicon glyphicon-shopping-cart cart"><span class="badge">'.Yii::$app->view->params['number'].'</span></span>',['/cart/view-cart']);?></li>
                    <li><?php echo Html::a('<span class=""><i class="fa fa-bell"></i>'.Yii::$app->view->params['countNotic'].'</span>',['/notification/index']);?></li>

                    <?php if (!empty($rmanager)): ?>
                        <?php 
                            $count = 0;
                            foreach ($lvl as $k => $level) {
                                $orderitem = Orderitem::find()->where('Restaurant_ID=:id AND OrderItem_Status=:s',[':id'=>$level['Restaurant_ID'],':s'=>'Pending'])->joinwith(['food'])->count();
                                $count += $orderitem;
                            }
                            echo Html::a('<span class="glyphicon glyphicon-list-alt">('.$count.')</span>',['/Restaurant/restaurant/phonecooking'],['data-toggle'=>'modal','data-target'=>'#add-modal']);
                        ?>
                    <?php endif;?>

                    <li><?php echo Html::a('<i class="fa fa-cutlery"></i>',['/Restaurant/restaurant/restaurant-service']);?></li>
                    <li><?php echo Html::a('<span class="glyphicon glyphicon-user">',['/user/user-profile']);?></li>
                    <?php } elseif(Deliveryman::find()->where('User_id=:id',[':id'=>Yii::$app->user->identity->id])->one()){ ?>
                    <li><?php echo Html::a('<span class="glyphicon glyphicon-shopping-cart cart"><span class="badge">'.Yii::$app->view->params['number'].'</span></span>',['/cart/view-cart']);?></li>
                    <li><?php echo Html::a('<span class=""><i class="fa fa-bell"></i>'.Yii::$app->view->params['countNotic'].'</span>',['/notification/index']);?></li>
                    <li><?php echo Html::a('<i class="fa fa-truck"></i>',['/order/deliveryman-orders']);?></li>
                    <li><?php echo Html::a('<span class="glyphicon glyphicon-user">',['/user/user-profile']);?></li>
                    <?php } else{ ?>
                    <li><?php echo Html::a('<span class="glyphicon glyphicon-shopping-cart cart"><span class="badge">'.Yii::$app->view->params['number'].'</span></span>',['/cart/view-cart']);?></li>
                    <li><?php echo Html::a('<span class=""><i class="fa fa-bell"></i>'.Yii::$app->view->params['countNotic'].'</span>',['/notification/index']);?></li>
                    <li><?php echo Html::a('<span class="glyphicon glyphicon-user">',['/user/user-profile']);?></li>
                    <?php } ?>
                </ul>
            </div>
        </nav>

<!--<div class="container-fluid" ">-->

        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?php  $cookies = Yii::$app->request->cookies;?>
        <?php $emptyCookie = empty($cookies['halal']) ? 1: 0?>
		
        <div id="type-modal" class="modal fade" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body food-type">
                        <p>Please Select Type</p>
                        <?php echo Html::hiddenInput('cookie',$emptyCookie) ?>
                        <div class="row">
                            <div class="col-xs-6 non-halal box">
                                <?php echo Html::a('<span>Non-HALAL<i class="fa fa-check"></i></span>','#')?>
                            </div>
                            <div class="col-xs-6 halal box">
                                <?php echo Html::a('<span>HALAL<i class="fa fa-check"></i></span>','#')?>
                            </div>
                        </div>       
                    </div>
                </div>
            </div>
        </div>
        <div class="page-wrap">
            <?= $content ?>
        </div>

<!--
<footer class="footer ">
    <div class="container">
        <p class="pull-left">&copy; hamsterEat <?= date('Y') ?></p>

       
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
                    <?php if (Yii::$app->user->isGuest)
                    { ?>
                        <li><?php echo Html::a('Login' ,['site/login-popup'], ['data-toggle'=>'modal','data-target'=>'#login-modal']) ?></li>
                        <li><?php echo Html::a('Signup' ,['site/ruser']) ?></li> <?php
                    }
                    ?>
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
				<a href="mailto:support@hamsterEat.my" target="_blank" class="raised-btn main-btn">Email Us</a>

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