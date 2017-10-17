
<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use common\models\Bank;
$this->title = "Top up";	
?>
<style>
.ocontainer{
	margin-left:10%;
}
.outer-container{
  display:flex;
  align-items: center;
  justify-content:center;
}

.menu-container{
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  grid-column-gap: 0px;
  grid-row-gap: 15px;
  margin-bottom: 50px;
  align-items: center;
  justify-content:center;
}

.item{
  font-size: 12px;
  color: black;
  background-color: white;
  min-width: 280px;
  min-height: 150px;
  border: 1px solid #fff;
}

.item p{
  font-size:12px;
  color:black;
}
.item .inner-item{
  margin:10px 25px 10px 30px;

}

.item img{
    width:200px;
  height:80px;
}

</style>

<div class="container">
 
        
		<div class="col-lg-6 col-lg-offset-1" style="text-align:center" id="topup">
     
		<h1>Offline Topup</h1>
		<div class="ocontainer">
	<div class="outer-container">
    <div class="menu-container">
	<?php foreach($bank as $bank):  ?>
	<div class="item">

	<div class="img"><?php echo Html::img('@web/imageLocation/bank/'.$bank['Bank_PicPath']) ?></div>
 <div class="inner-item">
	<p><?php echo $bank['Bank_AccNo']; ?></p>

		<a href=" <?php echo yii\helpers\Url::to($bank ['redirectUrl']); ?> " style="display:block" >Go to <?php echo $bank['Bank_Name']; ?> website</a>
           </div>
</div>   <?php endforeach; ?>
          </div>
		</div>    
</div> 
		   <?php $form = ActiveForm::begin(); ?>
			
				<?= $form->field($model, 'Account_TopUpAmount') ?>
								
                <?= $form->field($upload, 'imageFile')->fileInput() ?>
                       <div class="form-group">
                    <?= Html::submitButton('Upload', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
    				

</div>
</div>