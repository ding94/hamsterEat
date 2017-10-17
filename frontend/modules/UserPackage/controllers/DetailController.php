<?php

namespace frontend\modules\UserPackage\controllers;

use yii\web\Controller;
use Yii;
use common\models\Package\UserPackageDetail;

class DetailController extends Controller
{
	/*
	*
	*/
	public static function newPackageDetail($post,$pid)
	{
		$data = new UserPackageDetail;
		$data->load($post);
		$data->pid = $pid;
		return $data;
	}
}