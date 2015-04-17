<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

use app\models\City;
use app\models\API;

use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $request = Yii::$app->request;
        $zip = $request->get('zip');
        $api = new API;
        $api_url = '/cities' . ($zip ? "?zip=".$zip : "");
        $cities = $api->curl($api_url);
        $dataProvider = new ArrayDataProvider([
                'allModels' => $cities['data'],
                'sort' => [
                        'attributes' => ['id', 'city', 'state', 'zip_5'],
                 ],
                'pagination' => [
                        'pageSize' => 10,
                ],
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider
	    ,'zip' => $zip
        ]);
    }

    /*public function actionIndex()
    {
	return $this->render('index');
    }*/

    public function actionCities()
    {
	$request = Yii::$app->request;
	$zip = $request->get('zip');  
	\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	if (!$zip) {
                $data = City::find()->all();
		return ['status'=>1,'data'=>$data];
        } else if (($data = City::find()->where(['zip_5'=>$zip])->all()) !== null) {
        	return ['status'=>1,'data'=>$data];
	} else {
		return ['status'=>0,'error_code'=>400,'message'=>'Bad request'];
        }
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}
