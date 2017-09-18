<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Upload;

use wbraganca\dynamicform\DynamicFormWidget;
$this->title = 'New Food Item';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="site-newfood">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please insert your food's details:</p>

    <div class="row">
        <div class="">
            <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
                <?= $form->field($food, 'Food_FoodPicPath')->fileInput()->label('Picture') ?>

                <?= $form->field($food, 'Food_Name')->textInput()->label('Food Name') ?>

                <?= $form->field($food, 'Food_Halal')->inline(true)->radioList(['Non-Halal'=>'Non-Halal','Halal'=>'Halal'])->label('Halal') ?>

                <?= $form->field($food, 'Food_Type')->inline(true)->checkboxList([ 'Curry'=>'Curry', 'Dim Sum'=>'Dim Sum', 'Fast Food'=>'Fast Food', 'Finger Foods'=>'Finger Foods', 'Fish'=>'Fish', 'Gluten-Free'=>'Gluten-Free', 'Malay'=>'Malay', 'Meat'=>'Meat', 'Noodles'=>'Noodles', 'Pasta'=>'Pasta', 'Rice'=>'Rice', 'Salad'=>'Salad', 'Sashimi'=>'Sashimi', 'Soup'=>'Soup', 'Sweets'=>'Sweets', 'Tacos'=>'Tacos', 'Waffle'=>'Waffle'])->label('Food Type') ?>

                <?= $form->field($food, 'Food_Price')->textInput()->label('Food Price') ?>

                 <?= $form->field($food, 'Food_Desc')->textInput()->label('Food Description') ?>

                  
               <?php DynamicFormWidget::begin([
                    'widgetContainer' => 'dynamicform_wrapper',
                    'widgetBody' => '.container-items',
                    'widgetItem' => '.house-item',
                    'limit' => 3,
                    'min' => 1,
                    'insertButton' => '.add-house',
                    'deleteButton' => '.remove-house',
                    'model' => $foodtype[0],
                    'formId' => 'dynamic-form',
                    'formFields' => [
                    'FoodType_ID',
                    'Food_ID',
                    'Selection_Type',
        ],
    ]); ?>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Food Option</th>
                <th style="width: 842px;">Rooms</th>
                <th class="text-center" style="width: 90px;">
                    <button type="button" class="add-house btn btn-success btn-xs"><span class="glyphicon glyphicon-plus"></span></button>
                </th>
            </tr>
        </thead>
        <tbody class="container-items">
        <?php foreach ($foodtype as $i => $foodtype): ?>
            <tr class="house-item">
                <td class="vcenter">
                    <?php
                        // necessary for update action.
                        if (! $foodtype->isNewRecord) {
                            echo Html::activeHiddenInput($foodtype, "[{$i}]id");
                        }
                    ?>
                    <?= $form->field($foodtype, "[{$i}]Selection_Type")->label(false)->textInput(['maxlength' => true]) ?>
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
    
 



</div>
                <div class="form-group">
                    <?= Html::submitButton('Done', ['class' => 'btn btn-primary', 'name' => 'insert-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
