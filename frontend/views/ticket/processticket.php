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

    $this->title = 'My Questions';
    $this->params['breadcrumbs'][] = $this->title;
    
?>
<div class="container">
<div class="site-contact">
    <div class="tableHeader">
        <ul>
            <li class="hover">
                <a href="index.php?r=ticket/submit-ticket">Create Ticket </a>
            </li>
            <li>
                <a href="index.php?r=ticket/index"> Ticket In Process</a>
            </li>
            <li>
                <a href="index.php?r=ticket/completed">Completed</a>
            </li>
        </ul>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>  

    <div class="col-md-8 col-md-offset-1">
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
        <div style="padding-left: 35%"> <?= Html::a('Create a Ticket', ['/ticket/submit-ticket'], ['class'=>'btn btn-primary']) ?></div>
    </div>



</div>
</div>