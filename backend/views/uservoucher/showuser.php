<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;
use iutbay\yii2fontawesome\FontAwesome as FA;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;

    $this->title = $name."'s Coupons";
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Voucher'), 'url' => ['/user/uservoucherlist']];
    $this->params['breadcrumbs'][] = $this->title;
    
?>
	<?=Html::beginForm(['vouchers/delete','direct'=>'2'],'post'); ?>
	<?= Html::submitButton('Delete Coupon',  [
        'class' => 'btn btn-danger', 
        'data' => [
        		'confirm' => 'Confirm delete these coupons?',
                'method' => 'post',
            ]]);?>

	<?= GridView::widget([

        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'columns' => [
        	['class' => 'yii\grid\CheckboxColumn',],
            'code',
            'discount',
            //'voucher_type.description',
            [
                'attribute' => 'discount_types.description',
                'filter' => array( "1"=>"Percentages(%)","2"=>"Amount"),
            ],

            [
                'attribute' => 'discount_items.description',
                'filter' => array( "1"=>"Discount from purchase","2"=>"Discount from delivery charge","4"=>"Discount from Service Charge","3"=>"Discount from Total"),
            ],

            ['class' => 'yii\grid\ActionColumn' ,
             'template'=>'{editvoucher}',
             'buttons' => [
                'editvoucher' => function($url , $model)
                {
                    return  Html::a(FA::icon('pencil-square-o 2x') , $url , ['title' => "Edit Voucher"]);
                },
              ]
            ],
            
        ],
    ]); ?>
<?= Html::a('Back', ['/uservoucher/index'], ['class'=>'btn btn-primary']) ?>
	<?= Html::endForm();?> 