<?php

namespace backend\controllers;
use backend\models\Admin;
use backend\models\Login;
use yii\web\IdentityInterface;
use yii\web\Request;
use yii\web\User;

class LoginController extends \yii\web\Controller
{
    public function actionLogin(){
//        var_dump(\Yii::$app->security->generatePasswordHash('123456'));die;
        $model=new Login();
        $request=new Request();
        if ($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                if($model->login()){
                   $id=\Yii::$app->user->identity->getId();
                    $admin=Admin::findOne(['id'=>$id]);
                    $admin->last_login_time=time();
                    $admin->last_login_ip=\Yii::$app->request->userIP;
                    $admin->save();
                    \Yii::$app->session->setFlash('success','登录成功');
                 /*   $session = \Yii::$app->session;
                    var_dump($session);die;*/
                    return $this->redirect(['admin/index']);
                }
            }
        }
        return $this->render('login',['model'=>$model]);
    }

    public function actions(){
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                //设置验证码参数
                'minLength'=>3,
                'maxLength'=>3,
            ],
        ];
    }

    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect(['admin/index']);
    }
}
