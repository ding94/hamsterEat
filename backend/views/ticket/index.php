
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
use common\models\User;

    $this->title = 'Tickets/Questions Submitted by Users';
    $this->params['breadcrumbs'][] = $this->title;
    
?>

<?= GridView::widget([
        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'columns' => [

            //[ 'class' => 'yii\grid\SerialColumn',],
            'Ticket_ID',
            [
                'attribute' => 'User_id',
                'value' => function($model){
                    $user = User::find()->where('id=:id',[':id'=>$model->User_id])->one();
                    if (!empty($user)) {
                        return $user['username'];
                    }
                    else{
                        return $model['User_id'];
                    }
                },
                'label' => 'User',
            ],
            'Ticket_Category',
            'Ticket_Content',
            'ticket_status.description',
            ['class' => 'yii\grid\ActionColumn' , 
                 'template'=>' {img}',
                 'buttons' => [
                    'img' => function($url,$model)
                    {
                        if(!empty($model->Ticket_PicPath)){
                            return Html::a('Picture',Yii::$app->params['backend-submitticket-pic'].$model->Ticket_PicPath,['target'=>'_blank']); //open page in new tab
                        }
                    },
                ],
            ],

            ['class' => 'yii\grid\ActionColumn' ,
             'template'=>'{reply}',
             'buttons' => [
                'reply' => function($url , $model)
                {
                    return  Html::a(FA::icon('comment 2x') , $url , ['title' => 'Reply Problem']);
                },
              ]
            ],

            ['class' => 'yii\grid\ActionColumn' ,
             'template'=>'{confirm}',
             'buttons' => [
                'confirm' => function($url , $model)
                {
                    return $model->Ticket_Status <=2 ?  Html::a(FA::icon('check 2x') , $url , ['title' => 'Problem Solved','data-confirm'=>"Complete this ticket? Ticket ID: ".$model->Ticket_ID]) : "";
                },
              ]
            ],

            
        ],
    ])?>

