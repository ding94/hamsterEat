<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use frontend\assets\MyOrdersAsset;
$this->title = "My Orders";

MyOrdersAsset::register($this);
?>
<div class = "container">


    <?php echo "<h1> My Orders </h1>";
    echo '<div class="content">';
    echo'<ul id="order"class="nav nav-pills">';
    echo'  <li class="active"><a data-toggle="pill" href="#pending"><h4>Pending<span class="badge">'.Yii::$app->view->params['countPending'].'</h4></span></a></li>';
    echo'   <li><a data-toggle="pill" href="#preparing"><h4>Preparing<span  class="badge">'.Yii::$app->view->params['countPreparing'].'</span></h4></a></li>';
    echo'   <li><a data-toggle="pill" href="#pickup"><h4>Pick Up in Process<span class="badge">'.Yii::$app->view->params['countPickup'].'</h4></span></a></li>';
    echo'    <li><a data-toggle="pill" href="#ontheway"><h4>On The Way<span class="badge">'.Yii::$app->view->params['countOntheway'].'</h4></span></a></li>';
    echo'     <li><a data-toggle="pill" href="#completed"><h4>Completed<span class="badge">'.Yii::$app->view->params['countCompleted'].'</h4></span></a></li>';
    echo'  </ul>';
    echo "<br>";
    echo "<br>";
    ?>
    <div class="tab-content">
     <div id= "pending" class = "tab-pane fade in active">
      <?php

      echo "<table class='table table-user-info orderTable' style='width:80%;'>";
      echo "<tr>";
      echo "<th><center> Delivery ID </th>";
               // echo "<th><center> Current Status </th>";
      echo "<th><center> Date and Time Placed </th>";
      echo "<th><center> Rate </th>";
      echo "</tr>";
      foreach ($order1 as $orders) : 

        if($orders['Orders_Status']== 'Pending')
        {

                    //$label='<span class="label label-warning">'.$orders['Orders_Status'].'</span>';
           echo "<tr class='orderRow'>";
                    //echo "<a href=".yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]).">";
           echo "<td><center><a href=".yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]).">".$orders['Delivery_ID']."</a></td>";

                   // echo "<td><center><a href=".yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]).">".$label."</a></td>";
           date_default_timezone_set("Asia/Kuala_Lumpur");
           echo "<td><center><a href=".yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]).">".date('d/m/Y H:i:s', $orders['Orders_DateTimeMade'])."</a></td>";
           if ($orders['Orders_Status']!= 'Completed')
           {
            echo "<td> </td></tr>";
        }
        else
        {
            echo "<td><center>".Html::a('Rate This Delivery', ['rating/index','id'=>$orders['Delivery_ID']], ['class'=>'btn btn-primary'])."</td>";
                    //echo "</a>";
            echo "</tr>";
        }
    }
endforeach;
echo "</table>";
echo"</div>";
?>

