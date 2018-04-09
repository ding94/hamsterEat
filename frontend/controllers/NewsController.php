<?php

namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl; 
use common\models\News;
use yii\data\ActiveDataProvider;

class NewsController extends CommonController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

	public function actionNewsAll()
	{
		// $query = News::find()->orderBy('id DESC')->all();
		// var_dump($query);exit;
		$dataProvider = new ActiveDataProvider([
            'query' => News::find()->orderBy('id DESC'),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
		return $this->render('newsall',['dataProvider'=>$dataProvider]);
	}

	public function actionNews($id)
	{
		$model=News::find()->orderBy('id DESC')->limit(10)->all();
		$news=News::find()->where('id = :id',[':id' => $id])->one();
		return $this->renderPartial('news',['model'=>$model,'id'=>$id,'news'=>$news]);
	}

    public function actionNewsSimple($id)
    {
        $model=News::find()->orderBy('id DESC')->limit(10)->all();
        $news=News::find()->where('id = :id',[':id' => $id])->one();
        return $this->renderPartial('newssimple',['model'=>$model,'id'=>$id,'news'=>$news]);
    }
}
