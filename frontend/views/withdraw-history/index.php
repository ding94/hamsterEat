<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\Select2;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Admin;
use common\models\Bank;
use frontend\assets\TopupWithdrawMpHistoryAsset;

$this->title = Yii::t('common','Withdraw History');
TopupWithdrawMpHistoryAsset::register($this);
?>

<div class="balance">
<div id="userprofile" class="row">
   <div class="userprofile-header">
        <div class="userprofile-header-title"><?php echo Html::encode($this->title)?></div>
    </div>
    <div class="topup-detail">
        <div class="col-sm-2" style="padding-bottom:20px;">
        	<div class="dropdown-url">
                <?php 
                    echo Select2::widget([
                        'name' => 'url-redirect',
                        'hideSearch' => true,
                        'data' => $link,
                        'options' => [
                            'placeholder' => 'Go To ...',
                            'multiple' => false,

                        ],
                        'pluginEvents' => [
                             "change" => 'function (e){
                                location.href =this.value;
                            }',
                        ]
                    ])
                ;?>
            </div>
           	<div class="nav-url">
           		<ul class="nav nav-pills nav-stacked">
                	<li role="presentation" class="active"><a href="#" class="btn-block userprofile-edit-left-nav"><?= Yii::t('common','Balance history')?></a></li>
                	<li role="presentation"><?php echo Html::a(Yii::t('common','Top Up'),['/topup/index'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
                	<li role="presentation"><?php echo Html::a(Yii::t('common','Withdraw'),['/withdraw/index'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
            	</ul>
           	</div>
        </div>
        <div class="col-sm-10 right-side">
            <div  id="withdraw-history" class="history-container">
              <table class="table table-user-information" id="display">
                <tr>
                    <td id="right" class="link history-font"><?php echo Html::a(Yii::t('common','User Balance History'),['/user/userbalance'],['class'=>'btn-block remove-all'])?></td> 
                    <td id="middle" class="link history-font"><?php echo Html::a(Yii::t('common','Top Up History'),['/topup-history/index'],['class'=>'btn-block remove-all'])?></td>
                    <td id="left" class="history-font" ><?php echo Html::encode($this->title)?></td>
                </tr>  
              </table>
            </div> 
            <div class="account-history">
             	<?= GridView::widget([
					'dataProvider' => $model,
					'filterModel' => $searchModel,
					'tableOptions'=>['class'=>'table table-hover border'],
					'summary' => '',
					'columns' => [
						[
							'label' => Yii::t('common','Amount').'(RM)',	
							'attribute' => 'withdraw_amount',
							'filterInputOptions' => [
								'class'       => 'form-control',
								'placeholder' => Yii::t('user','Search Amount'),
							],
							'contentOptions' => ['data-th' => 'Amount'],
						],
						[
		                    'attribute' => 'bank.Bank_Name',
							
		                    'filterInputOptions' => [
		                            'class'       => 'form-control',
		                            'placeholder' => Yii::t('user','Search Bank Name'),
		                    ],
		                    'contentOptions' => ['data-th' => 'Bank Name'],
		                    'label' => Yii::t('user','Bank Name'),
								
		                ],
						[
							'label' => Yii::t('common','Status'),
		                    'format' => 'raw',
		                    'headerOptions' => ['width' => "15px"],
		                    'contentOptions' => ['style' => 'font-size:20px;'],
							'attribute' => 'accounttopup_status.title',
							'value' => function($model){
		                        return Html::tag('span' , $model->accounttopup_status->title ,['class' => $model->accounttopup_status->labelName ]);
		                    },
							'filter' => $list,
							'contentOptions' => ['data-th' => 'Status'],
						],
						[
			                'attribute' => 'reason',
			                'filterInputOptions' => [
			                    'class'       => 'form-control',
			                    'placeholder' => Yii::t('user','Search Reason'),
			                ],
			                'contentOptions' => ['data-th' => 'Reason'],
			                'label' => Yii::t('common','Reason'),
		            	],
					],
				]); ?>
            </div>
        </div>
    </div>
</div>
</div>