<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;

?>

<div class="receipt-deposit-form">

<!-- The Deposit Information    -->
    <?php $form = ActiveForm::begin(['id' => 'deposit-form']); ?>
    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($modelDeposit, 'effective_date')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($modelDeposit, 'staff_id')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($modelDeposit, 'billing_currency_id')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

<!-- The Receipts on the Deposit -->
    <div class="row panel-body">
        <?php DynamicFormWidget::begin([
            'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
            'widgetBody' => '.container-payments', // required: css class selector
            'widgetItem' => '.payment-item', // required: css class
            'insertButton' => '.add-payment', // css class
            'deleteButton' => '.del-payment', // css class
            'model' => $modelsPayment[0],
            'formId' => 'deposit-form',
            'formFields' => [
                'created_date',
                'cash_receipt_id',
                'voided',
            ],
        ]); ?>


        <h4>Deposit Receipts</h4>
        <table class="table table-bordered">
            <thead>
                <tr class="active">
                    <td></td>
                    <td><?= Html::activeLabel($modelsPayment[0], 'created_date'); ?></td>
                    <td><?= Html::activeLabel($modelsPayment[0], 'cash_receipt_id'); ?></td>
                    <td><?= Html::activeLabel($modelsPayment[0], 'voided'); ?></td>
                    <td><label class="control-label">Receipt Items</label></td>
                </tr>
            </thead>

            <tbody class="container-payments"><!-- widgetContainer -->
            <?php foreach ($modelsPayment as $i => $modelPayment): ?>
                <tr class="payment-item"><!-- widgetBody -->
                    <td>
                        <button type="button" class="del-payment btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                        <?php
                        // necessary for update action.
                        if (! $modelPayment->isNewRecord) {
                            echo Html::activeHiddenInput($modelPayment, "[{$i}]id");
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $form->field($modelPayment, "[{$i}]created_date")->begin();
                            echo Html::activeTextInput($modelPayment, "[{$i}]created_date", ['maxlength' => true, 'class' => 'form-control']); //Field
                            echo Html::error($modelPayment,"[{$i}]created_date", ['class' => 'help-block']); //error
                            echo $form->field($modelPayment, "[{$i}]created_date")->end();
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $form->field($modelPayment, "[{$i}]cash_receipt_id")->begin();
                            echo Html::activeTextInput($modelPayment, "[{$i}]cash_receipt_id", ['maxlength' => true, 'class' => 'form-control']); //Field
                            echo Html::error($modelPayment,"[{$i}]cash_receipt_id", ['class' => 'help-block']); //error
                            echo $form->field($modelPayment, "[{$i}]cash_receipt_id")->end();
                        ?>
                    </td>
                    <td>
                        <?php
                            if(!$modelPayment->isNewRecord && $modelPayment->cashReceipt->voided) {
                                $modelPayment->voided = $modelPayment->cashReceipt->voided;
                            }
                            echo $form->field($modelPayment, "[{$i}]voided")->begin();
                            echo Html::activeCheckbox($modelPayment, "[{$i}]voided", ['class' => 'form-control', 'label'=>'']); //Field
                            echo Html::error($modelPayment,"[{$i}]voided", ['class' => 'help-block']); //error
                            echo $form->field($modelPayment, "[{$i}]voided")->end();
                        ?>
                    </td>

<!-- The Items on the Receipt -->
                    <td id="payment_loads">

                        <?php DynamicFormWidget::begin([
                            'widgetContainer' => 'dynamicform_inner', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                            'widgetBody' => '.container-loads', // required: css class selector
                            'widgetItem' => '.load-item', // required: css class
                            'insertButton' => '.add-load', // css class
                            'deleteButton' => '.del-load', // css class
                            'model' => $modelsPaymentLoads[$i][0],
                            'formId' => 'deposit-form',
                            'formFields' => [
                                'departure_nav_waypt_airport_id',
                                'destination_nav_waypt_airport_id',
                                'load_rate_id',
                                'price',
                            ],
                        ]);

                        ?>

                        <table class="table table-bordered">
                            <thead>
                                <tr class="active">
                                    <td></td>
                                    <td><?= Html::activeLabel($modelsPaymentLoads[$i][0], 'departure_nav_waypt_airport_id'); ?></td>
                                    <td><?= Html::activeLabel($modelsPaymentLoads[$i][0], 'destination_nav_waypt_airport_id'); ?></td>
                                    <td><?= Html::activeLabel($modelsPaymentLoads[$i][0], 'load_rate_id'); ?></td>
                                    <td><?= Html::activeLabel($modelsPaymentLoads[$i][0], 'paymentLoadsPrice.ttl_price_target'); ?></td>
                                </tr>
                            </thead>
                            <tbody class="container-loads"><!-- widgetContainer -->
                            <?php foreach ($modelsPaymentLoads[$i] as $ix => $modelPaymentLoads): ?>
                                <tr class="load-item"><!-- widgetBody -->
                                    <td>
                                        <button type="button" class="del-load btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                                        <?php
                                        // necessary for update action.
                                        if (! $modelPaymentLoads->isNewRecord) {
                                            echo Html::activeHiddenInput($modelPaymentLoads, "[{$i}][{$ix}]id");
                                        }
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                            echo $form->field($modelPaymentLoads, "[{$i}][{$ix}]departure_nav_waypt_airport_id")->begin();
                                            echo Html::activeTextInput($modelPaymentLoads, "[{$i}][{$ix}]departure_nav_waypt_airport_id", ['maxlength' => true, 'class' => 'form-control']); //Field
                                            echo Html::error($modelPaymentLoads,"[{$i}][{$ix}]departure_nav_waypt_airport_id", ['class' => 'help-block']); //error
                                            echo $form->field($modelPaymentLoads, "[{$i}][{$ix}]departure_nav_waypt_airport_id")->end();
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            echo $form->field($modelPaymentLoads, "[{$i}][{$ix}]destination_nav_waypt_airport_id")->begin();
                                            echo Html::activeTextInput($modelPaymentLoads, "[{$i}][{$ix}]destination_nav_waypt_airport_id", ['maxlength' => true, 'class' => 'form-control']); //Field
                                            echo Html::error($modelPaymentLoads,"[{$i}][{$ix}]destination_nav_waypt_airport_id", ['class' => 'help-block']); //error
                                            echo $form->field($modelPaymentLoads, "[{$i}][{$ix}]destination_nav_waypt_airport_id")->end();
                                        ?>

                                    </td>
                                    <td>
                                        <?php
                                            echo $form->field($modelPaymentLoads, "[{$i}][{$ix}]load_rate_id")->begin();
                                            echo Html::activeTextInput($modelPaymentLoads, "[{$i}][{$ix}]load_rate_id", ['maxlength' => true, 'class' => 'form-control']); //Field
                                            echo Html::error($modelPaymentLoads,"[{$i}][{$ix}]load_rate_id", ['class' => 'help-block']); //error
                                            echo $form->field($modelPaymentLoads, "[{$i}][{$ix}]load_rate_id")->end();
                                        ?>
                                    </td>
                                    <td>
                                        <?php

                                            if(!$modelPaymentLoads->isNewRecord) {
                                                $modelPaymentLoads->price = $modelPaymentLoads->readLoadPrice();
                                            }
                                            echo $form->field($modelPaymentLoads, "[{$i}][{$ix}]price")->begin();
                                            echo Html::activeTextInput($modelPaymentLoads, "[{$i}][{$ix}]price", ['maxlength' => true, 'class' => 'form-control']); //Field
                                            echo Html::error($modelPaymentLoads,"[{$i}][{$ix}]price", ['class' => 'help-block']); //error
                                            echo $form->field($modelPaymentLoads, "[{$i}][{$ix}]price")->end();
                                        ?>
                                    </td>

                                </tr>
                            <?php endforeach; // end of loads loop ?>
                            </tbody>
                            <tfoot>
                                <td colspan="5" class="active"><button type="button" class="add-load btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button></td>
                            </tfoot>
                        </table>
                        <?php DynamicFormWidget::end(); // end of loads widget ?>

                    </td> <!-- loads sub column -->
                </tr><!-- payment -->
            <?php endforeach; // end of payment loop ?>
            </tbody>
            <tfoot>
                <td colspan="5" class="active">
                    <button type="button" class="add-payment btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                </td>
            </tfoot>
        </table>
        <?php DynamicFormWidget::end(); // end of payment widget ?>

    </div>
    
    <div class="form-group">
        <?= Html::submitButton($modelPayment->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
    </div>
                

    <?php ActiveForm::end(); ?>
   
    
</div>