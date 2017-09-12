<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Upload;

$this->title = 'New Food Item';
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script> 


<script>  
 $(document).ready(function(){  
$(".add").click(function(){
$(".new-fields").append('<div class="table-responsive">  <table class="table table-bordered" id="dynamic_field">
                                        <?php $form = ActiveForm::begin(['id' => 'form-newfood']); ?>
                                        <tr>  
                                        <td>Selection Type:</td>
                                        <td colspan="2">  <?= $form->field($foodselection, 'Selection_Type')->textInput()->label(false) ?></td>
                                        </tr>
                                        <tr>
                                        <td>Selection Name:</td><td>Selection Price:</td><td>Selection Description:</td>
                                        </tr>
                                        <tr>
                                        <td>  <?= $form->field($foodselection, 'Selection_Name')->textInput()->label(false) ?></td><td>  <?= $form->field($foodselection, 'Selection_Price')->textInput()->label(false) ?></td><td>  <?= $form->field($foodselection, 'Selection_Desc')->textInput()->label(false) ?></td>
                                        </tr></table>
                                        <?php ActiveForm::end(); ?>
                                         ');
});
 });  
 </script>
<div class="site-newfood">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please insert your food's details:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-newfood']); ?>
                <?= $form->field($food, 'Food_FoodPicPath')->fileInput()->label('Picture') ?>

                <?= $form->field($food, 'Food_Name')->textInput()->label('Food Name') ?>

                <?= $form->field($food, 'Food_Halal')->inline(true)->radioList(['N'=>'Non-Halal','H'=>'Halal'])->label('Halal') ?>

                <?= $form->field($food, 'Food_Type')->inline(true)->checkboxList([ 'Curry'=>'Curry', 'Dim Sum'=>'Dim Sum', 'Fast Food'=>'Fast Food', 'Finger Foods'=>'Finger Foods', 'Fish'=>'Fish', 'Gluten-Free'=>'Gluten-Free', 'Malay'=>'Malay', 'Meat'=>'Meat', 'Noodles'=>'Noodles', 'Pasta'=>'Pasta', 'Rice'=>'Rice', 'Salad'=>'Salad', 'Sashimi'=>'Sashimi', 'Soup'=>'Soup', 'Sweets'=>'Sweets', 'Tacos'=>'Tacos', 'Waffle'=>'Waffle'])->label('Food Type') ?>

                <?= $form->field($food, 'Food_Price')->textInput()->label('Food Price') ?>

                 <?= $form->field($food, 'Food_Desc')->textInput()->label('Food Description') ?>

                  <?= $form->field($foodselection, 'Food_ID',['template' => '{label}{error}'])->textInput()->label('Food Option') ?>
                <form action ="my-result.php" method="post">
                <div class="data-div">
                <div class="button-div">
                <input type="button" value="add" class="add"> <input type="button" value="remove" class="remove">
                </div>
                 <div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field">
                                        <tr>  
                                        <td>Selection Type:</td>
                                        <td colspan="2">  <?= $form->field($foodselection, 'Selection_Type')->textInput()->label(false) ?></td>
                                        </tr>
                                        <tr>
                                        <td>Selection Name:</td><td>Selection Price:</td><td>Selection Description:</td>
                                        </tr>
                                        <tr>
                                        <td>  <?= $form->field($foodselection, 'Selection_Name')->textInput()->label(false) ?></td><td>  <?= $form->field($foodselection, 'Selection_Price')->textInput()->label(false) ?></td><td>  <?= $form->field($foodselection, 'Selection_Desc')->textInput()->label(false) ?></td>
                                        </tr>
                               </table>  
                               
                          </div>
                          <div class="new-fields">
                          </div>
                          </div>  
                     </form>  
            

                <div class="form-group">
                    <?= Html::submitButton('Done', ['class' => 'btn btn-primary', 'name' => 'insert-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>