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
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'on' => ['ticket']],
        ];
    }
     public function upload($path)
    {
        if ($this->validate()) {
            //$path = './imageLocation/(file name)/'
            //save path = './imageLocation/(file name)/(image name).(extension)'
            //save database = '(image name).(extension)'
            $this->imageFile->saveAs($path.$this->imageFile->baseName.'.'.$this->imageFile->extension);
        
            return true;
        } else 
        {
            return false;
        }
    }
}