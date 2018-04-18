<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\User;
use iutbay\yii2fontawesome\FontAwesome as FA;
use backend\assets\CompanyAsset;
use yii\bootstrap\Modal;
CompanyAsset::register($this);

$this->title = 'Company order list';
$this->params['breadcrumbs'][] = $this->title;
?>

<body>
    <div class = 'container'>
        <div class='row'>
            <div class='col-md-10'>
                <table class='table table-hover' style="background-color: white">
                    <tr>
                        <td><b>Company Ordered Item</b></td>
                        <td><b>Get PDF List</b></td>
                    </tr>
                    <?php foreach ($data as $k => $company): ?>
                        <tr>
                            <td><?= $company['name'] ?></td>
                            <td><?= Html::a('Get Pdf File',['/company/get-order-pdf','cid'=>$company['id']],['class'=>'btn btn-primary']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</body>