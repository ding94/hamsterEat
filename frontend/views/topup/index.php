
<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use kartik\widgets\Select2;
use common\models\Bank;
use frontend\assets\TopupIndexAsset;
$this->title = Yii::t('common','Top up');	
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
                        'options' =>    [
                            'placeholder' => Yii::t('common','Go To ...'),
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
		        	<li role="presentation" ><?php echo Html::a(Yii::t('common','User Balance'),['/user/userbalance'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
		        	<li role="presentation" class="active"><a href="#" class="btn-block userprofile-edit-left-nav"><?= Yii::t('common','Top Up') ?></a></li>
		        	<li role="presentation"><?php echo Html::a(Yii::t('common','Withdraw'),['/withdraw/index'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
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
							<p><?= Yii::t('topup','Bank Account Number') ?> :</p>
							<p><?php echo $label['Bank_AccNo']?></p>
							<?php echo Html::a(Yii::t('topup','Go To').' '.$label['Bank_Name']." WebSite",$label['redirectUrl'] ,['target'=>'_blank'])?>		
						</div>

					<?php endforeach;?>		
				
				</div>

				<?= $form->field($model,'Account_ChosenBank')->dropDownList($banklist)->label(Yii::t('user','Chosen Bank')) ?>

			
				<?= $form->field($model, 'Account_TopUpAmount')->label(Yii::t('user','Total Top Up Amount').'(RM)') ?>
										
		        <?= $form->field($upload, 'imageFile')->fileInput()->label(Yii::t('common','Upload Image')) ?>
		        <div class="form-group">
		            <?= Html::submitButton(Yii::t('common','Submit'), ['class' => 'raised-btn main-btn']) ?>
		        </div>

		        <?php ActiveForm::end(); ?>
	  		</div>
        </div>
    </div>
</div>
</div>