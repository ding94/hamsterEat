<?php
namespace common\models\Account;

use common\models\Account\AccountForce;
use yii\data\ActiveDataProvider;

class AccountForceSearch extends AccountForce
{
	public $user;
	public $operater;
	public function rules()
	{
		return [
			[['id','user','operater','amount','reduceOrPlus'],'safe'],
		];
	}

	public function search($params)
	{
		$query = AccountForce::find()->orderBy(['created_at' => SORT_DESC]);

		$query->joinWith(['user','admin']);

		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['user'] = [
	        'asc' => ['user.username' => SORT_ASC],
	        'desc' => ['user.username' => SORT_DESC],
	    ];

	    $dataProvider->sort->attributes['operater'] = [
	        'asc' => ['admin.adminname' => SORT_ASC],
	        'desc' => ['admin.adminname' => SORT_DESC],
	    ];

	    $this->load($params);

	    $query->andFilterWhere([
            AccountForce::tableName().'.id' => $this->id,
            'amount' => $this->amount,
            'reduceOrPlus' => $this->reduceOrPlus,
        ]);

	    $query->andFilterWhere(['like','user.username' , $this->user]);
	    $query->andFilterWhere(['like','admin.adminname' , $this->operater]);

		return $dataProvider;
	}
}