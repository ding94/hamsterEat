<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use common\models\Upload;

use wbraganca\dynamicform\DynamicFormWidget;
$this->title = 'Edit Food Item';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-newfood">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please insert your food's details:</p>

    <div class="row">
        <div class="col-sm-6">
            <?php $form = ActiveForm::begin(['action' => ['/food/postedit','id' => $food->Food_ID],'id' => 'dynamic-form']); ?>
                <?= $form->field($food, 'PicPath')->fileInput()->label('Picture') ?>
                <?= $form->field($food, 'Name')->textInput()->label('Name') ?>
               
               <?php echo $form->field($food, 'roundprice', [
                    'addon' => [
                        'append' => ['content' => ' 1.3  '],
                        //'groupOptions' => ['class'=>'input-group-lg'],
                        'contentAfter' => '<input id="afterprice" class="form-control" name="Food[Price]" onchange="changePrice()" type="text" value="'.$food['Price'].'">'
                    ]
                ])->textInput(['readonly' => true,'id'=>'price'])->label("Money Received");?>
     
                <?php echo '<label class="control-label">Type</label>';
                        echo Select2::widget([
                            'name' => 'Type_ID',
                            'value' => $chosen,
                            'data' => $type,
                            'showToggleAll' => false,
                            'options' => ['placeholder' => 'Select a type ...', 'multiple' => true],
                            'pluginOptions' => [
                                'tags' => true,
                                'maximumInputLength' => 10,
                                'maximumSelectionLength' => 3,
                            ],
                        ]);
                ?>
                <br>
                <br>
                 <?= $form->field($food, 'Description')->textInput()->label('Description') ?>
                  
               <?php DynamicFormWidget::begin([
                    'widgetContainer' => 'dynamicform_wrapper',
                    'widgetBody' => '.container-items',
                    'widgetItem' => '.house-item',
                    'limit' => 3,
                    'min' => 0,
                    'insertButton' => '.add-house',
                    'deleteButton' => '.remove-house',
                    'model' => $foodtype[0],
                    'formId' => 'dynamic-form',
                    'formFields' => [
                    'ID',
                    'Food_ID',
                    'TypeName',
                    'Min',
                    'Max',
        ],
    ]); ?>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="col-md-3">Food Option</th>
                <th style="width: 842px;">Selection</th>
                <th class="text-center" style="width: 90px;">
                    <button type="button" class="add-house btn btn-success btn-xs"><span class="glyphicon glyphicon-plus"></span></button>
                </th>
            </tr>
        </thead>
        <tbody class="container-items">
        <?php foreach ($foodtype as $i => $foodtype): ?>
            <tr class="house-item" >
                <td class="vcenter">
                    <?php
                        // necessary for update action.
                        if (! $foodtype->isNewRecord) {
                            echo Html::activeHiddenInput($foodtype, "[{$i}]ID");
                        }
                    ?>
                    <?= $form->field($foodtype, "[{$i}]TypeName")->textInput(['maxlength' => true]) ?>
                    <?= $form->field($foodtype, "[{$i}]Min")->textInput(['maxlength' => true]) ?>
                    <?= $form->field($foodtype, "[{$i}]Max")->textInput(['maxlength' => true]) ?>
                </td>
                <td>
                     <?= $this->render('foodselection', [
                        'form' => $form,
                        'i' => $i,
                        'foodselection' => $foodselection[$i],
                    ]) ?>
                </td>
                <td class="text-center vcenter" style="width: 90px; verti">
                    <button type="button" class="remove-house btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus"></span></button>
                </td>
            </tr>
         <?php endforeach; ?>
        </tbody>
    </table>
    <?php DynamicFormWidget::end(); ?>

                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'insert-button']) ?>
                </div>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

 
