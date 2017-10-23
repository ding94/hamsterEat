<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\models\user\Useraddress;
use common\models\user\Userdetails;
use backend\models\auth\AuthAssignment;
use frontend\models\Deliveryman;
use common\models\DeliveryAttendence;
use common\models\Rmanager;
use common\models\Account\Accountbalance;  
use common\models\Account\Memberpoint;
/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_UNVERIFIED = 1;
    const STATUS_REFERRAL = 2;
    const STATUS_ACTIVE = 10;
     public $role;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim' ,'on' => ['changeAdmin']],
            ['username', 'required' , 'on' => ['changeAdmin']],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.' , 'on' => ['changeAdmin']],
            ['username', 'string', 'min' => 2, 'max' => 255 , 'on' => ['changeAdmin']],

            ['email', 'trim' , 'on' => ['changeAdmin']],
            ['email', 'required' , 'on' => ['changeAdmin']],
            ['email', 'email' , 'on' => ['changeAdmin']],
            ['email', 'string', 'max' => 255 , 'on' => ['changeAdmin']],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.' , 'on' => ['changeAdmin']],
            
            ['status', 'default', 'value' => self::STATUS_UNVERIFIED],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE,self::STATUS_UNVERIFIED, self::STATUS_DELETED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::find()->where('id = :id',[':id' => $id])->andWhere(['between', 'status', self::STATUS_UNVERIFIED, self::STATUS_ACTIVE])->one();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::find()->where('username = :username',[':username' => $username])->andWhere(['between', 'status', self::STATUS_UNVERIFIED, self::STATUS_ACTIVE])->one();
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
     public static function findByEmail($email)
     {
         return static::find()->where('email = :email',[':email' => $email])->andWhere(['between', 'status', self::STATUS_UNVERIFIED, self::STATUS_ACTIVE])->one();
     }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getUseraddress()
    {
        return $this->hasOne(Useraddress::className(),['User_id' => 'id']);
    }

    public function getUserdetails()
    {
        return $this->hasOne(Userdetails::className(),['User_id' => 'id']);
    }

    public function getAuthAssignment()
    {
        return $this->hasOne(AuthAssignment::className(),['user_id' => 'id']);
    }

    public function getDeliveryman()
    {
        return $this->hasOne(Deliveryman::className(),['User_id' => 'id']);
    }

    public function getManager()
    {
        return $this->hasOne(Rmanager::className(),['User_Username' => 'username']);
    }

    public function getBalance()
    {
        return $this->hasOne(Accountbalance::className(),['User_Username' => 'username']);
    }

    public function getMemberpoint()
    {
        return $this->hasOne(Memberpoint::className(),['uid' => 'id']);
    }

}

