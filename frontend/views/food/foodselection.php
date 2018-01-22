<?php

use yii\helpers\Html;
use wbraganca\dynamicform\DynamicFormWidget;
use frontend\assets\AddFoodAsset;

AddFoodAsset::register($this);
?>           
  
<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_inner',
    'widgetBody' => '.container-rooms',
    'widgetItem' => '.room-item',
    'limit' => 20,
    'min' => 1,
    'insertButton' => '.add-room',
    'deleteButton' => '.remove-room',
    'model' => $foodselection[0],
    'formId' => 'dynamic-form',
    'formFields' => [
        'Food_ID',
        'Name',
        'Price',
        'BeforeMarkedUp',
        'Nickname',
    ],
]); ?>
<table class="table table-bordered selectionTable" >
    <thead>
        <tr>
             <th class="text-center">
                <button type="button" class="add-room btn btn-success btn-xs"><span class="glyphicon glyphicon-plus"></span></button>
            </th>
            <th><?= Yii::t('food','Selection Name') ?></th>
            <th><?= Yii::t('food','Received') ?></th>
            <th><?= Yii::t('food','Price') ?></th>
            <th><?= Yii::t('food','Nickname') ?></th>
        </tr>
    </thead>
    <tbody class="container-rooms">
    <?php foreach ($foodselection as $ix => $foodselection): ?>
        <tr class="room-item" >
             <td class="text-center vcenter" ">
                <button type="button" class="remove-room btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus"></span></button>
            </td>
            <td class="vcenter">
                <?php
                    // necessary for update action.
                    if (! $foodselection->isNewRecord) {
                        echo Html::activeHiddenInput($foodselection, "[{$i}][{$ix}]ID");
                    }
                ?>
                <?= $form->field($foodselection, "[{$i}][{$ix}]Name")->label(false)->textInput(['maxlength' => true]) ?>
                
            </td>
              
             <td class="vcenter selectionBefore"><?= $form->field($foodselection, "[{$i}][{$ix}]BeforeMarkedUp")->label(false)->textInput(['maxlength' => true ,'onChange' => 'markUp(2)']) ?></td>
             <td class="vcenter selectionPrice"><?= $form->field($foodselection, "[{$i}][{$ix}]Price")->label(false)->textInput(['maxlength' => true,'onChange' => 'markUp(1)']) ?></td>  
             <td class="vcenter"><?= $form->field($foodselection, "[{$i}][{$ix}]Nickname")->label(false)->textInput(['maxlength' => true]) ?></td>
        </tr>
     <?php endforeach; ?>
    </tbody>
</table>
<?php DynamicFormWidget::end(); ?>

