
<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use frontend\assets\MyOrdersHistoryAsset;
$this->title = "My Orders History";
MyOrdersHistoryAsset::register($this);
?>
<div class = "container">
    <div>
        <h1><?= Html::encode($this->title) ?></h1>
        <br>
        <br>
        <table class='table table-user-info orderTable' style='width:80%;'>
            <thead>
                <tr>
                    <th><center> Delivery ID </th>
                    <th><center> Current Status </th>
                    <th><center> Date and Time Placed </th>
                    <th><center> Invoice </center></th>
                </tr>
            </thead>
            <?php foreach ($orders as $orders) : ?>
                <?php if($orders['Orders_Status']== 'Rating Done') :
                    $label='<span class="label label-success">'.$orders['Orders_Status'].'</span>';
                   
                 endif ;?>
                 <tbody>
                     <tr class='orderRow'>
                        <td><center><?php echo Html::a($orders['Delivery_ID'] ,['invoice-pdf','did'=>$orders['Delivery_ID']], ['target'=>'_blank']); ?></center></td>
                        <td><center><?php echo Html::a($label ,['invoice-pdf','did'=>$orders['Delivery_ID']], ['target'=>'_blank']); ?></td>
                        <?php date_default_timezone_set("Asia/Kuala_Lumpur"); ?>
                        <td><center> <?php echo Html::a(date('d/m/Y H:i:s', $orders['Orders_DateTimeMade']) ,['invoice-pdf','did'=>$orders['Delivery_ID']], ['target'=>'_blank']); ?></td>
                        <td><center><?php echo Html::a("Invoice Detail" ,['invoice-pdf','did'=>$orders['Delivery_ID']], ['target'=>'_blank' ,'class'=>'raised-btn main-btn']); ?></center></td>
                     </tr>
                 </tbody>
               
                    
           <?php endforeach;?>
        </table>
    </div>
</div>