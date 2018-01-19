<?php
use yii\helpers\Html;
use common\models\User;

?>

<div class="container" style="padding-top: 100px">
	<div class="row">
		<div class="h2 text-center"><?= Yii::t('site','Please verify your email address') ?></div>
		<div class="text-center"><p><?= Yii::t('site','Please sign in your email to activate authentication') ?></p></div>
		<?php if (Yii::$app->user->identity->status == 2) { ?>
		<div class="text-center"><p><?= Yii::t('site',"Didn't receive activation email?") ?></p><a href="<?php echo yii\helpers\Url::to(['site/resendconfirmlink-referral'])?>"><?= Yii::t('site','Resend activation email') ?></a></div>
		<?php } else { ?>
		<div class="text-center"><p><?= Yii::t('site',"Didn't receive activation email?") ?></p><a href="<?php echo yii\helpers\Url::to(['site/resendconfirmlink'])?>"><?= Yii::t('site','Resend activation email') ?></a></div>
		<?php } ?>
	</div>
</div>