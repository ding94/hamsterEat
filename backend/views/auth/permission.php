<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;

    $this->title =  'Auth Permission';
    $this->params['breadcrumbs'][] = $this->title;
?>

    <?= GridView::widget([
        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'name',
                'filterInputOptions' => [
                    'class'       => 'form-control',
                    'placeholder' => 'Search Name',
                ],
            ],
            [
                'attribute' => 'description',
                'filterInputOptions' => [
                    'class'       => 'form-control',
                    'placeholder' => 'Search Description',
                ],
            ],
            'updated_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
            ],
        ],
    ]); ?>

