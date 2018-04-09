<?php
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\NewsAsset;

NewsAsset::register($this);
?>
<div id="news-container" class="container">
	<?php echo $news['text']; ?>
</div>