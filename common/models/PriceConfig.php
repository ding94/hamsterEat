<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use common\models\PriceConfig;

/**
 * This is the model class for table "price_config".
 *
 * @property int $id
 * @property string $item
 * @property string $type
 * @property string $value Percent use decimal to present (e.g 50% = 0.5 )
 */
class PriceConfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'price_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item', 'type', 'value'], 'required'],
            [['item'], 'string'],
            [['value'], 'number'],
            [['type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item' => 'Item',
            'type' => 'Type',
            'value' => 'Value',
        ];
    }

    public function search($params,$case)
    {
        $query = PriceConfig::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        return $dataProvider;
    }
}
