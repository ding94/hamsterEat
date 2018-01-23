<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\food\Foodselectiontype;
use common\models\food\Foodselection;
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
                    'attribute' => 'FoodType_ID',
                    'value' => function($model)
                    {
                        $data = Foodselectiontype::findOne($model->FoodType_ID);
                        return $data->TypeName;
                    },
                    'group'=>true, 
                ],
                [
                    'attribute' => 'Selection_ID',
                    'value' => function($model)
                    {
                        $data = Foodselection::findOne($model->Selection_ID);
                        
                        return $data->Name;
                    },
                ]
                
            ],
        ])
        ?>
    <?php endif ; ?>
