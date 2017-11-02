
<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use common\models\Bank;
use frontend\assets\TopupIndexAsset;
$this->title = "Top up";	
TopupIndexAsset::register($this);
?>
<div class="container">
	<div class="col-md-6 col-md-offset-3" id="topup">
		<h1>Offline Topup</h1>
		<?php $form = ActiveForm::begin(); ?>
		<div class="outer-container1">
			<div class="menu-container1">
				<?php foreach($bank as $k => $value):  ?>
					<div class="item1">
					<div class="img"><input type="radio" name="Bank_ID" value=<?php echo $value['Bank_ID']; ?> ><?php echo Html::img('@web/imageLocation/bank/'.$value['Bank_PicPath']) ?></div>
						<div class="inner-item1">
							<br><p><?php echo $value['Bank_AccNo']; ?></p>
							<a href=" <?php echo yii\helpers\Url::to($value ['redirectUrl']); ?> ">Go to <?php echo $value['Bank_Name']; ?> website</a>
						</div>
					</div>
		   
			<?php endforeach; ?>
			 </div>
		</div>

		   
			
				<?= $form->field($model, 'Account_TopUpAmount') ?>
								
                <?= $form->field($upload, 'imageFile')->fileInput() ?>
                       <div class="form-group">
                    <?= Html::submitButton('Upload', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
    				

	</div>
</div>