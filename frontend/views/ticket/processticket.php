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

$this->title = 'My Questions';
UserAsset::register($this);
?>
<div class="ticket">
<div class="container" id="userprofile">

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
                    <li role="presentation" class="active"><a href="#" class="btn-block userprofile-edit-left-nav">All</a></li>
                    <li role="presentation"><?php echo Html::a("Submit Ticket",['/ticket/submit-ticket'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
                    <li role="presentation"><?php echo Html::a("Completed Ticket",['/ticket/completed'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
                </ul>
            </div>
        </div>
       
    <div class="col-sm-8 userprofile-edit-input">
	<p style="text-align:center;">We’re thrilled to hear from you, so talk to us any time you like.
	</p><br>
        <table class="table table-inverse">
            <tr >
                <th>Serial No.</th>
                <th>Category</th> 
                <th>Subject</th>
                <th>Status</th>
                <th>Chat</th>
            </tr>

            <?php  
                    foreach ($model as $k => $model) { ?>
                
                    <tr>
                        <td>
                            <?php $k+=1; echo $k; ?>
                        </td>
                        <td>
                            <?php echo $model['Ticket_Category']; ?>
                        </td>
                        <td>
                            <?php echo $model['Ticket_Subject']; ?>
                        </td>
                        <td>
                             <?php if ($model['Ticket_Status'] == 1) {
                                    echo "Submitted";
                                }
                                elseif ($model['Ticket_Status'] == 2) {
                                     echo "Replied";
                                 }
                                 else {
                                      echo "error";
                                  } 
                            ?>
                        </td>
                        
                        <td>
                            <a href=<?php echo  Url::to(['ticket/chatting','sid'=>$k,'tid'=>$model['Ticket_ID']]); ?> >
                                <font color="blue">Go Chat</font>
                            </a>
                        </td>
                    </tr>
            <?php   }   ?>
        </table>
        <div class="form-group" style="padding-left: 30%">
            <?= Html::a('Create a Ticket', ['/ticket/submit-ticket'], ['class'=>'btn btn-primary']) ?>
            <?= Html::a('Completed Ticket', ['/ticket/completed'], ['class'=>'btn btn-primary']) ?>
            </div>

    </div>
    </div>
    </div>
</div>


