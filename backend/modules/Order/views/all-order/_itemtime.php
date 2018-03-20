<?php

/* @var $this yii\web\View */

use yii\widgets\DetailView;

    echo DetailView::widget([
    'model' => $model,
    'attributes' =>[
        'Change_PendingDateTime:datetime',
        'Change_PreparingDateTime:datetime',
        'Change_ReadyForPickUpDateTime:datetime',
        'Change_PickedUpDateTime:datetime',
    ],
    
]);
?>