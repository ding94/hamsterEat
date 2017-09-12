<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'New Restaurant';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please enter the details of your Restaurant:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-newrestaurant']); ?>
                
                <?= $form->field($restaurant, 'Restaurant_Name')->textInput(['autofocus' => true]) ?>

                <?= $form->field($restaurant, 'Restaurant_UnitNo') ?>

                <?= $form->field($restaurant, 'Restaurant_Street') ?> <br>

                <?php echo "<strong>"."Restaurant Area"; ?> </strong> <br> <?php echo $areachosen; ?> <br> <br>

                <?php echo "<strong>"."Restaurant Postcode"; ?> </strong> <br> <?php echo $postcodechosen; ?> <br> <br>

                <?php echo "<strong>"."Restaurant Group Area"; ?> </strong> <br> <?php echo $restArea; ?> <br> <br>

                <?= $form->field($restaurant, 'Restaurant_Tag')->inline(true)->checkboxList([ 'American'=>'American', 'Asian'=>'Asian', 'Beverages'=>'Beverages', 'Chinese'=>'Chinese', 'Desserts'=>'Desserts', 'Fast Food'=>'Fast Food', 'Healthy Food'=>'Healthy Food', 'Indian'=>'Indian', 'Indonesian'=>'Indonesian', 'Italian'=>'Italian', 'Japanese'=>'Japanese', 'Korean'=>'Korean', 'Malaysian Food'=>'Malaysian Food', 'Mexican'=>'Mexican', 'Middle Eastern'=>'Middle Eastern', 'Pizza'=>'Pizza', 'Seafood'=>'Seafood', 'Sushi'=>'Sushi', 'Thai'=>'Thai', 'Vegetarian'=>'Vegetarian', 'Western'=>'Western'])->label('Restaurant Type (Select Up to 3)') ?>

                <?= $form->field($restaurant, 'Restaurant_Pricing')->inline(true)->radioList(["1"=>'Less than RM 10',"2"=>'More than RM 10', "3"=>'More Than RM 100'])->label('Average Food Prices') ?>

                <?= $form->field($restaurant, 'Restaurant_LicenseNo') ?>

                <?= $form->field($restaurant, 'Restaurant_RestaurantPicPath')->fileInput()->label('Restaurant Picture') ?>

                <?= Html::hiddenInput("restArea", $restArea); ?>
                <?= Html::hiddenInput("postcodechosen", $postcodechosen); ?>
                <?= Html::hiddenInput("areachosen", $areachosen); ?>
                
                
                <div class="form-group">
                    <?= Html::submitButton('Create Restaurant', ['class' => 'btn btn-primary', 'name' => 'newrestaurant-button']) ?>                
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>