<?php
/**
 * Created by PhpStorm.
 * User: fonpah
 * Date: 19.02.2015
 * Time: 16:04
 */

namespace app\controllers;



use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\web\UploadedFile;
use app\models\CsvForm;
use app\models\UserCSV;

class ImportController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [

        ];
    }

    public function actionIndex(){
        $model = new CsvForm();
        if (\Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->file && $model->validate()) {
                $dataProvider = $this->readFile($model);
                return $this->render('result', ['dataProvider' => $dataProvider]);
            }

        }
        return $this->render('index', ['model' => $model]);
    }

    private function readFile(CsvForm $model)
    {
        $header = array();
        $arr = array();

        $row = 1;
        if (($handle = fopen($model->file->tempName, 'r')) !== false) {
            while (($data = fgetcsv($handle, null, ',')) !== false) {
                if ($row == 1) {
                    $header = $data;
                } else {
                    $nameValues = [];
                    foreach ($header as $key => $val) {
                        $nameValues[strtolower($val)] = $data[$key];
                    }

                    if ($nameValues['city'] && in_array(strtolower($nameValues['city']),array('duesseldorf','düsseldorf'))) {
                        $m = new UserCSV();
                        $m->setAttributes($nameValues, false);
                        $arr[] = $m;
                    }
                }
                $row++;
            }
            fclose($handle);
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $arr,
            'sort' => false,
            'pagination' => [
                'pageSize' => 1000,
            ],
        ]);
        return $dataProvider;
    }

}