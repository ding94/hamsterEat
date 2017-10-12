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
use backend\models\Admin;
	$this->title = 'User Withdraw';
	$this->params['breadcrumbs'][] = $this->title;
	
?>
	<?= GridView::widget([
        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'showFooter'=>true,

        'columns' => [
	        ['class' => 'yii\grid\ActionColumn',
				'template'=>'{approve} ',
	            'header' => "Approve",
				'buttons' => [
	            'approve' => function($url , $model){
						
	                    $url = Url::to(['withdraw/approve' ,'id'=>$model->id,'admin'=>Yii::$app->user->identity->id]) ;
	                    return Html::a('Approve' , $url , ['title' => 'Approve','data-confirm'=>"Confirm action?"]);
						
					},
	            ]
			],
			
			['class' => 'yii\grid\ActionColumn' , 
	            'template'=>'{cancel} ',
	            'header' => "Reject",
				'buttons' => [
	            'cancel' => function($url , $model){
						
	                	$url = Url::to(['withdraw/cancel' ,'id'=>$model->id]) ;
	                    return Html::a('Cancel' , $url , ['title' => 'Reject','data-confirm'=>"Confirm action?"]);
						
					},
	            ]
			],

			[
                'attribute' => 'acc_name',
                'filterInputOptions' => [
                    'class'       => 'form-control',
                    'placeholder' => 'Search Account Name',
                ],
            ],

            [
                'attribute' => 'withdraw_amount',
                'filterInputOptions' => [
                    'class'       => 'form-control',
                    'placeholder' => 'Search Amount',
                ],
            ],

            [
                'attribute' => 'to_bank',
                'filterInputOptions' => [
                    'class'       => 'form-control',
                    'placeholder' => 'Search Bank Acount',
                ],
            ],

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
					'inCharge',
			
		/*	[
                'attribute' => 'inCharge',
                'value'=> function($model){
						$name ="";
						if(!empty($model->inCharge))
						{
							$name = Admin::findOne($model->inCharge)->adminname;
						}
						return $name;
                    
					},
                'filterInputOptions' => [
                    'class'       => 'form-control',
                    'placeholder' => 'Search In Charge Person',
                ],
            ],*/

            [
                'attribute' => 'reason',
                'filterInputOptions' => [
                    'class'       => 'form-control',
                    'placeholder' => 'Search Reason',
                ],
            ],
		],
               
    	         
    ]); ?>