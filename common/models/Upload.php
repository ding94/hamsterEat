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
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'on' => ['ticket']],
        ];
    }
     public function upload($path)
    {
        //default location = 'imageLocation/'
        if ($this->validate()) {
            //var_dump($this);exit;
            $this->imageFile->saveAs($path.$this->imageFile->baseName.'.'.$this->imageFile->extension);
        
            return true;
        } else 
        {
            return false;
        }
    }
}