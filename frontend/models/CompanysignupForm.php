<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use common\models\User;
use common\models\Area;
use common\models\Company\{Company,CompanyEmployees};
use borales\extensions\phoneInput\PhoneInputValidator;
/**
 *Company Signup form
 */
class CompanysignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $name;
    public $licenseno;
    public $address;
    public $postcode;
    public $area;
    public $contact_name;
    public $contact_number;

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

            ['contact_name', 'required','message'=>'Contact name'.' cannot be blank.'],
            ['contact_name', 'unique', 'targetClass' => '\common\models\Company\Company', 'message' => 'This company name has already been taken.'],

            ['contact_number', 'required','message'=>'Contact no'.' cannot be blank.'],
            ['contact_number','match','pattern'=>'/^[0]{1}[1-9]{1}[0-9]{7,9}$/'],

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
   
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->status = User::STATUS_UNVERIFIED;
		
        $area=Area::find()->where(['Area_id' =>$this->area])->one();
        $company= new Company();
        $company->name = $this->name;
        $company->contact_number = $this->contact_number;
        $company->contact_name = $this->contact_name;
        $company->license_no = "123456789";
        $company->address = $this->address;
        $company->postcode = $this->postcode;
        $company->status = 0; 
        $company->area =$area->Area_Area ;
        $company->area_group = $area->Area_Group;
      
        if($user->validate() && $company->validate())
        {
            if($user->save())
            {
                $company->owner_id = $user->id;
                if($company->save())
                {   
                    $employee = new CompanyEmployees();
                    $employee['cid'] = $company['id'];
                    $employee['uid'] = $user['id'];
                    $employee['status'] = 1;
                    $employee->save(false);
                    return $user;
                }
                else
                {
                    $user->delete();
                }
            }
        }
        return null;
    }
}
