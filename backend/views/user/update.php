<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\assets\UserAsset;

UserAsset::register($this);

	$this->title = "Edit User";
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User'), 'url' => ['index']];
	$this->params['breadcrumbs'][] = $this->title;
?>

	<?php $form = ActiveForm::begin();
    	echo $form->field($model, 'email')->textInput();
    	
    	if($list['value'] === 0):
        	echo $form->field($model,'role')->dropDownlist($list['data'], ['prompt'=>'Choose...']);
        	echo Html::hiddenInput('type', 1);
        else :
        	echo $form->field($model,'role')->hiddenInput(['value'=>$list['value']])->label(false);
        	echo Html::hiddenInput('type', 2);
        endif;
        $enableRider = false;
        $enableManager = false;
        if($list['value'] === "rider") :
        	$enableRider = true;
        endif;
       	if($list['value'] === 'restaurant manager') :
        	$enableManager = true;
        endif;
      
    ?>
    <div class="restaurant-manager <?php echo $enableManager ? "" : 'none'  ?>">
    	<?php
    		echo $form->field($manager,'Rmanager_NRIC', ['enableClientValidation' => $enableManager])->textInput();
    		echo $form->field($manager,'uid')->hiddenInput(['value'=>$model->id])->label(false);
    		echo $form->field($manager,'User_Username')->hiddenInput(['value'=>$model->username])->label(false);
    		echo $form->field($manager,'Rmanager_Approval')->hiddenInput(['value'=>1])->label(false);
        ?>
    </div>
    <div class="rider <?php echo $enableRider ? "" : 'none'  ?>">
    	<?php
    		echo $form->field($deliveryMan,'User_id')->hiddenInput(['value'=>$model->id])->label(false);
    		echo $form->field($deliveryMan,'DeliveryMan_Approval')->hiddenInput(['value'=>1])->label(false);
        	echo $form->field($deliveryMan,'DeliveryMan_CarPlate', ['enableClientValidation' => $enableRider])->textInput();
        	echo $form->field($deliveryMan,'DeliveryMan_LicenseNo', ['enableClientValidation' => $enableRider])->textInput();
        	echo $form->field($deliveryMan,'DeliveryMan_VehicleType', ['enableClientValidation' => $enableRider])->dropdownList([ 'Motorcycle'=>'Motorcycle', 'Car'=>'Car', 'Van'=>'Van'],['prompt' => 'Select Vehicle Type']);
    	?>
    </div>
    <div>
        <?php 
            echo $form->field($userdetails,'User_ContactNo')->textInput();
        ?>
    </div>
    	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Add') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	   </div>
	<?php ActiveForm::end();?>