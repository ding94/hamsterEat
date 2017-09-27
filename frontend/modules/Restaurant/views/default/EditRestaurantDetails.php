<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = "Edit Restaurant's Details";
$this->params['breadcrumbs'][] = $this->title;
$tags = explode(",",$restaurantdetails['Restaurant_Tag']);
$tags2 = [$tags[0],$tags[1],$tags[2]];
$restaurantdetails->Restaurant_Tag = $tags2;
if (!is_null($restArea))
{
    $restaurantdetails['Restaurant_AreaGroup']=$restArea;
}

if (!is_null($postcodechosen))
{
    $restaurantdetails['Restaurant_Postcode']=$postcodechosen;
}

if (!is_null($areachosen))
{
    $restaurantdetails['Restaurant_Area']=$areachosen;
}
?>
<div class="site-signup">
           <div class="col-lg-5 col-lg-offset-4">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to details:</p>
</div>
    <div class="row">
              <div class="col-lg-5 col-lg-offset-4">

            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($restaurantdetails, 'Restaurant_Name')->textInput()->label('Restaurant Name') ?>

                <?= $form->field($restaurantdetails, 'Restaurant_LicenseNo')->textInput()->label('Restaurant License No') ?>

                <?php echo "<strong>"."Restaurant Area"; ?> </strong> <br> <?php echo $restaurantdetails['Restaurant_Area']; ?> <br> <br>

                <?php echo "<strong>"."Restaurant Postcode"; ?> </strong> <br> <?php echo $restaurantdetails['Restaurant_Postcode']; ?> <br> <br>

                <?php echo "<strong>"."Restaurant Group Area"; ?> </strong> <br> <?php echo $restaurantdetails['Restaurant_AreaGroup']; ?> <br> <br>

                <?php echo Html::a('Edit Area', ['edit-restaurant-area', 'rid'=>$restaurantdetails['Restaurant_ID']], ['class'=>'btn btn-default']); ?> <br> <br>

                <?= $form->field($restaurantdetails, 'Restaurant_Pricing')->radioList(["1"=>'Less than RM 10',"2"=>'More than RM 10', "3"=>'More Than RM 100'])->label('Average Food Prices') ?>

                <?= $form->field($restaurantdetails, 'Restaurant_Tag')->inline(true)->checkboxList(['American'=>'American', 'Asian'=>'Asian', 'Beverages'=>'Beverages', 'Chinese'=>'Chinese', 'Desserts'=>'Desserts', 'Fast Food'=>'Fast Food', 'Healthy Food'=>'Healthy Food', 'Indian'=>'Indian', 'Indonesian'=>'Indonesian', 'Italian'=>'Italian', 'Japanese'=>'Japanese', 'Korean'=>'Korean', 'Malaysian Food'=>'Malaysian Food', 'Mexican'=>'Mexican', 'Middle Eastern'=>'Middle Eastern', 'Pizza'=>'Pizza', 'Seafood'=>'Seafood', 'Sushi'=>'Sushi', 'Thai'=>'Thai', 'Vegetarian'=>'Vegetarian', 'Western'=>'Western'])->label('Restaurant Type (Select Up to 3)') ?>
          
                <?= $form->field($restaurantdetails, 'Restaurant_RestaurantPicPath')->fileInput()->label('Picture') ?>

                <div class="form-group">
                    <?= Html::submitButton('Done', ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>