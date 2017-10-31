<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\Session;
use frontend\controllers\CartController;
$this->title = "Order Placed";
?>
<body  >
    <div class="tab-content col-md-12" id="aftercheckout" style="margin-left:20%;">
        <table class="table table-user-info" style="width:60%;">
            <tr>
                <th><center><h2>Thanks for placing your order with hamsterEat<br>Your Order has Been Made</h2></th>
            </tr>
            <tr>
                <td><center><strong>Your Delivery ID is : <?php echo $did ?></strong></td>
            </tr>
            <?php if ($timedate['Orders_PaymentMethod'] == "Cash on Delivery")
            {
                echo "<tr>";
                    echo "<th><center> You have chosen Cash on Delivery payment method</th>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td><center> Please prepare a total of RM ".CartController::actionRoundoff1decimal($timedate['Orders_TotalPrice'])." for our rider.</td>";
                echo "</tr>";
            }
            elseif ($timedate['Orders_PaymentMethod'] == "Account Balance")
            {
                echo "<tr>";
                echo "<th><center> You have paid with your Account Balance</th>";
            echo "</tr>";
            echo "<tr>";
                echo "<td><center> You have a total of RM xx.xx left in your account.</td>";
            echo "</tr>";
            }
            ?>
            <tr>
                <td><center><?php echo Html::a('More Detail', ['/order/my-orders'], ['class'=>'btn btn-primary'])?></center></td>
            </tr>
        </table>

    </div>
</body>