
<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use common\models\Bank;
$this->title = "Top up";	
?>
<style>
.outer-container1{
  display:flex;
  align-items: center;
  justify-content:center;
}

.menu-container1{
  display: grid;
  width:1200px;
  grid-template-columns: 1fr 1fr 1fr;
  grid-column-gap: 15px;
  grid-row-gap: 15px;
  margin-bottom: 50px;
  align-items: center;
  justify-content:center;
}
.item1{
  font-size: 15px;
  color: black;
  min-width: 200px;
  min-height: 150px;
  border: 1px solid #fff;

}
.item1 img{
    width:200px;
  height:80px;
}

a:hover {
    color: #ffda00;
}


</style>

<div class="container">
	<div class="col-lg-6 col-lg-offset-1" style="text-align:center" id="topup">
		<h1>Offline Topup</h1>
		<?php $form = ActiveForm::begin(); ?>
		<div class="outer-container1">
			<div class="menu-container1">
				<?php foreach($bank as $k => $value):  ?>
					<div class="item1">
					<div class="img"><input type="radio" name="Bank_ID" value=<?php echo $value['Bank_ID']; ?> style="margin-top:15px;"><?php echo Html::img('@web/imageLocation/bank/'.$value['Bank_PicPath']) ?></div>
						<div class="inner-item1">
							<br><p><?php echo $value['Bank_AccNo']; ?></p>
							<a href=" <?php echo yii\helpers\Url::to($value ['redirectUrl']); ?> " style="display:block" >Go to <?php echo $value['Bank_Name']; ?> website</a>
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