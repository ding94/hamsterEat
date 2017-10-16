<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
$this->title = "My Orders";
?>
<div class = "container">
    <div>
        <?php echo "<h1> My Orders </h1>";
        echo "<br>";
        echo "<br>";
        echo "<table class='table table-user-info orderTable' style='width:80%;'>";
            echo "<tr>";
                echo "<th><center> Delivery ID </th>";
                echo "<th><center> Current Status </th>";
                echo "<th><center> Date and Time Placed </th>";
                echo "<th><center> Rate </th>";
            echo "</tr>";
        foreach ($orders as $orders) : 
                if($orders['Orders_Status']== 'Pending')
                {
                    $label='<span class="label label-warning">'.$orders['Orders_Status'].'</span>';
                }
                elseif($orders['Orders_Status']== 'Preparing')
                {
                    $label='<span class="label label-info">'.$orders['Orders_Status'].'</span>';
                }
                elseif($orders['Orders_Status']== 'Pick Up in Process')
                {
                    $label='<span class="label label-info">'.$orders['Orders_Status'].'</span>';
                }
                elseif($orders['Orders_Status']== 'On The Way')
                {
                    $label='<span class="label label-info">'.$orders['Orders_Status'].'</span>';
                }
                elseif($orders['Orders_Status']== 'Completed')
                {
                    $label='<span class="label label-success">'.$orders['Orders_Status'].'</span>';
                }
                echo "<tr class='orderRow'>";
                    //echo "<a href=".yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]).">";
                    echo "<td><center><a href=".yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]).">".$orders['Delivery_ID']."</a></td>";
                   
                    echo "<td><center><a href=".yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]).">".$label."</a></td>";
                    date_default_timezone_set("Asia/Kuala_Lumpur");
                    echo "<td><center><a href=".yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]).">".date('d/m/Y H:i:s', $orders['Orders_DateTimeMade'])."</a></td>";
                    if ($orders['Orders_Status']!= 'Completed')
                    {
                        echo "</tr>";
                    }
                    else
                    {
                    echo "<td><center>".Html::a('Rate This Delivery', ['rating/index','id'=>$orders['Delivery_ID']], ['class'=>'btn btn-primary'])."</td>";
                    //echo "</a>";
                echo "</tr>";
                    }
            endforeach;
        echo "</table>"; ?>
    </div>
</div>