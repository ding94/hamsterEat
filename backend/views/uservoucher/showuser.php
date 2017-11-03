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
                'attribute' => 'voucher_type.type',
                'filter' => array( "2"=>"Percentages(%)","5"=>"Amount"),
            ],

            [
                'attribute' => 'voucher_item.description',
                'filter' => array( "7"=>"Discount from purchase","8"=>"Discount from Service Charge","9"=>"Discount from Total"),
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