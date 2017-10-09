<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;

  $this->title = 'Food Detail';
  $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Restuarant Detail '), 'url' => ['default/index']];
  $this->params['breadcrumbs'][] = $this->title;
  
?>
   

  <?= GridView::widget([
        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'columns' => [
            'Food_ID',
            'Description',
            'Sales',
            'Rating',
            'created_at:datetime',
            'updated_at:datetime',
        ]
    ]); ?>