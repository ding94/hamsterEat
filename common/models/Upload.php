<?php
namespace common\models;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
class Upload extends Model
{
	/**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }
     public function upload()
    {
        //default location = 'imageLocation/'
        if ($this->validate()) {
            $this->imageFile->saveAs( 'imageLocation/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
        
            return true;
        } else 
        {
            return false;
        }
    }
}