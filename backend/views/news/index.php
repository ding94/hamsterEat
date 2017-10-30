<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\StringHelper;

?>
<?= Html::a('Add News', ['/news/addnews'], ['class'=>'btn btn-success']) ?>
<?= GridView::widget([
        'dataProvider' => $model,
        'columns' => [
	
			// ['class' => 'yii\grid\ActionColumn' , 
   //           'template'=>' {img}',
   //           'buttons' => [
			// 	'img' => function($url,$model)
	  //               {
	  //                   return Html::a('Picture',Yii::$app->urlManagerFrontEnd->baseUrl.'/'.$model->name,['target'=>'_blank']); //open page in new tab
	  //               },
   //            	],
			// ],
			
			[
            	'attribute' => 'id',
            ],
            [
                'attribute' => 'name',
            ],

            [
                'attribute' => 'text',
                'value' => function($model)
                {
                    return StringHelper::truncate($model->text,50);
                },
                'contentOptions' => [' overflow: auto; word-wrap: break-word;'],
            ],

            ['class' => 'yii\grid\ActionColumn' , 
             'template'=>' {preview}',
             'buttons' => [
				'preview' => function($url,$model)
	                {
	                    return Html::a('Preview',Url::to(['news/preview', 'id' => $model->id]),['target'=>'_blank']); //open page in new tab
	                },
              	],
			],

            [
                'attribute' => 'startTime',
            ],

            [
                'attribute' => 'endTime',
            ],

            ['class' => 'yii\grid\ActionColumn',
             'template' => '{update}',
        	],

            ['class' => 'yii\grid\ActionColumn',
             'template' => '{delete}',
        	]
					
        ],
		
		
    ])?>