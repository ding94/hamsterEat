<?php
namespace common\models\Account;

use common\models\Account\AccountForce;
use yii\data\ActiveDataProvider;

class AccountForceSearch extends AccountForce
{

	public function search($params)
	{
		$query = AccountForce::find();

		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

		
		$this->load($params);

		return $dataProvider;
	}
}