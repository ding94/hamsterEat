<?php
/* @var $this yii\web\View */
$this->title = "Restaurant Orders History";
use common\models\food\Food;
use common\models\Orderitemselection;
use common\models\food\Foodselection;
use common\models\food\Foodselectiontype;
use common\models\Orders;
use common\models\Orderitem;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
use common\models\MonthlyUnix;
use frontend\controllers\CartController;
?>

<style>
    #w0{
        display:inline-flex;
        width: 1140px;
        padding-left:35%;
        padding-top:3%;
        height:100px;
    }
    #monthlyunix-year{
        margin-left:15px;
        margin-top:20px;
    }
    .btn-primary{
        height:33px;
        margin-top:25px;
        margin-left:15px;
        width:80px;
    }
</style>

<div class = "container">
    <div class = "shijuin">
    <?php 
        $form = ActiveForm::begin(); 
        if ($mode == 1)
        {
            echo $form->field($selected, 'Month')->dropDownList($months, ['style'=>'width:120px', 'value'=>$currentmonth])->label('Filter by Month');
            echo $form->field($selected, 'Year')->dropDownList($year, ['style'=>'width:120px', 'value'=>$currentyear])->label('');
            echo Html::submitButton('Filter', ['class' => 'btn btn-primary', 'name' => 'filter-button']);
        }
        else
        {
            echo $form->field($selected, 'Month')->dropDownList($months, ['style'=>'width:120px', 'value'=>$selectedmonth])->label('Filter by Month');
            echo $form->field($selected, 'Year')->dropDownList($year, ['style'=>'width:120px', 'value'=>$selectedyear])->label('');
            echo Html::submitButton('Filter', ['class' => 'btn btn-primary', 'name' => 'filter-button']);
        }
	    activeForm::end(); ?>
    </div>
    <div>
        <?php echo "<h1> Earnings for ".$restaurantname."</h1>";
            echo "<br>";
            echo "<table class= table table-user-info style= 'border:1px solid black;'>";
                echo "<tr>";
                    echo "<th><center> Month </th>";
                    echo "<th><center> Amount (RM) </th>";
                echo "</tr>";

                if ($mode == 1)
                {
                    echo "<tr>";
                        echo "<td><center>".$currentmonth."</td>";
                        echo "<td><center>".CartController::actionRoundoff1decimal($totalearnings)."</td>";
                    echo "</tr>";
                }
                else
                {
                    echo "<tr>";
                        echo "<td><center>".$selectedmonth."</td>";
                        echo "<td><center>".CartController::actionRoundoff1decimal($totalearnings)."</td>";
                    echo "</tr>";
                }
            echo "</table>";
        ?>
    </div>
</div>