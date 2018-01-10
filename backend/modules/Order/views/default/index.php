<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;
use common\models\Order\Orderitem;

  $this->title = 'Delivery List';
  $this->params['breadcrumbs'][] = $this->title;
  echo Html::a('Go to Orders List', Url::to(['/order/default/order']),['class'=>'btn btn-primary']);
?>
  <?= GridView::widget([
        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'columns' => [
            'Delivery_ID',
            'Orders_TotalPrice',
            'Orders_PaymentMethod',

            [
              'attribute'=>'quantity',
              'label' => 'No. Orders',
              'value' => function($model){
                return Orderitem::find()->where('Delivery_ID=:d',[':d'=>$model['Delivery_ID']])->count();
              }
            ],
            
            'Orders_DateTimeMade:datetime',

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