<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<?= Html::a('Back', ['/news/index'], ['class'=>'btn btn-success']) ?>
<div class="container">
	<div class="row">
		<h1><?php echo $model['enText']['name'] ?></h4>
			<?php echo $model['enText']['text'] ?>
	</div>
	<?php if(!empty($model['zhText'])): ?>
		<div class="row">
			<h1><?php echo $model['zhText']['name'] ?></h4>
				<?php echo $model['zhText']['text'] ?>
		</div>
	<?php endif;?>
</div>