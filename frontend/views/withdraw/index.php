<?php
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
/* @var $this yii\web\View */
?>

<div class="container">
	<div class="tab-content col-md-6 col-md-offset-1" id="withdraw">
	<h1>User Withdraw</h1>
	<br><i><p>My Balance: <?php echo $balance['User_Balance']; ?></i></p>
	<br><i><p>You can withdraw below RM<?php echo $balance['User_Balance']-2; ?>. Transfer fee RM2.</i></p><br>
    
              <?php $form = ActiveForm::begin(); ?>

             
					<?= $form->field($model, 'withdraw_amount')->textInput() ?>
					<?= $form->field($model, 'bank_name')->textInput()?>
				    <?= $form->field($model, 'to_bank')->textInput() ?>		
					<?= $form->field($model, 'acc_name')->textInput() ?>				   
					
                <div class="form-group">
                    <?= Html::submitButton('Upload', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
            
	</div>
</div>