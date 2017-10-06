<?php
/* @var $this yii\web\View */
$this->title = "Delivery Man Orders";
use common\models\food\Food;
use common\models\Orderitemselection;
use common\models\food\Foodselection;
use common\models\food\Foodselectiontype;
use common\models\Orders;
use common\models\Orderitem;
use common\models\Restaurant;
use yii\helpers\Html;
$this->title = "Delivery Orders";
?>

<div class = "container">
    <div><?php
            foreach ($dman as $dman) :
                echo "<table class= table table-user-info style= width:80%;>";
                    echo "<tr>";
                        echo "<th><center> Delivery ID </th>";
                        echo "<th><center> Username </th>";
                        echo "<th><center> Date to be Received </th>";
                        echo "<th><center> Time to be Received </th>";
                        echo "<th><center> Current Status </th>";
                        echo "<th><center> Time Placed </th>";
                        echo "<th><center> Collect (RM) </th>";
                    echo "</tr>";
                    
                    $orderdetails = Orders::find()->where('Delivery_ID = :did', [':did'=>$dman['Delivery_ID']])->one();

                    echo "<tr>";
                        echo "<td><center>".$orderdetails['Delivery_ID']."</td>";
                        echo "<td><center>".$orderdetails['User_Username']."</td>";
                        echo "<td><center>".$orderdetails['Orders_Date']."</td>";
                        echo "<td><center>".$orderdetails['Orders_Time']."</td>";
                        echo "<td><center>".$orderdetails['Orders_Status']."</td>";
                        date_default_timezone_set("Asia/Kuala_Lumpur");
                        $timeplaced = date('d/m/Y H:i:s', $orderdetails['Orders_DateTimeMade']);
                        echo "<td><center> $timeplaced </td>";
                        echo "<td><center>".$orderdetails['Orders_TotalPrice']."</td>";

                    echo "</tr>";
                    echo "<tr>";
                        echo "<th><center> Order ID </th>";
                        echo "<th><center> Restaurant Name </th>";
                        echo "<th colspan = 2><center> Restaurant Address </th>";
                        echo "<th><center> Quantity </th>";
                        echo "<th><center> Current Status </th>";
                        echo "<th><center> Update Status </th>";
                    echo "</tr>";

                    $orderitemdetails = Orderitem::find()->where('Delivery_ID = :did', [':did'=>$orderdetails['Delivery_ID']])->orderBy(['Order_ID'=>SORT_ASC])->all();
                    
                    foreach ($orderitemdetails as $orderitemdetails) :
                        echo "<tr>";
                            echo "<td><center>".$orderitemdetails['Order_ID']."</td>";
                            $foodname = Food::find()->where('Food_ID = :fid', [':fid'=>$orderitemdetails['Food_ID']])->one();
                            $restname = Restaurant::find()->where('Restaurant_ID = :rid', [':rid'=>$foodname['Restaurant_ID']])->one();
                            echo "<td><center>".$restname['Restaurant_Name']."</td>";
                            echo "<td colspan = 2><center>".$restname['Restaurant_UnitNo'].', '.$restname['Restaurant_Street'].', '.$restname['Restaurant_Area'].', '.$restname['Restaurant_Postcode'].'.'."</td>";

                            echo "<td><center>".$orderitemdetails['OrderItem_Quantity']."</td>";
                            echo "<td><center>".$orderitemdetails['OrderItem_Status']."</td>";
                            if ($orderitemdetails['OrderItem_Status'] == 'Pending') :
                            {
                                echo "<td><center> Wait for Food to be Prepared </td>";
                            }
                            elseif ($orderitemdetails['OrderItem_Status'] == 'Preparing') :
                            {
                                echo "<td><center> Wait for Food to be Prepared </td>";
                            }
                            elseif ($orderitemdetails['OrderItem_Status'] == 'Ready For Pick Up') :
                            {
                                echo "<td><center>".Html::a('Picked Up', ['update-pickedup', 'oid'=>$orderitemdetails['Order_ID'], 'did'=>$dman['Delivery_ID']], ['class'=>'btn btn-primary'])."</td>";
                            }
                            endif;

                            if ($orderdetails['Orders_Status'] != 'On The Way') :
                            {
                                echo "</tr>";
                            }
                            else :
                            {
                                echo "<td><center>".Html::a('Completed', ['update-completed', 'oid'=>$orderitemdetails['Order_ID'], 'did'=>$dman['Delivery_ID']], ['class'=>'btn btn-primary'])."</td>";
                                echo "</tr>";
                            }
                            endif;
                    endforeach;
                echo "</table>";
                echo "<br>";
                echo "<br>";
            endforeach;
        ?>
    </div>
</div>