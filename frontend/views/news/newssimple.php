<?php
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\NewsAsset;

NewsAsset::register($this);
?>
<div class="content-background"></div>
<div id="news-container" class="container">
	<?php echo $news['text']; ?>
</div>