<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use iutbay\yii2fontawesome\FontAwesome as FA;
use yii\db\ActiveRecord;

	$this->title = 'Bank List';
	$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::a('Add Bank', ['/bank/addbank'], ['class'=>'btn btn-success']) ?>
<?= GridView::widget([
        'dataProvider' => $model,
        'columns' => [
	
		/*	['class' => 'yii\grid\ActionColumn' , 
             'template'=>' {img}',
             'buttons' => [
				'img' => function($url,$model)
	                {
	                    return Html::a('Picture',Yii::$app->urlManagerFrontEnd->baseUrl.'/'.$model->name,['target'=>'_blank']); //open page in new tab
	                },
              	],
			],*/
			
			[
            	'attribute' => 'Bank_Name',
            ],
            [
                'attribute' => 'Bank_AccNo',
            ],

            ['class' => 'yii\grid\ActionColumn' ,
                 'template'=>' {img}',
                 'buttons' => [
                    'img' => function($url,$model)
                    {
                        if(!empty($model->Bank_PicPath)){
                            return Html::a('Picture',Yii::$app->params['bank'].$model->Bank_PicPath,['target'=>'_blank']);
                        }
                    },
                ],
            ],

            ['class' => 'yii\grid\ActionColumn' ,
                 'template'=>' {img}',
                 'buttons' => [
                    'img' => function($url,$model)
                    {
                        return  $model->redirectUrl ? Html::a($model->redirectUrl,$model->redirectUrl,['target'=>'_blank']) :'';
                    },
                ],
            ],
			
			[
                'attribute' => 'status',
                'value' => function($model)
                {
                    return $model->status ==10 ? 'Active' : 'Inactive';
                },
                'filter' => array( "10"=>"Active","0"=>"Inactive"),

            ],

			 ['class' => 'yii\grid\ActionColumn' , 
             'template'=>'{update} ',
             'header' => "Update",
             'buttons' => [
                'update' => function($url , $model)
                {
                   $url = Url::to(['bank/update','id'=>$model->Bank_ID]);
                    
                   return $model ? Html::a(FA::icon('pencil lg') , $url , ['title' => 'Update']) : "";
                },
              ]
            ],
			
			['class' => 'yii\grid\ActionColumn' , 
             'template'=>' {active} ',
			  'header' => "Action",
             'buttons' => [
                'active' => function($url , $model)
                {
                    if($model->status == 0)
                    {
                         $url = Url::to(['bank/active' ,'id'=>$model->Bank_ID]);
                    }
                    else
                    {
                        $url = Url::to(['bank/deactivate' ,'id'=>$model->Bank_ID]) ;
                    }
                   
                    return  $model->status ==10  ? Html::a(FA::icon('toggle-on lg') , $url , ['title' => 'Deactivate']) : Html::a(FA::icon('toggle-off lg') , $url , ['title' => 'Activate']);
                },
              ]
            ], ['class' => 'yii\grid\ActionColumn',
             'template' => '{delete}',
			  'header' => "Delete",
        	]			
        ],
		
		
    ])?>