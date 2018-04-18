<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use kartik\mpdf\Pdf;
use common\models\Area;
use common\models\User;
use common\models\Company\Company;
use common\models\Company\CompanyEmployees;
use common\models\Order\DeliveryAddress;
use common\models\Deliveryman;
use common\models\DeliverymanCompany;
use yii\web\UploadedFile;
use common\models\SelfObject;
use yii\helpers\Url;
use yii\db\Query;

class CompanyController extends CommonController
{

	public function actionIndex()
    {
    	$searchModel = new Company();
       	$dataProvider = $searchModel->search(Yii::$app->request->queryParams,1);

        return $this->render('index',['dataProvider'=>$dataProvider, 'searchModel'=>$searchModel]);
    }

    public function actionRegister()
    {
    	$company = new Company();
    	$company->scenario = 'register';
        $postcode = ArrayHelper::map(Area::find()->all(),'Area_Postcode','Area_Postcode');

        if (Yii::$app->request->post()) {
            $company->load(Yii::$app->request->post());
            $company['status'] = 1;
            $company['area_group'] = Area::find()->where('Area_Postcode=:p',[':p'=>$company['postcode']])->one()->Area_Group;
            $owner = User::find()->where('username=:u',[':u'=>$company['username']])->one();//get owner data by username

            if (!empty($owner)) {
                //if the username was an owner of another company, set flase
                if (CompanyEmployees::find()->where('uid=:uid',[':uid'=>$owner['id']])->one()) {
                    Yii::$app->session->setFlash('error','User was other company owner or employee!');
                    return $this->render('register',['company'=>$company,'postcode'=>$postcode]);
                }
                //else setup owner id
                $company['owner_id'] = $owner['id'];
            }
            else{
                //if username connot be found
                Yii::$app->session->setFlash('error','Fail to found user!');
                return $this->render('register',['company'=>$company,'postcode'=>$postcode]);
            }
            
            if ($company->validate()) {
                Yii::$app->session->setFlash('success','Success!');
                $company->save();

                $employee = new CompanyEmployees;
                $employee['cid'] = $company['id'];
                $employee['uid'] = $company['owner_id'];
                $employee['status'] =1;
                $employee->save();
            }
            return $this->redirect(['/company/index']);
        }
    	 
    	return $this->render('register',['company'=>$company,'postcode'=>$postcode]);
    }

    public function actionGetArea()
    {
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $cat_id = $parents[0];
                $out = self::getAreaList($cat_id); 
                echo json_encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo json_encode(['output'=>'', 'selected'=>'']);
    }

    public function actionApprovecompany($id,$approval)
    {
        $company = Company::findOne($id);
        switch ($approval) {
            case 0:
                $company['status'] =0 ;
                $company->save();
                Yii::$app->session->setFlash('success','Success!');
                break;
            case 1:
                $company['status'] =1 ;
                $company->save();
                Yii::$app->session->setFlash('success','Success!');
                break;
            
            default:
                Yii::$app->session->setFlash('warning','Failed!');
                break;
        }
        return $this->redirect(['/company/index']);
    }

    public static function getAreaList($postcode)
    {
        $area = Area::find()->where(['like','Area_Postcode' , $postcode])->select(['Area_ID', 'Area_Area'])->all();
        $areaArray = [];
        foreach ($area as $area) {
            $object = new SelfObject();
            $object->id = $area['Area_Area'];
            $object->name = $area['Area_Area'];

            $areaArray[] = $object;
        }
        return $areaArray;
    }

    public function actionEdit($id)
    {
        $company = Company::find()->where('id=:id',[':id'=>$id])->one();
        $company['username'] = User::find()->where('id=:id',[':id'=>$company['owner_id']])->one()->username;
        $company->scenario = 'register';
        $postcode = ArrayHelper::map(Area::find()->all(),'Area_Postcode','Area_Postcode');

        if (Yii::$app->request->post()) {
            $company->load(Yii::$app->request->post());
            $company['status'] = 1;
            $owner = User::find()->where('username=:u',[':u'=>$company['username']])->one();
            if (!empty($owner)) {
                $company['owner_id'] = $owner['id'];
            }
            else{
                Yii::$app->session->setFlash('error','Fail to found user!');
                return $this->redirect(['/company/index']);
            }
            if ($company->validate()) {
                Yii::$app->session->setFlash('success','Success!');
                $company->save();
            }
            return $this->redirect(['/company/index']);
        }

        return $this->renderAjax('register',['company'=>$company, 'postcode'=>$postcode]);
    }

    public function actionAddRider($id)
    {
        $query = Deliveryman::find()->where('DeliveryMan_Approval = 1');
        $deliveryman = array();
        foreach($query->each() as $value)
        {
            $deliveryman[$value->User_id] = User::findOne($value->User_id)->username;
        } 
        $company = DeliverymanCompany::find()->where('cid=:cid',[':cid'=>$id])->one();
        if (empty($company)) {
            $company = new DeliverymanCompany();
        }
        if(Yii::$app->request->post()){
            $company->load(Yii::$app->request->post());
            $company->cid = $id;
            if ($company->validate()) {
                Yii::$app->session->setFlash('success','Success!');
                $company->save();
            } else{
                Yii::$app->session->setFlash('error','Assign Fail!');
                return $this->redirect(['/company/index']);
            }
            return $this->redirect(['/company/index']);
        }
        return $this->renderAjax('add-rider',['company'=>$company,'deliveryman'=>$deliveryman]);
    }

