
<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use iutbay\yii2fontawesome\FontAwesome as FA;
use yii\helpers\ArrayHelper;
use common\models\food\Food;
    $this->title = 'Restaurants';
    $this->params['breadcrumbs'][] = $this->title;
    
?>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [ 'class' => 'yii\grid\SerialColumn',],
            'Restaurant_Name',
            'Restaurant_Manager',


            ['class' => 'yii\grid\ActionColumn' ,
             'header' => 'Show Speed',
             'template'=>'{speed}',
             'buttons' => [
                'speed' => function($url , $model)
                {
                    $food = Food::find()->where('Restaurant_ID=:rid',[':rid'=>$model['Restaurant_ID']])->one();
                    if (!empty($food)) {
                        return  Html::a(FA::icon('clock-o 2x') , Url::to(['/restaurant/restaurant/speedrating','rid'=>$model['Restaurant_ID']]), ['title' => 'Reply Problem']);
                    }
                    
                },
              ]
            ],

        ],
    ])?>

