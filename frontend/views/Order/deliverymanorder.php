<?php
/* @var $this yii\web\View */
$this->title = "Delivery Man Orders";
use common\models\Food;
use common\models\Orderitemselection;
use common\models\Foodselection;
use common\models\Foodtype;
use common\models\Orders;
use common\models\Orderitem;
use yii\helpers\Html;
use common\models\Restaurant;
?>

<div class = "container">
    <div><?php
            foreach ($dman as $dman) :
                echo "<table class= table table-user-info style= width:80%;>";
                    echo "<tr>";
                        echo "<th><center> Delivery ID </th>";
                        echo "<th><center> Username </th>";
                        echo "<th><center> Address </th>";
                        echo "<th><center> Date to be Received </th>";
                        echo "<th><center> Time to be Received </th>";
                        echo "<th><center> Current Status </th>";
                    echo "</tr>";

                    echo "<tr>";
                        echo "<td><center>".$dman['Delivery_ID']."</td>";
                        echo "<td><center>".$dman['User_Username']."</td>";
                        echo "<td><center>".$dman['Orders_Location'].', '.$dman['Orders_Area'].', '.$dman['Orders_Postcode'].'.'."</td>";
                        echo "<td><center>".$dman['Orders_Date']."</td>";
                        echo "<td><center>".$dman['Orders_Time']."</td>";
                        echo "<td><center>".$dman['Orders_Status']."</td>";
                        echo "<td><center> </td>";

                    echo "</tr>";
                    echo "<tr>";
                        echo "<th><center> Order ID </th>";
                        echo "<th><center> Restaurant Name </th>";
                        echo "<th><center> Restaurant Address </th>";
                        echo "<th><center> Quantity </th>";
                        echo "<th><center> Current Status </th>";
                        echo "<th><center> Update Status </th>";
                    echo "</tr>";

                    $orderitemdetails = Orderitem::find()->where('Delivery_ID = :did', [':did'=>$dman['Delivery_ID']])->orderBy(['Order_ID'=>SORT_ASC])->all();
                    
                    foreach ($orderitemdetails as $orderitemdetails) :
                        echo "<tr>";
                            echo "<td><center>".$orderitemdetails['Order_ID']."</td>";
                            $foodrestaurant = Food::find()->where('Food_ID = :fid', [':fid'=>$orderitemdetails['Food_ID']])->one();
                            $restname = Restaurant::find()->where('Restaurant_ID = :rid', [':rid'=>$foodrestaurant['Restaurant_ID']])->one();
                            echo "<td><center>".$restname['Restaurant_Name']."</td>";
                            echo "<td><center>".$restname['Restaurant_UnitNo'].', '.$restname['Restaurant_Street'].', '.$restname['Restaurant_Area'].', '.$restname['Restaurant_Postcode'].'.'."</td>";
                            echo "<td><center>".$orderitemdetails['OrderItem_Quantity']."</td>";
                            echo "<td><center>".$orderitemdetails['OrderItem_Status']."</td>";
                            if ($orderitemdetails['OrderItem_Status'] == 'Pending')
                            {
                                echo "<td><center> Pending </td>";
                            }
                            elseif ($orderitemdetails['OrderItem_Status'] == 'Preparing')
                            {
                                echo "<td><center> Preparing </td>";
                            }
                            elseif ($orderitemdetails['OrderItem_Status'] == 'Ready For Pick Up')
                            {
                                echo "<td><center>".Html::a('Pick Up', ['update-pickedup', 'oid'=>$orderitemdetails['Order_ID'], 'did'=>$dman['Delivery_ID']], ['class'=>'btn btn-primary'])."</td>";
                            }
                        echo "</tr>";
                    endforeach;
                echo "</table>";
                echo "<br>";
                echo "<br>";
            endforeach;
        ?>
    </div>
</div>