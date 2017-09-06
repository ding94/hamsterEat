<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;
use yii\bootstrap\Modal;

	$this->title = 'Admin List';
	$this->params['breadcrumbs'][] = $this->title;
?>

  <?=Html::a('Add New Admin',['/admin/add'],['class' => 'btn btn-success',]);?>

	<?= GridView::widget([
        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'adminname',
                    'filterInputOptions' => [
                        'class'       => 'form-control',
                        'placeholder' => 'Search Admin',
                    ],
                ],
                [
                    'attribute' => 'email',
                    'filterInputOptions' => [
                        'class'       => 'form-control',
                        'placeholder' => 'Search Email',
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
                'adminAuthAssignment.item_name',
                 'updated_at:datetime',
				 
			['class' => 'yii\grid\ActionColumn' , 
             'template'=>'{update} ',
             'header' => "Update",
			 'buttons' => [
				   'update' => function ($url, $model) {     
                                return Html::a(FA::icon('pencil lg'), $url, [
                                        'title' => Yii::t('yii', 'Update'),
                                ]);                                
            
                              }
			 ],
           
            ],
				 
            ['class' => 'yii\grid\ActionColumn' , 
             'template'=>' {active} ',
			  'header' => "Action",
             'buttons' => [
                'active' => function($url , $model)
                {
                    if($model->status == 0)
                    {
                         $url = Url::to(['admin/active' ,'id'=>$model->id]);
                    }
                    else
                    {
                        $url = Url::to(['admin/delete' ,'id'=>$model->id]) ;
                    }
                   
                    return  $model->status ==10  ? Html::a(FA::icon('toggle-on lg') , $url , ['title' => 'Deactivate']) : Html::a(FA::icon('toggle-off lg') , $url , ['title' => 'Activate']);
                },
              ]
            ],
        ],
    ]); ?>
