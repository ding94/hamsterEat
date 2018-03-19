<?php
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $user common\models\User */
$confirmLink = Url::to(['/order/order-details','did'=>$did],true);
?>

<?= $message ;?>
 More Detail <?= $confirmLink ?>