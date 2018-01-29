<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\problem\ProblemStatus;
use common\models\Order\StatusType;
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

<?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax'=>true,
        'striped'=>true,
        'hover'=>true,
        'panel'=>[
            'layout'=>'{export} {toggleData}',
        ],                
        'columns' => [

            [
                'attribute' =>'Delivery_ID',
                'group'=>true,  
                'headerOptions' => ['style' => 'width:9%'],
            ],
           
            [
                'attribute' => 'Order_ID',
                
            ],
            [
                'attribute' => 'name',
                'value' =>'order.address.name',
                'headerOptions' => ['style' => 'width:12%'],
            ],
            [
                'header'=> 'Contact Number',
                'attribute' => 'contactno',
                'value' =>'order.address.contactno',
                'mergeHeader'=>'true',
                'headerOptions' => ['style' => 'width:12%'],
            ],

            [
                'attribute' => 'reasons',
                'value' => function($model){return ProblemStatus::find()->where('id=:id',[':id'=>$model['reason']])->one()->description;},
                'filter' => array("1"=>"Lack of ingredients","2"=>"Closing food","3"=>"Closing restaurant","4"=>"Others"),
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Any Reason'],
                'headerOptions' => ['style' => 'width:10%'],
            ],

            'description',
            [

                'attribute'=>'status',
                'value' => function($model)
                {

                    $status = StatusType::findOne($model->order->Orders_Status);
                    return $status->type;
                },
                'contentOptions' => function ($model, $key, $index, $column) { return ['style'=>'font: bold 12px/30px Georgia'];},
                'filter' => array("8"=>"Canceled","9"=>"Canceled and Refunded"),
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Any Status'],
            ], 
            

            [
                'attribute' => 'foodName',
                'value' => 'order_item.food.Name',
                'mergeHeader'=>'true',
            ],

            [
                'attribute' => 'foodSelect',
                'value' => 'order_item_select.food_selection.Name',
                'mergeHeader'=>'true',
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
                    return $model['status']==1 ? Html::a(FA::icon('check 2x') , $url , ['title' => 'Problem Solved','data-confirm'=>"Prolem solved?"]) : "";
                },
              ],
            ],
        ],
    ])?>