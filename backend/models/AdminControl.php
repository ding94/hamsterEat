<?php

namespace backend\models;

use yii\base\Model;
use backend\models\Admin;
use yii\data\ActiveDataProvider;

class AdminControl extends Admin 
{
  	public $id;
  	public $adminname;
  	public $email;
  	public $status;
  	public $adminTittle;
    public $role;

    public function attributes()
    {
        return array_merge(parent::attributes(),['adminAuthAssignment.item_name']);
    }

    public function rules()
    {
        return [
            ['adminname', 'trim' ,'on' => ['addAdmin']],
            ['adminname', 'required' , 'on' => ['addAdmin']],
            ['adminname', 'unique', 'targetClass' => '\backend\models\Admin', 'message' => 'This username has already been taken.' , 'on' => ['addAdmin']],
            ['adminname', 'string', 'min' => 2, 'max' => 255 , 'on' => ['addAdmin']],

            ['email', 'trim' , 'on' => ['addAdmin']],
            ['email', 'required' , 'on' => ['addAdmin']],
            ['email', 'email' , 'on' => ['addAdmin']],
            ['email', 'string', 'max' => 255 , 'on' => ['addAdmin']],
            ['email', 'unique', 'targetClass' => '\backend\models\Admin', 'message' => 'This email address has already been taken.' , 'on' => ['addAdmin']],

            ['password', 'required', 'on' => ['addAdmin']],
            ['password', 'string', 'min' => 6 , 'on' => ['addAdmin']],

            ['role' , 'required' , 'on' =>['addAdmin']],

            ['email'  , 'unique' ,'on' => ['searchAdmin']],
            [['adminAuthAssignment.item_name','adminname','status'],'safe' ,'on'=>['searchAdmin']],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
	  public function search($params)
    {
        $query = Admin::find();
       
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['adminAuthAssignment.item_name'] = [
              'asc' => ['item_name' => SORT_ASC],
              'desc' => ['item_name' => SORT_DESC],
        ];


        $query->joinWith('adminAuthAssignment');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $this->load($params);
        $query->andFilterWhere(['status' => $this->status])
              ->andFilterWhere(['like','adminname' , $this->adminname])
              ->andFilterWhere(['like','email' , $this->email])
              ->andFilterWhere(['like', 'item_name',$this->getAttribute('adminAuthAssignment.item_name')]);
        
        return $dataProvider;
    }

    public function add()
    {
        if (!$this->validate()) {
              return null;
        }
        $model = new Admin();

        $model->adminname = $this->adminname;
        $model->email = $this->email;
        $model->status = Admin::STATUS_ACTIVE;
        $model->setPassword($this->password);
        $model->generateAuthKey();
        $model->save();
       
        $auth = \Yii::$app->authManager;
        $authorRole = $auth->getRole($this->role);
        $auth->assign($authorRole, $model->getId());
        return $model;
    }
}