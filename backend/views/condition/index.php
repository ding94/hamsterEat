<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use iutbay\yii2fontawesome\FontAwesome as FA;

$this->title = 'Condition List';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= Html::a('Add Voucher', ['/condition/setcondition'], ['class'=>'btn btn-success']) ?>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

			[
            	'attribute' => 'vid',
                'label' => 'Voucher ID',
            ],
            [
            	'attribute' => 'condition_description.description',
                'label' => 'Condition',
            ],
        ],

    ])?>