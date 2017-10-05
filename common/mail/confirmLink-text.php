<?php
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $user common\models\User */
$confirmLink = Url::to(['site/confirm','id' => $id, 'auth_key' => $auth_key],true);
?>

Your confirmation link <?= $confirmLink ?>