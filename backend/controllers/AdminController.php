<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Admin;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class AdminController extends \yii\web\Controller
{
    public function actionIndex(){
        $Admin=Admin::find();

        $pager = new Pagination([
            'totalCount' => $Admin->count(),//总条数
            'defaultPageSize' => 4//每页多少条
        ]);
        $models = $Admin->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['pager'=>$pager,'models'=>$models]);
    }
    public function actionAdd(){
        $model=new Admin();
        $model->scenario=Admin::SCENARIO_ADD;
        $model->scenario=Admin::SCENARIO_EDIT;
        $request=new Request();
        if ($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save(false);
                $authManager=\Yii::$app->authManager;
//                var_dump($model->roles);die;
                if ($model->roles){
                    foreach($model->roles as $role1){
                        $role= $authManager->getRole($role1);
                        $authManager->assign($role,$model->id);
                    }
                }
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['admin/index']);
            }else{
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id){

        $model=Admin::findOne(['id'=>$id]);
        $model->scenario=Admin::SCENARIO_EDIT;
        if($model==null){
            throw new NotFoundHttpException('用户不存在');
        }
        $authManager=\Yii::$app->authManager;
        $roles=$authManager->getRolesByUser($id);
        $model->roles=array_keys($roles);
        $password=$model->password_hash;
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
//                $model->password_hash= \Yii::$app->security->generatePasswordHash($model->password_hash);
               if($model->password_hash!=$password){
                   $model->password_hash= \Yii::$app->security->generatePasswordHash($request->password);
               }
                $model->last_login_time=time();
                $model->last_login_ip=$request->userIP;
                $model->save(false);
                $authManager->revokeAll($id);
//                var_dump($model->roles);die;
                if($model->roles){
                    foreach($model->roles as $role1){
                        $role= $authManager->getRole($role1);
                        $authManager->assign($role,$model->id);
                    }
                }
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['admin/index']);
            }else{
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDelete(){
        $id=\Yii::$app->request->post('id');
        $brand=Admin::findOne(['id'=>$id]);
        $auth=\Yii::$app->authManager;
        $auth->revokeAll($id);
        $brand->delete();
        return 'success';

    }
    public function actionPassword(){
      $model=new Admin();
        $model->scenario=Admin::SCENARIO_ADD;
        $model->scenario=Admin::SCENARIO_OLD;
        $request=new Request();
        if ($request->isPost){
            $model->load($request->post());

            if($model->validate()){
                $id=\Yii::$app->user->identity->getId();
                $user = Admin::findOne(['id'=>$id]);
                if(\Yii::$app->security->validatePassword($model->old_password, $user->password_hash)){
                   $user->password_hash=\Yii::$app->security->generatePasswordHash($model->password);
                   $user->save();
                    \Yii::$app->session->setFlash('success', '修改成功');
                    return $this->redirect(['admin/index']);
                }else{
                    $model->addError('old_password','密码不正确');
                }
            }else{
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->render('password',['model'=>$model]);
    }

    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                'except'=>['logout','login','captcha','error','user']
            ]
        ];
    }

}
