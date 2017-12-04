<?php
use yii\helpers\Html;
use common\models\user\Userdetails;
use frontend\assets\ManageStaffAsset;
use kartik\widgets\Select2;

$this->title = "Manage ".$id['Restaurant_Name']."'s Staff";
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
                <ul id="manage-staff-nav" class="nav nav-pills nav-stacked">
                    <?php if ($me['RmanagerLevel_Level'] == 'Owner'){ ?>
                        <li role="presentation"><?php echo Html::a("View Earnings",['show-monthly-earnings', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                    <?php }
                    if ($me['RmanagerLevel_Level'] == 'Owner' || $me['RmanagerLevel_Level'] == 'Manager') { ?>
                        <li role="presentation"><?php echo Html::a("Edit Details",['edit-restaurant-details', 'rid'=>$rid, 'restArea'=>$id['Restaurant_AreaGroup'], 'areachosen'=>$id['Restaurant_Area'], 'postcodechosen'=>$id['Restaurant_Postcode']],['class'=>'btn-block'])?></li>
                        <li role="presentation" class="active"><?php echo Html::a("Manage Staffs",['manage-restaurant-staff', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                        <li role="presentation"><?php echo Html::a("Restaurants Orders",['/order/restaurant-orders', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                        <li role="presentation"><?php echo Html::a("Restaurants Orders History",['/order/restaurant-order-history', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                        <li role="presentation"><?php echo Html::a("Manage Menu",['/food/menu', 'rid'=>$rid,'page'=>'menu'],['class'=>'btn-block'])?></li>
                    <?php } elseif ($me['RmanagerLevel_Level'] == 'Operator'){ ?>
                        <li role="presentation"><?php echo Html::a("Restaurants Orders",['/order/restaurant-orders', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                        <li role="presentation"><?php echo Html::a("Restaurants Orders History",['/order/restaurant-order-history', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div class="col-sm-10" id="manage-staff-content">
<div>
    <?php echo Html::a('Add Staffs', ['all-rmanagers', 'rid'=>$id['Restaurant_ID']], ['class'=>'raised-btn main-btn']) ?>
</div>
<br>
    <table class = "table table-restaurant-staff">
        <thead>
            <tr>
                <th colspan = 2> Username </th>
                <th> Position </th>
                <th colspan = 2> Date Time Added </th>
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

                if ($data['User_Username'] == $id['Restaurant_Manager'])
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
                elseif ($data['User_Username'] == Yii::$app->user->identity->username)
                {
                    echo "<tr>";
                        echo "<td>".Html::img($picpath, ['class' => 'img-responsive', 'style'=>'height:40px; width:55px; margin:auto;'])."</td>";
                        echo "<td data-th='Username'>".$data['User_Username']."</td>";
                        echo "<td data-th='Position'>".$data['RmanagerLevel_Level']."</td>";
                        $dt = new DateTime('@'.$data['Rmanager_DateTimeAdded']);
                        $dt->setTimeZone(new DateTimeZone('Asia/Kuala_Lumpur'));
                        echo "<td data-th='Date Time Added'>".$dt->format('d-m-Y H:i:s')."</td>"; //Returns IST
                        echo "<td>".Html::a('Leave', ['delete-restaurant-staff', 'rid'=>$data['Restaurant_ID'], 'uname'=>$data['User_Username']], ['class'=>'raised-btn secondary-btn'])."</td>";
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
                        echo "<td>".Html::a('Delete', ['delete-restaurant-staff', 'rid'=>$data['Restaurant_ID'], 'uname'=>$data['User_Username']], ['class'=>'raised-btn secondary-btn','data-confirm'=>'Are you sure you want to remove?'])."</td>";
                    echo "</tr>";
                }
            }
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
                        echo "<td>".Html::a('Leave', ['delete-restaurant-staff', 'rid'=>$data['Restaurant_ID'], 'uname'=>$data['User_Username']], ['class'=>'raised-btn secondary-btn'])."</td>";
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
                        echo "<td>".Html::a('Delete', ['delete-restaurant-staff', 'rid'=>$data['Restaurant_ID'], 'uname'=>$data['User_Username']], ['class'=>'raised-btn secondary-btn','data-confirm'=>'Are you sure you want to remove?'])."</td>";
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
                        echo "<td>".Html::a('Leave', ['delete-restaurant-staff', 'rid'=>$data['Restaurant_ID'], 'uname'=>$data['User_Username']], ['class'=>'raised-btn secondary-btn'])."</td>";
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
                        echo "<td>".Html::a('Delete', ['delete-restaurant-staff', 'rid'=>$data['Restaurant_ID'], 'uname'=>$data['User_Username']], ['class'=>'raised-btn secondary-btn','data-confirm'=>'Are you sure you want to remove?'])."</td>";
                    echo "</tr>";
                }
            }
        }
         ?>
    </table>
        </div>
    </div>
</div>