<?php
/* @var $this yii\web\View */
?>
<div class = "container">
    <div>
        <?php echo "<h1> My Orders </h1>";
        echo "<br>";
        echo "<br>";
        echo "<table class= table table-user-info style= width:80%;>";
            echo "<tr>";
                echo "<th><center> Delivery ID </th>";
                echo "<th><center> Current Status </th>";
                echo "<th><center> Date and Time Placed </th>";
            echo "</tr>";
        foreach ($orders as $orders) : 

                echo "<tr>";
                    echo "<a href=".yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]).">";
                    echo "<td><center>".$orders['Delivery_ID']."</td>";
                    echo "<td><center>".$orders['Orders_Status']."</td>";
                    date_default_timezone_set("Asia/Kuala_Lumpur");
                    echo "<td><center>".date('d/m/Y H:i:s', $orders['Orders_DateTimeMade'])."</td>";
                    echo "</a>";
                echo "</tr>";
            endforeach;
        echo "</table>"; ?>
    </div>
</div>
