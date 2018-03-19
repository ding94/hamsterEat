<?php

/* @var $this yii\web\View */

use yii\widgets\DetailView;

    echo DetailView::widget([
    'model' => $model,
    'attributes' =>[
        'name',
        'contactno',
        'companyName',
        'location',
        'postcode',
        'deliveryName',
    ],
    
]);
?>