<?php

use yii\helpers\Html;
use kartik\widgets\Select2;
use kartik\form\ActiveForm;
use frontend\assets\AddFoodAsset;

$this->title = $food->isNewRecord ? Yii::t('food','New Food Item') : Yii::t('food','Edit Food Item');
AddFoodAsset::register($this);
?>
<div class="food-container container">
	<div class="food-header">
        <div class="food-header-title"><?= Html::encode($this->title) ?></div>
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
                <li role="presentation"><?php echo Html::a("<i class='fa fa-chevron-left'></i>".Yii::t('common','Back'),['/Food/default/menu','rid' => $food->Restaurant_ID])?></li>
                <?php $link = array_splice($link, 1);?>
                <?php foreach($link as $url=>$urlname):?>
                    <li role="presentation" class=<?php echo $urlname=="Edit Food" ? "active" :"" ?>>
                        <a class="btn-block" href=<?php echo $url?>><?php echo Yii::t('m-restaurant',$urlname)?></a>
                    </li>   
                  <?php endforeach ;?>
              </ul>
            </div>
       </div>
       <div class="col-sm-10 food-content">
       		<?php $form = ActiveForm::begin(); 
       		 	echo $form->field($name, 'translation')->textInput()->label(Yii::t('common','Name'));
                
                echo $form->field($food, 'Nickname')->textInput()->label(Yii::t('food','Nickname'));
                echo $form->field($status,'default_limit')->dropDownList($array['status']);
                echo $form->field($food, 'roundprice', [
                    'addon' => [
                    'append' => [
                        'content' => '<i class="fa fa-times"></i> 1.3 <i>=</i>',
                    ],
                    //'groupOptions' => ['class'=>'input-group-lg'],
                         'contentAfter' => '<input id="afterprice" class="form-control" name="Food[Price]" value = "'.$food->Price.'"type="text">'
                    ]
                ])->textInput(['id'=>'price'])->label(Yii::t('food','Money Received')); 

                 echo $form->field($junction, 'Type_ID')->widget(Select2::classname(), [
				    'data' => $array['type'],
				    
				    'options' => ['placeholder' => 'Select a type ...'],
				    'pluginOptions' => [
				        'tags' => true,
                        'maximumInputLength' => 20,
				    ],
				]);
                
                echo $form->field($food, 'Description')->textInput()->label(Yii::t('common','Description')) ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('common','Save'), ['class' => 'raised-btn main-btn', 'name' => 'insert-button']) ?>
                </div>
             <?php ActiveForm::end(); ?> 
       </div>
    </div>
</div>