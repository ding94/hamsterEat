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
?>

<div class = "container">
    <div>
        <?php echo "<h1> Earnings for ".$restaurantname."</h1>";
        echo "<br>";
        echo "<br>";
        
            foreach ($result as $result) :
              
                echo "<table class= table table-user-info style= 'border:1px solid black;'>";
                    echo "<tr>";
                        echo "<th><center> Month </th>";
                        echo "<th><center> Amount (RM) </th>";
                    echo "</tr>";
                    
                echo "</table>";
                echo "<br>";
                echo "<br>";
            endforeach;
        ?>
    </div>
</div>