<?php

use yii\helpers\Html;
use wbraganca\dynamicform\DynamicFormWidget;

?>           
           
             <?php DynamicFormWidget::begin([
                    'widgetContainer' => 'dynamicform_inner',
                    'widgetBody' => '.container-rooms',
                    'widgetItem' => '.room-item',
                    'limit' => 10,
                    'min' => 1,
                    'insertButton' => '.add-room',
                    'deleteButton' => '.remove-room',
                    'model' => $foodselection[0],
                    'formId' => 'dynamic-form',
                    'formFields' => [
                    'Selection_ID',
                    'FoodType_ID',
                    'Food_ID',
                    'Selection_Name',
                    'Selection_Price',
                    'Selection_Desc',
    ],
]); ?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th><?= Yii::t('food','Food Selection Name') ?></th>
            <th><?= Yii::t('food','Price') ?></th>
            <th><?= Yii::t('common','Description') ?></th>
            <th class="text-center">
                <button type="button" class="add-room btn btn-success btn-xs"><span class="glyphicon glyphicon-plus"></span></button>
            </th>
        </tr>
    </thead>
    <tbody class="container-rooms">
    <?php foreach ($foodselection as $ix => $foodselection): ?>
        <tr class="room-item">
            <td class="vcenter">
                <?php
                    // necessary for update action.
                    if (! $foodselection->isNewRecord) {
                        echo Html::activeHiddenInput($foodselection, "[{$i}][{$ix}]Selection_ID");
                    }
                ?>
                <?= $form->field($foodselection, "[{$i}][{$ix}]Selection_Name")->label(false)->textInput(['maxlength' => true]) ?>
                
                
            </td>
             <td class="vcenter"><?= $form->field($foodselection, "[{$i}][{$ix}]Selection_Price")->label(false)->textInput(['maxlength' => true]) ?></td>
             <td class="vcenter"><?= $form->field($foodselection, "[{$i}][{$ix}]Selection_Price")->label(false)->textInput(['maxlength' => true]) ?></td>
             <td class="vcenter"><?= $form->field($foodselection, "[{$i}][{$ix}]Selection_Desc")->label(false)->textInput(['maxlength' => true]) ?></td>
            <td class="text-center vcenter" style="width: 90px;">
                <button type="button" class="remove-room btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus"></span></button>
            </td>
        </tr>
     <?php endforeach; ?>
    </tbody>
</table>
<?php DynamicFormWidget::end(); ?>