<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;

$this->title = "Edit Restaurant's Details";
$this->params['breadcrumbs'][] = $this->title;

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
           <div class="col-lg-6 col-lg-offset-3">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to details:</p>
</div>
    <div class="row">
             <div class="col-lg-6 col-lg-offset-3">

            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($restaurantdetails, 'Restaurant_Name')->textInput()->label('Restaurant Name') ?>

                <?= $form->field($restaurantdetails, 'Restaurant_LicenseNo')->textInput()->label('Restaurant License No') ?>

                <?php echo "<strong>"."Restaurant Area"; ?> </strong> <br> <?php echo $restaurantdetails['Restaurant_Area']; ?> <br> <br>

                <?php echo "<strong>"."Restaurant Postcode"; ?> </strong> <br> <?php echo $restaurantdetails['Restaurant_Postcode']; ?> <br> <br>

                <?php echo "<strong>"."Restaurant Group Area"; ?> </strong> <br> <?php echo $restaurantdetails['Restaurant_AreaGroup']; ?> <br> <br>

                <?php echo Html::a('Edit Area', ['edit-restaurant-area', 'rid'=>$restaurantdetails['Restaurant_ID']], ['class'=>'btn btn-default']); ?> <br> <br>

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

                <?= $form->field($restaurantdetails, 'Restaurant_Pricing')->radioList(["1"=>'Less than RM 10',"2"=>'More than RM 10', "3"=>'More Than RM 100'])->label('Average Food Prices') ?>
          
                <?= $form->field($restaurantdetails, 'Restaurant_RestaurantPicPath')->fileInput()->label('Picture') ?>

                <div class="form-group">
                    <?= Html::submitButton('Done', ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>