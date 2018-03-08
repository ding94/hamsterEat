<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;

    $this->title = 'Average Food Prepare Time';
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Restaurants'), 'url' => ['show-restaurants']];
    $this->params['breadcrumbs'][] = $this->title;
?>

<head>
	<h3>Restaurant: <strong><?= $restaurant['restaurantEnName']['translation']; ?></strong></h3>
</head>

<body>
	<?php $form = ActiveForm::begin(['action' =>['/restaurant/restaurant/speedrating', 'rid' => $restaurant['Restaurant_ID']], 'method' => 'post',]); 

	if(!empty($post)):?>
		<h4>Date: <?= $post['Restaurant']['timestart']; ?> ~ <?= $post['Restaurant']['timeend']; ?> </h4>
	<?php endif;?>

<div class='container'>
	<div class="row">
		<div class="col-md-3">
			<?= $form->field($restaurant, 'timestart')->widget(DatePicker::classname(), [
				'options' => ['placeholder' => 'Enter start date...',],
				'pluginOptions' => [
				    'format' => 'yyyy-mm-dd',
				    'autoclose'=>true,
				],
			]) ?>
		</div>
		<div class="col-md-3">
			<?= $form->field($restaurant, 'timeend')->widget(DatePicker::classname(), [
				'options' => ['placeholder' => 'Enter end date...'],
				'pluginOptions' => [
					'format' => 'yyyy-mm-dd',
					'autoclose'=>true,
				]
			]) ?>
		</div>
		<div class="col-md-4" style="margin-top: 25px;">
			<?= Html::submitButton('Filter', ['class' => 'btn btn-primary']) ?>
        	<?= Html::a('Back',Url::to(['/restaurant/restaurant/show-restaurants']) , ['class' => 'btn btn-primary']) ?>
		</div>
	</div>
</div>	

<?php ActiveForm::end(); ?>
	<div class="container" style="background-color: white;">
		<table class="table table-hover" style="background-color:#80bfff;margin-top: 10px;">
			<thead>
		      	<tr>
		      		<th>Food Name</th>
		      		<th>Orders</th>
		        	<th>Pending</th>
		        	<th>Preparing</th>
		        	<th>Ready For Pickup</th>
		        	<th>Pickuped up</th>
		      	</tr>
			</thead>

			<?php foreach ($data as $k => $value) : ?>
			    <tbody>
		    		<tr>
		    			<td><?= $foodname[$k]; ?></td>
		    			<td><?= $value['divider']; ?></td>
				    	<?php if ($value['divider'] <= 0) {$value['divider'] =1;}?>
		        		<td><?= gmdate("H:i:s",$value['pending']/$value['divider']); ?></td>
		        		<td><?= gmdate("H:i:s",$value['preparing']/$value['divider']); ?></td>
		        		<td><?= gmdate("H:i:s",$value['ready']/$value['divider']); ?></td>
		        		<td><?= gmdate("H:i:s",$value['pickedup']/$value['divider']); ?></td>
		      		</tr>
		    	</tbody>

		    <?php endforeach;?>
		</table>
	</div>
</body>