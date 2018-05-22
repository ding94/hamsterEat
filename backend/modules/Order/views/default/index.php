<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;
use common\models\Order\Orderitem;
use kartik\grid\GridView;

  $this->title = 'Delivery Pending List';
  $this->params['breadcrumbs'][] = $this->title;
  echo Html::a('Go to Orders List', Url::to(['/order/default/order']),['class'=>'btn btn-primary']);
?>
  <?= GridView::widget([
        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'columns' => [
            
            [
              'attribute'=>'Delivery_ID',
              'filterInputOptions' => ['placeholder' => 'Search Delivery ID', 'class'=>'form-control'],
            ],
            'Orders_TotalPrice',
            [
              'attribute'=>'quantity',
              'label' => 'Order Quantity (Per Food)',
              'value' => function($model){
                return Orderitem::find()->where('Delivery_ID=:d',[':d'=>$model['Delivery_ID']])->count();
              }
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
              'format' => 'raw'
            ],
       
            [                  
                 'attribute' => 'Orders_DateTimeMade',
                 'value' => 'Orders_DateTimeMade',
                 'filter' => \yii\jui\DatePicker::widget(['model'=>$searchModel, 'attribute'=>'Orders_DateTimeMade', 'dateFormat' => 'yyyy-MM-dd','options' => ['class' => 'form-control', 'placeholder' => 'Select Date']]),
                 'format' => 'datetime',
            ],
            
            

            ['class' => 'yii\grid\ActionColumn' ,
             'template'=>'{showdetails}',
             'buttons' => [
                'showdetails' => function($url , $model)
                {
                    return  Html::a(FA::icon('search-plus 2x') , $url , ['title' => "View Order Detail, ID: ".$model->Delivery_ID]);
                },
              ]
            ],

            ['class' => 'yii\grid\ActionColumn' ,
             'template'=>'{editorder}',
             'buttons' => [
                'editorder' => function($url , $model)
                {
                    return  Html::a(FA::icon('pencil-square-o 2x') , $url , ['title' => "Edit Order"]);
                },
              ]
            ],

            ['class' => 'yii\grid\ActionColumn' ,
             'template'=>'{delete}',
             'buttons' => [
                'delete' => function($url , $model)
                {
                    return  Html::a('Cancel Orders' , Url::to(['/order/default/cancel-delivery','did'=>$model['Delivery_ID']]) , ['title' => "Cancel Orders",'data'=>['confirm'=>'Cancel these orders? Delivery ID: '.$model['Delivery_ID']]]);
                },
              ]
            ],

        ]
    ]); ?>