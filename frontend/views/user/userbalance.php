<?php
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;
/* @var $this yii\web\View */
?>

<div class="container">
	<div class="tab-content col-md-6 col-md-offset-1" id="userprofile">
		<table class="table table-user-information"><h1>User Balance</h1>
                <tbody>
                  <tr>
                    <td>My Balance:</td>
                    <td><?php echo $model['User_Balance']; ?></td>
                  </tr>
               </tbody>
            </table>
            <a class="btn btn-md btn-warning" href="<?php echo yii\helpers\Url::to(['topup/index'])?>">Top Up</a>
            <a class="btn btn-md btn-warning" href="<?php echo yii\helpers\Url::to(['withdraw/index'])?>">Withdraw</a>
            <?php 
             // if ($offlinetopup['rejectReason']!= null) { 
              // echo Html::a('Reject Reason', '#', ['id' => 'rejectreason','data-toggle' => 'modal','data-target' => '#reason-modal','class' => 'btn btn-success',]);
            //  }
              ?>
              


	</div>
</div>

<?php 
Modal::begin([
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
Modal::end(); 
?>