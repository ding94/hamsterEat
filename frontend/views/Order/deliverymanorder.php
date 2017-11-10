<?php
/* @var $this yii\web\View */
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
<h1>Delivery Orders</h1>
    <div><?php
   
            foreach ($dman as $dman) :
                echo "<table class= table table-user-info style= 'border:1px solid black;'>";
                    echo "<tr>";
                        echo "<th ><center> Delivery ID </th>";
                       // echo "<th><center> Username </th>";
                       // echo "<th><center> Date to be Received </th>";
                        echo "<th><center> Time to be Received </th>";
                         echo "<th><center> Order address </th>";
                          echo "<th ><center> Order Postcode</th>";
                        //echo "<th><center> Current Status </th>";
                       // echo "<th><center> Time Placed </th>";
                        echo "<th ><center> Collect (RM) </th>";
                        echo "<th ><center> View Map </th>";
                    echo "</tr>";
                    
                    $orderdetails = Orders::find()->where('Delivery_ID = :did', [':did'=>$dman['Delivery_ID']])->one();
                    $location=$dman['Orders_Location'];
                    $postcode=$dman['Orders_Postcode'];
                    $district=$dman['Orders_Area'];
                    echo "<tr>";
                        echo "<td ><center>".$orderdetails['Delivery_ID']."</td>";
                        //echo "<td><center>".$orderdetails['User_Username']."</td>";
                      //  echo "<td><center>".$orderdetails['Orders_Date']."</td>";
                        echo "<td ><center>".$orderdetails['Orders_Time']."</td>";
                         echo "<td ><center>".$orderdetails['Orders_Location']."</td>";
                          echo "<td ><center>".$orderdetails['Orders_Postcode']."</td>";
                        //echo "<td><center>".$orderdetails['Orders_Status']."</td>";
                       // date_default_timezone_set("Asia/Kuala_Lumpur");
                       // $timeplaced = date('d/m/Y H:i:s', $orderdetails['Orders_DateTimeMade']);
                       // echo "<td><center> $timeplaced </td>";
                       if ($orderdetails['Orders_PaymentMethod'] != 'Cash on Delivery')
                       {
                            echo "<td ><center> 0.00 </td>";
                       }
                       else
                       {
                            echo "<td ><center>".$orderdetails['Orders_TotalPrice']."</td>";
                       }
                         echo "<td ><center><a class='btn btn-info' target='_blank' href='http://maps.google.com/maps?daddr=".$location.",+".$postcode.",+".$district.",+Malaysia&amp;ll='>Show Location</a></td>";

                    echo "</tr>";
                    echo "<tr>";
                       // echo "<th><center> Order ID </th>";
                        echo "<th><center> Restaurant Name </th>";
                        echo "<th colspan = 2><center> Area </th>";
                        echo "<th><center> Quantity </th>";
                        echo "<th><center> Current Status </th>";
                        echo "<th><center> Update Status </th>";
                    echo "</tr>";

                    $orderitemdetails = Orderitem::find()->where('Delivery_ID = :did', [':did'=>$orderdetails['Delivery_ID']])->orderBy(['Order_ID'=>SORT_ASC])->all();
                    
                    foreach ($orderitemdetails as $orderitemdetails) :
                        echo "<tr>";
                           // echo "<td><center>".$orderitemdetails['Order_ID']."</td>";
                            $foodname = Food::find()->where('Food_ID = :fid', [':fid'=>$orderitemdetails['Food_ID']])->one();
                            $restname = Restaurant::find()->where('Restaurant_ID = :rid', [':rid'=>$foodname['Restaurant_ID']])->one();
                            echo "<td><center>".$restname['Restaurant_Name']."</td>";
                            echo "<td colspan = 2><center>".$restname['Restaurant_Area']."</td>";

                            echo "<td><center>".$orderitemdetails['OrderItem_Quantity']."</td>";
                            if($orderitemdetails['OrderItem_Status']== 'Pending')
                            {
                                $label='<span class="label label-warning">'.$orderitemdetails['OrderItem_Status'].'</span>';
                            }
                            elseif($orderitemdetails['OrderItem_Status']== 'Preparing')
                            {
                                $label='<span class="label label-info">'.$orderitemdetails['OrderItem_Status'].'</span>';
                            }
                            elseif($orderitemdetails['OrderItem_Status']== 'Ready For Pick Up')
                            {
                                $label='<span class="label label-info">'.$orderitemdetails['OrderItem_Status'].'</span>';
                            }
                            elseif($orderitemdetails['OrderItem_Status']== 'Picked Up')
                            {
                                $label='<span class="label label-info">'.$orderitemdetails['OrderItem_Status'].'</span>';
                            }
                            echo "<td><center>".$label."</td>";
                            if ($orderitemdetails['OrderItem_Status'] == 'Pending') :
                            {
                                echo "<td><center><span class='label label-warning'> Wait for Food to be Prepared </span></td>";
                            }
                            elseif ($orderitemdetails['OrderItem_Status'] == 'Preparing') :
                            {
                                echo "<td><center><span class='label label-warning'> Wait for Food to be Prepared </span></td>";
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