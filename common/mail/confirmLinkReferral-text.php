<?php
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $user common\models\User */
$confirmLink = Url::to(['site/confirm-referral','id' => $id, 'auth_key' => $auth_key, 'referral_name'=>$referral_name],true);
?>

Your confirmation link <?= $confirmLink ?>