<?php
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\NewsAsset;

NewsAsset::register($this);
?>
<div id="news-container" class="container">
	<div class="row">
<div id="news-list">
	<ul>
		<li id="list-header">
			<?= Yii::t('news','Website Notice') ?>
		</li>
	<?php foreach ($model as $k => $n) { ?>
		<li id="list-news-links">
		<?= Html::a('<span>'.$n['name'].'</span>', Url::toRoute(['news/news', 'id' => $n['id']]), ['class' => 'profile-link']) ?>
		</li>
	<?php } ?>
		<li id="list-news-links" class="text-right">
			<?= Html::a('<span>'.Yii::t('news','MORE').'</span><i class="fa fa-arrow-right" aria-hidden="true"></i>', Url::toRoute(['news/news-all']), ['class' => 'profile-link']) ?>
		</li>
	</ul>
</div>
<div id="news-content">
		<div class="h1">
			<span><?php echo $news['name']; ?></span><span id="news-date" class="pull-right"><?php echo $news['NewsDate'] ?></span>
		</div>
		<?php echo $news['text']; ?>
	</div>
	</div>
</div>