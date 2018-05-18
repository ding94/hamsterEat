<?php

namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl; 
use common\models\News;
use yii\web\Cookie;
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
                    [
                        'actions'=>['news-cookie'],
                        'allow'=>true,
                        'roles'=>['?','@'],
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
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $cookies = Yii::$app->request->cookies;
        if (!empty($cookies['language'])) {
            if ($cookies['language']->value == 'zh') {
                $language = 'zhText';
            }
            else{
                $language = 'enText';
            }
        }
        else{
            $language = 'enText';
        }

        $model=News::find()->orderBy('news.id DESC')->joinWith('enText','zhText')->limit(10)->all();
        $news=News::find()->andWhere(['<=','startTime',date('Y-m-d H:i:s')])->andWhere(['>=','endTime',date('Y-m-d H:i:s')])->orderBy('news.startTime DESC')->all();
        return $this->renderPartial('newssimple',['model'=>$model,'id'=>$id,'language'=>$language,'news'=>$news]);
    }

    public function actionNewsCookie()
    {    
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $news=News::find()->andWhere(['<=','startTime',date('Y-m-d H:i:s')])->andWhere(['>=','endTime',date('Y-m-d H:i:s')])->orderBy('news.startTime DESC')->one();
        
        $cookies = Yii::$app->request->cookies;

        if (empty($cookies->getValue('news-read')) || (($cookies->getValue('news-read')) != $news->id)){
            $cookie =  new Cookie([
                'name' => 'news-read',
                'value' => $news->id,
                'expire' => strtotime(date('Y-m-d 23:59:59')) ,
            ]);
            \Yii::$app->getResponse()->getCookies()->add($cookie);
        }
    }
}
