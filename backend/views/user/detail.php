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
       'userdetails.User_ContactNo',
       [
       		'label' => 'Full Name',
       		'attribute' => 'userdetails.fullname',
       ],
       'balance.User_Balance',
       'balance.AB_topup',
       'balance.AB_minus',
       'created_at:datetime',
       'updated_at:datetime',                  
    ],
]);
?>