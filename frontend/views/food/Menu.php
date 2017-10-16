<?php
use yii\helpers\Html;
use frontend\controllers\CartController;
$this->title = $rname."'s"." Menu";
?>

<div class="container">
	<div class="col-md-8 col-md-offset-1" id="menu">
		<?php if ($page == 'menu'){ ?>
        <h1><?php echo $rname."'s";?> Menu</h1>
        <?php } else { ?>
        <h1><?php echo $rname."'s";?> Menu Recycle Bin</h1>
        <?php } ?>
            <?php
            {   
                echo "<br>";
                echo "<table class = 'table table-bordered table-list'>";
                 echo "<td><center>".Html::a('Insert Food', ['/food/insert-food','rid'=>$rid], ['class'=>'btn btn-primary'])."</td>";
                 if ($page == 'menu')
                 {
                    echo "<td><center>".Html::a('Recycle Bin', ['/food/recycle-bin','rid'=>$rid,'page'=>'recyclebin'], ['class'=>'btn btn-primary'])."</td>";
                 }
                 else
                 {
                    echo "<td><center>".Html::a('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Menu&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', ['/food/menu','rid'=>$rid,'page'=>'menu'], ['class'=>'btn btn-primary'])."</td>";
                 }
                 echo "</table>";
                 echo "<br>";
                    echo "<table class = 'table table-striped table-bordered table-list'>";
                 echo "<tr>";
                 echo"<th> Food Picture </th><th> Food Name </th><th> Food Type </th><th> Food Price (RM) </th>";
                  echo "<th class='text-center'><em class='fa fa-cog'></em></th>";
                  echo "</tr>";
            foreach($menu as $menu) {
              
                echo "<tr>";
                 $picpath = $menu['PicPath'];
                 echo '<td>' ?> <?php echo Html::img('@web/imageLocation/foodImg/'.$picpath, ['class' => 'pull-left img-responsive','style'=>'height:200px; width:300px; margin:auto;']) ?> <?php echo "</td>";
  
                echo '<td>'.$menu['Name'].'</td>';
                echo "<td><center>";
                foreach($menu['foodType']as $type) :
                    echo $type['Type_Desc'];
                    echo "<br>";
                endforeach;
                echo "</td>";
                echo '<td>'.CartController::actionRoundoff1decimal($menu['Price']).'</td>';
                echo '<td>';
                echo "<center>".Html::a('', ['/food/edit-food','id'=>$menu['Food_ID']], ['class'=>'btn btn-default fa fa-pencil']);
                if ($menu['foodStatus']['Status'] == true)
                {
                    echo "<center>".Html::a('', ['delete','id'=>$menu['Food_ID'],'rid'=>$menu['Restaurant_ID'],'page'=>$page], ['class'=>'btn btn-danger fa fa-trash','data-confirm'=>'Are you sure you want to delete?']);
                }
                elseif ($menu['foodStatus']['Status'] == false)
                {
                    echo "<center>".Html::a('', ['delete','id'=>$menu['Food_ID'],'rid'=>$menu['Restaurant_ID'],'page'=>$page], ['class'=>'btn btn-default fa fa-undo']);
                    echo "<center>".Html::a('', ['delete-permanent','id'=>$menu['Food_ID'],'rid'=>$menu['Restaurant_ID'],'page'=>$page], ['class'=>'btn btn-danger fa fa-remove','data-confirm'=>'Are you sure you want to delete?']);
                }
                echo'</td></tr>';
                echo "</tr>";
                
            }
                 
                echo "</table>";
                
                
            }
                   ?>
            </div>
            </div>
        