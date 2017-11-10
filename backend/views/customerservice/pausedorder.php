<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\problem\ProblemStatus;
use yii\bootstrap\Modal;

if ($page=='solved') {
   $this->title = "Solved Orders";
}
else{
    $this->title = "Today's Problem Orders";
}
    

Modal::begin([
      'header' => '<h2 class="modal-title">Details</h2>',
      'id'     => 'add-modal',
      'size'   => 'modal-md',
      'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
]);
Modal::end();
?>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'Delivery_ID',

            [
                'attribute' => 'Order_ID',
                'headerOptions' => ['style' => 'width:9%'],
            ],

            'order.User_fullname',

            [
                'attribute' => 'contactno',
                'value' =>'order.User_contactno',
                'headerOptions' => ['style' => 'width:12%'],
            ],

            [
                'attribute' => 'reasons',
                'value' => function($model){return ProblemStatus::find()->where('id=:id',[':id'=>$model['reason']])->one()->description;},
                'filter' => array("1"=>"Lack of ingredients","2"=>"Closing food","3"=>"Closing restaurant","4"=>"Others"),
                'headerOptions' => ['style' => 'width:10%'],
            ],

            'description',
            [
                'attribute'=>'order_item.OrderItem_Status',
                'contentOptions' => function ($model, $key, $index, $column) { return ['style'=>'font: bold 12px/30px Georgia'];},
            ], 
            

            [
                'attribute' => 'foodName',
                'value' => 'order_item.food.Name',
            ],

            [
                'attribute' => 'foodSelect',
                'value' => 'order_item_select.food_selection.Name',
            ],

            ['class' => 'yii\grid\ActionColumn' ,
             'template'=>'{detail}',
             'buttons' => [
                'detail' => function($url,$model)
                {
                    return Html::a('Details',$url, ['title' => 'Problem Solved','data-toggle'=>'modal','data-target'=>'#add-modal']);
                },
              ],
            ],

            ['class' => 'yii\grid\ActionColumn' ,
             'template'=>'{solved}',
             'buttons' => [
                'solved' => function($url , $model)
                {
                    return Html::a(FA::icon('check 2x') , $url , ['title' => 'Problem Solved','data-confirm'=>"Prolem solved?"]);
                },
              ],
            ],
        ],
    ])?>