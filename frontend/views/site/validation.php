<?php
use yii\helpers\Html;
use common\user\User;
 
?>

<div class="container" style="padding-top: 100px">
	<div class="row">
		<div class="h2 text-center">Please verify your email address</div>
		<div class="text-center"><p>Please sign in your email to activate authentication</p></div>
		<div class="text-center"><p>Didn't receive activation email?</p><a href="<?php echo yii\helpers\Url::to(['site/resendconfirmlink'])?>">Resend activation email</a></div>
	</div>
</div>