<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Json;
use common\models\OrderCartNickName;
use common\models\Cart\Cart;

class OrderNickNameController extends Controller
{
	/*
	* pop up model for order nickname
	* quantity and cid base on cookie
	*/
	public function actionConvertToCookie()
	{
		$model = new OrderCartNickName;

		return $this->renderAjax('convert',['model'=>$model]);
	}

	/*
	* post action for save data
	*/
	public function actionGenerateNick()
	{
		$data = array(
			'value'=>-1,
			'message'=>"",
		);
		
		$modelArray = self::modelValidate();

		if($modelArray['value'] != 1)
		{
			return Json::encode($modelArray);
		}
		$model = $modelArray['message'];

		$transaction = Yii::$app->db->beginTransaction();
        $valid = true; 
		try{
			foreach($model as $value)
			{
				$value->save();
				if($value->save())
                {
                    $valid = true && $valid;
                }
                else
                {
                    break;
                }
			}

			if($valid)
            {
                $transaction->commit();
                $data['message'] = "Sucess Add Nick Name";
               	$data['value'] = 1;
                return JSON::encode($data);
            }
            $transaction->rollBack();
		}

		catch(Exception $e)
        {
            $transaction->rollBack();
        }

        $data['message'] = "Something Went Wrong!!";
        
        return JSON::encode($data);
	}

	/*
	* validate nickname data leave anything 
	*/
	protected static function modelValidate()
	{
		$data = array(
			'value'=>-1,
			'message'=>"",
		);

		$model = array();
		$post = Yii::$app->request->post();

		if(empty($post['OrderCartNickName']))
		{
			$data['message'] ="SomeThing Went Wrong!!";
			return $data;
		}

		$modelData = self::createModel();

		if(empty($modelData['message']))
		{
			$data['value'] = 2;
			$data['message'] = "You Have Not Adding Any Nick Name For The Order";
			return $data;
		}
		
		if(!$modelData['value'])
		{
			return $modelData;
		}
		
		$model = $modelData['message'];
		
		$valid = self::detectQuantity($model);
		if($valid)
		{
			$data['value'] =1;
			$data['message'] = $model;
			return $data;
		}
		$data['message'] = "Something Went Wrong!!";
		return $data;
	}

	/*
	* generate mutiple model
	* and validate cookie id with pass tid
	*/
	protected static function createModel()
	{
		$data = array(
			'value'=>false,
			'message'=>"",
		);
		$model = array();
		$post = Yii::$app->request->post();
		$nickname = $post['OrderCartNickName'];
		$valid = true;

		foreach ($nickname as $key => $value) 
		{

			if(!empty($value['nickname']))
			{
				$postData['OrderCartNickName'] = $value;
				$n = new OrderCartNickName;
				$n->load($postData);
				$n->type = "1";
				$cookies = Yii::$app->request->cookies;
				$nick = $cookies->getValue('cartNickName');
				if($nick['id'] != $n->tid)
				{
					$data['message'] = "Something Went Wrong";
					return $data;
				}
				$valid = $n->validate() && $valid;
				$model[] = $n;
			}
			
		}
		
		$data['value'] = $valid;
		$data['message'] = $model;
		return $data;
	}

	/*
	* count quantity base on cart and nick name quantity is not more then
	*/
	protected static function detectQuantity($model)
	{
		$cookies = Yii::$app->request->cookies;
		$nick = $cookies->getValue('cartNickName');
		
		$countDataBase = OrderCartNickName::find()->where('type = 1 and tid = :tid',[':tid'=>$nick['id']])->count() + count($model);

		$cart = Cart::find()->where('id = :id and uid = :uid',[':id'=>$nick['id'],':uid'=> Yii::$app->user->identity->id])->one();
		
		if(empty($cart))
		{
			return false;
		}
		
		if($countDataBase <= $cart->quantity )
		{
			return true;
		}
		return false;
	}
}