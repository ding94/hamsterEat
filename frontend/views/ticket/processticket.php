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
use kartik\widgets\Select2;
use backend\models\Admin;
use frontend\assets\UserAsset;

$this->title = Yii::t('ticket','My Questions');
UserAsset::register($this);
?>
<div class="ticket">
<div class="container" id="ticketh">

   <div class="userprofile-header">
        <div class="userprofile-header-title"><?php echo Html::encode($this->title)?></div>
    </div>
   <div class="userprofile-detail">
        <div class="col-sm-2">
            <div class="dropdown-url">
                <?php 
                    echo Select2::widget([
                        'name' => 'url-redirect',
                        'hideSearch' => true,
                        'data' => $link,
                        'options' => [
                            'placeholder' => Yii::t('common','Go To ...'),
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
                    <li role="presentation" class="active"><a href="#" class="btn-block userprofile-edit-left-nav"><?= Yii::t('common','All') ?></a></li>
                    <li role="presentation"><?php echo Html::a(Yii::t('ticket','Submit Ticket'),['/ticket/submit-ticket'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
                    <li role="presentation"><?php echo Html::a(Yii::t('ticket','Completed Ticket'),['/ticket/completed'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
                </ul>
            </div>
        </div>
       
    <div class="col-sm-8 right-side">
	<p style="text-align:center;padding-top:20px;"><?= Yii::t('ticket',"We're thrilled to hear from you, so talk to us any time you like.") ?>
	</p><br>
	<div class="ticket-history">
        <table class="table table-inverse">
            <tr >
                <th><?= Yii::t('ticket','Serial No.') ?></th>
                <th><?= Yii::t('common','Category') ?></th> 
                <th><?= Yii::t('ticket','Subject') ?></th>
                <th><?= Yii::t('common','Status') ?></th>
                <th><?= Yii::t('common','Date') ?></th>
                <th><?= Yii::t('ticket','Chat') ?></th>
            </tr>

            <?php  
                    foreach ($model as $k => $model) { ?>
                    <tr>
                        <td data-th="Serial No.">
                            <?php $k+=1; echo $k; ?>
                        </td>
                        <td data-th="Category">
                            <?php echo Yii::t('ticket',$model['Ticket_Category']); ?>
                        </td>
                        <td data-th="Subject">
                            <?php echo $model['Ticket_Subject']; ?>
                        </td>
                        <td data-th="Status">
                             <?php if ($model['Ticket_Status'] == 1) {
                                    echo Yii::t('ticket',"Submitted");
                                }
                                elseif ($model['Ticket_Status'] == 2) {
                                    echo Yii::t('ticket','Replied');
                                }
                                else {
                                    echo Yii::t('common','error');
                                } 
                            ?>
                        </td>
                        <td data-th="Date">
                            <?= date('Y-m-d h:i:s',$model['Ticket_DateTime']); ?>
                        </td>
                        <td data-th="Chat">
                            <a href=<?php echo  Url::to(['ticket/chatting','sid'=>$k,'tid'=>$model['Ticket_ID']]); ?> >
                                <font color="blue"><?= Yii::t('ticket','Go Chat') ?></font>
                            </a>
                        </td>
                    </tr>
            <?php   }   ?>
        </table>
        <div class="form-group" id="ticketb">
            <?= Html::a(Yii::t('ticket','Create a Ticket'), ['/ticket/submit-ticket'], ['class'=>'raised-btn main-btn resize-btn']) ?>
            <?= Html::a(Yii::t('ticket','Completed Ticket'), ['/ticket/completed'], ['class'=>'raised-btn main-btn resize-btn']) ?>
        </div>

    </div>
    </div>
    </div>
    </div>
</div>


