<?php

use yii\helpers\Html;


$this->title = "Edit Food Selection";
$this->params['breadcrumbs'][] = ['label' => 'Food Detail', 'url' => ['/restaurant/food','id'=>0]];
$this->params['breadcrumbs'][] = ['label' => 'Food Type And Selection', 'url' => ['/restaurant/type/index','id'=>$model->Food_ID]];
$this->params['breadcrumbs'][] = $this->title;

?>