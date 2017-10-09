<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView

?>
  
<?php
    echo DetailView::widget([
    'model' => $model,
    'attributes' => [
       'username',
       'email',
       'manager.Rmanager_NRIC',
       'created_at:datetime',
       'updated_at:datetime',                  
    ],
]);
?>