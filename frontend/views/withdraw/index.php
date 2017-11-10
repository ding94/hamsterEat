<?php
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use kartik\widgets\Select2;
use frontend\assets\TopupIndexAsset;
/* @var $this yii\web\View */
$this->title = "Withdraw money";
TopupIndexAsset::register($this);
?>

<div class="container">

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
           	<div class="nav-url">
           		<ul class="nav nav-pills nav-stacked">
		        	<li role="presentation" ><?php echo Html::a("User Balance",['/user/userbalance'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
		        	<li role="presentation"><?php echo Html::a("Top Up",['/topup/index'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
		        	<li role="presentation" class="active"><a href="#" class="btn-block userprofile-edit-left-nav">Withdraw</a></li>
	    		</ul>
           	</div>
        </div>
        <div class="col-md-8 withdraw-right">
	        <br><i><p>My Balance: <?php echo $balance['User_Balance']; ?></i></p>
			<?php $balance['User_Balance']-2;
				if ($balance['User_Balance'] <=0) { ?>
					<br><i><p>You cannot withdraw if your account doesn't have RM3 or higher.</i></p><br>
				<?php }else{ ?>

					<br><i><p>You can withdraw below RM<?php echo $balance['User_Balance']-2; ?>. Transfer fee RM2.</i></p><br>

				<?php } ?>
				<?php $form = ActiveForm::begin(); ?>

             
					<?= $form->field($model, 'withdraw_amount')->textInput() ?>
					<?= $form->field($model, 'bank_name')->dropDownList($bank)?>
				    <?= $form->field($model, 'to_bank')->textInput() ?>		
					<?= $form->field($model, 'acc_name')->textInput() ?>				   
					
                <div class="form-group">
                    <?= Html::submitButton('Withdraw', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
	
        </div>
    </div>
</div>
</div>
