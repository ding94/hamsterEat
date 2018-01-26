<?php

namespace common\models\translate;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "sentences_source".
 *
 * @property int $id
 * @property string $category
 * @property string $message
 *
 * @property Sentences[] $sentences
 */
class SentencesSource extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sentences_source';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message'], 'string'],
            [['category'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category' => 'Category',
            'message' => 'Message',
        ];
    }

    public function search($params,$case=1)
    {
        
        $query = self::find()->orderBy('category ASC')->joinWith('sentences')->andWhere(['!=','category','faq']);
        switch ($case) {
            case 1:
                $dataProvider = new ActiveDataProvider([
                    'query' => $query,
                    'pagination' => [ 'pageSize' => 10 ],
                ]);
                break;
                
            case 2:
                $dataProvider = new ActiveDataProvider([
                    'query' => $query,
                    'pagination' => [ 'pageSize' => 500 ],
                ]);
            break;

            case 3:
                $query = self::find()->orderBy('category ASC')->joinWith('sentences')->where('category =:c',[':c'=>'faq']);
                $dataProvider = new ActiveDataProvider([
                    'query' => $query,
                    'pagination' => [ 'pageSize' => 500 ],
                ]);
                break;
            
            default:
                # code...
                break;
        }
        

        $this->load($params);
        return $dataProvider;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSentences()
    {
        return $this->hasMany(Sentences::className(), ['id' => 'id']);
    }

    public function getEn()
    {
        return $this->hasOne(Sentences::className(), ['id' => 'id'])->where('language = :l',[':l'=>'en']);
    }

    public function getZh()
    {
        return $this->hasOne(Sentences::className(), ['id' => 'id'])->where('language = :l',[':l'=>'zh']);
    }
}
