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
?>

<div class = "container">
    <?php $form = ActiveForm::begin(); ?>

	<?php ActiveForm::end(); ?>
    <div>
        <?php echo "<h1> Earnings for ".$restaurantname."</h1>";

            echo "<table class= table table-user-info style= 'border:1px solid black;'>";
                echo "<tr>";
                    echo "<th><center> Month </th>";
                    echo "<th><center> Amount (RM) </th>";
                echo "</tr>";
            echo "</table>";
        ?>
    </div>
</div>