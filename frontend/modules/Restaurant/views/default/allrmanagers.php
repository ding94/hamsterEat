<?php
use yii\helpers\Html;
use common\models\user\Userdetails;
use common\models\Rmanagerlevel;
use kartik\widgets\ActiveForm;
use frontend\assets\AddStaffAsset;

$this->title = Yii::t('m-restaurant','Add Staff');
AddStaffAsset::register($this);
?>
<div id="add-staff-container" class="container">
    <div class="add-staff-header">
        <div class="add-staff-header-title"><?= Html::encode($this->title) ?></div>
    </div>
    <div class="content">
        <div class="col-sm-2">
            <ul id="add-staff-nav" class="nav nav-pills nav-stacked">
                <li role="presentation"><?php echo Html::a("<i class='fa fa-chevron-left'></i> ".Yii::t('common','Back'),['manage-restaurant-staff', 'rid'=>$rid],['class'=>'btn-block'])?></li>
            </ul>
        </div>
        <div id="add-staff-content" class="col-sm-10">
            <?php 
                $form = ActiveForm::begin(['id' => 'dynamic-form','type' => ActiveForm::TYPE_INLINE]);
                echo $form->field($food,'Nickname')->textInput(['class'=>'search-bar','placeholder'=>Yii::t('m-restaurant','Search Restaurant Managers')])->label(false);
                echo Html::submitButton(Yii::t('common','Search'),['class' => 'btn-search raised-btn main-btn', 'name' => 'search-button']);
                if ($keyword != '') {
            ?>
                <div>
                    <h3><?= Yii::t('m-restaurant','Showing results similar to')?> <?php echo $keyword; ?></h3>
                </div>
            <?php         
                 } 
                 ActiveForm::end();
            ?>
            <table class="table table-restaurant-details">
                <thead>
                    <tr>
                        <th><?= Yii::t('common','Picture')?></th>
                        <th><?= Yii::t('common','Username')?></th>
                        <th><?= Yii::t('user','Full Name')?></th>
                        <th><?= Yii::t('m-restaurant','Add as Owner')?></th>
                        <th><?= Yii::t('m-restaurant','Add as Manager')?></th>
                        <th><?= Yii::t('m-restaurant','Add as Operator')?></th>
                    </tr>
                </thead>
                <?php
                    foreach ($allrmanagers as $data):
                ?>
                <tr>
                    <?php
                        $find = Rmanagerlevel::find()->where('Restaurant_ID = :rid and User_Username = :uname',[':rid'=>$rid,':uname'=>$data['username']])->one();
                        if (is_null($find)){
                            $name = Userdetails::find()->where('User_Username = :uname',[':uname'=>$data['username']])->one();
                            if (is_null($name['User_PicPath'])){
                                $picpath = "DefaultPic.png";
                            }
                            else
                            {
                                $picpath = $name['User_PicPath'];
                            }
                            ?>
                            <td><?php echo Html::img('@web/imageLocation/'.$picpath, ['class' => 'img-responsive', 'style'=>'height:40px; width:50px; margin:auto;']); ?></td>
                            <td class="with" data-th="Username"><?php echo $data['username']; ?></td>
                            <td class="with" data-th="Full Name"><?php echo $name['User_FirstName'].' '.$name['User_LastName']; ?></td>
                            <td><?php echo Html::a(Yii::t('m-restaurant','Add as Owner'), ['add-staff', 'rid'=>$rid, 'uname'=>$data['username'], 'num'=>1], ['class'=>'raised-btn main-btn','data-confirm'=>'Are you sure you want to add?']); ?></td>
                            <td><?php echo Html::a(Yii::t('m-restaurant','Add as Manager'), ['add-staff', 'rid'=>$rid, 'uname'=>$data['username'], 'num'=>2], ['class'=>'raised-btn main-btn','data-confirm'=>'Are you sure you want to add?']); ?></td>
                            <td><?php echo Html::a(Yii::t('m-restaurant','Add as Operator'), ['add-staff', 'rid'=>$rid, 'uname'=>$data['username'], 'num'=>3], ['class'=>'raised-btn main-btn','data-confirm'=>'Are you sure you want to add?']); ?></td>
                    <?php
                        }
                    endforeach;
                    ?>
                </tr>
            </table>
        </div>
    </div>
</div>
