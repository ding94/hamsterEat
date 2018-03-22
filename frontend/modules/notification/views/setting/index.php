<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use frontend\assets\NotificationAsset;
use kartik\widgets\Select2;
use yii\bootstrap\ActiveForm;

$this->title = "Notification Setting";

NotificationAsset::register($this);
?>

<div id="userprofile" class="container">
    <div class="userprofile-header">
       <div class="userprofile-header-title"><?php echo Html::encode($this->title)?></div>
    </div>
    <div class="userprofile-detail">
    	<div class="col-sm-2">
            <div class="dropdown-url">
                 <?php 
                    echo Select2::widget([
                        'name' => 'url-redirect',
                        'hideSearch' => true,
                        'data' => $link,
                        'options' => [
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
            <div class="nav-url">
                <ul id="deliveryman-orders-nav" class="nav nav-pills nav-stacked">
                    <?php foreach($link as $url=>$value):?>
                        <li role="presentation" class=<?php echo $value== "Setting" ? "active" :"" ?>>
                            <a class="btn-block" href=<?php echo $url?>><?php echo Yii::t('notification',$value) ?></a>
                        </li>
                    <?php endforeach ;?>
                   
                </ul>
            </div>
        </div>
        <div class="col-sm-10 notification-right">
        	<?php $form = ActiveForm::begin();?>
        	<?php foreach($data as $tid => $status):?>
        	<table class="table">
        		<caption><?= $name[$tid]['name'];?></caption>
        		<thead>
        			<tr>
        				<th></th>
        				<th width="15%">Notification</th>
        				<th width="15%">Email</th>
        				<th width="15%">Sms</th>
        			</tr>
        		</thead>
        		<tbody>
        	
        			<?php foreach($status as $sid=> $setting):?>
        			<tr>
        				<td><?= $name[$tid][$sid];?></td>
        				<?php foreach($setting as $value):?>
        				<td>
        					<?php if($value->enable == -1 || $value->enable ==2):
									$num = $value->enable == -1 ? 0 : 1;?>
        					<div class="form-group">
        						<div class="checkbox">
        							<label></label>
        							<?= Html::checkBox('force',$num,['disabled'=>true]);?>
        						</div>
        					</div>	
        					<?php else:
        						$id = $value->tableSchema->name == 'notification_setting' ? $value->id : $value->setting_id;
		        				echo $form->field($value,'['.$id.']enable')->checkBox()->label("");
		        			endif;?>
        				</td>
        					
        				<?php endforeach;?>
        			</tr>
        			<?php endforeach;?>
        			
        		</tbody>
        	</table>
        	<?php endforeach;?>
        	<div class="form-group">
        		   <?php echo Html::submitButton(Yii::t('common','Update'), ['class' => 'raised-btn main-btn', 'name' => 'insert-button']);?>
        	</div>
        	<?php ActiveForm::end();?>	
        </div>
    </div>
</div>