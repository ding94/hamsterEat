
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

    $this->title = 'Vouchers List';
    $this->params['breadcrumbs'][] = $this->title;
    
?>
	<?php $form = ActiveForm::begin();?>
	<?= Html::a('Add New Voucher', ['/vouchers/add'], ['class'=>'btn btn-success']) ?>
    <?= Html::submitButton('Remove Vouchers',  [
        'class' => 'btn btn-danger', 
        'data' => [
                'confirm' => 'Are you sure want to delete these vouchers?',
                'method' => 'post',
            ]]);?>
        
    <?= Html::a('Generate new Vouchers', ['/vouchers/generate'], ['class'=>'btn btn-warning']);?>

	<?= GridView::widget([
        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'columns' => [
             ['class' => 'yii\grid\CheckboxColumn',],

            //[ 'class' => 'yii\grid\SerialColumn',],
            'id',
            'code',
            'discount',
            'voucher_type.description',
            'voucher_item.description',
            'startDate:datetime',
            'endDate:datetime',
            /*['class' => 'yii\grid\ActionColumn' ,
             'template'=>'{confirm}',
             'buttons' => [
                'confirm' => function($url , $model)
                {
                    return $model->Ticket_Status <=2 ?  Html::a(FA::icon('check 2x') , $url , ['title' => 'Problem Solved','data-confirm'=>"Complete this ticket? Ticket ID: ".$model->Ticket_ID]) : "";
                },
              ]
            ],*/

            
        ],
    ])?>

   <?php ActiveForm::end();?> 