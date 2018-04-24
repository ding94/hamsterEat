<?php
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\NewsAsset;

NewsAsset::register($this);
?>
<div class="content-background"></div>
<div id="news-container" class="container">
	<?php foreach ($news as $k => $value) : ?>
		<h1><?= $value[$language]['name'];  ?></h1>
		<p><?php echo $value[$language]['text']; ?></p>
	<?php endforeach;?>
</div>