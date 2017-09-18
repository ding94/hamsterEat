<?php
namespace backend\models;

use Yii;
use common\models\User;
use common\models\user\Userdetails;
use yii\data\ActiveDataProvider;

class UserSearch extends User
{
	public function attributes()
    {
        return array_merge(parent::attributes(),['userdetails.fullname','userdetails.User_ContactNo','userdetails.User_AccountBalance','authAssignment.item_name']);
    }

    public function rules()
    {
        return [
            ['email' , 'unique'],
            [['id','username' ,'userdetails.fullname' ,'userdetails.User_AccountBalance','userdetails.User_ContactNo' ,'status' ,'authAssignment.item_name'] ,'safe'],
        ];
    }

	public function search($params)
	{
		$query = User::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

        $dataProvider->sort->attributes['userdetails.fullname'] = [
            'asc'=>['User_FirstName'=>SORT_ASC, 'User_LastName'=>SORT_ASC],
            'desc'=>['User_FirstName'=>SORT_DESC, 'User_LastName'=>SORT_DESC],
        ];

        $dataProvider->sort->attributes['userdetails.User_AccountBalance'] = [
            'asc'=>['User_AccountBalance'=>SORT_ASC],
            'desc'=>['User_AccountBalance'=>SORT_DESC],
        ];

        $dataProvider->sort->attributes['userdetails.User_ContactNo'] = [
            'asc'=>['User_ContactNo'=>SORT_ASC],
            'desc'=>['User_ContactNo'=>SORT_DESC],
        ];

        $dataProvider->sort->attributes['authAssignment.item_name'] = [
        	 'asc'=>['item_name'=>SORT_ASC],
            'desc'=>['item_name'=>SORT_DESC],
        ];

		$query->joinWith(['userdetails','authAssignment']);

		$this->load($params);

		$query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,

        ]);

        $query->andFilterWhere(['like','username' , $this->username]);
        $query->andFilterWhere(['like','email' , $this->email]);
        $query->andFilterWhere(['like','email' , $this->getAttribute('userdetails.User_ContactNo')]);
        $query->andFilterWhere(['like','item_name' , $this->getAttribute('authAssignment.item_name')]);
        $query->andFilterWhere(['like','email' , $this->getAttribute('userdetails.User_AccountBalance')]);
       	$query->andFilterWhere(['or',
                                    ['like','User_FirstName',$this->getAttribute('userdetails.fullname')],
                                    ['like','User_LastName',$this->getAttribute('userdetails.fullname')],
                                    ['like', 'concat(User_FirstName, " " , User_LastName) ', $this->getAttribute('userdetails.fullname')]
                               ]);
	

		return $dataProvider;
	}
}