<?php

/* @var $this yii\web\View */
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\Select2;

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
        echo "<tr>";
        if ($area['Area_Group'] == $area['Area_Group'])
        {
            echo "<td><center>".$area['Area_Group']."</td>";
        }
        echo "<td><center>".$area['Area_Area']."</td>";
        echo "<td><center>".$area['Area_Postcode']."</td>";
        echo "<td><center>".$area['Area_State']."</td>";
        echo "</tr>";

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
