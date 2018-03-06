<?php
use yii\helpers\Html;
use common\models\user\Userdetails;
use frontend\assets\ManageStaffAsset;
use kartik\widgets\Select2;

$this->title = Yii::t('m-restaurant',"Manage").' '. $resname.Yii::t('common',"'s")." ".Yii::t('m-restaurant','Staff');
ManageStaffAsset::register($this);
?>
<div id="manage-staff-container" class = "container">
    <div class="manage-staff-header">
        <div class="manage-staff-header-title"><?= Html::encode($this->title) ?></div>
    </div>
    <div class="content">
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
                <ul id="manage-staff-nav" class="nav nav-pills nav-stacked">
                    <?php foreach($link as $url=>$name):?>
                        <li role="presentation" class=<?php echo $name=="Manage Staffs" ? "active" :"" ?>>
                            <a class="btn-block" href=<?php echo $url?>><?php echo Yii::t('m-restaurant',$name)?></a>
                        </li>   
                    <?php endforeach ;?>
                </ul>
            </div>
        </div>
        <div class="col-sm-10" id="manage-staff-content">
<div>
    <?php echo Html::a(Yii::t('m-restaurant','Add Staff'), ['all-rmanagers', 'rid'=>$id['Restaurant_ID']], ['class'=>'raised-btn main-btn']) ?>
