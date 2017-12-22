<?php

namespace common\models\vouchers;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "vouchers_set_condition".
 *
 * @property integer $id
 * @property integer $vid
 * @property integer $condition_id
 * @property double $amount
 */
class VouchersSetCondition extends \yii\db\ActiveRecord
{
    public $code;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vouchers_set_condition';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vid', 'condition_id', 'amount'], 'required'],
            [['code'],'string'],
            [['code'],'required','on'=>'set'],
            [['vid', 'condition_id'], 'integer'],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vid' => 'Vid',
            'condition_id' => 'Condition ID',
            'amount' => 'Amount',
        ];
    }

    public function search($params,$action)
    {
        $query = self::find();
        

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        return $dataProvider;
    }

    public function getCondition_description()
    {
        return $this->hasOne(VouchersCondition::className(),['id' => 'condition_id']); 
    }
}
