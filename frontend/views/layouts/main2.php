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
        'options' => [
            'class' => 'topnav navbar-fixed-top MainNav',
        ],
    ]);
    $menuItems = [
        ['label' => 'About', 'url' => ['/site/about']],
        ['label' => 'Contact', 'url' => ['/site/contact']],
        //['label' => 'Cart', 'url' => ['/site/contact']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        if (Rmanager::find()->where('uid=:id',[':id'=>Yii::$app->user->identity->id])->one()) {
            $restaurant = Restaurant::find()->where('Restaurant_Manager=:rm',[':rm'=>Yii::$app->user->identity->username])->all();
            $menuItems[] = ['label' => '<span class="glyphicon glyphicon-home"></span> Restaurants',

            ];
            foreach ($restaurant as $k => $each) {
            $menuItems[2]['items'][$k] = ['label' => $each['Restaurant_Name'],'url' => ['/Restaurant/default/restaurant-details','rid'=>$each['Restaurant_ID']]];
            $menuItems[2]['items'][$k+count($restaurant)] = '<li class="divider"></li>';
            }
        }
        $menuItems[] = ['label' => 'My Profile', 'url' => ['/user/user-profile']];
        $menuItems[] = ['label' => 'Create Restaurant', 'url' => ['Restaurant/default/new-restaurant-location']];
        $menuItems[] = ['label' => 'Ticket', 'url' => ['/ticket']];
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
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
            ['label' => 'My Profile', 'url' => ['/user/user-profile']];
            
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
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
                    ['label' => 'My account','icon' => '','options' => ['class' => 'active'], 'items' => [
                        ['label' => 'Top up', 'url' => Url::to(['/topup/index'])],
                        ['label' => 'Withdraw Money', 'url' => Url::to(['/withdraw/index'])],
                    ]],
                     ['label' => '<i class="glyphicon glyphicon-cog"></i> Member Settings','options' => ['class' => 'active'], 'items' => [
                        ['label' => 'User Profile', 'url' => Url::to(['/user/user-profile'])],
                    ]],
                    ['label' => 'Customer Service', 'options' => ['class' => 'active'], 'items' => [
                       ['label' => 'Submit Ticket', 'url' => Url::to(['/ticket/submit-ticket'])],
                    ]],
                     ['label' => 'Delivery Man', 'options' => ['class' =>'active'],'items'=>[
                        ['label' => 'Daily Sign In' , 'url' => Url::to(['/Delivery/daily-sign-in/index'])],
                        ]
                    ]
            ]]);     
            ?>
        </div>
    </div>


    <div class="container col-md-9" style="padding-top: 5%;">
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


<footer class="footer navbar-fixed-bottom">
    <div class="container">
        <p class="pull-left">&copy; hamsterEat <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
