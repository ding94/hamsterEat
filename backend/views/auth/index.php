<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;

    $this->title =  'Auth Role';
    $this->params['breadcrumbs'][] = $this->title;
?>

    <?= Html::a('Add Role', ['/auth/add'], ['class'=>'btn btn-success']) ?>
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
                'template' => '{view} {delete}',
            ],
        ],
    ]); ?>

