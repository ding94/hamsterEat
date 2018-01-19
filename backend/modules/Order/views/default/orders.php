<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;

  $this->title = 'Orders Pending List';
  $this->params['breadcrumbs'][] = $this->title;

  echo Html::a('Go to Delivery List', Url::to(['/order/default/index']),['class'=>'btn btn-primary']);
  
?>
  <?= GridView::widget([
        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'columns' => [

            ['class' => 'yii\grid\ActionColumn' ,
              'template'=>'{delete}',
              'buttons' => [
                'delete' => function($url,$model)
                {
                  return  Html::a(FA::icon('times 2x') ,['/order/default/deleteorder','oid'=>$model['Order_ID']] , ['title' => "delete Order",'data'=>['confirm'=>'Delete this order? ID: '.$model['Order_ID']]]);
                },
              ]
            ],

            'Order_ID',
            'order.Delivery_ID',
            'order.User_Username',
            'order.Orders_PaymentMethod',
            'food.Name',
            [
              'attribute' =>'selection',
              'value'=>function($model){
                if (!empty($model['order_selection'])) {
                  $string = "";
                  foreach ($model['order_selection'] as $k => $value) {
                    $string .= $value['food_selection']['Name'].', ';
                  }
                  return $string;
                }
                
              }
            ],
            'order.Orders_DateTimeMade:datetime',
        ]
      ]
    ); ?>