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
use backend\models\Admin;
use common\models\Bank;
use frontend\assets\TopupWithdrawMpHistoryAsset;

$this->title = 'Top Up History';
TopupWithdrawMpHistoryAsset::register($this);
?>

<div id="userprofile" class="row">
   <div class="userprofile-header">
        <div class="userprofile-header-title"><?php echo Html::encode($this->title)?></div>
    </div>
    <div class="topup-detail">
        <div class="col-sm-2 ">
           <ul class="nav nav-pills nav-stacked">
                <li role="presentation" class="active"><a href="#" class="btn-block userprofile-edit-left-nav">Balance history</a></li>
                <li role="presentation"><?php echo Html::a("Top Up",['/topup/index'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
                <li role="presentation"><?php echo Html::a("Withdraw",['/withdraw/index'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
            </ul>
        </div>
        <div class="col-sm-10 right-side">
            <div  id="topup-history" class="history-container">
              <table class="table table-user-information" id="display">
                <tr>
                    <td id="right" class="link history-font"><?php echo Html::a("User Balance History",['/user/userbalance'],['class'=>'btn-block remove-all'])?></td> 
                    <td id="middle" class="history-font"><?php echo Html::encode($this->title)?></td>
                    <td id="left" class="link history-font"><?php echo Html::a("Withdraw History",['/withdraw-history/index'],['class'=>'btn-block remove-all'])?></td>
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
						'label' => 'Amount(RM)',
		                'attribute' => 'Account_TopUpAmount',
		                'filterInputOptions' => [
		                    'class'       => 'form-control',
		                    'placeholder' => 'Search Amount',
		                ],
		                'contentOptions' => ['data-th' => 'Amount'],
		            ],
					[
		                'attribute' => 'bank.Bank_Name',	
		                'filterInputOptions' => [
		                    'class'       => 'form-control',
		                    'placeholder' => 'Search Bank Name',
		                ],
		                'contentOptions' => ['data-th' => 'Bank Name'],		
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
						'contentOptions' => ['data-th' => 'Status'],		
					],
					[
			            'attribute' => 'Account_RejectReason',
			            'filterInputOptions' => [
			                'class'       => 'form-control',
			                'placeholder' => 'Search Reason',
			            ],
			            'contentOptions' => ['data-th' => 'Reason'],
		            ],
				],])?>
            </div>
        </div>
    </div>
</div>

  
           