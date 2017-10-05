<?php
/* @var $this yii\web\View */
$this->title = "Invoice";
use common\models\food\Food;
use common\models\Orderitemselection;
use common\models\food\Foodselection;
use common\models\food\Foodselectiontype;
use common\models\Orders;
use yii\helpers\Html;
?>

<div class = "container">
    <div>
        <?php echo "<h1><center> Invoice </h1>";
        echo "<br>";
        echo "<br>";
        echo "<table class= table table-user-info style= width:80%;>";
            echo "<tr>";
                echo "<th><center> Address </th>";
                echo "<td><center> $address </td>";
                echo "<th><center> Status </th>";
                echo "<td><center> $status </td>";
            echo "</tr>";
            echo "<tr>";
                echo "<th><center> Receiving Date </th>";
                echo "<td><center> $date </td>";
                echo "<th><center> Receiving Time </th>";
                echo "<td><center> $time </td>";
            echo "</tr>";
            echo "<tr>";
                echo "<th><center> Payment Method </th>";
                echo "<td><center> $paymethod </td>";
                echo "<th><center> Time Placed </th>";
                echo "<td><center> $timeplaced </td>";
            echo "</tr>";
        echo "</table>";
        echo "<br>";
        echo "<br>";

        echo "<table class= table table-user-info style= width:80%;>";
            echo "<tr>";
            echo "<th><center>Order ID</th>";
                echo "<th colspan = 2><center>Food Name</th>";
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
            echo "<td><center>".$orderitemdetails['Order_ID']."</td>"; ?>
            <td><center><?php echo Html::img('@web/imageLocation/'.$fooddetails['PicPath'], ['class' => 'img-responsive','style'=>'height:60px; width:90px; margin:auto;']); ?></td><?php
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
          $did = Orders::find()->where('Delivery_ID = :did',[':did'=>$did])->one();
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
            echo "<td><center><strong> Total (RM): </strong></td>";
            echo "<td align="."right><strong>".$did['Orders_TotalPrice']."</strong></td>";
          echo "</tr>";
          echo "</table>";
        ?>
    </div>
</div>