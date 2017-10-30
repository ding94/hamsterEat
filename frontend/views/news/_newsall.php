<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>  

<?= Html::a('<span>'.$model->name.'</span><span class="pull-right">'.$model->NewsDate.'</span>', Url::toRoute(['news/news', 'id' => $model->id]), ['class' => 'profile-link']) ?>