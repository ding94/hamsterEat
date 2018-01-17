<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "languages".
 *
 * @property int $id
 * @property string $name
 * @property string $iso_639-1
 * @property int $activation 0=deactive, 1=activated
 * @property string $excel_column
 */
class Languages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'languages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activation'], 'integer'],
            [['name'], 'string', 'max' => 49],
            [['iso_639-1'], 'string', 'max' => 2],
            [['excel_column'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'iso_639-1' => 'Iso 639 1',
            'activation' => 'Activation',
            'excel_column' => 'Excel Column',
        ];
    }

    public function search($params)
    {
        $query=self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        return $dataProvider;
    }
}
