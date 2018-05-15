<?php
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $user common\models\User */

//for email verification user
/*$confirmLink = Url::to(['/site/confirm','id' => $id, 'auth_key' => $auth_key],true);
if ($back == 0) {
	$confirmLink = Yii::$app->urlFrontEnd->createUrl(['site/confirm','id' => $id, 'auth_key' => $auth_key]);
}*/

$link = Url::to(['/site/index'],true);

?>
<div style=" line-height: 0.5;color:black; font-family: 'Times New Roman', Times, serif; font-size: 15px;">
	<p>Dear <?= $username ?>,</p>
	<a href="https://www.hamstereat.my">
		<?php echo Html::img('https://hamstereat.my//imageLocation/event/discount1-20180515.jpg', ['class' => 'img-responsive col-xs-12']); ?>
	</a>
	<p>Are you going out for lunch on a hot day?</p>
	<p>How long you have no ordering food from HamsterEat?</p>
	<br>
	<p>Now! Grab your food with just a simple click.</p>
	<br>
	<p>Order your meal before 11 am with just a few simple clicks.</p>
	<p>Sit back, relax, and we'll get your food delivered before 1 pm.</p>
	<br>
	<p>Order your lunch now from <a href="https://www.hamstereat.my">www.hamstereat.my</a></p>
	<br>
	<p>Thank you and enjoy your meal with us ^^ </p>
</div>