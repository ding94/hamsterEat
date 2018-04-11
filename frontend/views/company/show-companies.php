<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class='row'>
	<div class = "col-md-5" style='background-color: white;margin-left: 25%'>
		<h2><b>Companies List</b></h2>
		<table class='table table-hover col-md-6'>
			<?php foreach ($list as $k => $company): ?>
				<tr>
					<td><?= $company['name'] ?></td>
					<td><?= Html::a('<font">Register</font>',['/company/register-employee','cid'=>$company['id']], ['data-confirm'=>"Do you want to register as this company's employee?",'class'=>'btn btn-success']) ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
</div>
	
