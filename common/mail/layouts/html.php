<?php
use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<style>
.password-reset{
	font-family: "Verdana","Arial",Sans-serif; 
	display: block;
	border: 1px solid #ffbe61;
	width: 75%;
	-webkit-border-radius: 10px;
    -moz-border-radius: 10px;
    border-radius: 10px;
    padding: 30px;
}

.password-reset h1{
	text-align: center;
}

.password-reset p{
	text-align: center;
}

.password-reset a{
	padding: 10px 15px;
	background: #FF8300;
	color: #FFF;
	text-decoration: none;
	-webkit-border-radius: 30px;
    -moz-border-radius: 30px;
    border-radius: 30px;
}

.password-reset .verify-button{
	text-align: center;
}
</style>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <?= $content ?>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