</div>
<br>
    <table class = "table table-restaurant-staff">
        <thead>
            <tr>
                <th colspan = 2> <?=Yii::t('common','Username')?> </th>
                <th> <?= Yii::t('m-restaurant','Position')?> </th>
                <th colspan = 2> <?= Yii::t('m-restaurant','Date Time Added')?> </th>
            </tr>
        </thead>
        <?php 
        if ($id['Restaurant_Manager'] == Yii::$app->user->identity->username)
        {
            foreach ($rstaff as $data)
            {
                $pic = Userdetails::find()->where('User_Username = :uname',[':uname'=>$data['User_Username']])->one();
                if(is_null($pic['User_PicPath']))
                {
                    $picpath = "/hamstereat/frontend/web/imageLocation/DefaultPic.png";
                }
                else
                {
                    $picpath = $pic['User_PicPath'];
                }
                $dt = new DateTime('@'.$data['Rmanager_DateTimeAdded']);
                $dt->setTimeZone(new DateTimeZone('Asia/Kuala_Lumpur'));

                if ($data['User_Username'] == $id['Restaurant_Manager']):?> 
                    <tr>
                        <td><?= Html::img($picpath, ['class' => 'img-responsive', 'style'=>'height:40px; width:55px; margin:auto;']) ?></td>
                        <td data-th='Username'><?= $data['User_Username'] ?></td>
                        <td data-th='Position'><?= Yii::t('m-restaurant',$data['RmanagerLevel_Level']) ?></td>
                        <td data-th='Date Time Added'><?= $dt->format('d-m-Y H:i:s') ?></td>
                   </tr>

                <?php elseif ($data['User_Username'] == Yii::$app->user->identity->username): ?>

                    <tr>
                        <td><?= Html::img($picpath, ['class' => 'img-responsive', 'style'=>'height:40px; width:55px; margin:auto;'])?></td>
                        <td data-th='Username'><?= $data['User_Username'] ?></td>
                        <td data-th='Position'><?= $data['RmanagerLevel_Level']?></td>
                        <td data-th='Date Time Added'><?= $dt->format('d-m-Y H:i:s')?></td>
                        <td><?= Html::a(Yii::t('common','Leave'), ['delete-restaurant-staff', 'rid'=>$data['Restaurant_ID'], 'uname'=>$data['User_Username']], ['class'=>'raised-btn secondary-btn'])?></td>
                    </tr>
                
                <?php else :?>
                    <tr>
                        <td><?= Html::img($picpath, ['class' => 'img-responsive', 'style'=>'height:40px; width:55px; margin:auto;'])?></td>
                        <td data-th='Username'><?= $data['User_Username']?></td>
                        <td data-th='Position'><?= $data['RmanagerLevel_Level']?></td>
                        <td data-th='Date Time Added'><?= $dt->format('d-m-Y H:i:s')?></td>
                        <td><?= Html::a('Delete', ['delete-restaurant-staff', 'rid'=>$data['Restaurant_ID'], 'uname'=>$data['User_Username']], ['class'=>'raised-btn secondary-btn','data-confirm'=>Yii::t('m-restaurant','Are you sure you want to remove?')])?></td>
                    </tr>
                
            <?php endif; }
        }
        elseif ($me['RmanagerLevel_Level'] == 'Owner')
        {
            foreach ($rstaff as $data)
            {
                $pic = Userdetails::find()->where('User_Username = :uname',[':uname'=>$data['User_Username']])->one();
                if(is_null($pic['User_PicPath']))
                {
                    $picpath = "/hamstereat/frontend/web/imageLocation/DefaultPic.png";
                }
                else
                {
                    $picpath = $pic['User_PicPath'];
                }

                if ($data['User_Username'] == Yii::$app->user->identity->username)
                {
                    echo "<tr>";
                        echo "<td>".Html::img($picpath, ['class' => 'img-responsive', 'style'=>'height:40px; width:55px; margin:auto;'])."</td>";
                        echo "<td data-th='Username'>".$data['User_Username']."</td>";
                        echo "<td data-th='Position'>".$data['RmanagerLevel_Level']."</td>";
                        $dt = new DateTime('@'.$data['Rmanager_DateTimeAdded']);
                        $dt->setTimeZone(new DateTimeZone('Asia/Kuala_Lumpur'));
                        echo "<td data-th='Date Time Added'>".$dt->format('d-m-Y H:i:s')."</td>"; //Returns IST
                        echo "<td>".Html::a(Yii::t('common','Leave'), ['delete-restaurant-staff', 'rid'=>$data['Restaurant_ID'], 'uname'=>$data['User_Username']], ['class'=>'raised-btn secondary-btn'])."</td>";
                    echo "</tr>";
                }
                elseif ($data['User_Username'] == $id['Restaurant_Manager'] || $data['RmanagerLevel_Level'] == 'Owner')
                {
                    echo "<tr>";
                        echo "<td>".Html::img($picpath, ['class' => 'img-responsive', 'style'=>'height:40px; width:55px; margin:auto;'])."</td>";
                        echo "<td data-th='Username'>".$data['User_Username']."</td>";
                        echo "<td data-th='Position'>".$data['RmanagerLevel_Level']."</td>";
                        $dt = new DateTime('@'.$data['Rmanager_DateTimeAdded']);
                        $dt->setTimeZone(new DateTimeZone('Asia/Kuala_Lumpur'));
                        echo "<td data-th='Date Time Added'>".$dt->format('d-m-Y H:i:s')."</td>"; //Returns IST
                    echo "</tr>";
                }
                else
                {
                    echo "<tr>";
                        echo "<td>".Html::img($picpath, ['class' => 'img-responsive', 'style'=>'height:40px; width:55px; margin:auto;'])."</td>";
                        echo "<td data-th='Username'>".$data['User_Username']."</td>";
                        echo "<td data-th='Position'>".$data['RmanagerLevel_Level']."</td>";
                        $dt = new DateTime('@'.$data['Rmanager_DateTimeAdded']);
                        $dt->setTimeZone(new DateTimeZone('Asia/Kuala_Lumpur'));
                        echo "<td data-th='Date Time Added'>".$dt->format('d-m-Y H:i:s')."</td>"; //Returns IST
                        echo "<td>".Html::a('Delete', ['delete-restaurant-staff', 'rid'=>$data['Restaurant_ID'], 'uname'=>$data['User_Username']], ['class'=>'raised-btn secondary-btn','data-confirm'=>Yii::t('m-restaurant','Are you sure you want to remove?')])."</td>";
                    echo "</tr>";
                }
            }
        }
        else
        {
            foreach ($rstaff as $data)
            {
                $pic = Userdetails::find()->where('User_Username = :uname',[':uname'=>$data['User_Username']])->one();
                if(is_null($pic['User_PicPath']))
                {
                    $picpath = "/hamstereat/frontend/web/imageLocation/DefaultPic.png";
                }
                else
                {
                    $picpath = $pic['User_PicPath'];
                }

                if ($data['User_Username'] == Yii::$app->user->identity->username)
                {

                    echo "<tr>";
                        echo "<td>".Html::img($picpath, ['class' => 'img-responsive', 'style'=>'height:40px; width:55px; margin:auto;'])."</td>";
                        echo "<td data-th='Username'>".$data['User_Username']."</td>";
                        echo "<td data-th='Position'>".$data['RmanagerLevel_Level']."</td>";
                        $dt = new DateTime('@'.$data['Rmanager_DateTimeAdded']);
                        $dt->setTimeZone(new DateTimeZone('Asia/Kuala_Lumpur'));
                        echo "<td data-th='Date Time Added'>".$dt->format('d-m-Y H:i:s')."</td>"; //Returns IST
                        echo "<td>".Html::a(Yii::t('common','Leave'), ['delete-restaurant-staff', 'rid'=>$data['Restaurant_ID'], 'uname'=>$data['User_Username']], ['class'=>'raised-btn secondary-btn'])."</td>";
                    echo "</tr>";
                }
                elseif ($data['RmanagerLevel_Level'] == 'Owner' || $data['RmanagerLevel_Level'] == 'Manager')
                {
                    echo "<tr>";
                        echo "<td>".Html::img($picpath, ['class' => 'img-responsive', 'style'=>'height:40px; width:55px; margin:auto;'])."</td>";
                        echo "<td data-th='Username'>".$data['User_Username']."</td>";
                        echo "<td data-th='Position'>".$data['RmanagerLevel_Level']."</td>";
                        $dt = new DateTime('@'.$data['Rmanager_DateTimeAdded']);
                        $dt->setTimeZone(new DateTimeZone('Asia/Kuala_Lumpur'));
                        echo "<td data-th='Date Time Added'>".$dt->format('d-m-Y H:i:s')."</td>"; //Returns IST
                    echo "</tr>";
                }
                else
                {
                    echo "<tr>";
                        echo "<td>".Html::img($picpath, ['class' => 'img-responsive', 'style'=>'height:40px; width:55px; margin:auto;'])."</td>";
                        echo "<td data-th='Username'>".$data['User_Username']."</td>";
                        echo "<td data-th='Position'>".$data['RmanagerLevel_Level']."</td>";
                        $dt = new DateTime('@'.$data['Rmanager_DateTimeAdded']);
                        $dt->setTimeZone(new DateTimeZone('Asia/Kuala_Lumpur'));
                        echo "<td data-th='Date Time Added'>".$dt->format('d-m-Y H:i:s')."</td>"; //Returns IST
                        echo "<td>".Html::a('Delete', ['delete-restaurant-staff', 'rid'=>$data['Restaurant_ID'], 'uname'=>$data['User_Username']], ['class'=>'raised-btn secondary-btn','data-confirm'=>Yii::t('m-restaurant','Are you sure you want to remove?')])."</td>";
                    echo "</tr>";
                }
            }
        }
         ?>
    </table>
        </div>
    </div>
</div>