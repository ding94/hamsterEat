<?php

/* @var $this yii\web\View */

use yii\widgets\DetailView;

    echo DetailView::widget([
    'model' => $model,
    'attributes' =>[
        'OChange_PendingDateTime:datetime',
        'OChange_PreparingDateTime:datetime',
        'OChange_PickUpInProcessDateTime:datetime',
        'OChange_OnTheWayDateTime:datetime',
        'OChange_CompletedDateTime:datetime',
    ],
    
]);
?>