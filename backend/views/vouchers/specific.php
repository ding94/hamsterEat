
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
use common\models\vouchers\Vouchers;

    $this->title = 'Special Vouchers List';
    $this->params['breadcrumbs'][] = $this->title;
    
?>
	<?=Html::beginForm(['vouchers/delete','direct'=>'3'],'post'); ?>
    	<?= Html::a('Create New Voucher', ['/vouchers/addspec'], ['class'=>'btn btn-success']) ?>
        <?= Html::submitButton('Remove Vouchers',  [
            'class' => 'btn btn-danger', 
            'data' => [
                    'confirm' => 'Are you confirm to delete these vouchers?',
                    'method' => 'post',
                ]]);?>
    <br>

	<?= GridView::widget([
        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'columns' => [
             ['class' => 'yii\grid\CheckboxColumn',],

            'id',

            ['class' => 'yii\grid\ActionColumn' ,
             'template'=>'{morespec}',
             'buttons' => [
                'morespec' => function($url , $model)
                {
                    return Html::a("Add discount" , $url , ['title' => 'Add more discount item to this voucher']);
                },
              ],
            ],

            'code',

            [
                'attribute' => 'discount',
                 'value' => function($model){
                        if ($model->discount_type == 1) {
                                return $model->discount.' %';
                            }
                            return 'RM '.$model->discount;
                 }
            ],
            [
                'attribute' => 'discount_items.description',
                'filter' => array( "1"=>"Discount from purchase","2"=>"Discount from delivery charge","4"=>"Discount from Service Charge","3"=>"Discount from Total"),
            ],
            [
                'attribute' => 'voucher_status.description',
                'filter' => array( "1"=>"Activated","2"=>"Assigned","3"=>"Deactivated","4"=>"Expired","5"=>"Employee's Voucher"),
            ],
            [                  
                 'attribute' => 'startDate',
                 'value' => 'startDate',
                 'filter' => \yii\jui\DatePicker::widget(['model'=>$searchModel, 'attribute'=>'startDate', 'dateFormat' => 'yyyy-MM-dd',]),
                 'format' => 'datetime',
          
            ],
            [                  
                 'attribute' => 'endDate',
                 'value' => 'endDate',
                 'filter' => \yii\jui\DatePicker::widget(['model'=>$searchModel, 'attribute'=>'endDate', 'dateFormat' => 'yyyy-MM-dd',]),
                 'format' => 'datetime',
          
            ],
        ],
    ])?>

  <?= Html::endForm();?> 