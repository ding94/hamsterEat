<?php
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use kartik\widgets\Select2;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use frontend\assets\TopupWithdrawMpHistoryAsset;

TopupWithdrawMpHistoryAsset::register($this);
?>

<div class="balance">
<div id="userprofile" class="row">
   <div class="userprofile-header">
        <div class="userprofile-header-title"><?php echo Html::encode(Yii::t('common',$this->title))?></div>
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
	                <li role="presentation" class="active"><a href="#" class="btn-block userprofile-edit-left-nav"><?= Yii::t('common','Balance history') ?></a></li>
	                <li role="presentation"><?php echo Html::a(Yii::t('common','Top Up'),['/topup/index'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
	                <li role="presentation"><?php echo Html::a(Yii::t('common','Withdraw'),['/withdraw/index'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
	            </ul>
          	</div>
        </div>
        <div class="col-sm-10 right-side">
            <div  id="account-history" class="history-container">
              <table class="table table-user-information" id="display">
                <tr>
                    <td id="right" class="history-font"><?php echo Html::encode(Yii::t('common',$this->title))?></td> 
                    <td id="middle" class="link history-font"><?php echo Html::a(Yii::t('common','Top Up History'),['/topup-history/index'],['class'=>'btn-block remove-all'])?></td>
                    <td id="left" class="link history-font"><?php echo Html::a(Yii::t('common','Withdraw History'),['/withdraw-history/index'],['class'=>'btn-block remove-all'])?></td>
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
							'label' => Yii::t('common','Date'),
							'value' => 'created_at',
							'filter' => \yii\jui\DatePicker::widget(['model'=>$searchModel, 'attribute'=>'created_at', 'dateFormat' => 'yyyy-MM-dd','options'=>['class'=>'form-control','placeholder'=>Yii::t('user','Search Date')]]),
							'format' => 'html',
							'contentOptions' => [
								'data-th' => 'Time',
								],
						],
						[
		                    'attribute' => 'description',
		                    'filterInputOptions' => [
		                            'class'       => 'form-control',
		                            'placeholder' => Yii::t('user','Search Description'),
		                    ],
		                    'contentOptions' => ['data-th' => 'Description'],
		                    'header' => Yii::t('common','Description'),
								
		                ],
		                [
		                	'attribute' => 'system_type',
		                	'filterInputOptions' => [
		                			'class'       => 'form-control',
		                            'placeholder' => Yii::t('user','Search Type'),
		                	],
		                	'contentOptions' => ['data-th' => 'Type'],
		                	'header' => Yii::t('user','Type'),
		                ],
						[
						'label' => Yii::t('common','Amount').'(RM)',
		                
		                 'value' => function($model){
		                 	
		                	return $model->type== 0 ? "-".$model->amount : $model->amount;
		                },
		                'filterInputOptions' => [
		                    'class'       => 'form-control',
		                    'placeholder' => Yii::t('user','Search Amount'),
		                ],
		                'contentOptions' => ['data-th' => 'Amount'],
		            ],
					],
				]); ?>
			
            </div>
             
              
            </div>
        </div>
    </div>
</div>