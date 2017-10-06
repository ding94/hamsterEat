<?php
/* @var $this yii\web\View */
$this->title = "Invoice";
use common\models\food\Food;
use common\models\Orderitemselection;
use common\models\food\Foodselection;
use common\models\food\Foodselectiontype;
use common\models\Orders;
use common\models\Ordersstatuschange;
use common\models\User\Userdetails;
use yii\helpers\Html;

?>

<div class = "container">
    <div>
        <?php echo "<h1><center> Invoice </h1>";
        $did = Orders::find()->where('Delivery_ID = :did',[':did'=>$did])->one();
        $details = Userdetails:: find()->where('User_Username = :uname',[':uname'=>$did['User_Username']])->one();
        echo "<br>";
        echo "<br>";
        echo "<table class= table table-user-info style= width:100%;>";
            echo "<tr><td> SGShop Ecommerce Sdn Bhd </td></tr>";
            echo "<tr><td> 1123326T </td></tr>";
            echo "<tr><td> B-GF-05, Medini 6, Jalan Medini Sentral 5, Bandar Medini Iskandar Malaysia, 79250 Iskandar Puteri, Johor, Malaysia. </td></tr>";
        echo "</table>";
        echo "<br>";

        echo "<table class= table table-user-info style= width:100%;>";
            echo "<tr>";
                echo "<td><strong>Username: </strong>".$did['User_Username']."</td>";
                echo "<td><strong>Delivery ID: </strong>".$did['Delivery_ID']."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td><strong> Mobile No: </strong>".$details['User_ContactNo']."</td>";
                echo "<td><strong> Payment Mode: </strong>".$did['Orders_PaymentMethod']."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td><strong> Delivery Address: </strong>".$did['Orders_Location'].', '.$did['Orders_Area'].', '.$did['Orders_Postcode'].'.'."</td>";
                $timepay = Ordersstatuschange::find()->where('Delivery_ID = :did', [':did'=>$did['Delivery_ID']])->one();
                echo "<td><strong> Payment On: </strong>".date('d/m/Y H:i:s', $timepay['OChange_CompletedDateTime'])."</td>";
            echo "</tr>";
        echo "</table>";
        echo "<br>";
        echo "<br>";

        echo "<table class= table table-user-info style= width:100%;>";
            echo "<tr>";
            echo "<th><center>Order ID</th>";
                echo "<th><center>Food Name</th>";
                echo "<th><center>Unit Price (RM)</th>";
                echo "<th><center>Quantity</th>";
                echo "<th><center>Selections</th>";
                echo "<th><center>Selections Price (RM)</th>";
                echo "<th><center>LineTotal (RM)</th>";
                echo "<th colspan = 2><center>Remarks</th>";
            echo "</tr>";
        foreach ($orderitemdetails as $orderitemdetails) :
            $fooddetails = food::find()->where('Food_ID = :fid',[':fid'=>$orderitemdetails['Food_ID']])->one();

            echo "<tr>";
            ?>
            
            <?php
            echo "<td><center>".$orderitemdetails['Order_ID']."</td>";
            echo "<td><center>".$fooddetails['Name']."</td>";
            echo "<td align="."right>".$fooddetails['Price']."</td>";
            echo "<td><center>".$orderitemdetails['OrderItem_Quantity']."</td>";
            $selections = Orderitemselection::find()->where('Order_ID = :oid',[':oid'=>$orderitemdetails['Order_ID']])->all();
            echo "<td><center>";
            foreach ($selections as $selections) :
              $selectionname = Foodselection::find()->where('ID =:sid',[':sid'=>$selections['Selection_ID']])->one();
              $selectiontype = Foodselectiontype::find()->where('ID = :fid', [':fid'=>$selections['FoodType_ID']])->one();
              if (!is_null($selectionname))
              {
                echo $selectiontype['TypeName'].': &nbsp;'.$selectionname['Name'];
                echo "<br>";
              }

            endforeach;
            echo "</td>";
            echo "<td align="."right>".$orderitemdetails['OrderItem_SelectionTotal']."</td>";
            echo "<td align="."right>".$orderitemdetails['OrderItem_LineTotal']."</td>";
            echo "<td colspan = 2><center>".$orderitemdetails['OrderItem_Remark']."</td>";
            echo "</tr>";
          endforeach;
          
          //var_dump($did);exit;
          echo "<tr>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "<td><center><strong> Subtotal (RM): </strong></td>";
            echo "<td align="."right>".$did['Orders_Subtotal']."</td>";
          echo "</tr>";
          echo "<tr>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "<td><center><strong> Delivery Charge (RM): </strong></td>";
            echo "<td align="."right>".$did['Orders_DeliveryCharge']."</td>";
          echo "</tr>";
          echo "<tr>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "<td><center><strong> Discount (RM): </strong></td>";
            echo "<td align="."right>".$did['Orders_DiscountTotalAmount']."</td>";
          echo "</tr>";
          echo "<tr>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "<td><center><strong> Total (RM): </strong></td>";
            echo "<td align="."right><strong>".$did['Orders_TotalPrice']."</strong></td>";
          echo "</tr>";
          echo "</table>";


        ?>
    </div>
</div>