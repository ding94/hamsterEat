<?php
use yii\helpers\Html;
$this->title = "Restaurant Menu";
?>

<div class="container">
	<div class="col-md-8 col-md-offset-1" id="menu">
		<h1>My Menu</h1>
            <?php
            {
                 echo "<center>".Html::a('Insert Food', ['/food/insert-food','rid'=>$rid], ['class'=>'btn btn-primary']);
                 echo "<br>";
                 echo "<br>";
                 echo "<br>";
                    echo "<table class = 'table table-striped table-bordered table-list'>";
                 echo "<tr>";
                 echo"<th> Food Picture </th><th> Food Name </th><th> Food Type </th><th> Food Price (RM) </th>";
                  echo "<th class='text-center'><em class='fa fa-cog'></em></th>";
                  echo "</tr>";
            foreach($menu as $menu) {
              

                echo "<tr>";
                 $picpath = $menu['PicPath'];
                 echo '<td>' ?> <?php echo Html::img('@web/imageLocation/'.$picpath, ['class' => 'pull-left img-responsive','style'=>'height:200px; width:300px; margin:auto;']) ?> <?php echo "</td>";
  
                echo '<td>'.$menu['Name'].'</td>';
                echo "<td><center>";
                foreach($menu['foodType']as $type) :
                    echo $type['Type_Desc'];
                    echo "<br>";
                endforeach;
                echo "</td>";
                echo '<td>'.$menu['Price'].'</td>';
                echo '<td>';
                if ($menu['foodStatus']['Status'] == true)
                {
                    echo "<center>".Html::a('', ['delete','id'=>$menu['Food_ID'],'rid'=>$menu['Restaurant_ID']], ['class'=>'btn btn-danger fa fa-trash']);
                }
                elseif ($menu['foodStatus']['Status'] == false)
                {
                    echo "<center>".Html::a('', ['delete','id'=>$menu['Food_ID'],'rid'=>$menu['Restaurant_ID']], ['class'=>'btn btn-default fa fa-undo']);
                }
                echo "<center>".Html::a('', ['/food/edit-food','id'=>$menu['Food_ID']], ['class'=>'btn btn-default fa fa-pencil']);
                echo'</td></tr>';
                echo "</tr>";
                
            }
                 
                echo "</table>";
                
                
            }
                   ?>
            </div>
            </div>
        