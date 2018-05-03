<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\bootstrap\Modal;
use common\models\RestDays;
use iutbay\yii2fontawesome\FontAwesome as FA;

    $this->title = 'HamsterEat Pause Service Setting';
    $this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::a('Add Pause Service Day',['/orders/add-rest-day'],['class'=>'btn btn-primary']); ?>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
        	[
                'attribute' => 'rest_day_name',
                'label' => 'Name',
            ],
            [
                'attribute' => 'days',
                'label' => 'Date',
                'value' => function($model){
                    return $model['month'].$model['date'];
                }
            ],
        ],
    ])
?>