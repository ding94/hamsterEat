<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<?= Html::a('Add Banner', ['/banner/addbanner'], ['class'=>'btn btn-success']) ?>
<?= GridView::widget([
        'dataProvider' => $model,
        'columns' => [
	
			['class' => 'yii\grid\ActionColumn' , 
             'template'=>' {img}',
             'buttons' => [
				'img' => function($url,$model)
	                {
	                    return Html::a('Picture',Yii::$app->urlManagerFrontEnd->baseUrl.'/'.$model->name,['target'=>'_blank']); //open page in new tab
	                },
              	],
			],
			
			[
            	'attribute' => 'bannerid',
            ],
            [
                'attribute' => 'name',
            ],

            [
                'attribute' => 'title',
            ],

            [
                'attribute' => 'redirectUrl',
            ],

            [
                'attribute' => 'startTime',
            ],

            [
                'attribute' => 'endTime',
            ],

            ['class' => 'yii\grid\ActionColumn',
             'template' => '{delete}',
        	]
					
        ],
		
		
    ])?>