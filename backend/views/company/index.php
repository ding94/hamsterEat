<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\User;
use iutbay\yii2fontawesome\FontAwesome as FA;
use backend\assets\CompanyAsset;
use yii\bootstrap\Modal;
CompanyAsset::register($this);

$this->title = 'Registered Company';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php Modal::begin([
            'header' => '<h2 class="modal-title">Edit Company</h2>',
            'id'     => 'add-modal',
            'size'   => 'modal-md',
            //'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
    
Modal::end() ?>

<?php Modal::begin([
            'header' => '<h2 class="modal-title">Assign Deliveryman</h2>',
            'id'     => 'add-rider',
            'size'   => 'modal-md',
            //'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
    
Modal::end() ?>

<?= Html::a('Register new Company', ['/company/register'], ['class'=>'btn btn-success']);?>


<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

			[
            	'attribute' => 'name',
                'label' => 'Company Name',
            ],
            [
                'attribute' => 'owner',
                'value' => function ($model){ return User::find()->where('id=:id',[':id'=>$model['owner_id']])->one()->username;},
            ],

            [
                'attribute' => 'license_no',
            ],

            [
                'attribute' => 'location',
                'value' => function ($model){ return $model['address'].', '.$model['postcode'].', '.$model['area'];},
            ],

            [
                'attribute' => 'deliverymancompany.uid',
                'label' => 'Deliveryman',
                'value' => function($model){
                    $user = User::find()->where('id=:id',[':id'=>$model['deliverymancompany']['uid']])->one();
                    $name = empty($user) ? "Not Deliveryman Assign Yet": $user->username;
                  
                    return $name;
                },
            ],

            ['class' => 'yii\grid\ActionColumn' ,
                'header'=>'Assign Deliveryman' ,
                'template'=>' {addrider}',
                'buttons' => [
                    'addrider' => function($url,$model)
                    {
                        return  Html::a(FA::icon('plus 2x') , Url::to(['/company/add-rider', 'id'=>$model['id']]) , ['title' => 'Assign Deliveryman','data-toggle'=>'modal','data-target'=>'#add-rider']);
                    },
                ],
            ],

            ['class' => 'yii\grid\ActionColumn' , 
                'template'=>' {operate}',
                'buttons' => [
                    'operate' => function($url,$model)
                    {
                        return  $model->status == 1  ? Html::a(FA::icon('toggle-on lg') , $url , ['title' => 'Deactivate']) : Html::a(FA::icon('toggle-off lg') , $url , ['title' => 'Activate']);
                    },
                ],
            ],

            ['class' => 'yii\grid\ActionColumn' , 
                'template'=>' {edit}',
                'buttons' => [
                    'edit' => function($url,$model)
                    {
                        return  Html::a(FA::icon('pencil-square-o 2x') , Url::to(['/company/edit', 'id'=>$model['id']]) , ['title' => 'Edit Details','data-toggle'=>'modal','data-target'=>'#add-modal']);
                    },
                ],
            ],
        ],
		
		
    ])?>