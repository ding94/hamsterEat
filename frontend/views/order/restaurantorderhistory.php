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
        <?php echo "<h1> Orders History for ".$restaurantname['Restaurant_Name']."</h1>";
        echo "<br>";
        echo "<br>";

            foreach ($result as $result) :
              
                echo "<table class= table table-user-info style= width:80%;>";
                    echo "<tr>";
                        echo "<th><center> Delivery ID </th>";
                        echo "<th><center> Username </th>";
                        echo "<th><center> Date to be Received </th>";
                        echo "<th><center> Time to be Received </th>";
                        echo "<th><center> Current Status </th>";
                        echo "<th colspan = 2><center> Time Placed </th>";
                        echo "<th><center> </th>";
                    echo "</tr>";
                    
                    $orderdetails = Orders::find()->where('Delivery_ID = :did', [':did'=>$result['Delivery_ID']])->one();
                     if($orderdetails['Orders_Status']== 'Rating Done')
                {
                    $label='<span class="label label-success">'.$orderdetails['Orders_Status'].'</span>';
                }
                    echo "<tr>";
                        echo "<td><center>".$orderdetails['Delivery_ID']."</td>";
                        echo "<td><center>".$orderdetails['User_Username']."</td>";
                        echo "<td><center>".$orderdetails['Orders_Date']."</td>";
                        echo "<td><center>".$orderdetails['Orders_Time']."</td>";
                        echo "<td><center>".$label."</td>";
                        date_default_timezone_set("Asia/Kuala_Lumpur");
                        $timeplaced = date('d/m/Y H:i:s', $orderdetails['Orders_DateTimeMade']);
                        echo "<td colspan = 2><center> $timeplaced </td>";
                        echo "<td><center> </td>";

                    echo "</tr>";
                    echo "<tr>";
                        echo "<th><center> Order ID </th>";
                        echo "<th><center> Food Name </th>";
                        echo "<th><center> Selections </th>";
                        echo "<th><center> Quantity </th>";
                        echo "<th><center> Remarks </th>";
                        echo "<th><center> Current Status </th>";
                    echo "</tr>";

                    $orderitemdetails = "SELECT * from orderitem INNER JOIN food ON orderitem.Food_ID = food.Food_ID INNER JOIN restaurant on restaurant.Restaurant_ID = food.Restaurant_ID WHERE food.Restaurant_ID = ".$restaurantname['Restaurant_ID']." AND orderitem.Delivery_ID = ".$orderdetails['Delivery_ID']."";
                    $resultz = Yii::$app->db->createCommand($orderitemdetails)->queryAll();
                    //var_dump($resultz);exit;
                    foreach ($resultz as $orderitemdetails) :
                        echo "<tr>";
                            echo "<td><center>".$orderitemdetails['Order_ID']."</td>";
                            $foodname = Food::find()->where('Food_ID = :fid', [':fid'=>$orderitemdetails['Food_ID']])->one();
                            echo "<td><center>".$foodname['Name']."</td>";
                            $selections = Orderitemselection::find()->where('Order_ID = :oid',[':oid'=>$orderitemdetails['Order_ID']])->all();
                            echo "<td><center>";
                            foreach ($selections as $selections) :
                                $selectionname = Foodselection::find()->where('ID =:sid',[':sid'=>$selections['Selection_ID']])->one();
                                $selectiontype = Foodselectiontype::find()->where('ID = :fid', [':fid'=>$selections['FoodType_ID']])->one();
                                if (!is_null($selectionname['ID']))
                                {
                                    echo $selectiontype['TypeName'].': &nbsp;'.$selectionname['Name'];
                                    echo "<br>";
                                }
                            endforeach;
                            echo "</td>";
                            echo "<td><center>".$orderitemdetails['OrderItem_Quantity']."</td>";
                            echo "<td><center>".$orderitemdetails['OrderItem_Remark']."</td>";
                            echo "<td><center><span class='label label-info'>".$orderitemdetails['OrderItem_Status']."</span></td>";
                            if ($orderitemdetails['OrderItem_Status'] == 'Pending')
                            {
                                echo "<td><center>".Html::a('Preparing', ['update-preparing', 'oid'=>$orderitemdetails['Order_ID'], 'rid'=>$rid], ['class'=>'btn btn-primary'])."</td>";
                            }
                            elseif ($orderitemdetails['OrderItem_Status'] == 'Preparing')
                            {
                                echo "<td><center>".Html::a('Ready for Pick Up', ['update-readyforpickup', 'oid'=>$orderitemdetails['Order_ID'], 'rid'=>$rid], ['class'=>'btn btn-primary'])."</td>";
                            }
                            elseif ($orderitemdetails['OrderItem_Status'] == 'Ready For Pick Up')
                            {
                                echo "<td> Waiting for Pick Up </td>";
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