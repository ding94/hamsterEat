<?php
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $user common\models\User */
$confirmLink = Url::to(['site/confirm-referral','id' => $id, 'auth_key' => $auth_key, 'referral_name'=>$referral_name],true);
AppAsset::register($this);
?>
<div class="password-reset">
	<h1>Welcome to HamsterEat!</h1>
	<hr style="width:40%; border-bottom: 1px solid grey;" >
	<p>To start using all features of the website, please verify your email address.</p>
	<p>If you did not create an account with us using this address, please contact us at xxxx@hamstereat.com</p>
    <p>Your confirmation link: </p>
    <div class="verify-button"><?= Html::a('Verify your account', $confirmLink) ?></div>
</div>
