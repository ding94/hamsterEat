
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
use common\models\vouchers\Vouchers;

    $this->title = 'Vouchers List';
    $this->params['breadcrumbs'][] = $this->title;
    
?>
    <!-- Below div are not suitable to be used -->
    <!-- <div class="container" id="page-change-container">
        <table class="table table-user-information" id="display">
            <tr>
                <td id="active" onclick="window.document.location='../web/index.php?r=vouchers/index';">Show All</td>
                <td id="deactive" onclick="window.document.location='<?php echo Url::to(['vouchers/page','page'=>2]);?>'">Show Activated</td>
                <td id="deactive" onclick="window.document.location='<?php echo Url::to(['vouchers/page','page'=>3]);?>'">Show Assigned</td>
                <td id="deactive" onclick="window.document.location='<?php echo Url::to(['vouchers/page','page'=>4]);?>'">Show Deactivated</td>
            </tr>   
        </table>
    </div>-->

	<?=Html::beginForm(['vouchers/delete','direct'=>'1'],'post'); ?>
    	<?= Html::a('Add New Voucher', ['/vouchers/add'], ['class'=>'btn btn-success']) ?>
        <?= Html::submitButton('Remove Vouchers',  [
            'class' => 'btn btn-danger', 
            'data' => [
                    'confirm' => 'Are you confirm to delete these vouchers?',
                    'method' => 'post',
                ]]);?>
            
        <?= Html::a('Generate new Vouchers', ['/vouchers/generate'], ['class'=>'btn btn-warning']);?>
    
    <br>
	<?= GridView::widget([
        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'columns' => [
             ['class' => 'yii\grid\CheckboxColumn',],

            //[ 'class' => 'yii\grid\SerialColumn',],
            'id',

            ['class' => 'yii\grid\ActionColumn' ,
             'template'=>'{more}',
             'buttons' => [
                'more' => function($url , $model)
                {
                    return Html::a("Add discount" , $url , ['title' => 'Add more discount item to this voucher']);
                },
              ],
            ],

            'code',
            [
                'attribute' => 'discount',
                 'value' => function($model)
                        {
                            if ($model->discount_type == 1) {
                                return $model->discount.' %';
                            }
                            return 'RM '.$model->discount;
                        },
            ],
            [
                'attribute' => 'discount_items.description',
                'filter' => array( "1"=>"Discount from purchase","2"=>"Discount from delivery charge","4"=>"Discount from Service Charge","3"=>"Discount from Total"),
            ],
            [
                'attribute' => 'voucher_status.description',
                'filter' => array( "1"=>"Activated","2"=>"Assigned","3"=>"Deactivated","4"=>"Expired"),
            ],
            [                  
                 'attribute' => 'startDate',
                 'value' => 'startDate',
                 'filter' => \yii\jui\DatePicker::widget(['model'=>$searchModel, 'attribute'=>'startDate', 'dateFormat' => 'yyyy-MM-dd',]),
                 'format' => 'datetime',
          
            ],
            [                  
                 'attribute' => 'endDate',
                 'value' => 'endDate',
                 'filter' => \yii\jui\DatePicker::widget(['model'=>$searchModel, 'attribute'=>'endDate', 'dateFormat' => 'yyyy-MM-dd',]),
                 'format' => 'datetime',
          
            ],
            /*['class' => 'yii\grid\ActionColumn' ,
             'template'=>'{confirm}',
             'buttons' => [
                'confirm' => function($url , $model)
                {
                    return $model->Ticket_Status <=2 ?  Html::a(FA::icon('check 2x') , $url , ['title' => 'Problem Solved','data-confirm'=>"Complete this ticket? Ticket ID: ".$model->Ticket_ID]) : "";
                },
              ]
            ],*/

            
        ],
    ])?>

  <?= Html::endForm();?> 