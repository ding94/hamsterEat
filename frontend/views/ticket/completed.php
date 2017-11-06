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

    $this->title = 'My Completed Questions';
?>
<div class="container" id="userprofile">
<div class="userprofile-header">
        <div class="userprofile-header-title"><?php echo Html::encode($this->title)?></div>
    </div>
   <div class="userprofile-detail">
        <div class="col-sm-2">
           <ul class="nav nav-pills nav-stacked">
                <li role="presentation"><?php echo Html::a("All",['/ticket/index'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
                <li role="presentation"><?php echo Html::a("Submit Ticket",['/ticket/submit-ticket'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
				<li role="presentation" class="active"><a href="#" class="btn-block userprofile-edit-left-nav">Completed</a></li>
            </ul>
        </div>
<div class="col-sm-8 userprofile-edit-input">
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
                             <?php if ($model['Ticket_Status'] == 3) {
                                    echo "Completed";
                                }
                                 else {
                                      echo "error";
                                  } 
                            ?>
                        </td>
                        
                        <td>
                            <a href=<?php echo  Url::to(['ticket/chatting','sid'=>$k,'tid'=>$model['Ticket_ID']]); ?> >
                                <font color="blue">See Chat Record </font>
                            </a>
                        </td>
                    </tr>
            <?php   }   ?>
        </table>
        <div class="form-group" style="padding-left: 30%"> 
            <?= Html::a('Create a Ticket', ['/ticket/submit-ticket'], ['class'=>'btn btn-primary']) ?>
            <?= Html::a('Processing Ticket', ['/ticket/index'], ['class'=>'btn btn-primary']) ?>
        </div>
    </div>
</div>
</div>