<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['greeting', 'news'],
                'rules' => [
                    [
                        'actions' => ['greeting', 'news','bulkInsert'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['bulkInsert'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ]
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return string
     */
    public function actionGreeting()
    {
        return $this->render('greeting');
    }


    /**
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionNews()
    {
        if (!\Yii::$app->request->isAjax) {
            throw new BadRequestHttpException('Only AJAX request allowed');
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            $txt = file_get_contents(__DIR__ . '/../data/debian-hints.txt');
            $arr = explode('%', $txt);
            $res = [
                'success' => true,
                'data' => $arr
            ];
            return $res;
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }

    }

    /**
     * @return int
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionBulkinsert()
    {
        $header = array();
        $arr = array();
        $row = 1;
        if (($handle = fopen(__DIR__ . '/../data/logins.csv', 'r')) !== false) {
            while (($data = fgetcsv($handle, null, ',')) !== false) {
                if ($row == 1) {
                    $header = $data;
                } else {
                    $nameValues = [];
                    foreach ($header as $key => $val) {
                        $nameValues[strtolower($val)] = $data[$key];
                    }
                    $nameValues['password'] = \Yii::$app->security->generatePasswordHash($nameValues['password']);
                    $date = new \DateTime();
                    $nameValues['created_at'] = $date->format('Y-m-d H:i:s');
                    $arr[] = $nameValues;
                }
                $row++;
            }
            fclose($handle);
        }
        $header[] = 'created_at';
        return \Yii::$app->db->createCommand()->batchInsert('users', $header, $arr)->execute();
    }
}
