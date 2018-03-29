<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Notification\Notification;
/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">
    
    <?= Html::a('<span class="logo-mini">APP</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
    </nav>
</header>
