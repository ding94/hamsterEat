<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\bootstrap\Modal;
use iutbay\yii2fontawesome\FontAwesome as FA;
use backend\assets\PriceConfigAsset;
PriceConfigAsset::register($this);

    $this->title = 'Rules and Price config';
    $this->params['breadcrumbs'][] = $this->title;

Modal::begin([
	'header' => '<h2 class="modal-title">Price Configuration</h2>',
	'id'     => 'add-modal',
	'size'   => 'modal-md',
	'footer' => '<a href="#" class="raised-btn alternative-btn" data-dismiss="modal">Close</a>',
]);
 Modal::end();

?>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
        	'id',
        	'item',
        	'type',
        	'value',

        	['class' => 'yii\grid\ActionColumn' , 
                 'template'=>' {edit}',
                 'buttons' => [
                    'edit' => function($url,$model)
                    {
                        return Html::a('Edit Value',Url::to(['/price/editvalue','id'=>$model['id']]),['title' => 'Edit','data-toggle'=>'modal','data-target'=>'#add-modal']);
                    },
                ],
            ],

        ],
    ])
?>