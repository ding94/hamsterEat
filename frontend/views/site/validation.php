<?php
use yii\helpers\Html;
use common\models\User;

$user = User::find()->where('id = :id',[':id'=>Yii::$app->user->identity->id])->one();
?>

<div class="container" style="padding-top: 100px">
	<div class="row">
		<div class="h2 text-center">Please verify your email address</div>
		<div class="text-center"><p>Please sign in your email to activate authentication</p></div>
		<?php if ($user->status == 2) { ?>
		<div class="text-center"><p>Didn't receive activation email?</p><a href="<?php echo yii\helpers\Url::to(['site/resendconfirmlink-referral'])?>">Resend activation email</a></div>
		<?php } else { ?>
		<div class="text-center"><p>Didn't receive activation email?</p><a href="<?php echo yii\helpers\Url::to(['site/resendconfirmlink'])?>">Resend activation email</a></div>
		<?php } ?>
	</div>
</div>