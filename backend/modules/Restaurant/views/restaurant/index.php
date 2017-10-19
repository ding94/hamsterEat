<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;

  $this->title = 'Restuarant Lists';
  $this->params['breadcrumbs'][] = $this->title;
  
?>

<?= GridView::widget([
        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'columns' => [
        	'Restaurant_ID',
        	'Restaurant_Name',
        	/*'Restaurant_Manager',
        	'Restaurant_Area',
            'Restaurant_Status',
            'Restaurant_LicenseNo',*/
            'Restaurant_Rating',
            'Restaurant_DateTimeCreated',
            
            [
                'attribute' => 'approve',
                'format' => 'raw',
                'value' => function($model,$url)
                {
                    if($model->Restaurant_Status == "Closed")
                    {
                        $url =Url::to(['restaurant/active','id' =>$model->Restaurant_ID]);
                    }
                    else
                    {
                        $url = Url::to(['restaurant/deactive','id' =>$model->Restaurant_ID]);
                    }
                
                    return $model->Restaurant_Status == "Closed" ?  Html::a(FA::icon('toggle-off lg') , $url , ['title' => 'Activate']) :  Html::a(FA::icon('toggle-on lg') , $url , ['title' => 'Deactivate']);
                },
                'filter' =>  array( "Closed"=>"Closed","Operating"=>"Operating"),
            ],

        ]
]); ?>