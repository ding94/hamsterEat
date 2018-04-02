<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class='row'>
	<div class = "col-md-5">
		<h2><b>Companies List</b></h2>
		<table class='table table-hover'>
			<?php foreach ($list as $k => $company): ?>
				<tr>
					<td><?= $company['name'] ?></td>
					<td><?= Html::a('<font style="color:blue">Register</font>',['/company/register-employee','cid'=>$company['id']], ['data-confirm'=>"Do you want to register as this company's employee?"]) ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
</div>
	
