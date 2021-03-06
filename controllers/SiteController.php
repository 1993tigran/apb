<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    public $layout = 'login';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
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
//                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
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
            'login' => [
                'class' => 'yii2mod\user\actions\LoginAction',
                'on beforeLogin' => function ($event) {
                    // your custom code
                },
                'on afterLogin' => function ($event) {
                    // your custom code
                },
            ],
            'logout' => [
                'class' => 'yii2mod\user\actions\LogoutAction',
                'on beforeLogout' => function ($event) {
                    // your custom code
                },
                'on afterLogout' => function ($event) {
                    // your custom code
                },
            ],
            'signup' => [
                'class' => 'yii2mod\user\actions\SignupAction',
                'on beforeSignup' => function ($event) {
                    // your custom code
                },
                'on afterSignup' => function ($event) {
                    // your custom code
                },
            ],
            'request-password-reset' => [
                'class' => 'yii2mod\user\actions\RequestPasswordResetAction',
                'on beforeRequest' => function ($event) {
                    // your custom code
                },
                'on afterRequest' => function ($event) {
                    // your custom code
                },
            ],
            'password-reset' => [
                'class' => 'yii2mod\user\actions\PasswordResetAction',
                'on beforeReset' => function ($event) {
                    // your custom code
                },
                'on afterReset' => function ($event) {
                    // your custom code
                },
            ],
        ];
    }

    public function actionConnect()
    {
        $this->layout = false;
        return $this->render('connect');
    }
    public function actionInfo()
    {
       phpinfo();
    }
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }


}
