<?php

namespace frontend\modules\notification\controllers;

use yii\web\Controller;

/**
 * Default controller for the `notification` module
 */
class NoticController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
    	self::centerNotic(1,3,240);exit;
        return $this->render('index');
    }

    public static function centerNotic($tid,$sid,$id)
	{
		switch ($tid) {
			case 1:
				OrderController::createUserNotic($tid,$sid,$id);
				break;
			case 2:
				OrderController::createUserNotic($tid,$sid,$id);
			default:
				# code...
				break;
		}
		
	}
}
