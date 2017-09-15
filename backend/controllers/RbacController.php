<?php

namespace backend\controllers;

use backend\models\Permission;
use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\web\Request;

class RbacController extends \yii\web\Controller
{
    public function actionPermissionsIndex()
    {
      $auth=\Yii::$app->authManager;
      $permissions=$auth->getPermissions();
      //分页使用插件

      return $this->render('permissions-index',['permissions'=>$permissions]);
    }

    public function actionAddPermission(){
        $model=new PermissionForm();
        $model->scenario=PermissionForm::SCENARIO_ADD;
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $auth=\Yii::$app->authManager;//实例化一个权限对象
                $permission=$auth->createPermission($model->name);//创建权限
                $permission->description=$model->description;
                $auth->add($permission);
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['rbac/permissions-index']);
            }
        }
        return $this->render('permission',['model'=>$model]);
    }
    public function actionEditPermission($name){
        $auth=\Yii::$app->authManager;//实例化一个权限对象
        $model1=$auth->getPermission($name);
        $model=new PermissionForm();
        $model->name=$model1->name;
        $model->description=$model1->description;
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            //如果更改了名字,判断是否和其他一样
            if($model->name!=$model1->name){
                $model->scenario=PermissionForm::SCENARIO_ADD;
            }
            if($model->validate()){
                $model1->name=$model->name;
                $model1->description=$model->description;
                $auth->update($name,$model1);
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['rbac/permissions-index']);
            }
        }
        return $this->render('permission',['model'=>$model]);
    }
    public function actionDeletePermission(){
        $name=\Yii::$app->request->post('name');
        $auth=\Yii::$app->authManager;//实例化一个权限对象
        $model=$auth->getPermission($name);
        $auth->remove($model);
        return 'success';
    }
    public function actionAddRole(){
        $model=new RoleForm();
        $requset=new Request();
        if($requset->isPost){
            $model->load($requset->post());
            if($model->validate()){
                $auth=\Yii::$app->authManager;
                $role=$auth->createRole($model->name);//创建新的角色
                $role->description=$model->description;
                $auth->add($role);//添加角色
                if($model->permissions){
                    foreach ($model->permissions as $permissionName){
                        $permission=$auth->getPermission($permissionName);
                        $auth->addChild($role,$permission);//给角色分配权限
                    }
                }
                return $this->redirect(['rbac/index-role']);
            }
        }

        return $this->render('role',['model'=>$model]);
    }
    public function actionIndexRole(){
        $auth=\Yii::$app->authManager;
        $roles=$auth->getRoles();
        return $this->render('roles-index',['roles'=>$roles]);
    }

}
