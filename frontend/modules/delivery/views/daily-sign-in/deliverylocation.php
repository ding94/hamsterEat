<?php

/* @var $this yii\web\View */
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\Select2;
use common\models\Area;

$this->title = 'Delivery Location';
?>
<div class="site-index">

    <div class="container">
		<div class="tab-content col-md-6 col-md-offset-3" >
		<table class="table table-user-information"> <h1>Set Up Your Delivery Location</h1>
      <?php
        echo "<tr>";
        echo "<th ><center> Area Group </th>";
        echo "<th><center> Area </th>";
        echo "<th><center> Postcode </th>";
        echo "<th ><center> State</th>";
        echo "</tr>";
      ?>
      <?php
      foreach ($area as $area) :
        $amount = Area::find()->where('Area_Group = :ag', [':ag'=>$area['Area_Group']])->all();
        $count = count($amount);
          foreach ($amount as $amount) :
            if ($count > 0)
            {
              echo "<tr>";
                echo "<td rowspan = $count>".$amount['Area_Group']."</td>";
                echo "<td>".$amount['Area_Area']."</td>";
                echo "<td>".$amount['Area_Postcode']."</td>";
                echo "<td>".$amount['Area_State']."</td>";
              echo "</tr>";
            }
            else
            {
              echo "<tr>";
                echo "<td>".$amount['Area_Area']."</td>";
                echo "<td>".$amount['Area_Postcode']."</td>";
                echo "<td>".$amount['Area_State']."</td>";
              echo "</tr>";
            }
            $count = 0;
          endforeach;
      endforeach;
      ?>
 </table>
   <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
 <?= $form->field($find, 'DeliveryMan_AreaGroup')->widget(Select2::classname(), [
	    'data' => $postcodeArray,
	    'options' => ['placeholder' => 'Select area group ...','id'=>'postcode-select']])->label('Select Your Delivery Location'); 
	    ?>
          <?= Html::submitButton('Process', ['class' => 'btn btn-primary', 'name' => 'insert-button']) ?>
           <?php ActiveForm::end(); ?>
            </div>
            </div>
    </div>
</div>
