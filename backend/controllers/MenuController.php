<?php
namespace backend\controllers;

use backend\models\Menu;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

class MenuController extends Controller{
    public function actionIndex(){
        $model=Menu::find();
        $pager = new Pagination([
            'totalCount' => $model->count(),//总条数
            'defaultPageSize' => 4//每页多少条
        ]);
        $models = $model->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index', ['pager' => $pager, 'models' => $models]);
    }
    public function actionAdd(){
        $model=new Menu();
        $request=new Request();
        $auth=\Yii::$app->authManager;
        $permissions=$auth->getPermissions();
        $menu1=Menu::find()->all();
        $pa=['id'=>0,'name'=>'顶级分类',];
        $menu=ArrayHelper::merge([$pa],$menu1);
        if ($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                if($model->parent_menu==0){
                    $model->address='';
                }
                $model->save();
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['menu/index']);
            }else{
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->render('add', ['model'=>$model,'permissions'=>$permissions,'menu'=>$menu]);
    }
}