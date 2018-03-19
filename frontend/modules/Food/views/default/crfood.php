<?php

use yii\helpers\Html;
use kartik\widgets\Select2;
use kartik\form\ActiveForm;
use frontend\assets\AddFoodAsset;

$this->title = $food->isNewRecord ? Yii::t('food','New Food Item') : Yii::t('food','Edit Food Item');
AddFoodAsset::register($this);
?>
<?php if($food->isNewRecord):?>
<div class="container">
  <div class="checkout-progress-bar">
    <div class="circle active">
      <span class="label"><i class="fa fa-cutlery"></i></span>
      <span class="title"><?= Yii::t('common','Food') ?></span>
    </div>
    <span class="bar"></span>
    <div class="circle deactive">
      <span class="label"><i class="fa fa-plus"></i></span>
      <span class="title"><?= Yii::t('common','Selection') ?></span>
    </div>
    <span class="bar"></span>
    <div class="circle deactive">
      <span class="label"><i class="fa fa-picture-o"></i></span>
      <span class="title"><?= Yii::t('common','Image') ?></span>
    </div>
  </div> 
</div>
<?php endif;?>
<div class="food-container container">
	<div class="food-header">
        <div class="food-header-title"><?= Html::encode($this->title) ?></div>
    </div>
    <div class="content">
      <?php if(!$food->isNewRecord):?>
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
      <?php endif;
        $number = $food->isNewRecord ? 12 : 10;
      ?>
       <div class="col-sm-<?php echo $number;?> food-content">
       		<?php $form = ActiveForm::begin(); 
       		 	echo $form->field($name, 'translation')->textInput()->label(Yii::t('common','Name'));
                
                echo $form->field($food, 'Nickname')->textInput()->label(Yii::t('food','Nickname'));
                if($status->isNewRecord):
                  echo $form->field($status,'default_limit')->dropDownList($array['status'],['value'=>30]);
                else:
                   echo $form->field($status,'default_limit')->dropDownList($array['status']);
                endif;
             
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
                
              echo $form->field($food, 'Description')->textInput()->label(Yii::t('common','Description'));
             
              echo Html::submitButton(Yii::t('common','Save'), ['class' => 'raised-btn main-btn pull-left', 'name' => 'insert-button']) ?>
                
             <?php ActiveForm::end();
              if($number == 12):
                  echo Html::a(Yii::t('common','Back'),['menu','rid'=>$food->Restaurant_ID], ['class' => 'raised-btn secondary-btn change-password-resize-btn', 'name' => 'insert-button']);
                       
                endif;  
            ?>
       </div>
    </div>
</div>