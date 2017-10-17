<?php

namespace frontend\modules\UserPackage\controllers;

use yii\web\Controller;
use Yii;

class SelectionTypeController extends Controller
{
	/*
	* use for detect unset empty check
	* use for detect total quantity and select quantity matched
	* use for detect empty selection
	*/
	public static function detectEmptyQuantity($post)
	{
		$totalQuantity ="";
        foreach($post['UserPackageSelectionType'] as $k=>$selection)
        {
            if(empty($selection['check']))
            {
                unset($post['UserPackageSelectionType'][$k]);
            }
            $totalQuantity += $selection['quantity'];
        }

        if($totalQuantity != $post['UserPackageDetail']['quantity'])
        {
            Yii::$app->session->setFlash('warning', "Total Quantity does not match with selected quantity");
            return 1;    
        }
        return $post;
	}
}