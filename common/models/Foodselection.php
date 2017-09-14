<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "foodselection".
 *
 * @property integer $Selection_ID
 * @property integer $Food_ID
 * @property string $Selection_Name
 * @property string $Selection_Type
 * @property double $Selection_Price
 * @property string $Selection_Desc
 */
class Foodselection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'foodselection';
    }

     public static function createMultiple($modelClass, $multipleModels = [])
    {
        $model    = new $modelClass;
        $formName = $model->formName();
        $post     = Yii::$app->request->post($formName);
        $models   = [];

        if (! empty($multipleModels)) {
            $keys = array_keys(ArrayHelper::map($multipleModels, 'id', 'id'));
            $multipleModels = array_combine($keys, $multipleModels);
        }

        if ($post && is_array($post)) {
            foreach ($post as $i => $item) {
                if (isset($item['id']) && !empty($item['id']) && isset($multipleModels[$item['id']])) {
                    $models[] = $multipleModels[$item['id']];
                } else {
                    $models[] = new $modelClass;
                }
            }
        }

        unset($model, $formName, $post);

        return $models;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Selection_ID', 'Food_ID', 'Selection_Name', 'Selection_Type', 'Selection_Price', 'Selection_Desc'], 'required'],
            [['Selection_ID', 'Food_ID'], 'integer'],
            [['Selection_Price'], 'number'],
            [['Selection_Name', 'Selection_Type', 'Selection_Desc'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Selection_ID' => 'Selection  ID',
            'Food_ID' => 'Food  ID',
            'Selection_Name' => 'Selection  Name',
            'Selection_Type' => 'Selection  Type',
            'Selection_Price' => 'Selection  Price',
            'Selection_Desc' => 'Selection  Desc',
        ];
    }
}
