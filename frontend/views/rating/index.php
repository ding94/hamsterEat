<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

	$this->title = "Rating";
?>
<div class="row">
	<?php $form = ActiveForm::begin(['action' => ['rating/rating-data','id'=>$id],'method' => 'post']);?>
	<div class="col-md-6">
		<div class="panel panel-primary">
			<div class="panel-heading">Service Rating</div>
			<div class="panel-body">
				<?= $form->field($servicerating, 'ServiceRating_DeliverySpeed')->inline()->radioList($ratingLevel,[]) ?>
				<?= $form->field($servicerating, 'ServiceRating_UserExperience')->inline()->radioList($ratingLevel)?>
				<?= $form->field($servicerating, 'ServiceRating_Service')->inline()->radioList($ratingLevel) ?>
				<?= $form->field($servicerating,'ServiceRating_Comment')->textInput() ?>
			</div>
		</div>
		<div class="panel panel-success">
			<div class="panel-heading">Food Rating</div>
			<div class="panel-body">
				<?php foreach($orderitem as $k => $data):?>
					<?= $form->field($foodrating , '['.$k.']FoodRating_Rating')->inline()->radioList($ratingLevel)->label($data['food']['Food_Name']) ?>
					<?= $form->field($foodrating,'['.$k.']Food_ID')->hiddenInput(['value' => $data['Food_ID']])->label(false) ?>
				<?php endforeach ;?>
			</div>
		</div>
		<button class="btn btn-primary btn-lg btn-block">Submit</button>
	</div>
	<?php ActiveForm::end();?>
</div>

	

	