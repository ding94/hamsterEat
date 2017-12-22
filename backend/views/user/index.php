<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Url;
use iutbay\yii2fontawesome\FontAwesome as FA;

    $this->title =  'User List';
    $this->params['breadcrumbs'][] = $this->title;
?>

    <?= GridView::widget([

        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'headerOptions' => ['width' => "15px"],
            ],
            'username',
			'email',
             [
                'attribute' => 'status',
                'value' => function($model)
                {
                    return $model->status ==10 ? 'Active' : 'Inactive';
                },
                'filter' => array( "10"=>"Active","0"=>"Inactive"),
            ],
            'userdetails.fullname',
            'authAssignment.item_name',
            'userdetails.User_ContactNo',
            'balance.User_Balance',
			'created_at:time',
            'updated_at:time',

            ['class' => 'yii\grid\ActionColumn' , 
             'template'=>'{update}',
             ],
			['class' => 'yii\grid\ActionColumn' , 
             'template'=>'{active} ',
			 'header' => "Action",
             'buttons' => [
                'active' => function($url , $model)
                {
                    if($model->status == 0)
                    {
                         $url = Url::to(['user/active' ,'id'=>$model->id]);
                    }
                    else
                    {
                        $url = Url::to(['user/delete' ,'id'=>$model->id]) ;
                    }
                   
                    return  $model->status ==10  ? Html::a(FA::icon('toggle-on lg') , $url , ['title' => 'Deactivate']) : Html::a(FA::icon('toggle-off lg') , $url , ['title' => 'Activate']);
                },
              ]
            ],
        ],
    ]); ?>

