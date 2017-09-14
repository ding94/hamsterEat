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
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
                <?= $form->field($food, 'Food_FoodPicPath')->fileInput()->label('Picture') ?>

                <?= $form->field($food, 'Food_Name')->textInput()->label('Food Name') ?>

                <?= $form->field($food, 'Food_Halal')->inline(true)->radioList(['N'=>'Non-Halal','H'=>'Halal'])->label('Halal') ?>

                <?= $form->field($food, 'Food_Type')->inline(true)->checkboxList([ 'Curry'=>'Curry', 'Dim Sum'=>'Dim Sum', 'Fast Food'=>'Fast Food', 'Finger Foods'=>'Finger Foods', 'Fish'=>'Fish', 'Gluten-Free'=>'Gluten-Free', 'Malay'=>'Malay', 'Meat'=>'Meat', 'Noodles'=>'Noodles', 'Pasta'=>'Pasta', 'Rice'=>'Rice', 'Salad'=>'Salad', 'Sashimi'=>'Sashimi', 'Soup'=>'Soup', 'Sweets'=>'Sweets', 'Tacos'=>'Tacos', 'Waffle'=>'Waffle'])->label('Food Type') ?>

                <?= $form->field($food, 'Food_Price')->textInput()->label('Food Price') ?>

                 <?= $form->field($food, 'Food_Desc')->textInput()->label('Food Description') ?>

                  
                
                <?php DynamicFormWidget::begin([
                    
                    
                        'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                        'widgetBody' => '.container-items', // required: css class selector
                        'widgetItem' => '.item', // required: css class
                        'limit' => 4, // the maximum times, an element can be added (default 999)
                        'min' => 0, // 0 or 1 (default 1)
                        'insertButton' => '.add-item', // css class
                        'deleteButton' => '.remove-item', // css class
                        
                        'model' => $foodselection[0],
                        
                        'formId' => 'dynamic-form',
                        'formFields' => [
                            'Selection_ID',
                            'Food_ID',
                            'Selection_Name',
                            'Selection_Type',
                            'Selection_Price',
                            'Selection_Desc',
                        ],
                    ]); 
                    ?>
          <div class="panel panel-default">
        <div class="panel-heading">
            <h4>
                <i class="glyphicon glyphicon-envelope"></i> Food Option
                <button type="button" class="add-item btn btn-success btn-sm pull-right"><i class="glyphicon glyphicon-plus"></i> Add</button>
            </h4>
        </div>
        <div class="panel-body">
            <div class="container-items"><!-- widgetBody -->
            <?php foreach ($foodselection as $i =>  $foodselection): ?>
                <div class="item panel panel-default"><!-- widgetItem -->
                    <div class="panel-heading">
                        <h3 class="panel-title pull-left">Selection</h3>
                        <div class="pull-right">
                            <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                    
                        <?php
                            // necessary for update action.
                            if (! $foodselection->isNewRecord) {
                                echo Html::activeHiddenInput( $foodselection, "[{$i}]id");
                            }
                        ?>
                        <?= $form->field($foodselection, "[{$i}]Selection_Type")->textInput(['maxlength' => true]) ?>
                        <div class="row">
                        <table class="table table-bordered">
                         
                        <tbody class="container-loads"><!-- widgetContainer -->
                        <tr>
                          <td>
                                        <button type="button" class="del-load btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                                        <?php
                                        // necessary for update action.
                                        if (! $foodselection->isNewRecord) {
                                             echo Html::activeHiddenInput($foodselection, "[{$i}]id");
                                        }
                                        ?>
                                    </td>
                            
                               <td> <?= $form->field($foodselection, "[{$i}]Selection_Name")->textInput(['maxlength' => true]) ?> </td>
                            
                           
                               <td> <?= $form->field($foodselection, "[{$i}]Selection_Price")->textInput(['maxlength' => true]) ?></td>
                           
                            
                               <td> <?= $form->field($foodselection, "[{$i}]Selection_Desc")->textInput(['maxlength' => true]) ?></td>
                           
                            </tr>
                            </tbody>
                            
                            
                            <tfoot>
                                <td colspan="5" class="active"><button type="button" class="add-item btn btn-success btn-xs "><i class="glyphicon glyphicon-plus"></i></button></td>
                                
                            </tfoot>
                            </table>
                        </div><!-- .row -->
                      
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    </div><!-- .panel -->

                <?php DynamicFormWidget::end(); ?>
                <div class="form-group">
                    <?= Html::submitButton('Done', ['class' => 'btn btn-primary', 'name' => 'insert-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
