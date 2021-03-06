<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use frontend\assets\AddFoodAsset;
use wbraganca\dynamicform\DynamicFormWidget;

$this->title = current($type)->isNewRecord ? Yii::t('food','Add Food Selection') : Yii::t('food','Edit Food Selection');;
AddFoodAsset::register($this);
?>
<?php if ($status == 1):?>
<div class="container">
    <div class="checkout-progress-bar">
         <div class="circle done">
           <span class="label"><i class="fa fa-check"></i></span>
           <span class="title"><?= Yii::t('common','Food') ?></span>
        </div>
         <span class="bar done"></span>
        <div class="circle active">
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
        <?php if($status == 0):?>
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
        		<ul id="add-food-nav" class="nav nav-pills nav-stacked">
                      <li role="presentation"><?php echo Html::a("<i class='fa fa-chevron-left'></i>".Yii::t('common','Back'),['/Food/default/menu','rid' => $rid])?></li>
                    <?php $link = array_splice($link, 1);?>
                    <?php foreach($link as $url=>$urlname):?>
                        <li role="presentation" class=<?php echo $urlname=="Edit Food Selection" ? "active" :"" ?>>
                            <a class="btn-block" href=<?php echo $url?>><?php echo Yii::t('m-restaurant',$urlname)?></a>
                        </li>  
                    <?php endforeach ;?>
                </ul>
            </div>
    	</div>
        <?php endif;
            $number = $status == 0 ? 10 : 12;
        ?>
    	<div class="col-sm-<?php echo $number?> food-content">
    		<?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
    		<div class="form-group">
    			<?php DynamicFormWidget::begin([
                    'widgetContainer' => 'dynamicform_wrapper',
                    'widgetBody' => '.container-items',
                    'widgetItem' => '.house-item',
                    'limit' => 10,
                    'min' => 0,
                    'insertButton' => '.add-house',
                    'deleteButton' => '.remove-house',
                    'model' => current($type),
                    'formId' => 'dynamic-form',
                    'formFields' => [
                        'ID',
                        'Food_ID',
                        'TypeName',
                        'Min',
                        'Max',
                    ],
                ]); ?>
                <div class="food-table-outlet">
                	<table class="table table-bordered table-striped">
                		<thead>
	                        <tr>
	                             <th class="col-md-1 text-center ">
	                                <button type="button" class="add-house btn btn-success btn-xs"><span class="glyphicon glyphicon-plus"></span></button>
	                            </th>
	                            <th class="col-md-2"><?= Yii::t('food','Food Option') ?></th>
	                            <th class="col-md-8"><?= Yii::t('food','Selection') ?></th>
	                           
	                        </tr>
	                    </thead>
	                    <tbody class="container-items">
	                    <?php foreach ($type as $i => $data): ?>
	                    	<tr class="house-item" >
	                    		<td class="text-center vcenter" style="width: 90px; verti">
	                                <button type="button" class="remove-house btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus"></span></button>
	                            </td>
	                            <td class="vcenter">
	                            <?php
                                    // necessary for update action.
                                    if (! $data->isNewRecord) {
                                        echo Html::activeHiddenInput($data, "[{$i}]ID");
                                        echo Html::activeHiddenInput($typeName[$i], "[{$i}]id");
                                    }
                                   
                                    echo $form->field($typeName[$i], "[{$i}]translation")->label(Yii::t('food','Type'))->textInput(['maxlength' => true]);
                                   
                                    echo $form->field($data, "[{$i}]Min")->label(Yii::t('food','Minimum'))->textInput(['maxlength' => true]);
                                    echo $form->field($data, "[{$i}]Max")->label(Yii::t('food','Maximum'))->textInput(['maxlength' => true]); 
                                ?>
	                            </td>
	                            <td>      
                                <?= $this->render('selection', [ 'form' => $form,'i' => $i,'edit'=>1,'selection' => $selection[$i],'selectionName'=>$selectionName[$i]]) ?>
                            	</td>  
	                    	</tr>
	                    <?php endforeach; ?>
	                    </tbody>
                	</table>
                </div>
                <?php DynamicFormWidget::end(); ?>

                <?php echo Html::submitButton(Yii::t('common','Save'), ['class' => 'raised-btn main-btn', 'name' => 'insert-button']);
                    if($status == 1):
                        echo Html::a(Yii::t('common','Skip'),['/Food/image/create','id'=>$id,'rid'=>$rid], ['class' => 'raised-btn change-password-resize-btn', 'name' => 'insert-button']);
                       
                    endif;
                ?>
            </div>
    		<?php ActiveForm::end(); ?>
    	</div>
    </div>
</div>