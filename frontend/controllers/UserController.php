<?php
namespace frontend\controllers;

use yii\web\Controller;
use common\models\User;

class SiteController extends Controller
{
    public function actionUserProfile()
    {
        return $this->render('userprofile');
    }    
}

?>