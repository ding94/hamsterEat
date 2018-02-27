<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use iutbay\yii2fontawesome\FontAwesome as FA;
/* @var $this yii\web\View */
$this->title = "Food Type And Selection";
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Food Detail '), 'url' => ['/restaurant/food','id'=>0]];
$this->params['breadcrumbs'][] = $this->title;

$array = ['-1'=>'Deleted','0'=>'Close','1'=>'Open'];        
     
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'panel'=>['type'=>'success'],
        'columns' => [
            [
                'format' => 'raw',
                'attribute' => 'originName',
                'value' => function($model, $widget)
                {
                    return Html::a($model->selectedtpye->originName,['update-type' ,'id'=>$model->Type_ID]);
                },
                'group'=>true,
            ],
            [
                'attribute' => 'min',
                'value' => function($model)
                {
                    return $model->selectedtpye->Min;
                },
                'group'=>true,
            ],
            [
                'attribute' => 'max',
                'value' => function($model)
                {
                    return $model->selectedtpye->Max;
                },
                'group'=>true,
            ],
            'transName.translation',
            'Nickname',
            'BeforeMarkedUp',
            'Price',
            [
                'attribute' => 'Status',
                'value' => function($model)use($array)
                {
                    return $array[$model->Status];
                }
            ],
                
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{onOff} {update}',
                'buttons' => [
                    'onOff' => function($url , $model)
                    {
                        if($model->Status == -1)
                        {
                            $url =Url::to(['recover','id' =>$model->ID ]);
                            $name = "Recover";
                        }
                        else
                        {
                            if($model->Status == 0)
                            {
                                $status = 1;
                                $name = "Turn On";
                            }
                            else
                            {
                                $status = 0;
                                $name = "Turn Off";
                            }
                            $url =Url::to(['control','id' =>$model->ID ,'status' => $status]);
                        }
                        
                        return Html::a($name,$url,['data'=>['confirm'=>'Are you show you want to '.$name]]);
                    },
                    
                ],
            ],
        ],
    ])
?>
  
