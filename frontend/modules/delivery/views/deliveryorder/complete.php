<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\assets\DeliverymanOrdersAsset;
use kartik\widgets\Select2;
use kartik\widgets\ActiveForm;

$this->title = "Pick Up Orders";
DeliverymanOrdersAsset::register($this);
?>
<div class="container" id="deliveryman-orders-container">
    <div class="deliveryman-orders-header">
        <div class="deliveryman-orders-header-title"><?= Html::encode($this->title) ?></div>
    </div>
    <div class="content">
        <div class="col-sm-2">
            <div class="dropdown-url">
                 <?php 
                    echo Select2::widget([
                        'name' => 'url-redirect',
                        'hideSearch' => true,
                        'data' => $link,
                        'options' => [
                            'placeholder' => 'Go To ...',
                            'multiple' => false,

                        ],
                        'pluginEvents' => [
                             "change" => 'function (e){
                                location.href =this.value;
                            }',
                        ]
                    ])
                ;?>
            </div>
            <div class="nav-url">
                <ul id="deliveryman-orders-nav" class="nav nav-pills nav-stacked">
                	<?php foreach($link as $url=>$name):?>
                    	<li role="presentation" class=<?php echo $name=="Complete Orders" ? "active" :"" ?>>
                    		<a class="btn-block" href=<?php echo $url?>><?php echo $name?></a>
                    	</li>
                	<?php endforeach ;?>
                   
                </ul>
            </div>
        </div>
      
    </div>
</div>