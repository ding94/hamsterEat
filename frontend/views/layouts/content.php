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
use common\models\Rmanager;
use common\models\Restaurant;
use yii\bootstrap\Modal;

AppAsset::register($this);
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
        height: 740px;
    }

    #feedback-modal-1 .modal-content{

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
    <link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet">
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
	<div class="page-wrap">
	    <?= $content ?>
	</div>
</body>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>