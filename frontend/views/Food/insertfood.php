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
<div class="site-newfood">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please insert your food's details:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-newfood']); ?>
                <?= $form->field($food, 'Food_FoodPicPath')->fileInput()->label('Picture') ?>

                <?= $form->field($food, 'Food_Name')->textInput()->label('Food Name') ?>

                <?= $form->field($food, 'Food_Halal')->inline(true)->radioList(['N'=>'Non-Halal','H'=>'Halal'])->label('Halal') ?>

                <?= $form->field($food, 'Food_Type')->inline(true)->checkboxList([ 'Cu'=>'Curry', 'D'=>'Dim Sum', 'F'=>'Fast Food', 'Fi'=>'Finger Foods', 'F'=>'Fish', 'G'=>'Gluten-Free', 'Ma'=>'Malay', 'M'=>'Meat', 'N'=>'Noodles', 'P'=>'Pasta', 'R'=>'Rice', 'S'=>'Salad', 'Sa'=>'Sashimi', 'So'=>'Soup', 'Sw'=>'Sweets', 'T'=>'Tacos', 'W'=>'Waffle'])->label('Food Type') ?>

                <?= $form->field($food, 'Food_Price')->textInput()->label('Food Price') ?>

                 <?= $form->field($food, 'Food_Desc')->textInput()->label('Food Description') ?>

                

                <div class="form-group">
                    <?= Html::submitButton('Done', ['class' => 'btn btn-primary', 'name' => 'insert-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>