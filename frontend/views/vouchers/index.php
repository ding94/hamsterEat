<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Admin;

    $this->title = 'Usable Discount Codes';    
?>
<div class="container">
<div class="site-contact">
 <div class="col-lg-6 col-lg-offset-1" style="text-align:center">
    <h1><?= Html::encode($this->title) ?></h1>  

     
        
            <?php $j = 0;?>
            <?php   
                if (!empty($model)) { ?>
                    <table class="table table-inverse">
                    <tr >
                        <th>Serial No.</th>
                        <th>Code</th> 
                        <th>Discount</th>
                        <th>Item</th>
                        <th>Last Available Date</th>
                    </tr>
                  <?php foreach ($uservoucher as $k => $uservou) { ?>
                
                        <tr>
                            <td>
                                <?php $j+=1; echo $j; ?>
                            </td>
                            <td>
                                <?php echo $uservou['code']; ?>
                            </td>
                            <td>
                                <?php echo $uservou['discount']; ?>
                            </td>
                            <td>
                                <?php echo $uservou['discount_item']; ?>
                            </td>
                            <td>
                                <?php echo $uservou['endDate']; ?>
                            </td>
                            
                            
                        </tr>
                     
                <?php  }} elseif (empty($model)) { ?>
                <div><H4>Seems like you didn't have coupon yet..</H2></div>
           <?php }  ?>
        </table>
    </div>



</div>
</div>