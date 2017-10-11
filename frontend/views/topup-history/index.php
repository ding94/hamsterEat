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

?>

<div class="container" id="topup-history-container">
	<div class="tab-content col-md-7 col-md-offset-1" >
		
		<h2>My Account History</h2><br>

       <table class="table table-user-information" id="display">
        <tr>
            <td id="topup">Topup History</td>	

            <td id="withdraw" onclick="window.document.location='../web/index.php?r=withdraw-history/index';">Withdraw History</td>
        </tr>	
    </table>	
	
 <?= GridView::widget([
        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'columns' => [
			
			  [
                    'attribute' => 'Account_TopUpAmount',
                    'filterInputOptions' => [
                            'class'       => 'form-control',
                            'placeholder' => 'Search Amount',
                         ],
                    ],
					   
                    [
                    'attribute' => 'Account_ChosenBank',
					
                    'filterInputOptions' => [
                            'class'       => 'form-control',
                            'placeholder' => 'Search Bank Name',
                         ],
						
                    ],	
					/*[
						'label' => 'Status',
                        'format' => 'raw',
                        'headerOptions' => ['width' => "15px"],
                        'contentOptions' => ['style' => 'font-size:20px;'],
						'attribute' => 'accounttopup_status.title',
						'value' => function($model){
                            return Html::tag('span' , $model->accounttopup_status->title ,['class' => $model->accounttopup_status->labelName ]);
                        },
						'filter' => $list,
					],*/
					
					[
						'label' => 'Status',
						'attribute' => 'accounttopup_status.title',
						'value' => 'accounttopup_status.title',
						'filter' => $list,
					],
					  ],])?>
					  
					  
	</div>
</div>

  
           