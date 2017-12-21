<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider
/* @var $this yii\web\View */

?>
    <h4><?= Html::encode("Service Rating") ?></h1>
    <?= DetailView::widget([
        'model' => $model->servicerating,
        'attributes' => [
            'DeliverySpeed',
            'Service',
            'UserExperience',
            'Comment',
        ],
    ]) ?>
    <?php
        $dataProvider = new ArrayDataProvider([
            'allModels' => $model->foodrating,
        ]); 
      ?>
     <h4><?= Html::encode("Food Rating") ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'foods.Name',
            'foodstatus.title'
        ],
    ]); ?>


