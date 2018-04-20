<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Json;
use common\models\OrderCartNickName;
use common\models\Cart\Cart;
use yii\helpers\Url;

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
                $data['message'] = Yii::t('cart','Success Add Order Nickname');
               	$data['value'] = 1;
                return JSON::encode($data);
            }
            $transaction->rollBack();
		}

		catch(Exception $e)
        {
            $transaction->rollBack();
        }

        $data['message'] = Yii::t('food','Something Went Wrong. Please Try Again Later!');
        
        return Json::encode($data);
	}

	public function actionAddNick()
	{
		$data['value'] = -1;
		$data['message'] = "";
		$get = Yii::$app->request->get();
		if(empty($get['id']))
		{
			$data['message'] = Yii::t('food','Something Went Wrong. Please Try Again Later!');
			return Json::encode($data);
		}
		$id = $get['id'];
		$cart = Cart::findOne($id);

		if(empty($cart))
		{
			$data['message'] = Yii::t('food','Something Went Wrong. Please Try Again Later!');
			return Json::encode($data);
		}
		
		if($get['length'] > $cart->quantity)
		{
			$data['message'] = Yii::t("cart",'Cannot Not More Then Food Quantity');
			return Json::encode($data);
		}

		$link = Url::to(['/order-nick-name/update-nick','cid'=>$cart->id,'id'=>0]);
		$idData = Json::encode(['id'=>0,'cid'=>$cart->id]);
		$data['value'] = 1;
		$data['message'] = "<div class='input-group'><input type='text' class='form-control nick-edit' data-id='".$idData."' ><span class='input-group-btn'><a class='delete-nick btn btn-default'>".Yii::t('food','Delete')."</a></span></div><br>";
		return Json::encode($data);
	}

	public function actionUpdateNick()
	{
		$data['value'] = -1;
		$data['message'] = Yii::t('food','Something Went Wrong. Please Try Again Later!');

		$post = Yii::$app->request->post();
	
		if(empty($post))
		{
			return Json::encode($data);
		}

		$id = $post['id'];
		$cid = $post['cid'];

		if($id == 0)
		{
			$name = new OrderCartNickName;
			$name->type = '1';
			$name->tid = $cid;
		}
		else
		{
			$name = OrderCartNickName::findOne($id);
		}

		$name->nickname = $post['name'];

		if($name->save())
		{
			$data['value'] =1;
			$idData = Json::encode(['id'=>$name->id,'cid'=>$cid]);
			$data['message'] = $idData;
			return Json::encode($data);
		}

		return Json::encode($data);
	}

	/*
	* remove nick name
	*/
	public function actionRemoveNick()
	{
		$data['value'] = -1;
		$data['message'] = Yii::t('common','Delete Fail');

		$get = Yii::$app->request->get();
		if(empty($get['id']))
		{
			return Json::encode($data);
		}
		$id = $get['id'];

		$name = OrderCartNickName::findOne($id);

		if(empty($name))
		{
			return Json::encode($data);
		}

		if($name->delete())
		{
			$data['value'] = 1;
			$data['message'] = Yii::t('common','Delete Successfully');
		}

		return Json::encode($data);
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
			$data['message'] = Yii::t('food','Something Went Wrong. Please Try Again Later!');
			return $data;
		}

		$modelData = self::createModel();

		if(empty($modelData['message']))
		{
			$data['value'] = 2;
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
		$data['message'] = Yii::t("cart","The Cart Item Already Delete!!");
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
					$data['message'] = Yii::t('food','Something Went Wrong. Please Try Again Later!');
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