<?php
/* @var $this yii\web\View */
$this->title = "Restaurant Orders";
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
        <?php echo "<h1> Orders for ".$restaurantname['Restaurant_Name']."</h1>";
        echo "<br>";
        echo "<br>";

            foreach ($result as $result) :
                echo "<table class= table table-hover style= 'border:1px solid black;'>";
                   // echo "<tr>";
                       // echo "<th><center> Delivery ID </th>";
        
                        
                    //echo "</tr>";
                    
                    $orderdetails = Orders::find()->where('Delivery_ID = :did', [':did'=>$result['Delivery_ID']])->one();

                    echo "<tr>";
                        echo "<th colspan = '6' ><center>Delivery ID: ".$orderdetails['Delivery_ID']."</th>";
                       
                         echo "</tr>";
                       
                        
                       
                       // echo "<td><center> </td>";

                  
                    echo "<tr>";
                        echo "<th><center> Order ID </th>";
                        echo "<th><center> Food Name </th>";
                        echo "<th><center> Selections </th>";
                        echo "<th><center> Quantity </th>";
                        echo "<th><center> Remarks </th>";
                      
                        echo "<th><center> Update Status </th>";
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
                                echo "<td><center><span class='label label-warning'> Waiting for Pick Up </span></td>";
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