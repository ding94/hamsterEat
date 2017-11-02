<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use frontend\assets\NewRestaurantAsset;

$this->title = 'New Restaurant';
$this->params['breadcrumbs'][] = $this->title;
NewRestaurantAsset::register($this);
?>
<div class="site-signup">
    <div class="col-lg-8 col-lg-offset-4">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>Please enter the details of your Restaurant:</p>
    </div>
    <div class="container">
        <div class="col-lg-6 col-lg-offset-3">
            <?php $form = ActiveForm::begin(['id' => 'form-newrestaurant']); ?>
                
            <?= $form->field($restaurant, 'Restaurant_Name')->textInput(['autofocus' => true]) ?>

            <?= $form->field($restaurant, 'Restaurant_UnitNo') ?>

            <?= $form->field($restaurant, 'Restaurant_Street') ?> <br>

            <?php echo "<strong>"."Restaurant Area"; ?> </strong> <br> <?php echo $areachosen; ?> <br> <br>

            <?php echo "<strong>"."Restaurant Postcode"; ?> </strong> <br> <?php echo $postcodechosen; ?> <br> <br>

            <?php echo "<strong>"."Restaurant Group Area"; ?> </strong> <br> <?php echo $restArea; ?> <br> <br>

            <div class="five">
                <?php echo '<label class="control-label">Type</label>';
                        echo Select2::widget([
                            'name' => 'Type_ID',
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
            </div>
            <?= $form->field($restaurant, 'Restaurant_Pricing')->inline(true)->radioList([
            "1"=>'Less than RM 10',
            "2"=>'More than RM 10', 
            "3"=>'More Than RM 100'])->label('Average Food Prices') ?>

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