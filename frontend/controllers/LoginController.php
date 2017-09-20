<?php

namespace frontend\controllers;

use frontend\models\Login;
use frontend\models\Member;
use yii\web\ForbiddenHttpException;
use yii\web\Request;

class LoginController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model=new Login();
        $model1=new Member();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post(),'');
            if ($model->validate()){
               if($model->login()){
                   $model1->last_login_time=time();
                   $model1->last_login_ip=\Yii::$app->request->userIP;
                   $this->redirect(['index/index']);
               }else{
                   throw new ForbiddenHttpException('密码不正确');
               }
            }
        }
        return $this->renderPartial('index');
    }

    public function actionVilidateUser($username){
        $model=new Member();
        $user= $model->find()->where(['username'=>$username])->one();
        if(!$user){
            return 'false';
        }else{
            return 'true';
        }
    }

    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect(['index/index']);
    }

}
