<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use common\models\PriceConfig;
use common\models\ActiveDataPrivider;
class PriceController extends Controller
{
	public function actionIndex()
	{
		$searchModel = new PriceConfig();
       	$dataProvider = $searchModel->search(Yii::$app->request->queryParams,1);
       	
        return $this->render('index',['dataProvider'=>$dataProvider, 'searchModel'=>$searchModel]);
	}

	public function actionEditvalue($id)
	{
		$conf = PriceConfig::findOne($id);

		if ($post=Yii::$app->request->post()) {
			$conf['value'] = $post['PriceConfig']['value'];
			if ($conf->validate()) {
				//$conf->save();
				Yii::$app->session->setFlash('success','Saved!');
				return $this->redirect(Yii::$app->request->referrer);
			}
			else{
				Yii::$app->session->setFlash('error','Save Failed!');
				return $this->redirect(Yii::$app->request->referrer);
			}
		}

		return $this->renderAjax('editvalue',['conf'=>$conf]);
	}
}