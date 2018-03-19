
<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
//use yii\helpers\Url;
use yii\grid\GridView;
//use yii\grid\ActionColumn;
//use yii\db\ActiveRecord;
//use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;

    $this->title = 'Give voucher to '.$name;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Voucher'), 'url' => ['/user/uservoucherlist']];
    $this->params['breadcrumbs'][] = $this->title;
    
?>
	<?php $form = ActiveForm::begin();?>
	    <?= $form->field($model, 'code')->textInput() ?>
        <?= $form->field($voucher, 'discount')->textInput()->input('',['placeholder' => 'Not required when existing code used']) ?>
        <?= $form->field($voucher ,'discount_type')->dropDownList($type)?>
        <?= $form->field($voucher ,'discount_item')->dropDownList($item)?>
        <?= $form->field($model, 'endDate')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => 'Date voucher deactived'],
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true,
                'startDate' => date('Y-m-d h:i:s'), 
                'todayBtn' => true,
            ]]) 
        ?>

    <div class="form-group">
        <?= Html::submitButton('Add', [
            'class' => 'btn btn-success', 
            'data' => [
                'confirm' => 'If code alr exist, it will replace by its own discounts, continue?',
                'method' => 'post',
        ]]);?>
        <?= Html::a('Back', ['/uservoucher/index'], ['class'=>'btn btn-primary']) ?>
    </div>

   <?php ActiveForm::end();?> 

   </br>

    <H3>Usable Vouchers </H3>

     <?= GridView::widget([

        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'code',
            'discount',
            'discount_type.description',
            'discount_items.description',
            
        ],
    ]); ?>