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
                    'Food_ID',
                    'Name',
                    'Price',
                    'Nickname',
    ],
]); ?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Selection Name</th><th>Price</th><th>Nickname</th>
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
                        echo Html::activeHiddenInput($foodselection, "[{$i}][{$ix}]ID");
                    }
                ?>
                <?= $form->field($foodselection, "[{$i}][{$ix}]Name")->label(false)->textInput(['maxlength' => true]) ?>
                
                
            </td>
             <td class="vcenter"><?= $form->field($foodselection, "[{$i}][{$ix}]Price")->label(false)->textInput(['maxlength' => true]) ?></td>
             <td class="vcenter"><?= $form->field($foodselection, "[{$i}][{$ix}]Nickname")->label(false)->textInput(['maxlength' => true]) ?></td>
            <td class="text-center vcenter" style="width: 90px;">
                <button type="button" class="remove-room btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus"></span></button>
            </td>
        </tr>
     <?php endforeach; ?>
    </tbody>
</table>
<?php DynamicFormWidget::end(); ?>