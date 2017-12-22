<?php

namespace common\models\Cart;

use Yii;
use common\models\food\Foodselection;

/**
 * This is the model class for table "cart_selection".
 *
 * @property integer $id
 * @property integer $cid
 * @property integer $selectionid
 */
class CartSelection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cart_selection';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cid'], 'required'],
            [ 'selectionid','safe'],
            [['cid', 'selectionid'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cid' => 'Cid',
            'selectionid' => 'Selectionid',
        ];
    }
}
