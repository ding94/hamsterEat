<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;
use yii\bootstrap\Modal;

  $this->title = 'Restuarant Detail';
  $this->params['breadcrumbs'][] = $this->title;
  
?>
    <?php
    Modal::begin([
        'id' => 'managerDetail',
        'header' => '<h4 class="modal-title">...</h4>',
        'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
    $requestUrl = Url::toRoute('default/manager');
    Modal::end();
    $this->registerJs("
        $('#managerDetail').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var modal = $(this)
            var title = button.data('title') 
            var href = button.attr('href') 
            modal.find('.modal-title').html(title)
            modal.find('.modal-body').html('<i class=\"fa fa-spinner fa-spin\"></i>')
            $.post(href)
                .done(function( data ) {
                    modal.find('.modal-body').html(data)
                });
            })
    ");
    ?>

  <?= GridView::widget([
        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'Restaurant_ID',
                'headerOptions' => ['width' => "15px"],
            ],
            [
                'attribute' => 'Restaurant_Manager',
                'format' => 'raw',
                'value' => function($model)
                {
                   
                    return Html::a($model->Restaurant_Manager,['default/manager' ,'name'=>$model->Restaurant_Manager],['data-toggle'=>"modal",'data-target'=>"#managerDetail",'data-title'=>"Restuarant Manager Detail",]);
                },
            ],
            [
                'attribute' => 'Restaurant_Name',
            ],
            [
                'attribute' => 'Restaurant_Status',
                'filter' => array( "Operating"=>"Operating","Under Renovation"=>"Under Renovation"),
            ],
           
            [
                'attribute' => 'address',
                'value' => 'fulladdress',
            ],
            'Restaurant_DateTimeCreated:datetime',
            [
                'attribute' => 'approve',
                'format' => 'raw',
                'value' => function($model,$url)
                {
                    if($model->manager['Rmanager_Approval'] == 0)
                    {
                        $url =Url::to(['default/active','name' =>$model->Restaurant_Manager]);
                    }
                    else
                    {
                        $url = Url::to(['default/deactive','name' =>$model->Restaurant_Manager]);
                    }
                
                    return $model->manager['Rmanager_Approval'] == 0 ?  Html::a(FA::icon('toggle-off lg') , $url , ['title' => 'Activate']) :  Html::a(FA::icon('toggle-on lg') , $url , ['title' => 'Deactivate']);
                },
                'filter' =>  array( 0=>"Deactive",1=>"Active"),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{earning}',
                'header' => "Earning",
                'buttons' => [
                    'earning' => function($url , $model)
                    {
                        $url =  Url::to(['/restaurant/restaurant/profit' ,'id'=>$model->Restaurant_ID]);

                        return Html::a('View' , $url , ['class' => 'text-underline','title' => 'Restaurant Earning'])   ;
                    },
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{fooddetail}',
                'header' => "Food Detail",
                'buttons' => [
                    'fooddetail' => function($url , $model)
                    {
                        $url =  Url::to(['food/index' ,'id'=>$model->Restaurant_ID]);

                        return Html::a('View' , $url , ['class' => 'text-underline','title' => 'Food Detail'])   ;
                    },
                ],
            ],
        ]
    ]); ?>