<?php

namespace common\models;

use Yii;
use common\models\food\Foodselectiontype;
use yii\helpers\ArrayHelper;

class Model extends \yii\base\Model
{
    /**
     * Creates and populates a set of models.
     *
     * @param string $modelClass
     * @param array $multipleModels
     * @return array
     */
    public static function createMultiple($modelClass, $multipleModels = [])
    {
        $model    = new $modelClass;
        $formName = $model->formName();
        $post     = Yii::$app->request->post($formName);
        $models   = [];
        
        if (! empty($multipleModels)) {
            $keys = array_keys(ArrayHelper::map($multipleModels, 'ID', 'ID'));
            $multipleModels = array_combine($keys, $multipleModels);
        }
       
        if ($post && is_array($post)) {
            foreach ($post as $i => $item) {
                if (isset($item['ID']) && !empty($item['ID']) && isset($multipleModels[$item['ID']])) {
                    $models[] = $multipleModels[$item['ID']];
                } else {
                    $models[] = new $modelClass;
                }
            }
        }

        unset($model, $formName, $post);

        return $models;
    }
}
