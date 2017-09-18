<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Ticket;
use common\models\Replies;
use common\models\Upload;
use common\models\User;
use backend\models\Admin;
use yii\web\UploadedFile;
/**
 * Site controller
 */
class TicketController extends Controller
{

	public function actionIndex()
    {
    	$searchModel = new Ticket();
       	$dataProvider = $searchModel->search(Yii::$app->request->queryParams,1);

        return $this->render('index',['model'=>$dataProvider, 'searchModel'=>$searchModel]);
    }

    public function actionReply($id)
    {
    	$model = Ticket::find()->where('Ticket_ID = :id',[':id' => $id])->one();
    	$reply = new Replies;
    	$upload = new Upload;
    	$upload->scenario = 'reply';
        $chat = Replies::find()->where('Ticket_ID = :id',[':id' => $id])->all();
        $name = User::find()->where('id = :id', [':id' => $model->User_id])->one()->username;
        foreach ($chat as $k => $chatt) {
            
            if ($chatt['Replies_ReplyBy'] == 1) {
                 $chatt['Replies_ReplyPerson'] = User::find()->where('id = :id',[':id' => $chatt['Replies_ReplyPerson']])->one()->username;
            }
            elseif ($chatt['Replies_ReplyBy'] == 2 ) {
                $chatt['Replies_ReplyPerson'] = Admin::find()->where('id = :id',[':id' => $chatt['Replies_ReplyPerson']])->one()->adminname . " reply";
            }
        }
       

        
    		if (Yii::$app->request->post()) {
                
                $path = Yii::$app->urlManagerBackEnd->baseUrl.'/'.Yii::$app->params['replyticket'];

                $upload->imageFile =  UploadedFile::getInstance($upload, 'imageFile');
                if (!empty($upload->imageFile)) {
                    
                    $upload->imageFile->name = time().'.'.$upload->imageFile->extension;
                    $upload->upload($path.'/');
                    $reply->Replies_PicPath = $path.'/'.$upload->imageFile->name;

                }
            	
    			
    			$reply->load(Yii::$app->request->post());
    			$reply->Ticket_ID = $model->Ticket_ID;
    			$reply->Replies_DateTime = time();
    			$reply->Replies_ReplyBy = 2;
    			$reply->Replies_ReplyPerson = Yii::$app->user->identity->id;
    			
    			$model->Ticket_Status = 2;
    			
    			if ($reply->validate() && $model->validate()) {
    				$reply->save();
    				$model->save();
    				Yii::$app->session->setFlash('success', "Replied!");
                    return $this->redirect(['/ticket/reply','id'=>$id]);
    			}
    		}


    	return $this->render('staffreply',['model'=>$model,'reply'=>$reply,'upload'=>$upload,'chat'=>$chat,'name'=>$name]);
    }

}