<div id = "preparing" class= "tab-pane fade">
   <?php

   echo "<table class='table table-user-info orderTable' style='width:80%;'>";
   echo "<tr>";
   echo "<th><center> Delivery ID </th>";
                //echo "<th><center> Current Status </th>";
   echo "<th><center> Date and Time Placed </th>";
   echo "<th><center> Rate </th>";
   echo "</tr>";
   foreach ($order2 as $orders) : 
    if($orders['Orders_Status']== 'Preparing')
    {
                     //$label='<span class="label label-info">'.$orders['Orders_Status'].'</span>';
       echo "<tr class='orderRow'>";
                    //echo "<a href=".yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]).">";
       echo "<td><center><a href=".yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]).">".$orders['Delivery_ID']."</a></td>";

                   // echo "<td><center><a href=".yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]).">".$label."</a></td>";
       date_default_timezone_set("Asia/Kuala_Lumpur");
       echo "<td><center><a href=".yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]).">".date('d/m/Y H:i:s', $orders['Orders_DateTimeMade'])."</a></td>";
       if ($orders['Orders_Status']!= 'Completed')
       {
        echo "<td> </td></tr>";
    }
    else
    {
        echo "<td><center>".Html::a('Rate This Delivery', ['rating/index','id'=>$orders['Delivery_ID']], ['class'=>'btn btn-primary'])."</td>";
                    //echo "</a>";
        echo "</tr>";
    }
}
endforeach;
echo "</table>";
echo"</div>";
?>
<div id = "pickup" class= "tab-pane fade">
   <?php
   echo "<table class='table table-user-info orderTable' style='width:80%;'>";
   echo "<tr>";
   echo "<th><center> Delivery ID </th>";
                //echo "<th><center> Current Status </th>";
   echo "<th><center> Date and Time Placed </th>";
   echo "<th><center> Rate </th>";
   echo "</tr>";
   foreach ($order3 as $orders) : 
    if($orders['Orders_Status']== 'Pick Up in Process')
    {
                     //$label='<span class="label label-info">'.$orders['Orders_Status'].'</span>';
       echo "<tr class='orderRow'>";
                    //echo "<a href=".yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]).">";
       echo "<td><center><a href=".yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]).">".$orders['Delivery_ID']."</a></td>";

                   // echo "<td><center><a href=".yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]).">".$label."</a></td>";
       date_default_timezone_set("Asia/Kuala_Lumpur");
       echo "<td><center><a href=".yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]).">".date('d/m/Y H:i:s', $orders['Orders_DateTimeMade'])."</a></td>";
       if ($orders['Orders_Status']!= 'Completed')
       {
        echo "<td> </td></tr>";
    }
    else
    {
        echo "<td><center>".Html::a('Rate This Delivery', ['rating/index','id'=>$orders['Delivery_ID']], ['class'=>'btn btn-primary'])."</td>";
                    //echo "</a>";
        echo "</tr>";
    }
}
endforeach;
echo "</table>";
echo"</div>";
?>
<div id = "ontheway" class= "tab-pane fade">
   <?php
   echo "<table class='table table-user-info orderTable' style='width:80%;'>";
   echo "<tr>";
   echo "<th><center> Delivery ID </th>";
                //echo "<th><center> Current Status </th>";
   echo "<th><center> Date and Time Placed </th>";
   echo "<th><center> Rate </th>";
   echo "</tr>";
   foreach ($order4 as $orders) : 
    if($orders['Orders_Status']== 'On The Way')
    {
                     //$label='<span class="label label-info">'.$orders['Orders_Status'].'</span>';
       echo "<tr class='orderRow'>";
                    //echo "<a href=".yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]).">";
       echo "<td><center><a href=".yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]).">".$orders['Delivery_ID']."</a></td>";

                  //  echo "<td><center><a href=".yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]).">".$label."</a></td>";
       date_default_timezone_set("Asia/Kuala_Lumpur");
       echo "<td><center><a href=".yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]).">".date('d/m/Y H:i:s', $orders['Orders_DateTimeMade'])."</a></td>";
       if ($orders['Orders_Status']!= 'Completed')
       {
        echo "<td> </td></tr>";
    }
    else
    {
        echo "<td><center>".Html::a('Rate This Delivery', ['rating/index','id'=>$orders['Delivery_ID']], ['class'=>'btn btn-primary'])."</td>";
                    //echo "</a>";
        echo "</tr>";
    }
}
endforeach;
echo "</table>";
echo"</div>";
?>
<div id = "completed" class= "tab-pane fade">
   <?php
   echo "<table class='table table-user-info orderTable' style='width:80%;'>";
   echo "<tr>";
   echo "<th><center> Delivery ID </th>";
                //echo "<th><center> Current Status </th>";
   echo "<th><center> Date and Time Placed </th>";
   echo "<th><center> Rate </th>";
   echo "</tr>";
   foreach ($order5 as $orders) : 
    if($orders['Orders_Status']== 'Completed' || $orders['Orders_Status']=='Rating Done')
    {
                     //$label='<span class="label label-info">'.$orders['Orders_Status'].'</span>';
       echo "<tr class='orderRow'>";
                    //echo "<a href=".yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]).">";
       echo "<td><center><a href=".yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]).">".$orders['Delivery_ID']."</a></td>";

                  //  echo "<td><center><a href=".yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]).">".$label."</a></td>";
       date_default_timezone_set("Asia/Kuala_Lumpur");
       echo "<td><center><a href=".yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]).">".date('d/m/Y H:i:s', $orders['Orders_DateTimeMade'])."</a></td>";
       if ($orders['Orders_Status']!= 'Completed')
       {
        echo "<td> </td></tr>";
    }
    else
    {
        echo "<td><center>".Html::a('Rate This Delivery', ['rating/index','id'=>$orders['Delivery_ID']], ['class'=>'btn btn-primary'])."</td>";
                    //echo "</a>";
        echo "</tr>";
    }
}
endforeach;
echo "</table>";
echo"</div>";
?>


</div>
</div>