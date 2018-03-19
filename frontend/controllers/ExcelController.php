<?php 
namespace frontend\controllers;

use yii\helpers\Html;
use yii\web\Controller;
use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use \PHPExcel;
use \PHPExcel_IOFactory;
/*use \PHPExcel_Settings;
use \PHPExcel_Style_Fill;
use \PHPExcel_Writer_IWriter;
use \PHPExcel_Worksheet;
use \PHPExcel_Style;*/
use common\models\food\Food;
use common\models\LanguageLine;

class ExcelController extends Controller
{
    public function changelanguage()
    {
    	chr(ord('A') + 2);
        $objPHPExcel = \PHPExcel_IOFactory::load(Yii::$app->params['langExcel'].'language.xlsx');
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

        var_dump($sheetData);exit;
    }

    public static function foodexcelcreate($lid)
    {
    	$line = LanguageLine::find()->where('id=:l',[':l'=>$lid])->one();

    	$objPHPExcel = new PHPExcel();
    	$objPHPExcel->setActiveSheetIndex(0);
    	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    	$objWriter->save(Yii::$app->params['langExcel'].$line['file_location']); 
    }

    public static function loadfood($food,$lid)
    {
    	$line = LanguageLine::find()->where('id=:l',[':l'=>$lid])->one();
    	$valid = file_exists(Yii::$app->params['langExcel'].$line['file_location']);
    	if ($valid) {
    		$objPHPExcel = PHPExcel_IOFactory::load(Yii::$app->params['langExcel'].$line['file_location']);
            switch ($lid) {
                case 1:
                    $name = $food['enName'];
                    break;
                case 2:
                    $name = $food['zhName'];
                    break;
                default:
                    # code...
                    break;
            }
	    	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$food['Food_ID'], $name);
	    	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	    	$objWriter->save(Yii::$app->params['langExcel'].$line['file_location']);
	    	return true;
    	}
    	else{
	    	return false;
    	}
    }

    public function actionTest()
    {
    	$food = Food::find()->where('Food_ID=:id',[':id'=>1])->one();
    	self::foodexcelcreate(2);
    	//self::loadfood($food,1);
    }
}