    public function actionAddEmployee($id)
    {
        $company = Company::find()->where('id=:id',[':id'=>$id])->one();
        $employee = new CompanyEmployees();
        $list = CompanyEmployees::find()->where('cid=:c',[':c'=>$id])->joinWith('user')->all();
        $url = Url::to(['/company/userlist']);
        if (Yii::$app->request->post()) {
            $employee->load(Yii::$app->request->post());
            $valid = CompanyEmployees::find()->where('uid=:u',[':u'=>$employee['uid']])->one();
            foreach ($list as $key => $value) {
                if (!empty($valid)) {
                    Yii::$app->session->setFlash('warning','User was assigned to company!');
                    return $this->redirect(['/company/add-employee','id'=>$id]);
                }
            }
            $employee['cid'] = $id;
            $employee['status'] = 1;
            $employee['created_at'] = time();
            $employee['updated_at'] = time();

            if ($employee->validate()) {
                $employee->save();
                Yii::$app->session->setFlash('success','Added!');
                return $this->redirect(['/company/add-employee','id'=>$id]);
            }
            else{
                Yii::$app->session->setFlash('warning','failed!');
                return $this->redirect(['/company/add-employee','id'=>$id]);
            }
        }
        return $this->render('add-employee',['company'=>$company,'employee'=>$employee,'url'=>$url,'list'=>$list]);
    }

    public function actionRemoveEmployee($id)
    {
        $employee = CompanyEmployees::find()->where('id=:id',[':id'=>$id])->one();
        $cid = $employee['cid'];
        if ($employee->delete()) {
            Yii::$app->session->setFlash('success','Deleted!');
        }
        else{
            Yii::$app->session->setFlash('warning','failed!');
        }
        return $this->redirect(['/company/add-employee','id'=>$employee['cid']]);
    }

    public function actionUserlist($rmanager=null,$q = null) 
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query= User::find()->select('id , username')->andWhere(['like','username',$q])->andWhere(['!=','id',$rmanager])->all();
            $out['results'] = array_values($query);
        }
        return $out;
    }

    public function actionOperate($id)
    {
        $company = Company::find()->where('id=:id',[':id'=>$id])->one();
        if ($company['status']==1) {
           $company['status'] = 0;
        }
        else{
           $company['status'] = 1;
        }
        
        if ($company->validate()) {
            $company->save();
            Yii::$app->session->setFlash('success','Success!');
            return $this->redirect(['/company/index']);
        }
    }

    public function actionCompanyOrderList()
    {
        $c = Company::find()->all();
        $j = 0;
        $data = [];
        foreach ($c as $key => $company) {
            foreach ($company['deliveryaddress'] as $k => $value) {
                if ($value['delivery']['Orders_DateTimeMade'] > strtotime(date('Y-m-d'))) {
                    $data[$j] = $company;
                    $j += 1;
                }
            }
        }

        return $this->render('company-order-list',['data'=>$data]);
    }

    public function actionGetOrderPdf($cid)
    {
        $time = strtotime(date('Y-m-d'));
        $orders = DeliveryAddress::find()->where('cid=:c',[':c'=>$cid])->andWhere(['>=','orders.Orders_DateTimeMade',$time])->joinWith('delivery','nickname')->all();
        if(empty($orders))
        {
            Yii::$app->session->setFlash('error', Yii::t('cart','Something Went Wrong!'));
            return $this->redirect(['/company/company-order-list']);
        }
        $i = 0;
        $data = []; //data default value
        $orderitem = []; // orderitem default value
        foreach ($orders as $k => $order) {
            foreach ($order['delivery']['item'] as $l => $item) {
                $orderitem[$i] = $item; // take all order items out
                $i += 1;
            }
        }

        foreach ($orderitem as $k => $item) {
            $restaurantname = $item['food']['restaurant']['Restaurant_Name']; //restaurant name
            $companyname = $item['order']['address']['company']['name']; //company name
            $did = $item['Delivery_ID']; // delivery id
            $data[$companyname][$restaurantname][$did][] = $item; //set all data,$item = order details
        }

         foreach ($data as $k => $restaurant) {
            foreach ($restaurant as $company => $deliveryid) {
                foreach ($deliveryid as $deliid => $items) {
                    foreach ($items as $l => $item) {
                        if (empty($delirowcount[$item['Delivery_ID']])) {
                            $delirowcount[$item['Delivery_ID']] =0;
                        }
                        $delirowcount[$item['Delivery_ID']] += $item['OrderItem_Quantity'];
                        $orderrowcount[$item['Order_ID']] = $item['OrderItem_Quantity'];
                    }
                }
            }
        }
        
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'content' => $this->renderPartial('orderslist',['data'=>$data,'delirowcount'=>$delirowcount,'orderrowcount'=>$orderrowcount]),
            'options' => [
                'title' => 'Orders List',
                //'subject' => 'Sample Subject',
            ],
            'methods' => [
                'SetHeader' => ['Generated By HAMSTEREAT'],
                'SetFooter' => ['|Page{PAGENO}|'],
            ]
            ]);
        return $pdf->render();
    }
}