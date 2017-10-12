<?php

namespace common\models\Report;

use Yii;

/**
 * This is the model class for table "report_category_restaurant_status".
 *
 * @property integer $id
 * @property string $title
 */
class ReportCategoryRestaurantStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'report_category_restaurant_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
        ];
    }
}
