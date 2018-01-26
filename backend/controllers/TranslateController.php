<?php

namespace backend\controllers;
use Yii;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\web\Controller;
use common\models\translate\Sentences;
use common\models\translate\SentencesSource;

Class TranslateController extends Controller
{
	public function actionIndex($case=1)
	{
		$searchModel = new SentencesSource();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$case);

        return $this->render('index',['dataProvider' => $dataProvider , 'searchModel' => $searchModel,'case'=>$case]);
	}

	public function actionAddtranslation($id,$language)
	{
		$sen = Sentences::find()->where('sentences.id=:id AND language=:l',[':id'=>$id,':l'=>$language])->joinWith('id0')->one();
		if (empty($sen)) {
			$sen = new Sentences;
			$sen['id'] = $id;
			$sen['language'] = $language;
		}

		if ($post = Yii::$app->request->post()) {
			$sen->load($post);
			if ($sen->validate()) {
				$sen->save();
				//Yii::$app->session->setFlash('success','Success!');
				return $this->redirect(Yii::$app->request->referrer);
			}
		}
		
		return $this->renderAjax('translation',['sen'=>$sen]);
	}

	public function actionFaq()
	{
		$searchModel = new SentencesSource();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,3);

        return $this->render('index',['dataProvider' => $dataProvider , 'searchModel' => $searchModel]);
	}
}