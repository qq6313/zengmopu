<?php

namespace backend\controllers;

use backend\models\Brand;
use flyok666\qiniu\Qiniu;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;
use flyok666\uploadifive\UploadAction;

class BrandController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $book = Brand::find()->orWhere(['status'=>0])->orWhere(['status'=>1]);;
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

            if($model->validate()){

                $model->save();//save方法默认会再次执行验证 $model->validate()
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
            if ($request->isPost) {
                $model->load($request->post());

                if ($model->validate()) {

                    $model->save();//save方法默认会再次执行验证 $model->validate()
                    \Yii::$app->session->setFlash('success', '添加成功');
                    return $this->redirect(['brand/index']);
                } else {
                    var_dump($model->getErrors());
                    exit;
                }
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDelete(){
        $id=\Yii::$app->request->post('id');
        $brand=Brand::findOne(['id'=>$id]);
        if($brand){
            $brand->status=-1;
            $brand->save(false);
            return 'success';
        }
        return '删除失败';
        }


    public function actions() {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ],
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                   /* $action->output['fileUrl'] = $action->getWebUrl();
                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"*/
                    $qiniu = new Qiniu(\Yii::$app->params['qiniuyun']);//引用qiniuyun的路径
                    $key = $action->getWebUrl();
                    $file=$action->getSavePath();//上传到七牛云,指定一个文件名
                    $qiniu->uploadFile($file,$key);
                    $url = $qiniu->getLink($key);//获取七牛云上传的url地址
                    $action->output['fileUrl'] =$url;
                },
            ],
        ];
    }

}
