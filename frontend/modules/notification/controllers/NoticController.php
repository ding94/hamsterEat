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
        return $this->render('index');
    }

    public static function centerNotic($type,$tid,$id)
	{
		switch ($type) {
			case 1:
				OrderController::createUserNotic($type,$tid,$id);
				break;
			case 2:
				OrderController::createUserNotic($type,$tid,$id);
			default:
				# code...
				break;
		}
		
	}
}
