<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use iutbay\yii2fontawesome\FontAwesome as FA;

    $this->title =  'User List';
    $this->params['breadcrumbs'][] = $this->title;
?>
<?php
    Modal::begin([
        'id' => 'userDetail',
        'header' => '<h4 class="modal-title">...</h4>',
        'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
  
    Modal::end();
    $this->registerJs("
        $('#userDetail').on('show.bs.modal', function (event) {
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
                'attribute' => 'id',
                'headerOptions' => ['width' => "15px"],
            ],
           
            [
                'attribute' => 'username',
                'format' => 'raw',
                'value' => function($model)
                {
                   
                     return Html::a($model->username,['user/detail' ,'id'=>$model->id],['data-toggle'=>"modal",'data-target'=>"#userDetail",'data-title'=>"User Detail",]);
                },
            ],
			'email',
            [  
                'label'=>'Phone No.',
                'attribute' => 'user_contactno',
                'value' => 'userdetails.User_ContactNo',
            ],
             [
                'attribute' => 'status',
                'value' => function($model)
                {
                    return $model->status ==10 ? 'Active' : 'Inactive';
                },
                'filter' => array( "10"=>"Active","0"=>"Inactive"),
            ],
            'authAssignment.item_name',  
            [
                'label' => 'User Balance',
                'attribute' => 'balance.User_Balance',
                'value' => 'balance.User_Balance',
                'headerOptions' => ['width' => "20px"],
            ],
			'created_at:datetime',
            'updated_at:datetime',

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
            ['class' => 'yii\grid\ActionColumn' , 
             'template'=>'{active} ',
             'header' => "Resend Email",
             'buttons' => [
                'active' => function($url , $model)
                {
                    if($model->status == 1)
                    {
                        return Html::a("Resend email", ['/site/resendconfirmlink','id'=>$model->id]);
                    }
                    else{
                        return Html::a("");
                    }
                   
                    return  $model->status ==10  ? Html::a(FA::icon('toggle-on lg') , $url , ['title' => 'Deactivate']) : Html::a(FA::icon('toggle-off lg') , $url , ['title' => 'Activate']);
                },
              ]
            ],
        ],
    ]); ?>

