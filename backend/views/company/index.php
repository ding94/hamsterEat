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

            ['class' => 'yii\grid\ActionColumn' , 
                'template'=>' {edit}',
                'buttons' => [
                    'edit' => function($url,$model)
                    {
                        return  Html::a(FA::icon('pencil-square-o 2x') , Url::to(['/company/edit', 'link'=>Yii::$app->request->url,'id'=>$model['id']]) , ['title' => 'Edit Details','data-toggle'=>'modal','data-target'=>'#add-modal']);
                    },
                ],
            ],
        ],
		
		
    ])?>