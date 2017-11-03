
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
use common\models\Vouchers;

    $this->title = 'Vouchers List';
    $this->params['breadcrumbs'][] = $this->title;
    
?>
	<?=Html::beginForm(['vouchers/delete','direct'=>'3'],'post'); ?>
    	<?= Html::a('Create New Voucher', ['/vouchers/add'], ['class'=>'btn btn-success']) ?>
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
                 'value' => function($model)
                        {
                            if ($model->discount_type == 100) {
                                return $model->discount.' %';
                            }
                            return 'RM '.$model->discount;
                        },
            ],
            [
                'attribute' => 'voucher_item.description',
                'filter' => array( "7"=>"Discount from purchase","8"=>"Discount from Service Charge","9"=>"Discount from Total"),
            ],
            [
                'attribute' => 'voucher_type.description',
                'filter' => array( "100"=>"Discount %","101"=>"Discount RM"),
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