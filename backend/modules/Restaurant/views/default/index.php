<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
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
                'attribute' => 'area',
                'value' => 'area.Area_State',
                'filter' => $stateList,
            ],
            [
                'attribute' => 'address',
                'value' => 'fulladdress',
            ],
            'Restaurant_DateTimeCreated:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{fooddetail}',
                'header' => "Food Detail",
                'buttons' => [
                    'fooddetail' => function($url , $model)
                    {
                        $url =  Url::to(['parcel/confirmreceived' ,'id'=>$model->id,'status'=>$model->status]);

                        return $model->status == 3 ? Html::a('Confirm Received' , $url , ['class' => 'text-underline','title' => 'Confirm Received','data-confirm'=>"Confirm action?"]): '' ;
                    },
                ],
            ],
        ]
    ]); ?>