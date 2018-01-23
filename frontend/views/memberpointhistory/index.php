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

TopupWithdrawMpHistoryAsset::register($this);
?>

<div class="container" id="mp-history-container">
	<div class="tab-content col-md-7 col-md-offset-1" >
		
		<h2><?= Yii::t('memberpoint-h','My Account History') ?></h2><br>

       <table class="table table-user-information" id="display">
        <tr>
           <td id="topup" onclick="window.document.location='../web/index.php?r=topup-history/index';">
                <?= Yii::t('memberpoint-h','Topup History') ?>
            </td>
			<td id="withdraw" onclick="window.document.location='../web/index.php?r=withdraw-history/index';">
                <?= Yii::t('common','Withdraw History') ?>
            </td>
			<td id="mp"><?= Yii::t('memberpoint-h','Point History') ?></td>
        </tr>	
    </table>	
	
 <?= GridView::widget([
        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'columns' => [
			
			  [
                    'attribute' => 'type',
                    'filterInputOptions' => [
                            'class'       => 'form-control',
                            'placeholder' => 'Search Type',
                         ],
                    ],
			 [
                    'attribute' => 'description',
					
                    'filterInputOptions' => [
                            'class'       => 'form-control',
                            'placeholder' => 'Search Description',
                         ],
						
                    ],
					  
					 [
                    'attribute' => 'amount',
					
                    'filterInputOptions' => [
                            'class'       => 'form-control',
                            'placeholder' => 'Search Amount',
                         ],
						
                    ],
					  ],])?>
					  
					  
	</div>
</div>

  
           