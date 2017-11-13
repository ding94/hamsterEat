<?php
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use kartik\widgets\Select2;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use frontend\assets\TopupWithdrawMpHistoryAsset;

TopupWithdrawMpHistoryAsset::register($this);
?>

<div class="balance">
<div id="userprofile" class="row">
   <div class="userprofile-header">
        <div class="userprofile-header-title"><?php echo Html::encode($this->title)?></div>
    </div>
    <div class="topup-detail">
        <div class="col-sm-2 ">
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
          		<ul class="nav nav-pills nav-stacked">
	                <li role="presentation" class="active"><a href="#" class="btn-block userprofile-edit-left-nav">Balance history</a></li>
	                <li role="presentation"><?php echo Html::a("Top Up",['/topup/index'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
	                <li role="presentation"><?php echo Html::a("Withdraw",['/withdraw/index'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
	            </ul>
          	</div>
        </div>
        <div class="col-sm-10 right-side">
            <div  id="balance-history" class="history-container">
              <table class="table table-user-information" id="display">
                <tr>
                    <td id="right" class="history-font"><?php echo Html::encode($this->title)?></td> 
                    <td id="middle" class="link history-font"><?php echo Html::a("Top Up History",['/topup-history/index'],['class'=>'btn-block remove-all'])?></td>
                    <td id="left" class="link history-font"><?php echo Html::a("Withdraw History",['/withdraw-history/index'],['class'=>'btn-block remove-all'])?></td>
                </tr>  
              </table>
            </div> 
            <div class="account-history">
            
		
             	<?= GridView::widget([
					'dataProvider' => $model,
					'filterModel' => $searchModel,
					'tableOptions'=>['class'=>'table table-hover border'],
					'summary' => '',
					'columns' => [
						[	
							'label' => 'Time',
							'value' => 'created_at',
							 'filter' => \yii\jui\DatePicker::widget(['model'=>$searchModel, 'attribute'=>'created_at', 'dateFormat' => 'yyyy-MM-dd',]),
							'format' => 'html',
							//'contentOptions' => ['data-th' => 'Time'],
						],
						[
		                    'attribute' => 'description',
		                    'filterInputOptions' => [
		                            'class'       => 'form-control',
		                            'placeholder' => 'Search Description',
		                    ],
		                    'contentOptions' => ['data-th' => 'Description'],
								
		                ],
						
						[
			                'attribute' => 'type',
							'value' => function($model){
								if($model->type==0){
									return $model->type="Negative";
								}
								else{
									return $model->type="Positive";
								}
		                           
								   },
		                    'filter' => array( "0"=>"Negative","1"=>"Positive"),
			                /*'filterInputOptions' => [
			                    'class'       => 'form-control',
			                    'placeholder' => 'Search Type',
			                ],*/
			                'contentOptions' => ['data-th' => 'Type'],
		            	],
						[
						'label' => 'Amount(RM)',
		                'attribute' => 'amount',
		                'filterInputOptions' => [
		                    'class'       => 'form-control',
		                    'placeholder' => 'Search Amount',
		                ],
		                'contentOptions' => ['data-th' => 'Amount'],
		            ],
					],
				]); ?>
			
            </div>
             
              
            </div>
        </div>
    </div>
</div>

<?php 
/*Modal::begin([
'id' => 'reason-modal',
'header' => '<h4 class="modal-title">Reject Reason</h4>',
'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
]); 
$requestUrl = Url::toRoute('user/rejectreason');
$js = <<<JS
$.get('{$requestUrl}', {},
function (data) {
$('.modal-body').html(data);
} 
);
JS;
$this->registerJs($js);
Modal::end(); */
?>