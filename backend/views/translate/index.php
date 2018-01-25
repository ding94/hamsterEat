<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use backend\assets\ModalAsset;
ModalAsset::register($this);

Modal::begin([
      'header' => '<h2 class="modal-title">Edit</h2>',
      'id'     => 'add-modal',
      'size'   => 'modal-md',
      'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
]);
Modal::end();

?>
<div style="margin: 10px">
        <?= Html::a('Show With Page', ['/translate/index','case'=>1],['class' => 'btn btn-primary']); ?>
        <?= Html::a('Show All', ['/translate/index','case'=>2],['class' => 'btn btn-primary']); ?>
</div>

<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn',],

            [
                'attribute' => 'message',
                'headerOptions' => ['style' => 'width:12%'],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{submit}',
                'buttons' => [
                    'submit' => function($url, $model, $key) {
                        return Html::a('ZH', ['/translate/addtranslation','id'=>$model['id'],'language'=>'zh'],['data-toggle'=>'modal','data-target'=>'#add-modal','class' => 'btn btn-primary', 'name' => 'Reply-button']);
                    }

                ],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{submit}',
                'buttons' => [
                    'submit' => function($url, $model, $key) {
                        return Html::a('EN', ['/translate/addtranslation','id'=>$model['id'],'language'=>'en'],['data-toggle'=>'modal','data-target'=>'#add-modal','class' => 'btn btn-primary', 'name' => 'Reply-button']);
                    }

                ],
            ],

            [
                'header' => 'Mandarin',
                'value' =>'zh.translation',
                'headerOptions' => ['style' => 'width:20%'],
            ],

            [
                'header' => 'English',
                'value' =>'en.translation',
                'headerOptions' => ['style' => 'width:20%'],
            ],
            
        ],
    ])?>
<?php ActiveForm::end(); ?>