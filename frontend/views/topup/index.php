
<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
$this->title = "Top up";	
?>

<div class="container">
 
        
		<div class="col-lg-6 col-lg-offset-1" style="text-align:center" id="topup">
     
			<h1>Offline Topup</h1>
			
            <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model,'Account_ChosenBank')->inline(true)->radioList($bank)->label("MayBank") ?>
				<?= $form->field($model, 'Account_TopUpAmount') ?>
								
                <?= $form->field($upload, 'imageFile')->fileInput() ?>
                       <div class="form-group">
                    <?= Html::submitButton('Upload', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
   
			</div>					
