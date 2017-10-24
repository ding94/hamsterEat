<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $type;
    public $status;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 6, 'max' => 12],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6, 'max' => 20],
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
    public function signup()
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
        
        return $user->save() ? $user : null;
    }
}
