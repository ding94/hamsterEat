<?php
/* @var $this yii\web\View */
$this->title = "Order Details";
?>
<div class = "container">
    <div>
        <?php echo "<h1> Details for Delivery ID: $did </h1>";
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
        echo "</table>"; ?>
    </div>
</div>
