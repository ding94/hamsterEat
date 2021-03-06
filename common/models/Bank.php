<?php

namespace common\models;
use yii\data\ActiveDataProvider;
use Yii;

/**
 * This is the model class for table "bank".
 *
 * @property integer $Bank_ID
 * @property string $Bank_Name
 * @property string $Bank_AccNo
 * @property string $Bank_PicPath
 */
class Bank extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bank';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Bank_Name', 'Bank_AccNo'], 'required'],
            [['Bank_Name', 'Bank_PicPath','redirectUrl'], 'string', 'max' => 255],
			[['status'], 'integer'],
            [['Bank_AccNo'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Bank_ID' => 'Bank  ID',
            'Bank_Name' => 'Bank  Name',
            'Bank_AccNo' => 'Bank  Acc No',
            'Bank_PicPath' => 'Bank  Pic Path',
			'redirectUrl' => 'Bank URL',
			'status' => 'Bank Status',
        ];
    }
	public function search($params)
    {
			  $query = self::find(); //自己就是table,找一找资料
		
		
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
		
		 
        return $dataProvider;
    }
}
