<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\food\FoodImg;
use frontend\controllers\CommonController;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use common\models\Upload;

class FoodImgController extends CommonController
{

	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['foodDetails', 'insertFood','menu','delete','editFood','postedit','recycleBin','deletePermanent','viewComments'],
                'rules' => [
                    [
                        'actions' => [ 'upload','delete'],

                        'allow' => true,
                        'roles' => ['restaurant manager'],
                    ],
                ],
            ],
        ];
    }
	public function actionUpload()
    {
        //$post = Yii::$app->requst->post();
        if (empty($_FILES['foodimg'])) {
            echo json_encode(['error'=>'No files found for upload.']); 
            // or you can throw an exception 
            return; // terminate
        }
        
        $post = Yii::$app->request->post();	
  		
        $validate = self::imgName($post['id'],$_FILES['foodimg']);
        $image = $_FILES['foodimg'];

        if($validate['value'] == 0)
        {
        	echo json_encode(['error'=>$validate['message']]);
        	return  ; 
        }
        $filename = $validate['message'];
       
        $target = Yii::$app->params['foodImg'] . $filename;
        $source = $image['tmp_name'];
       	 
        if(move_uploaded_file($source, $target))
        {
            $success = true;
        }
        else
        {
            $success = false;
        }
       	$output[] ="";
        if ($success === true) 
        {
            $id = self::save($post['id'], $filename);
           $output['initialPreview'] =  Yii::getAlias('@web').'/'.Yii::$app->params['foodImg'].$filename;
           $output['initialPreviewConfig'][0]['caption'] = $filename;
           $output['initialPreviewConfig'][0]['url'] = Url::to(['/food-img/delete','id'=>$id]);
           $output['initialPreviewConfig'][0]['key'] = $id;
 
        } 
        elseif ($success === false) 
        {
            $output = ['error'=>'Error while uploading images. Contact the system administrator'];
            unlink($target);   
        } 
        else {
            $output = ['error'=>'No files were processed.'];
        }
        echo json_encode($output);
        return;
    }

    public function actionDelete($id)
    {
        $data = FoodImg::findOne($id);

        if(empty($data))
        {
           echo json_encode( ['error'=>'Some Thing Went Wrong.']);
          
           return ;
        }
        $image = Yii::$app->params['foodImg'] . $data->img;
        if($data->delete())
        {
        	$output = ['success'=>'Sucess'];
            if(file_exists($image))
            {
                unlink($image);
            }
        }
        else
        {
            $output = ['error'=>'Some Thing Went Wrong.'];
        }
       
        echo json_encode($output);
        return ;
    }

    protected static function imgName($id,$image)
   	{
   		$data['value'] = '0';
   		$data['message'] = 'Maximun Upload.';
   		$query = FoodImg::find()->where("fid = :id",[':id' => $id]);
        $count = $query->count()+1;

        $tmp =   explode('.', $image['name']);
        $extension=end($tmp);
        if($count > 3)
        {
            return $data;
        }
        else if($count == 1)
        {
        	$name = $id."-".$count.".".$extension;
        }
        else
        {
        	$alreadyName = $query->all();
        	foreach($alreadyName as $name)
        	{	
        		$remove =  explode(".", $name->img);
        		$already[$remove[0]] = $remove[0];
        		
        	}
        	for($i= 1 ; $i < 4 ;$i++)
        	{
        		$createName = $id."-".$i;
        		
        		if(empty($already[$createName]))
        		{
        			$name = $createName.".".$extension;
        			break;
        		}
        	}
        	
        }

        $data['value'] = 1;
        $data['message'] = $name;
        return $data;
   	}


    protected static function save($fid,$filename)
    {
        $img = new FoodImg;
        $img->fid = $fid;
        $img->img = $filename;
        if($img->save())
        {
        	return $img->id;
        }
    }
}