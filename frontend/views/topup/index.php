
<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use kartik\widgets\Select2;
use common\models\Bank;
use frontend\assets\TopupIndexAsset;
$this->title = "Top up";	
TopupIndexAsset::register($this);
?>

<div class="balance">
<div id="userprofile" class="row">
   <div class="userprofile-header">
        <div class="userprofile-header-title"><?php echo Html::encode($this->title)?></div>
    </div>
    <div class="userprofile-detail">
        <div class="col-md-2 ">
        	<div class="dropdown-url">
                <?php 
                    echo Select2::widget([
                        'name' => 'url-redirect',
                        'hideSearch' => true,
                        'data' => $link,
                        'options' => [
                            'placeholder' => 'Go To ...',
                            'multiple' => false,

                        ],
                        'pluginEvents' => [
                             "change" => 'function (e){
                                location.href =this.value;
                            }',
                        ]
                    ])
                ;?>
            </div>
           	<div class ="nav-url">
           		 <ul class="nav nav-pills nav-stacked">
		        	<li role="presentation" ><?php echo Html::a("User Balance",['/user/userbalance'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
		        	<li role="presentation" class="active"><a href="#" class="btn-block userprofile-edit-left-nav">Top Up</a></li>
		        	<li role="presentation"><?php echo Html::a("Withdraw",['/withdraw/index'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
	    		</ul>
           	</div>
        </div>
        <div class="col-md-10 toptup-right">
	        <div class="row">
	  		<?php $form = ActiveForm::begin(['id' => 'topup']); ?>
	  			<div class="row label-final-outlet">
	  				<?php foreach($bank as $label):?>
						<div class="col-sm-4 label-outlet">
							
							<?php echo Html::img('@web/imageLocation/bank/'.$label['Bank_PicPath'],['class' => 'label-img']); ?>
							<p>Bank Account Number :</p>
							<p><?php echo $label['Bank_AccNo']?></p>
							<?php echo Html::a("Go To ".$label['Bank_Name']." WebSite",$label['redirectUrl'] ,['target'=>'_blank'])?>		
						</div>

					<?php endforeach;?>		
				
				</div>

				<?= $form->field($model,'Account_ChosenBank')->dropDownList($banklist)?>

			
				<?= $form->field($model, 'Account_TopUpAmount') ?>
										
		        <?= $form->field($upload, 'imageFile')->fileInput() ?>
		        <div class="form-group">
		            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
		        </div>

		        <?php ActiveForm::end(); ?>
	  		</div>
        </div>
    </div>
</div>
</div>