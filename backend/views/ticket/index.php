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

    $this->title = 'Tickets/Questions Submitted by Users';
    $this->params['breadcrumbs'][] = $this->title;
    
?>


<?= GridView::widget([
        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'columns' => [

            //[ 'class' => 'yii\grid\SerialColumn',],
            'Ticket_ID',
            'User_Username',
            'Ticket_Category',
            'Ticket_Content',
            'Ticket_Status',
            ['class' => 'yii\grid\ActionColumn' , 
                 'template'=>' {img}',
                 'buttons' => [
                    'img' => function($url,$model)
                    {
                        return Html::a('Picture',Yii::$app->urlManagerFrontEnd->baseUrl.'/'.$model->Ticket_PicPath,['target'=>'_blank']); //open page in new tab
                    },
                ],
            ],

            ['class' => 'yii\grid\ActionColumn' ,
             'template'=>'{reply}',
             'buttons' => [
                'reply' => function($url , $model)
                {
                    return  Html::a(FA::icon('comment 2x') , $url , ['title' => 'Reply Problem']);
                },
              ]
            ],

            
        ],
    ])?>

