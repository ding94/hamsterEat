<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Upload;

use wbraganca\dynamicform\DynamicFormWidget;
$this->title = 'Edit Food Item';
$this->params['breadcrumbs'][] = $this->title;
$tags = explode(',',$food['Food_Type']);

$food->Food_Type = $tags;
?>


<div class="site-newfood">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please insert your food's details:</p>

    <div class="row">
        <div class="col-sm-6">
            <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
                <?= $form->field($food, 'Food_FoodPicPath')->fileInput()->label('Picture') ?>

                <?= $form->field($food, 'Food_Name')->textInput()->label('Food Name') ?>

                <?= $form->field($food, 'Food_Halal')->inline(true)->radioList(['Non-Halal'=>'Non-Halal','Halal'=>'Halal'])->label('Halal') ?>
               
                 <?= $form->field($food, 'Food_Type')->inline(true)->checkboxList(['Curry'=>'Curry', 'Dim Sum'=>'Dim Sum', 'Fast Food'=>'Fast Food', 'Finger Foods'=>'Finger Foods', 'Fish'=>'Fish', 'Gluten-Free'=>'Gluten-Free', 'Malay'=>'Malay', 'Meat'=>'Meat', 'Noodles'=>'Noodles', 'Pasta'=>'Pasta', 'Rice'=>'Rice', 'Salad'=>'Salad', 'Sashimi'=>'Sashimi', 'Soup'=>'Soup', 'Sweets'=>'Sweets', 'Tacos'=>'Tacos', 'Waffle'=>'Waffle'])->label('Food Type') ?>
              
                <?= $form->field($food, 'Food_Price')->textInput()->label('Food Price') ?>

                 <?= $form->field($food, 'Food_Desc')->textInput()->label('Food Description') ?>

                  
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
                    'FoodType_ID',
                    'Food_ID',
                    'Selection_Type',
                    'FoodType_Min',
                    'FoodType_Max',
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
                            echo Html::activeHiddenInput($foodtype, "[{$i}]FoodType_ID");
                        }
                    ?>
                    <?= $form->field($foodtype, "[{$i}]Selection_Type")->label(false)->textInput(['maxlength' => true]) ?>
                    <?= $form->field($foodtype, "[{$i}]FoodType_Min")->label(false)->textInput(['maxlength' => true]) ?>
                    <?= $form->field($foodtype, "[{$i}]FoodType_Max")->label(false)->textInput(['maxlength' => true]) ?>
                </td>
                <td>
                     <?= $this->render('editfoodselection', [
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
