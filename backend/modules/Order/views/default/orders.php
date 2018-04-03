<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;
use kartik\grid\GridView;

  $this->title = 'Orders Pending List';
  $this->params['breadcrumbs'][] = $this->title;

  echo Html::a('Go to Delivery List', Url::to(['/order/default/index']),['class'=>'btn btn-primary']);
  
echo GridView::widget([
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
            [
              'attribute' =>'Order_ID',
              'filterInputOptions' => ['placeholder' => 'Search ID'],
            ],
            [ 
              'attribute' => 'Delivery_ID',
             'filterInputOptions' => ['placeholder' => 'Search ID'],
            ],
             [ 
              'attribute' => 'User_Username',
              'filterInputOptions' => ['placeholder' => 'Search ID'],
              'value'=> 'order.User_Username',
            ],
            [
              'label'=>'Payment Method',
              'attribute' => 'Orders_PaymentMethod',
              'filter' => ['Account Balance'=>'Account Balance','Cash on Delivery'=>'Cash on Delivery'],
              'filterType' => GridView::FILTER_SELECT2,
              'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
              'filterInputOptions' => ['placeholder' => 'Any Method'],
              'format' => 'raw',
              'value'=>'order.Orders_PaymentMethod',
            ],
            [   
              'attribute' => 'FoodName',
              'filterInputOptions' => ['placeholder' => 'Select Food'],
              'value'=>'food.originName',
            ],
            [
              'attribute' =>'selection',
              'value'=>function($model){
                if (!empty($model['order_selection'])) {
                  $string = "";
                  foreach ($model['order_selection'] as $k => $value) {
                    $string .= $value['food_selection']['originName'].', ';
                  }
                  return $string;
                }
                
              }
            ],
            [                  
              'attribute' => 'Orders_DateTimeMade',
              'value' => 'order.Orders_DateTimeMade',
              'filter' => \yii\jui\DatePicker::widget(['model'=>$searchModel, 'attribute'=>'Orders_DateTimeMade', 'dateFormat' => 'yyyy-MM-dd',]),
              'format' => 'datetime',
     
            ],
            
        ] 
      ]
    ); ?>