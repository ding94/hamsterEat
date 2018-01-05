<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use iutbay\yii2fontawesome\FontAwesome as FA;
/* @var $this yii\web\View */

?>
    <h4><?= Html::encode("Food Selection") ?></h4>
    <?php if(!empty($model)) : ?>
        <?php 
            $dataProvider = new ArrayDataProvider([
                'allModels' => $model,
            ]);
        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute' => 'typeName',
                    'value' => function($model)
                    {
                        return $model->selectedtpye->TypeName;
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
                'Name',
                'Nickname',
                'BeforeMarkedUp',
                'Price',
                /*[
                    'attribute' => 'tpyeStatus',
                    'format' => 'raw',
                    'value' => function($model)
                    {
                        if($model->Status == 0)
                        {
                            $url =Url::to(['food/type-control','id' =>$model->ID ,'status' => 1]);
                        }
                        else
                        {
                            $url =Url::to(['food/type-control','id' =>$model->ID ,'status' => 0]);
                        }
                        return $model->Status == 0 ?  Html::a(FA::icon('toggle-off lg') , $url , ['title' => 'ON']) :  Html::a(FA::icon('toggle-on lg') , $url , ['title' => 'OFF']);
                    },
                    'filter' =>  array( 0=>"Close",1=>"Open"),
                ],*/
            ],
        ])
        ?>
    <?php endif ; ?>
