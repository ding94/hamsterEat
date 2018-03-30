<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use frontend\assets\FoodOnOffAsset;

$this->title = Yii::t('food','Edit Food Name');
FoodOnOffAsset::register($this);
?>

<div class="container">
	<div class="food-service-header">
        <div class="food-service-title"><?= Html::encode($this->title) ?></div>
    </div>
    <div class="content">
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
              <ul id="food-menu-nav" class="nav nav-pills nav-stacked">
                   <li role="presentation"><?php echo Html::a("<i class='fa fa-chevron-left'></i>".Yii::t('common','Back'),['/Food/default/menu','rid' => $rid])?></li>
                <?php $link = array_splice($link, 1);?>
                <?php foreach($link as $url=>$urlname):?>
                    <li role="presentation" class=<?php echo $urlname=="Edit Name" ? "active" :"" ?>>
                        <a class="btn-block" href=<?php echo $url?>><?php echo Yii::t('m-restaurant',$urlname)?></a>
                    </li>   
                  <?php endforeach ;?>
              </ul>
            </div>
    	</div>
	    <div class="col-sm-10 food-onoff-content">
	    	<?php $form = ActiveForm::begin(); ?>
	    	<table class="table table-bordered">
	    		<thead>
					<tr><th colspan="3" class="center"><?= Html::encode($this->title) ?></th></tr>
				</thead>
				<tbody>
					<tr>
						<?php foreach($name as $i=>$single):
							$enable = $single->isNewRecord ? false : true;
						?>
						<td><?= $form->field($single,'['.$i.']translation',['enableClientValidation'=>$enable])->label(Yii::t('common',"Food")." ".Yii::t('food',$single->language)." ".Yii::t('common',"name"))?>
						<?= $form->field($single,'['.$i.']language')->hiddenInput()->label(false)?></td>
						<?php endforeach;?>
					</tr>
					<?php if(!empty($arrayData)):
					 		foreach($arrayData['type'] as $index=>$type):
					 		if(!empty($arrayData['selection'][$index])):?>
					<tr>
						<td rowspan=<?php echo count($arrayData['selection'][$index])+2?>>
						<?php 	foreach($type as $i=>$single):
								$enable = $single->isNewRecord ? false : true;
								$name  = $single['language'] == 'en' ? Yii::t('food','En Type Name') : Yii::t('food','Zh Type Name');
								echo $form->field($single,'['.$index.']['.$i.']translation',['enableClientValidation'=>$enable])->label($name);
							 	echo $form->field($single,'['.$index.']['.$i.']language')->hiddenInput()->label(false);
						 		endforeach;?>
						</td>
					</tr>
					<tr>
						<th><?= Yii::t('common','English')?></th>
						<th><?= Yii::t('common','Mandarin')?></th>
					</tr>
					<?php foreach($arrayData['selection'][$index] as $k => $selection):?>
					<tr>
						<?php 
							foreach($selection as $s=>$single):
							$enable = $single->isNewRecord ? false : true;
							
						?>
							<td>
								<?= $form->field($single,'['.$index.']['.$k.']['.$s.']translation',['enableClientValidation'=>$enable])->label(false)?>	
								<?= $form->field($single,'['.$index.']['.$k.']['.$s.']language')->hiddenInput()->label(false)?>	
							</td>
						<?php endforeach;?>
					</tr>
					<?php   
						endforeach;
					 	endif;
					 	endforeach;
					 	endif;?>
				</tbody>

	    	</table>
	    	<?= Html::submitButton(Yii::t('common','Submit'), ['class' => 'raised-btn main-btn submit-resize-btn']) ?>
	    	<?php ActiveForm::end(); ?>
	    </div>
</div>