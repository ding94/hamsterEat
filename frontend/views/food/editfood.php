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

$this->title = Yii::t('food','Edit Food Item');
AddFoodAsset::register($this);
?>


<div class="food-container container">
    <div class="food-header">
        <div class="food-header-title"><?= Html::encode($this->title) ?></div>
    </div>
    <div class="content">
       <div class="col-sm-2">
            <ul id="add-food-nav" class="nav nav-pills nav-stacked">
                <li role="presentation"><?php echo Html::a("<i class='fa fa-chevron-left'></i>".Yii::t('common','Back'),['food/menu','rid' => $food->Restaurant_ID,'page'=>'menu'])?></li>
                <li role="presentation"><?php echo Html::a(Yii::t('food','Name Edit'),['food-name/change','rid' => $food->Restaurant_ID,'fid'=>$food->Food_ID])?></li>
            </ul>
       </div>
       <div class="col-sm-10 food-content">
            <?php $form = ActiveForm::begin(['id' => 'dynamic-form','action' => ['/food/postedit','id'=>$food->Food_ID]]); ?>
          
                <?= $form->field($food->transName, 'translation')->textInput()->label(Yii::t('common','Name')) ?>

                <?= $form->field($food, 'Nickname')->textInput()->label(Yii::t('food','Nickname')) ?>

                <?= $form->field($food, 'roundprice', [
                    'addon' => [
                    'append' => [
                        'content' => '<i class="fa fa-times"></i> 1.3 <i>=</i>',
                    ],
                    //'groupOptions' => ['class'=>'input-group-lg'],
                        'contentAfter' => '<input id="afterprice" class="form-control" name="Food[Price]" value = "'.$food->Price.'"type="text">'
                    ]
                ])->textInput(['id'=>'price'])->label(Yii::t('food','Money Received'));?>
                
                <label class="control-label"><?= Yii::t('common','Type') ?></label>
                <?php  echo Select2::widget([
                            'name' => 'Type_ID',
                            'value' => $chosen,
                            'data' => $type,
                            'options' => ['placeholder' => 'Select a type ...'],
                            'pluginOptions' => [
                                'tags' => true,
                                'maximumInputLength' => 10,
                                
                            ],
                        ]);
                ?>
                
                <?= $form->field($food, 'Description')->textInput()->label(Yii::t('common','Description'));?>
             
                <?php DynamicFormWidget::begin([
                    'widgetContainer' => 'dynamicform_wrapper',
                    'widgetBody' => '.container-items',
                    'widgetItem' => '.house-item',
                    'limit' => 10,
                    'min' => 0,
                    'insertButton' => '.add-house',
                    'deleteButton' => '.remove-house',
                    'model' => current($foodtype),
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
                            <th class="col-md-2"><?= Yii::t('food','Food Option') ?></th>
                            <th class="col-md-8"><?= Yii::t('food','Selection') ?></th>
                           
                        </tr>
                    </thead>
                    <tbody class="container-items">
                    <?php foreach ($foodtype as $i => $tpye): ?>
                        <tr class="house-item" >
                            <td class="text-center vcenter" style="width: 90px; verti">
                                <button type="button" class="remove-house btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus"></span></button>
                            </td>
                            <td class="vcenter">
                                <?php
                                    // necessary for update action.
                                    if (! $tpye->isNewRecord) {
                                        echo Html::activeHiddenInput($tpye, "[{$i}]ID");
                                    }
                                    if(!empty($tpye->transName)):
                                        echo $form->field($tpye->transName, "[{$i}]translation")->label(Yii::t('food','Type'))->textInput(['maxlength' => true]);
                                    else:
                                         echo $form->field($typeName, "[{$i}]translation")->label(Yii::t('food','Type'))->textInput(['maxlength' => true]);
                                    endif;
                                    echo $form->field($tpye, "[{$i}]Min")->label(Yii::t('food','Minimum'))->textInput(['maxlength' => true]);
                                    echo $form->field($tpye, "[{$i}]Max")->label(Yii::t('food','Maximum'))->textInput(['maxlength' => true]); 
                                ?>
                            </td>
                            <td>      
                                <?= $this->render('foodselection', [ 'form' => $form,'i' => $i,'edit'=>1,'foodselection' => $foodselection[$i],'selectionName'=>$selectionName]) ?>
                            </td>   
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
                
                <?php DynamicFormWidget::end(); ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('common','Save'), ['class' => 'raised-btn main-btn', 'name' => 'insert-button']) ?>
                </div>
            <?php ActiveForm::end(); ?> 
       </div>
    </div>
</div>
