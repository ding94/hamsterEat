<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use common\models\User;
use common\models\Area;
use common\models\Company\{Company,CompanyEmployees};

/**
 *Company Signup form
 */
class CompanysignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $type;
    public $status;
    public $name;
    public $licenseno;
    public $address;
    public $postcode;
    public $area;
    public $userid;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required','message'=>Yii::t('common','Username').Yii::t('common',' cannot be blank.')],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 6, 'max' => 12],

            ['email', 'trim'],
            ['email', 'required','message'=>Yii::t('common','Email').Yii::t('common',' cannot be blank.')],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required','message'=>Yii::t('common','Password').Yii::t('common',' cannot be blank.')],
            ['password', 'string', 'min' => 6, 'max' => 20],

            ['name', 'required','message'=>'Company name'.' cannot be blank.'],
            ['name', 'unique', 'targetClass' => '\common\models\Company\Company', 'message' => 'This company name has already been taken.'],
            
            ['licenseno', 'required','message'=>'licenseno'.Yii::t('common',' cannot be blank.')],
            ['licenseno', 'string', 'min' => 6, 'max' => 20],

            ['address', 'required','message'=>'address'.Yii::t('common',' cannot be blank.')],

            ['postcode', 'required','message'=>'postcode'.Yii::t('common',' cannot be blank.')],

            ['area', 'required','message'=>'area'.Yii::t('common',' cannot be blank.')],
            
        ];
    }

    /**
     * Returns the attribute labels.
     *
     * See Model class for more details
     *  
     * @return array attribute labels (name => label).
     */
    public function attributeLabels()
    {
        return [
        'username' => 'Username',
        'password' => 'Password',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function companysignup()
    {   

         if (!$this->validate()) {

            return null;
        }

       
        if($this->status == 2){
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->status = 2;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->save(false);
        } else {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->save(false);
        }
		
        

        if($user->save())
		{
			if($this->type == 2){
			$auth = \Yii::$app->authManager;
			$authorRole = $auth->getRole('rider');
			$auth->assign($authorRole, $user->getId());
			} 
			else if($this->type == 1)
			{     
				$auth = \Yii::$app->authManager;
				$authorRole = $auth->getRole('restaurant manager');
				$auth->assign($authorRole, $user->getId());
			}
        }


       if ($user->validate()) {
            $userid = user::find()->where(['username' => $this->username])->one();
            
            $area=Area::find()->where(['Area_id' =>$this->area])->one();
            $company= new Company();
            $company->name = $this->name;
            $company->owner_id = $userid->id;
            $company->license_no = $this->licenseno;
            $company->address = $this->address;
            $company->postcode = $this->postcode;
            $company->status = 0; 
            $company->created_at = time();
            $company->updated_at = time();
            $company->area =$area->Area_Area ;
            $company->area_group = $area->Area_Group;
            $company->save(false);
            
        }
        
        
        return $user ? $user : null;
    }
}
