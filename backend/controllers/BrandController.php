<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $book = Brand::find();
//        var_dump($book);die;
        $pager = new Pagination([
            'totalCount' => $book->count(),//总条数
            'defaultPageSize' => 4//每页多少条
        ]);
        $models = $book->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index', ['pager' => $pager, 'models' => $models]);
    }
    public function actionAdd(){
        $model=new Brand();
        $request=new Request();
        if ($request->isPost){
            $model->load($request->post());
            $model->file = UploadedFile::getInstance($model, 'file');
            if($model->validate()){
                if($model->file){
                    $file = '/upload/' . uniqid() . '.' . $model->file->getExtension();//文件名(包含路径)
                    //保存文件(文件另存为)
                    $model->file->saveAs(\Yii::getAlias('@webroot') . $file, false);
                    $model->logo = $file;//上传文件的地址赋值给商品的logo字段
                }

                $model->save(false);//save方法默认会再次执行验证 $model->validate()
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id)
    {
        $model = Brand::findOne(['id'=>$id]);
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->validate()) {
                if($model->file){
                    $file = '/upload/' . uniqid() . '.' . $model->file->getExtension();//文件名(包含路径)
                    //保存文件(文件另存为)
                    $model->file->saveAs(\Yii::getAlias('@webroot') . $file, false);
                    $model->logo = $file;//上传文件的地址赋值给商品的logo字段
                }

                $model->save(false);//save方法默认会再次执行验证 $model->validate()
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['brand/index']);
            } else {
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
        public function actionDelete($id){
        $brand=Brand::findOne(['id'=>$id]);
        $brand->status=-1;

        $brand->save();
        \Yii::$app->session->setFlash('success', '删除成功');
        return $this->redirect(['brand/index']);
        }
}
