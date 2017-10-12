
<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

    $this->title = 'User List';
    $this->params['breadcrumbs'][] = $this->title;
    
?>
	<?php $form = ActiveForm::begin();?>
	<?= Html::submitButton('Apply Creation Coupon to Users',  [
        'class' => 'btn btn-success', 
        'data' => [
                'method' => 'post',
            ]]);?>

	<?= GridView::widget([
        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'columns' => [

            // 'class' => 'yii\grid\SerialColumn',],
            ['class' => 'yii\grid\CheckboxColumn',],
            'username',
            'email',
            
            ['class' => 'yii\grid\ActionColumn' ,
             'template'=>'{addvoucher}',
             'buttons' => [
                'addvoucher' => function($url , $model)
                {
                    return  Html::a(FA::icon('gift 2x') , $url , ['title' => 'Give Voucher']);
                },
              ]
            ],

            ['class' => 'yii\grid\ActionColumn' ,
             'template'=>'{showuser}',
             'buttons' => [
                'showuser' => function($url , $model)
                {
                    return  Html::a(FA::icon('user 2x') , $url , ['title' => "Show User's Voucher"]);
                },
              ]
            ],
        ],
    ])?>

   <?php ActiveForm::end();?> 