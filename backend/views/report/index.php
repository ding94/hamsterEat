<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;
use kartik\widgets\ActiveForm;

	$this->title = 'Report';
	$this->params['breadcrumbs'][] = $this->title;
	
?>
	
	<?= GridView::widget([
        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'columns' => [
                  'Report_ID',
    	            [
                    'label' => 'Username',
                    'attribute' => 'User_Username',
                  ],
    	            'Report_Category',
                  'Report_Reason',
                  'Report_PersonReported',
					// [
					// 	'label' => 'Status',
					// 	'attribute' => 'accounttopup_status.title',
					// 	'value' => 'accounttopup_status.title',
					// 	'filter' => $list,
					// ],
    	            'Report_DateTime',
        ],
		
		
    ])?>
	