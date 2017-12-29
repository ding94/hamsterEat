<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use common\models\Upload;
use frontend\assets\AddFoodAsset;
use wbraganca\dynamicform\DynamicFormWidget;

$this->title = 'Edit Food Item';
AddFoodAsset::register($this);
?>


<div class="food-container container">
    <div class="food-header">
        <div class="food-header-title"><?= Html::encode($this->title) ?></div>
    </div>
    <div class="content">
       <div class="col-sm-2">
            <ul id="add-food-nav" class="nav nav-pills nav-stacked">
                <li role="presentation"><?php echo Html::a("<i class='fa fa-chevron-left'></i> Back",['food/menu','rid' => $food->Restaurant_ID,'page'=>'menu'])?></li>
            </ul>
       </div>
       <div class="col-sm-10 food-content">
            <?php $form = ActiveForm::begin(['id' => 'dynamic-form','action' => ['/food/postedit','id'=>$food->Food_ID]]); ?>
          
                <?= $form->field($food, 'Name')->textInput()->label('Name') ?>

                <?= $form->field($food, 'Nickname')->textInput() ?>

                <?= $form->field($food, 'roundprice', [
                    'addon' => [
                    'append' => [
                        'content' => '<i class="fa fa-times"></i> 1.3 <i>=</i>',
                    ],
                    //'groupOptions' => ['class'=>'input-group-lg'],
                        'contentAfter' => '<input id="afterprice" class="form-control" name="Food[Price]" value = "'.$food->Price.'"type="text">'
                    ]
                ])->textInput(['id'=>'price'])->label("Money Received");?>
                <?= $form->field($foodjunction, 'Type_ID')->radioList(["3"=>'Halal',"4"=>'Non-Halal'])->label('<strong>Type</strong>') ?>
                <?php  echo Select2::widget([
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
                
                <div class="food-table-outlet">
                    <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                             <th class="col-md-1 text-center ">
                                <button type="button" class="add-house btn btn-success btn-xs"><span class="glyphicon glyphicon-plus"></span></button>
                            </th>
                            <th class="col-md-2">Food Option</th>
                            <th class="col-md-8">Selection</th>
                           
                        </tr>
                    </thead>
                    <tbody class="container-items">
                    <?php foreach ($foodtype as $i => $foodtype): ?>
                        <tr class="house-item" >
                            <td class="text-center vcenter" style="width: 90px; verti">
                                <button type="button" class="remove-house btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus"></span></button>
                            </td>
                            <td class="vcenter">
                                <?php
                                    // necessary for update action.
                                    if (! $foodtype->isNewRecord) {
                                        echo Html::activeHiddenInput($foodtype, "[{$i}]ID");
                                    }
                                ?>
                                <?= $form->field($foodtype, "[{$i}]TypeName")->label('Type')->textInput(['maxlength' => true]) ?>
                                <?= $form->field($foodtype, "[{$i}]Min")->label('Minimum')->textInput(['maxlength' => true]) ?>
                                <?= $form->field($foodtype, "[{$i}]Max")->label('Maximum')->textInput(['maxlength' => true]) ?>
                            </td>
                            <td>      
                                <?= $this->render('foodselection', [ 'form' => $form,'i' => $i,'foodselection' => $foodselection[$i]]) ?>
                            </td>   
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
                
                <?php DynamicFormWidget::end(); ?>

                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'raised-btn main-btn', 'name' => 'insert-button']) ?>
                </div>
            
            <?php ActiveForm::end(); ?> 
       </div>
    </div>
</div>
