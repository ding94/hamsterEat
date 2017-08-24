<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $user common\models\User */
$confirmLink = Url::to(['site/confirm','id' => $id, 'auth_key' => $auth_key],true);
?>
<div class="password-reset">
    <p>Your confirmation link <?= Html::a(Html::encode($confirmLink), $confirmLink) ?> </p>
   
</div>