<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;
use kartik\widgets\ActiveForm;
use common\models\Bank;

	$this->title = 'Offline Topup';
	$this->params['breadcrumbs'][] = $this->title;
	
?>
<?=Html::beginForm(['/finance/topup/direct'],'post');?>
<?=Html::submitButton('All', ['name'=>'Account_Action', 'value' => '0','class' => 'btn btn-info',]);?>
<?=Html::submitButton('Pending', ['name'=>'Account_Action', 'value' => '1','class' => 'btn btn-primary',]);?>
<?=Html::submitButton('Success', ['name'=>'Account_Action', 'value' => '3','class' => 'btn btn-success',]);?>
<?=Html::submitButton('Rejected', ['name'=>'Account_Action', 'value' => '4','class' => 'btn btn-danger',]);?>
<?=Html::submitButton('Problematic Payment', ['name'=>'Account_Action', 'value' => '2','class' => 'btn btn-warning',]);?> 
<?= Html::endForm();?> 
	
<?= GridView::widget([
  'dataProvider' => $model,
  'filterModel' => $searchModel,
  'columns' => [
		['class' => 'yii\grid\ActionColumn' , 
      'template'=>'{update} ',
      'header' => "Approve",
			'buttons' => [
        'update' => function($url , $model){
				  if($model->Account_Action == 3){
            $url = Url::to(['topup/undos','id'=>$model->Account_TransactionID,'admin'=>Yii::$app->user->identity->id]);
          }
          else{
            $url = Url::to(['topup/update' ,'id'=>$model->Account_TransactionID]) ;
          }

          return  $model->Account_Action !=3  ? Html::a('Approve' , $url , ['title' => 'update','data-confirm'=>"Confirm action?"]) : Html::a('Undo' , $url , ['title' => 'Reverse Success','data-confirm'=>"Confirm action?"]);
        },
      ]
		],
			
		['class' => 'yii\grid\ActionColumn' , 
      'template'=>'{cancel} ',
      'header' => "Reject",
			'buttons' => [
        'cancel' => function($url , $model){
				  if($model->Account_Action == 4){
            $url = Url::to(['topup/undo','id'=>$model->Account_TransactionID,'admin'=>Yii::$app->user->identity->id]);
          }
          else{
            $url = Url::to(['topup/cancel' ,'id'=>$model->Account_TransactionID]) ;
          }
                   
          return  $model->Account_Action !=4  ? Html::a('Cancel' , $url , ['title' => 'cancel','data-confirm'=>"Confirm action?"]) : Html::a('Undo' , $url , ['title' => 'Reverse Cancel','data-confirm'=>"Confirm action?"]);
				},
      ]
		],
		
    [
      'class' => 'yii\grid\ActionColumn' , 
      'template'=>' {img}',
      'buttons' => [
			'img' => function($url,$model){
        return Html::a('Picture',Yii::$app->params['topup'].$model->Account_ReceiptPicPath,['target'=>'_blank']);
                
        },
			],
		],

    'User_Username',
    'Account_TopUpAmount',
		//'Account_ChosenBank',
		[
      'attribute' => 'bank.Bank_Name',
			'filterInputOptions' => [
        'class'       => 'form-control',
        'placeholder' => 'Search Bank Name',
      ],
    ],

		[
			'label' => 'Status',
      'format' => 'raw',
      'headerOptions' => ['width' => "15px"],
      'contentOptions' => ['style' => 'font-size:20px;'],
			'attribute' => 'accounttopup_status.title',
			'value' => function($model){
        return Html::tag('span' , $model->accounttopup_status->title ,['class' => $model->accounttopup_status->labelName ]);
      },
			'filter' => $list,
		],

		[                  
      'attribute' => 'Account_TransactionDate',
      'value' => 'Account_TransactionDate',
      'filter' => \yii\jui\DatePicker::widget(['model'=>$searchModel, 'attribute'=>'Account_TransactionDate', 'dateFormat' => 'yyyy-MM-dd',]),
      'format' => 'datetime',
    ],
    // 'Account_TransactionDate:datetime',
    'Account_RejectReason',
		'Account_InCharge',
    //'rejectReason',
		//'picture',
					 
		['class' => 'yii\grid\ActionColumn' , 
      'template'=>'{edit} ',
      'header' => "Edit",
      'buttons' => [
        'edit' => function($url , $model){
          $url = Url::to(['topup/edit','id'=>$model->Account_TransactionID,'admin'=>Yii::$app->user->identity->id]);
                      
          return $model->Account_Action ==1  ? Html::a('Edit' , $url , ['title' => 'Edit']) : "";
        },
      ]
    ],

  ],

])?>
	