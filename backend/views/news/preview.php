<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<?= Html::a('Back', ['/news/index'], ['class'=>'btn btn-success']) ?>
<div class="container">
	<div class="row">
		<h1><?php echo $model['name'] ?></h4>
			<?php echo $model['text'] ?>
	</div>
</div>