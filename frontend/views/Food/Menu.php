<?php
use yii\helpers\Html;
?>

<div class="container">
	<div class="col-md-8 col-md-offset-1" id="menu">
		<h1>My Menu</h1>
            <?php
            {

               
                    echo "<table class = 'table table-striped table-bordered table-list'>";
                 echo "<tr>";
                 echo"<th> Food Picture </th><th> Food Name </th><th> Food Type </th><th> Food Price (RM) </th>";
                  echo "<th class='text-center'><em class='fa fa-cog'></em></th>";
                  echo "</tr>";
            foreach($menu as $menu) {
              

                echo "<tr>";
                 $picpath = $menu['Food_FoodPicPath'];
                 echo '<td>' ?> <?php echo Html::img('@web/imageLocation/'.$picpath, ['class' => 'pull-left img-responsive','style'=>'height:200px; width:300px; margin:auto;']) ?> <?php echo "</td>";
  
                echo '<td>'.$menu['Food_Name'].'</td>';
                echo '<td>'.$menu['Food_Type'].'</td>';
                echo '<td>'.$menu['Food_Price'].'</td>';
                echo '<td>';
                echo "<center>".Html::a('', ['/food/edit-food','id'=>$menu['Food_ID']], ['class'=>'btn btn-default fa fa-pencil']);
                echo "<center>".Html::a('', ['delete','id'=>$menu['Food_ID'],'rid'=>$menu['Restaurant_ID']], ['class'=>'btn btn-danger fa fa-trash']);
                echo'</td></tr>';
                echo "</tr>";
            }
                echo "</table>";
            }
                   ?>
                
                   
       
            </div>
            </div>
        