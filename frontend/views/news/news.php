<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="container"  style="padding-top: 50px;">
	<div class="row">
<div id="news-list" style="padding-top: 100px; width: 300px; min-height: 100vh; float: left;">
	<ul>
		<li style="background: white; border-left: 5px solid #FFBA00; font-size: 2vh; margin:5px; padding:15px;">
			Website Notice
		</li>
	<?php foreach ($model as $k => $n) { ?>
		<li>
		<?= Html::a('<span>'.$n['name'].'</span>', Url::toRoute(['news/news', 'id' => $n['id']]), ['class' => 'profile-link']) ?>
		</li>
	<?php } ?>
		<li class="text-right">
			<?= Html::a('<span>MORE</span><i class="fa fa-arrow-right" aria-hidden="true"></i>', Url::toRoute(['news/news-all']), ['class' => 'profile-link']) ?>
		</li>
	</ul>
</div>
<div style="width: 800px; float: left; margin-left: 50px;">
		<div class="h1" style="border-bottom: 1px solid;">
			<span><?php echo $news['name']; ?></span><span class="pull-right" style="font-size: 2.2vh;"><?php echo $news['NewsDate'] ?></span>
		</div>
		<?php echo $news['text']; ?>
	</div>
	</div>
</div>