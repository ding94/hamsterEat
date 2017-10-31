<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
$this->title = "My Orders";
?>
<div class = "container">
    <div class="row">
        <h1><?= Html::encode($this->title) ?></h1>
        <br>
        <br>
        <table class='table table-user-info orderTable' style='width:80%;'>
            <thead>
                <tr>
                    <th><center> Delivery ID </th>
                    <th><center> Current Status </th>
                    <th><center> Date and Time Placed </th>
                    <th><center> Rating</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orders as $order) :?>
                    <?php  
                    if($order['Orders_Status']== 'Pending')
                    {
                        $label='<span class="label label-warning">'.$order['Orders_Status'].'</span>';
                    }
                    elseif($order['Orders_Status']== 'Preparing')
                    {
                        $label='<span class="label label-info">'.$order['Orders_Status'].'</span>';
                    }
                    elseif($order['Orders_Status']== 'Pick Up in Process')
                    {
                        $label='<span class="label label-info">'.$order['Orders_Status'].'</span>';
                    }
                    elseif($order['Orders_Status']== 'On The Way')
                    {
                        $label='<span class="label label-info">'.$order['Orders_Status'].'</span>';
                    }
                    elseif($order['Orders_Status']== 'Completed')
                    {
                        $label='<span class="label label-success">'.$order['Orders_Status'].'</span>';
                    }?>
                    <tr class='orderRow'>
                       <td><center><?php echo Html::a($order['Delivery_ID'] ,['order-details','did'=>$order['Delivery_ID']]); ?></center></td> 
                       <td><center><?php echo Html::a($label ,['order-details','did'=>$order['Delivery_ID']]); ?></center></td> 
                       <?php date_default_timezone_set("Asia/Kuala_Lumpur"); ?>
                       <td><center><?php echo Html::a(date('d/m/Y H:i:s', $order['Orders_DateTimeMade']) ,['order-details','did'=>$order['Delivery_ID']]); ?></center></td> 
                       <?php if($order['Orders_Status'] == 'Completed'):?>
                            <td><center><?php echo Html::a('Rate This Delivery' ,['rating/index','id'=>$order['Delivery_ID']],['class'=>'btn btn-primary']); ?></center></td> 
                       <?php endif ?>
                    </tr>
                <?php endforeach ;?>
            </tbody>
        </table>
    </div>
</div>