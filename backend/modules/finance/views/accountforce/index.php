<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;
use kartik\widgets\ActiveForm;

  $this->title = 'Force Account History';
  $this->params['breadcrumbs'][] = $this->title;
  
?>
  <?=Html::a('Add',['/finance/accountforce/force'],['class' => 'btn btn-success',]);?>
  <?= GridView::widget([
        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'showFooter'=>true,   
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'id',
                'filterInputOptions' => [
                    'class'       => 'form-control',
                    'placeholder' => 'Search ID',
                ],
            ],
            [
              'attribute' => 'user',
              'value' => 'user.username',
              'filterInputOptions' => [
                    'class'       => 'form-control',
                    'placeholder' => 'Search User Name',
              ],
            ],
            [
              'attribute' => 'operater',
              'value' => 'admin.adminname',
              'filterInputOptions' => [
                    'class'       => 'form-control',
                    'placeholder' => 'Search Admin Name',
                ],
            ],
            [
              'attribute' => 'reduceOrPlus',
              'value' => function($model)
              {
                  return $model->reduceOrPlus == 0 ? 'Deduct' : 'Plus';
              },
              'filter' => array( "0"=>"Deduct","1"=>"Plus"),
            ],
            [
                'attribute' => 'amount',
                'filterInputOptions' => [
                    'class'       => 'form-control',
                    'placeholder' => 'Search Amount',
                ],
            ],
            'reason',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]); ?>