<?php
/**
 * Created by PhpStorm.
 * User: fonpah
 * Date: 19.02.2015
 * Time: 16:51
 */

namespace app\models;


use yii\base\Model;
use Yii;

class CsvForm extends Model{
    public $file;

    public function rules(){
        return [
            [['file'],'file','skipOnEmpty'=>false,'extensions'=>'csv','checkExtensionByMimeType' => false]
        ];
    }
    public function attributeLabels()
    {
        return [
            'file' => Yii::t('app', 'CSV File')
        ];
    }
}