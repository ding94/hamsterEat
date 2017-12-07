<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;


  $this->title = 'Delivery Lists';
  $this->params['breadcrumbs'][] = $this->title;
  
?>

  <?= GridView::widget([
        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'columns' => [
            'order_item.Order_ID',
            'order_item.Food_ID',
            'order_item.OrderItem_Quantity',
            'order_item.OrderItem_SelectionTotal',
            'order_item.OrderItem_LineTotal',
            [
                'attribute' => 'order_item.OrderItem_Status',
                'filter' => array( "Pending"=>"Pending","Not Placed"=>"Not Placed"),
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

        ]
    ]); ?>