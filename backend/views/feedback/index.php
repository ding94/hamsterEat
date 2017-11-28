<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

			[
            	'attribute' => 'User_Username',
            ],
            [
                'attribute' => 'Feedback_Category',
            ],

            [
                'attribute' => 'Feedback_Message',
            ],
            'Feedback_DateTime:datetime',

            ['class' => 'yii\grid\ActionColumn' , 
                 'template'=>' {img}',
                 'buttons' => [
                    'img' => function($url,$model)
                    {
                        if(!empty($model->Feedback_PicPath)){
                            return Html::a('Picture',Yii::$app->urlManagerFrontEnd->baseUrl.'/'.$model->Feedback_PicPath,['target'=>'_blank']);
                        }
                    },
                ],
            ],
        ],
		
		
    ])?>