<?php

namespace backend\controllers;

use backend\models\GoodsGallery;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;

class GoodsGalleryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model=GoodsGallery::find();
        $pager = new Pagination([
            'totalCount' => $model->count(),//总条数
            'defaultPageSize' => 4//每页多少条
        ]);
        $models = $model->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index', ['pager' => $pager, 'models' => $models]);
    }

        public function actionAdd()
        {
            $model=new GoodsGallery();
           /* if(isset($_POST['Upload'])) {
                $model->path=UploadedFile::getInstance($model,'path');
                $ext = $model->path->getExtension();
                $fileName = uniqid() . '.' . $ext;
                $model->path->saveAs('assets/' . $fileName);
            }*/
            $request=new Request();
            if ($request->isPost){
                $model->load($request->post());
                if($model->validate()){
                    $model->path=UploadedFile::getInstance($model,'path');
                    $ext = $model->path->getExtension();
                    $fileName = uniqid() . '.' . $ext;
                    $model->path->saveAs('assets/' . $fileName);
                    \Yii::$app->session->setFlash('success', '添加成功');
                    return $this->redirect(['goods-gallery/index']);
                }else{
                    var_dump($model->getErrors());
                    exit;
                }
            }
            $this->render('add', ['model'=>$model]);
        }



}
