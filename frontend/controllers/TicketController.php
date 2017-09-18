<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use common\models\User\Userdetails;
use common\models\User\Useraddress;;
use common\models\User;
use backend\models\Admin;
use common\models\Upload;
use common\models\Ticket;
use common\models\Ticketcategorytypes;
use common\models\Replies;
use yii\web\UploadedFile;

class TicketController extends Controller
{

public function actionSubmitTicket()
    {
        $model = new Ticket;
        $type = Ticketcategorytypes::find()->all();
        $data = ArrayHelper::map($type,'Category_Name','Category_Name');
        $path = Yii::$app->params['submitticket'];
        $upload = new Upload;
        $upload->scenario = 'ticket';

        if (Yii::$app->request->post()) {
            
            $post = Yii::$app->request->post();

            $upload->imageFile =  UploadedFile::getInstance($upload, 'imageFile');
            if (!empty($upload)) {
                $upload->imageFile->name = time().'.'.$upload->imageFile->extension;
                $post['Ticket']['Ticket_PicPath'] = $path.'/'.$upload->imageFile->name;
            
                $upload->upload($path.'/');
            }
            

            $model->User_id = Yii::$app->user->identity->id;
            $model->Ticket_DateTime = time();
            $model->Ticket_Status = 1;
            $model->load($post);
            $model->save(false);
            Yii::$app->session->setFlash('success', 'Upload Successful');
            return $this->redirect(['/ticket/submit-ticket']);
        }




        return $this->render("submitticket",['model' => $model, 'data' => $data,'upload'=>$upload]);
    }

    public function actionInProgress()
    {
        $model = Ticket::find()->joinWith('adminreply')->where('User_id = :id ', [':id'=>Yii::$app->user->identity->id])->orderBy('Ticket_ID DESC')->all();
        //var_dump($model['0']['adminreply']);exit;

        return $this->render('processticket', ['model'=>$model]);
    }

     public function actionChatting($sid,$tid)
    {
        $model= Replies::find()->where('Ticket_ID = :tid ', [':tid'=>$tid])->orderBy('Replies_DateTime ASC')->all();
        $reply = new Replies;
        $ticket = Ticket::find()->where('Ticket_ID = :id ', [':id'=>$tid])->one();
        $name = User::find()->where('id = :id',[':id'=>$ticket->User_id])->one()->username;
        $upload = new Upload;
        $upload->scenario = 'reply';


        foreach ($model as $k => $modell) {
            
            if ($modell['Replies_ReplyBy'] == 1) {
                 $modell['Replies_ReplyPerson'] = User::find()->where('id = :id',[':id' => $modell['Replies_ReplyPerson']])->one()->username;
            }
            elseif ($modell['Replies_ReplyBy'] == 2 ) {
                $modell['Replies_ReplyPerson'] = Admin::find()->where('id = :id',[':id' => $modell['Replies_ReplyPerson']])->one()->adminname . " reply";
            }
        }
       
        if (Yii::$app->request->post()) {

            $reply->load(Yii::$app->request->post());
            $reply->Ticket_ID = $tid;
            $reply->Replies_DateTime = time();
            $reply->Replies_ReplyBy = 1;
            $reply->Replies_ReplyPerson = Yii::$app->user->identity->id;
            
            $path = Yii::$app->params['submitticket'];
            $upload->imageFile =  UploadedFile::getInstance($upload, 'imageFile');

            if (!empty($upload->imageFile)) {

                $upload->imageFile->name = time().'.'.$upload->imageFile->extension;
                $upload->upload($path.'/');
                $reply->Replies_PicPath = $path.'/'.$upload->imageFile->name;

            }

           if ($reply->validate()) {
               $reply->save();
               Yii::$app->session->setFlash('success', 'Submitted!');
               return $this->redirect(['/ticket/chatting','sid'=>$sid,'tid'=>$tid]);
           }
           else
           {
                Yii::$app->session->setFlash('error', 'Upload Failed, Something went wrong');
           }



        }


        

        return $this->render('chat',['model'=>$model,'reply'=>$reply,'ticket'=>$ticket,'name'=>$name,'sid'=>$sid,'upload'=>$upload]);
    }
}