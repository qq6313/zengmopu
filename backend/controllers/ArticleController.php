<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\Article_detail;
use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\web\Request;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $book = Article::find()->orWhere(['status'=>0])->orWhere(['status'=>1]);
        $pager = new Pagination([
            'totalCount' => $book->count(),//总条数
            'defaultPageSize' => 4//每页多少条
        ]);
        $models = $book->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index', ['pager' => $pager, 'models' => $models]);
    }
    public function actionAdd(){
        $model=new Article();
        $request=new Request();
        if ($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->create_time=time();
                $model->save(false);//save方法默认会再次执行验证 $model->validate()

            }else{
                var_dump($model->getErrors());
                exit;
            }
        }
        $model1=new Article_detail();
        $request=new Request();
        if ($request->isPost){
            $model1->load($request->post());
            if($model1->validate()){
                $model1->article_id=$model->id;
                $model1->save(false);//save方法默认会再次执行验证 $model->validate()
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['article/index']);
            }else{
                var_dump($model->getErrors());
                exit;
            }
        }
        $category=ArticleCategory::find()->all();
        return $this->render('add',['model'=>$model,'category'=>$category,'model1'=>$model1]);
    }
    public function actionEdit($id){

        $model=Article::findOne(['id'=>$id]);
        $request=new Request();
        if ($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->create_time=time();
                $model->save(false);//save方法默认会再次执行验证 $model->validate()

            }else{
                var_dump($model->getErrors());
                exit;
            }
        }
        $model1=Article_detail::findOne(['article_id'=>$id]);
        $request=new Request();
        if ($request->isPost){
            $model1->load($request->post());
            if($model1->validate()){
                $model1->article_id=$model->id;
                $model1->save(false);//save方法默认会再次执行验证 $model->validate()
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['article/index']);
            }else{
                var_dump($model->getErrors());
                exit;
            }
        }
        $category=ArticleCategory::find()->all();
        return $this->render('add',['model'=>$model,'category'=>$category,'model1'=>$model1]);
    }
    public function actionDelete(){
            $id=\Yii::$app->request->post('id');
        $article=Article::findOne(['id'=>$id]);
        if($article){
            $article->status=-1;
            $article->save(false);
            return 'success';
        }
    }
    public function actionShow($id){
        $model=Article_detail::findOne(['article_id'=>$id]);
       return $this->render('show',['model'=>$model]);
    }
}
