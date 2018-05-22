<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'All Pause Service Time';
?>
<div class="site-error">
    <?= Html::a('Add Pause Condition',['/pause-service/set-pause-time'],['class'=>'btn btn-success']) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'date_format',
                'value' => function($model){
                    switch ($model['date_format']) {
                        case 'H':
                            $date = 'Hour';
                            break;
                        case 'N':
                            $date = 'Day of week';
                            break;
                        
                        default:
                            $date = 'error';
                            break;
                    }
                    return $date;
                },
            ],

            [
                'attribute' => 'symbol',
                'value' => function($model){
                    switch ($model['symbol']) {
                        case '==':
                            $symbol = 'Equals to';
                            break;
                        case '>':
                            $symbol = 'Bigger than';
                            break;
                        case '>=':
                            $symbol = 'Bigger or equals to';
                            break;
                        case '<':
                            $symbol = 'Smaller than';
                            break;
                        case '<=':
                            $symbol = 'Smaller or equals to';
                            break;
                        
                        default:
                            $symbol = 'error';
                            break;
                    }
                    return $symbol;
                },
            ],

            'time',
            
            ['class' => 'yii\grid\ActionColumn' ,
             'template'=>'{more}',
             'buttons' => [
                'more' => function($url , $model)
                {
                    return Html::a("Delete" , ['/pause-service/condition-delete','id'=>$model['id']] , ['title' => 'Delete this condition']);
                },
              ],
            ],
        ],
    ])?>

</div>
