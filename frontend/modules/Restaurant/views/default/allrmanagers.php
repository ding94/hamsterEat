<?php
use yii\helpers\Html;
use common\models\user\Userdetails;
use common\models\Rmanagerlevel;
use yii\bootstrap\ActiveForm;
?>

<div class="container">
<br>
<br>
<br>
    <?php
    {
        //var_dump($num);exit;
        if ($num == "1")
        {
            echo "<h1>Add an Owner to Your Restaurant</h1>";

            $form = ActiveForm::begin(['id' => 'dynamic-form']);
            echo "<table class = table table-restaurant-staff style=width:35%; margin:auto;>";
            echo "<td>".$form->field($food, 'Nickname')->textInput(['style'=>'width:300px', 'placeholder' => "Search Restaurant Managers"])->label('')."</td>";
            echo "<td>".Html::submitButton('Search', ['class' => 'btn btn-primary', 'name' => 'search-button', 'style'=>'margin-top:19px;'])."</td>";
            echo "</table>";
            ActiveForm::end();

            echo "<table class = 'table table-restaurant-details'>";
            echo "<tr>";
                echo "<th><center> Picture </th>";
                echo "<th><center> Username </th>";
                echo "<th><center> Full Name </th>";
                echo "<th><center> Add as Owner </th>";
            echo "</tr>";

            foreach ($allrmanagers as $data) :
            echo "<br>";
            echo "<br>";
            echo "<tr>";
            $find = Rmanagerlevel::find()->where('Restaurant_ID = :rid and User_Username = :uname',[':rid'=>$rid, ':uname'=>$data['username']])->one();
            if (is_null($find))
            {
                $name = Userdetails::find()->where('User_Username = :uname',[':uname'=>$data['username']])->one();
                if(is_null($name['User_PicPath']))
                {
                    $picpath = "DefaultPic.png";
                }
                else
                {
                    $picpath = $name['User_PicPath'];
                }
                echo "<td>".Html::img('@web/imageLocation/'.$picpath, ['class' => 'img-responsive', 'style'=>'height:40px; width:50px; margin:auto;'])."</td>";
                echo "<td><center>".$data['username']."</td>";
                echo "<td><center>".$name['User_FirstName'].' '.$name['User_LastName']."</td>";
                echo "<td><center>".Html::a('Add', ['add-as-owner', 'rid'=>$rid, 'uname'=>$data['username'], 'num'=>$num], ['class'=>'btn btn-primary'])."</td>";
                echo "</tr>";
            }
            
            endforeach;
            
            echo "</table>";
        }
        elseif ($num == "2")
        {
            echo "<h1>Add a Manager to Your Restaurant</h1>";
            
            $form = ActiveForm::begin(['id' => 'dynamic-form']);
            echo "<table class = table table-restaurant-staff style=width:35%; margin:auto;>";
            echo "<td>".$form->field($food, 'Nickname')->textInput(['style'=>'width:300px', 'placeholder' => "Search Restaurant Managers"])->label('')."</td>";
            echo "<td>".Html::submitButton('Search', ['class' => 'btn btn-primary', 'name' => 'search-button', 'style'=>'margin-top:19px;'])."</td>";
            echo "</table>";
            ActiveForm::end();

            echo "<table class = 'table table-restaurant-details'>";
            echo "<tr>";
                echo "<th><center> Picture </th>";
                echo "<th><center> Username </th>";
                echo "<th><center> Full Name </th>";
                echo "<th><center> Add as Manager </th>";
            echo "</tr>";

            foreach ($search as $data) :
            echo "<br>";
            echo "<br>";

            echo "<tr>";
            $find = Rmanagerlevel::find()->where('Restaurant_ID = :rid and User_Username = :uname',[':rid'=>$rid, ':uname'=>$data['username']])->one();
            if (is_null($find))
            {
                $name = Userdetails::find()->where('User_Username = :uname',[':uname'=>$data['username']])->one();
                if(is_null($name['User_PicPath']))
                {
                    $picpath = "DefaultPic.png";
                }
                else
                {
                    $picpath = $name['User_PicPath'];
                }
                echo "<td>".Html::img('@web/imageLocation/'.$picpath, ['class' => 'img-responsive', 'style'=>'height:40px; width:50px; margin:auto;'])."</td>";
                echo "<td><center>".$data['username']."</td>";
                echo "<td><center>".$name['User_FirstName'].' '.$name['User_LastName']."</td>";
                echo "<td><center>".Html::a('Add', ['add-as-manager', 'rid'=>$rid, 'uname'=>$data['username'], 'num'=>$num], ['class'=>'btn btn-primary'])."</td>";
                echo "</tr>";
            }
            
            endforeach;
            
            echo "</table>";
        }
        else
        {
            echo "<h1>Add an Operator to Your Restaurant</h1>";
            
            $form = ActiveForm::begin(['id' => 'dynamic-form']);
            echo "<table class = table table-restaurant-staff style=width:35%; margin:auto;>";
            echo "<td>".$form->field($food, 'Nickname')->textInput(['style'=>'width:300px', 'placeholder' => "Search Restaurant Managers"])->label('')."</td>";
            echo "<td>".Html::submitButton('Search', ['class' => 'btn btn-primary', 'name' => 'search-button', 'style'=>'margin-top:19px;'])."</td>";
            echo "</table>";
            ActiveForm::end();

            echo "<table class = 'table table-restaurant-details'>";
            echo "<tr>";
                echo "<th><center> Picture </th>";
                echo "<th><center> Username </th>";
                echo "<th><center> Full Name </th>";
                echo "<th><center> Add as Operator </th>";
            echo "</tr>";

            foreach ($search as $data) :
            echo "<br>";
            echo "<br>";

            echo "<tr>";
            $find = Rmanagerlevel::find()->where('Restaurant_ID = :rid and User_Username = :uname',[':rid'=>$rid, ':uname'=>$data['username']])->one();
            if (is_null($find))
            {
                $name = Userdetails::find()->where('User_Username = :uname',[':uname'=>$data['username']])->one();
                if(is_null($name['User_PicPath']))
                {
                    $picpath = "DefaultPic.png";
                }
                else
                {
                    $picpath = $name['User_PicPath'];
                }
                echo "<td>".Html::img('@web/imageLocation/'.$picpath, ['class' => 'img-responsive', 'style'=>'height:40px; width:50px; margin:auto;'])."</td>";
                echo "<td><center>".$data['username']."</td>";
                echo "<td><center>".$name['User_FirstName'].' '.$name['User_LastName']."</td>";
                echo "<td><center>".Html::a('Add', ['add-as-operator', 'rid'=>$rid, 'uname'=>$data['username'], 'num'=>$num], ['class'=>'btn btn-primary'])."</td>";
                echo "</tr>";
            }
            
            endforeach;
            
            echo "</table>";
        }
}
    ?>
</div>
