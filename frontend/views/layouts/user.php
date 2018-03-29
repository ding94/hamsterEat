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
use common\models\Company\Company;
use kartik\widgets\SideNav;
use yii\helpers\Url;
use common\models\Rmanager;
use common\models\Rmanagerlevel;
use common\models\RestaurantName;
use common\models\Deliveryman;
use common\models\Restaurant;
use frontend\assets\NotificationAsset;
use frontend\assets\UserAsset;
use common\models\Order\Orders;
use common\models\Order\Orderitem;
AppAsset::register($this);
NotificationAsset::register($this);
UserAsset::register($this);

use common\models\LanguageLine;
use frontend\controllers\CommonController;
$language = Yii::$app->request->cookies->getValue('language');
if (empty($language)) {
    $language = CommonController::getLanguage();
}
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href=<?php echo Url::to('@web/SysImg/Icon.png')?>>
        <?= Alert::widget(['options'=>[
        'style'=>'position:fixed;
                    top:80px;
                    right:25%;
                    width:50%;
                    z-index:5000;',
   ],]);?>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php Modal::begin([
            'header' => '<h2 class="modal-title">'.Yii::t('layouts','Feedback').'</h2>',
            'id'     => 'feedback-modal',
            'size'   => 'modal-sm',
            //'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
    
    Modal::end() ?>

    <?php Modal::begin([
            'header' => '<h2 class="modal-title">'.Yii::t('layouts','Placed Orders').'</h2>',
            'id'     => 'add-modal',
            'size'   => 'modal-lg',
            'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">'.Yii::t('common','Close').'</a>',
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
    
    if (Yii::$app->user->isGuest) {
          $menuItems[] = ['label' => '<span id ="cart1" class="glyphicon glyphicon-shopping-cart"></span> ', 'url' => ['/cart/view-cart']];
        $menuItems[] = ['label' => '<span class="glyphicon glyphicon-user"></span>'.Yii::t('common','Signup'), 'url' => ['/site/signup']];
        $menuItems[] = ['label' => '<span class="glyphicon glyphicon-log-in"></span>'.Yii::t('common','Login'), 'url' => ['/site/login']];
    }
       

     else {
        $menuItems[] = ['label' => '<span class="glyphicon glyphicon-shopping-cart cart"><span class="badge">'.Yii::$app->params['countCart'].'</span></span> ', 'url' => ['/cart/view-cart']];
       
        $menuItems[] = ['label' => '<span> <i class="fa fa-bell"></i>'.Yii::$app->params['countNotic'].'</span>' ,'options'=> ['id'=>'notication']];
        $keys = array_keys($menuItems);
      
        if(empty(Yii::$app->params['notication']))
        {
            $menuItems[end($keys)]['items'][] = ['label' => '<h4 class="menu-title">'.Yii::t('layouts','Empty Notication').'</h4>'];
            $menuItems[end($keys)]['items'][] = '<li class="divider"></li>';
            $menuItems[end($keys)]['items'][] = ['label' => '<h4 class="menu-title pull-right">'.Yii::t('layouts','View All').'</h4>','url' => ['/notification/notic/index']];
        }
        else
        {
            $menuItems[end($keys)]['items'][] = ['label' => '<h4 class="menu-title">'.Yii::t('layouts','Notifications').'</h4>'];
            $menuItems[end($keys)]['items'][] = '<li class="divider"></li>';
            $menuItems[end($keys)]['items'][] = '<div class="inner-notic">';
            foreach(Yii::$app->params['notication'] as $i=> $notic)
            {
               
                $menuItems[end($keys)]['items'][] = ['label' => '<h4 class="item-title">'.Yii::$app->params['listOfNotic'][$i]['description'].'</h4>' ];
                foreach($notic as $data)
                {
                    $ago = Yii::$app->formatter->asRelativeTime($data['created_at']);
                    
                    $menuItems[end($keys)]['items'][] = ['label' => '<h4 class="item-info">'.$data['name'].' from '.$ago.'</h4>','url' => $data['url']];
                }
            }
            $menuItems[end($keys)]['items'][] = '</div>';
            $menuItems[end($keys)]['items'][] = '<li class="divider"></li>';
            $menuItems[end($keys)]['items'][] = "<li><div class='col-sm-6'>".Html::a('<h4 class="menu-title">'.Yii::t('layouts','Mark All as Read').'</h4>',['/notification/notic/turnoff'])."</div><div class='col-sm-6'>".Html::a('<h4 class="menu-title pull-right">'.Yii::t('layouts','View All').'</h4>',['/notification/notic/index'])."</div></li>";
        }
        

        $rmanager = Rmanager::find()->where('uid=:id AND Rmanager_Approval=:ra',[':id'=>Yii::$app->user->identity->id,':ra'=>1])->one();
        if (!empty($rmanager)) {
            $menuItems[] = ['label' => '<span class="glyphicon glyphicon-list-alt">'];
            $key = array_keys($menuItems);
            $lvl = Rmanagerlevel::find()->where('User_Username=:u',[':u'=>$rmanager['User_Username']])->all();
            $count = 0;

            foreach ($lvl as $k => $level) {
                $cookies = Yii::$app->request->cookies;
                $resname = CommonController::getRestaurantName($level['Restaurant_ID']);
                $orderitem = Orderitem::find()->where('Restaurant_ID=:id AND OrderItem_Status=:s',[':id'=>$level['Restaurant_ID'],':s'=>2])->joinwith(['food'])->count();

                if ($orderitem > 0) {
                    $menuItems[end($key)]['items'][] = ['label'=>$resname.'('.$orderitem.')','url'=>['/Restaurant/restaurant/cooking-detail','rid'=>$level['Restaurant_ID']]];
                    $menuItems[end($key)]['items'][] = '<li class="divider"></li>';
                }
                $count += $orderitem;
            }
            if ($count <= 0){
                $menuItems[end($key)]['items'][] = ['label'=>'<h5>'.Yii::t('layouts','Empty Orders').'</h5>'];
            }
        }

        $menuItems[] = ['label' => '<span class="glyphicon glyphicon-user"></span><span class="username"> ' . Yii::$app->user->identity->username . '</span>', 'items' => [
                       ['label' => Yii::t('layouts','Profile'), 'url' => ['/user/user-profile'] ,'options'=> ['class'=>'list-user'],],
                        '<li class="divider"></li>',
                    ]];
         $keys = array_keys($menuItems);
        if ($rmanager) {
            $menuItems[end($keys)]['items'][] =['label' => Yii::t('layouts','Restaurants'), 'url' => ['/Restaurant/restaurant/restaurant-service']];
            $menuItems[end($keys)]['items'][] = '<li class="divider"></li>';
        }
        if (Deliveryman::find()->where('User_id=:id',[':id'=>Yii::$app->user->identity->id])->one()){
                $menuItems[end($keys)]['items'][] =['label' => Yii::t('layouts','Delivery Orders'), 'url' => ['/Delivery/deliveryorder/order']];
                $menuItems[end($keys)]['items'][] = '<li class="divider"></li>';
        }
        
        /*if ($company = Company::find()->where('owner_id=:id',[':id'=>Yii::$app->user->identity->id])->one()) {
            $menuItems[end($keys)]['items'][] =['label' => 'Company', 'url' => ['/company/index']];
            $menuItems[end($keys)]['items'][] = '<li class="divider"></li>';
        }*/

        $menuItems[end($keys)]['items'][] = ['label' => Yii::t('common','Logout'), 'url' => ['/site/logout'],'linkOptions'=>['data-method'=>'post']];
        // $menuItems[] = ['label' => '<i class="fa fa-globe"></i><span class="language"> Language </span>', 'items' => [
        //                 ['label' => 'English', 'url' => Url::to(['/site/changelanguage','lang'=>'en'])],
        //                 '<li class="divider"></li>',
        //                 ['label' => 'Chinese', 'url' => Url::to(['/site/changelanguage','lang'=>'zh'])]
        //                 ]];
        $menuItems[] = '<li class="special-nav-item">'.Html::a('EN',['/site/changelanguage','lang'=>'en']).'<span>|</span>'.Html::a('中文',['/site/changelanguage','lang'=>'zh']).'</li>'; 
    }
     
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => $menuItems,
        
    ]);
    NavBar::end();
    ?>
</div>
<div class="inital-chat-container">
        <div class="chat-container">
            <div class="minified-box">
               <div class="chat-indicator"></div>
               <div class="minified-wrapper">
                   <div class="chat-text-container">
                       <?php echo Html::a('<i class="fa fa-comments" aria-hidden="true"></i>','https://tawk.to/chat/5ab860194b401e45400e0a00/1c9g3hsq7/?$_tawk_popout=true',['target'=>'_blank','class'=>'status-icon']);?>
                   </div>
               </div>
            </div>
        </div>
    </div>
<nav id="bottom-navbar">
            <div>
                <ul>
                    <?php if(Yii::$app->user->isGuest){ ?>
                    <li><?php echo Html::a('<i class="fa fa-user-plus"></i><span>'.Yii::t('layouts','Sign Up').'</span>',['/site/ruser']);?></li>
                    <li><?php echo Html::a('<span class="glyphicon glyphicon-log-in"></span><span> Sign In</span>',['/site/login-popup'],['data-toggle'=>'modal','data-target'=>'#login-modal']);?></li>
                    <?php } elseif(Rmanager::find()->where('uid=:id',[':id'=>Yii::$app->user->identity->id])->one()) { ?>
                    <li><?php echo Html::a('<span class="glyphicon glyphicon-shopping-cart cart"><span class="badge">'.Yii::$app->params['countCart'].'</span></span>',['/cart/view-cart']);?></li>
                    <li><?php echo Html::a('<span class=""><i class="fa fa-bell"></i>'.Yii::$app->params['countNotic'].'</span>',['/notification/notic/index']);?></li>

                    <?php if (!empty($rmanager)): ?>
                        <?php 
                            $count = 0;
                            foreach ($lvl as $k => $level) {
                                $orderitem = Orderitem::find()->where('Restaurant_ID=:id AND OrderItem_Status=:s',[':id'=>$level['Restaurant_ID'],':s'=>2])->joinwith(['food'])->count();
                                $count += $orderitem;
                            }
                            echo Html::a('<span class="glyphicon glyphicon-list-alt"></span><span class="badge">('.$count.')</span>',['/Restaurant/restaurant/phonecooking'],['data-toggle'=>'modal','data-target'=>'#add-modal']);
                        ?>
                    <?php endif;?>

                    <li><?php echo Html::a('<i class="fa fa-cutlery"></i>',['/Restaurant/restaurant/restaurant-service']);?></li>
                    <li><?php echo Html::a('<span class="glyphicon glyphicon-user">',['/user/user-profile']);?></li>                    <?php } elseif(Deliveryman::find()->where('User_id=:id',[':id'=>Yii::$app->user->identity->id])->one()){ ?>
                    <li><?php echo Html::a('<span class="glyphicon glyphicon-shopping-cart cart"><span class="badge">'.Yii::$app->params['countCart'].'</span></span>',['/cart/view-cart']);?></li>
                    <li><?php echo Html::a('<span class=""><i class="fa fa-bell"></i>'.Yii::$app->params['countNotic'].'</span>',['/notification/notic/index']);?></li>
                    <li><?php echo Html::a('<i class="fa fa-truck"></i>',['/order/deliveryman-orders']);?></li>
                    <li><?php echo Html::a('<span class="glyphicon glyphicon-user">',['/user/user-profile']);?></li>
                    <?php } else{ ?>
                    <li><?php echo Html::a('<span class="glyphicon glyphicon-shopping-cart cart"><span class="badge">'.Yii::$app->params['countCart'].'</span></span>',['/cart/view-cart']);?></li>
                    <li><?php echo Html::a('<span class=""><i class="fa fa-bell"></i>'.Yii::$app->params['countNotic'].'</span>',['/notification/notic/index']);?></li>
                    <li><?php echo Html::a('<span class="glyphicon glyphicon-user">',['/user/user-profile']);?></li>
                    <li><?php echo Html::a('<span class="glyphicon glyphicon-log-out">',['/site/logout'],['data-method'=>'post']);?></li>
                    <?php } ?>
                </ul>
            </div>
        </nav>

    <div class="container page-wrap">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <div class="container vertical-divider">
            <ul id="nav" class="nav nav-default">
                <li id="profile" role="presentation"><label class="label-btn"><i class="fa fa-user fa-lg"></i><?php echo Html::a('<span>'.Yii::t('layouts','Profile').'</span>',['/user/user-profile'])?></label></li>
                <li id="balance" role="presentation"><label class="label-btn"><i class="fa fa-money fa-lg"></i><?php echo Html::a('<span>'.Yii::t('common','Balance').'</span>',['/user/userbalance'])?></label></li>
                <li  id="order" role="presentation"><label class="label-btn"><i class="fa fa-cutlery fa-lg"></i><?php echo Html::a('<span>'.Yii::t('layouts','Order').'</span>',['/order/my-orders'])?></label></li>
                <li id="ticket" role="presentation"><label class="label-btn"><i class="fa fa-comment fa-lg"></i><?php echo Html::a('<span>'.Yii::t('layouts','Ticket').'</span>',['/ticket/index'])?></label></li>
                <li id="voucher" role="presentation"><label class="label-btn"><i class="fa fa-ticket fa-lg"></i><?php echo Html::a('<span>'.Yii::t('layouts','Vouchers').'</span>',['/vouchers/index'])?></label></li>

            </ul>
        </div>
        <div class="outer-content">
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
            <div id="Box4" class="col-sm-3 col-xs-12">
                <?php echo Html::a('English',['/site/changelanguage','lang'=>'en'],['class'=>'btn raised-btn main-btn']); ?>
                <?php echo Html::a('中文',['/site/changelanguage','lang'=>'zh'],['class'=>'btn raised-btn main-btn']); ?>
            </div>
			<div id="Box1" class = "col-sm-5 col-xs-12">
                <h3 id="footertitle">HamsterEat</h3>
                <hr>
                <ul id="linklist" class="list-unstyled">
                    <li><?php echo Html::a(Yii::t('layouts','Feedback'), Url::to(['/site/feed-back', 'link'=>Yii::$app->request->url]), ['data-toggle'=>'modal','data-target'=>'#feedback-modal']) ?></li>
                    <li><?php echo Html::a(Yii::t('common','About Us'),['site/about']) ?></li>
                    <li><?php echo Html::a(Yii::t('common','Guide'),['site/faq']) ?></li>
                    <li><a href="../HomeCookedDelicacies/Help.php"><?= Yii::t('common','Help') ?></a></li>
                </ul>
                
            </div>

            <div id="Box2" class = "col-sm-5 col-xs-12">
                <h3><?= Yii::t('site','Contact Us') ?></h3>
                <hr>
                <ul id="linklist" class="list-unstyled">
                    <li> <?php echo Html::a(Yii::t('common','Contact'),['site/contact']) ?></li>
                </ul>
                <p><?= Yii::t('site','Tel') ?>. 1700-818-360</p>

                <p><?= Yii::t('common','Email') ?>. support@hamsterEat.my</p>
                <a href="mailto:support@hamsterEat.my" target="_blank" class="raised-btn main-btn"><?= Yii::t('layouts','Email Us')?></a>

            </div>
            
            <!-- <div id="Box3" class = "col-sm-3 col-xs-12">
                <h3> Yii::t('common','Follow')  |  Yii::t('common','Get in Touch') </h3>
                <hr>
                 <center>
                 <a target="_blank" href="https://www.facebook.com" class="btn btn-social-icon btn-facebook"><span class="fa fa-facebook"></span></a>
                 <a target="_blank" href="https://plus.google.com" class="btn btn-social-icon btn-google"><span class="fa fa-google"></span></a>
                 <a target="_blank" href="https://www.instagram.com" class="btn btn-social-icon btn-instagram"><span class="fa fa-instagram"></span></a>
                 </center>               
            </div> -->

		<!-- </div> -->
</div>
		
	</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>