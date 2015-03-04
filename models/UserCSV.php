<?php
/**
 * Created by PhpStorm.
 * User: fonpah
 * Date: 19.02.2015
 * Time: 16:41
 */

namespace app\models;

use Yii;
use yii\base\Model;

class UserCSV extends Model{
    public $last_name;
    public $first_name;
    public $birthday;
    public $city;
    public $age;

    public function attributeLabels()
    {
        return [
            'last_name' => Yii::t('app', 'Last Name'),
            'first_name' => Yii::t('app', 'First Name'),
            'birthday' => Yii::t('app', 'Birthday'),
            'city' => Yii::t('app', 'City'),
            'age' => Yii::t('app', 'Age'),
        ];
    }

    public function setAttributes($value, $safe =true){
        parent::setAttributes($value, $safe);
        $this->calcAge();

    }

    public function calcAge(){
        if($this->birthday){
            $from = \DateTime::createFromFormat('d.m.Y', $this->birthday);
            $to = new \DateTime('today');
            $this->age = $from->diff($to)->y;
        }

    }
}