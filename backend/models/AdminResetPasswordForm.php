<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use backend\models\Admin;

class AdminResetPasswordForm extends Model
{
	public $password;
	public $repass;
	public $id;

	public function rules()
    {
        return [
            [['password', 'repass'], 'required'],
            ['password', 'string', 'min' => 6],
            ['repass' , 'compare' , 'compareAttribute' => 'password' ,'message' => 'Password and Confirm Password must be same'],
        ];
    }

    public function attributeLabels()
    {
        return [
            
            'password' => 'Password',
            'repass' => 'Confirm password',
        ];
    }

    public function changepass()
    {
    	$model = $this->getAdmin($this->id);
    	if(!$this->hasErrors())
    	{
    		$model->setPassword($this->password);
    		$model->generateAuthKey();
    		return $model->save();
    	}
    }

     /**
     * Finds admin by [[id]]
     *
     * @return User|null
     */
    protected function getAdmin($id)
    {
        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}