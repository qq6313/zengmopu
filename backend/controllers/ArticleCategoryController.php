<?php

namespace backend\controllers;

use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;

class ArticleCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $book = ArticleCategory::find();
        $pager = new Pagination([
            'totalCount' => $book->count(),//总条数
            'defaultPageSize' => 4//每页多少条
        ]);
        $models = $book->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index', ['pager' => $pager, 'models' => $models]);
    }
    public function actionAdd(){
        $model=new ArticleCategory();
        $request=new Request();
        if ($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save(false);//save方法默认会再次执行验证 $model->validate()
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['article-category/index']);
            }else{
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id){
        $model=ArticleCategory::findOne(['id'=>$id]);
        $request=new Request();
        if ($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save(false);//save方法默认会再次执行验证 $model->validate()
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['article-category/index']);
            }else{
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDelete($id){
        $brand=ArticleCategory::findOne(['id'=>$id]);
        $brand->status=-1;

        $brand->save(false);
        \Yii::$app->session->setFlash('success', '删除成功');
        return $this->redirect(['article-category/index']);
    }
}
