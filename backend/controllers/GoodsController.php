<?php

namespace backend\controllers;


use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use backend\models\GoodsSearch;
use flyok666\qiniu\Qiniu;
use flyok666\uploadifive\UploadAction;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Request;

class GoodsController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    public function actionIndex()
    {
        $model1=Goods::find();
        $model = new GoodsSearch();
        $model->search($model1);

        $pager = new Pagination([
            'totalCount' => $model1->count(),//总条数
            'defaultPageSize' => 2//每页多少条
        ]);
        $models = $model1->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index', ['pager' => $pager,'model' =>$model,'models' => $models]);
    }
    public function actionAdd()
    {
        $model = new Goods();
        $model1=new GoodsIntro();
        $model2=new GoodsDayCount();
        $brand_id = Brand::find()->all();
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            $model1->load($request->post());
            if ($model->validate() && $model1->validate()) {
                $model->create_time = time();
                $date=date('Y-m-d', time());
                $da=date('Ymd', time());
                $time=GoodsDayCount::findOne(['day'=>$date]);
                if($time){
                    $time->count+=1;
                    $time->save();
                    if($time->count<10){
                        $model->sn=$da.'000'.$time->count;
                    }elseif($time->count<100){
                        $model->sn=$da.'00'.$time->count;
                    }elseif($time->count<1000){
                        $model->sn=($da.'0'.$time->count);
                    }
                }else{
                    $model->sn=$da.'0001';
                    $model2->count=1;
                    $model2->day=date('Ymd', time());
                    $model2->save();
                }
                $model->save();//save方法默认会再次执行验证 $model->validate()
                $model1->goods_id=$model->id;
                $model1->save();

                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['goods/index']);
            } else {
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->render('add',['model'=>$model,'brand_id'=>$brand_id,'model1'=>$model1]);
    }

    public function actionEdit($id)
    {
        $model =Goods::findOne(['id'=>$id]);
        $model1=GoodsIntro::findOne(['goods_id'=>$id]);
        $goodscategory = GoodsCategory::find()->all();
        $brand_id = Brand::find()->all();
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            $model1->load($request->post());

            if ($model->validate() && $model1->validate()) {
                $model->create_time = time();
                $date=date('Ymd', time());
                $time=GoodsDayCount::find()->where(['day'=>$date])->orderBy(['count'=>SORT_DESC])->asArray()->one();
                if($time){
                    if($time->count<10){
                        $model->sn=$date.'000'.$time->count;
                    }elseif($time->count<100){
                        $model->sn=$date.'00'.$time->count;
                    }elseif($time->count<1000){
                        $model->sn=$date.'0'.$time->count;
                    }
                }
                $model->save();//save方法默认会再次执行验证 $model->validate()
                $model1->goods_id=$model->id;
                $model1->save();

                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['goods/index']);
            } else {
                var_dump($model->getErrors());
                exit;
            }
        }

        return $this->render('add',['goodscategory'=>$goodscategory,'model'=>$model,'brand_id'=>$brand_id,'model1'=>$model1]);
    }
    public function actionDelete(){
        $id=\Yii::$app->request->post('id');
        $goods=Goods::findOne(['id'=>$id]);
        $goods_intro=GoodsIntro::findOne(['goods_id'=>$id]);
        $date=date('Y-m-d',$goods->create_time);
        $day=GoodsDayCount::find()->where(['day'=>$date])->orderBy(['count'=>SORT_DESC])->one();
        if($goods && $goods_intro ){
            $goods->delete();
            $goods_intro->delete();
        if($day){
            $day->count-=1;
            $day->save();
        }


                return 'success';
        }
        return '删除失败';
    }
    public function actionShow($id){
        $model=GoodsIntro::findOne(['goods_id'=>$id]);
        return $this->render('show',['model'=>$model]);
    }
    public function actionGallery($id)
    {

        $goods = Goods::findOne(['id'=>$id]);
        $request=new Request();
     /*   $model=new GoodsGallery();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $model->goods_id=$goods->id;
                $model->save();
            } else {
                var_dump($model->getErrors());
                exit;//失败就提示错误信息并且结束后面代码的执行
            }
        }*/

        return $this->render('gallery',['goods'=>$goods]);

    }
    public function actionDelGallery(){
        $id = \Yii::$app->request->post('id');
        $model = GoodsGallery::findOne(['id'=>$id]);
        if($model && $model->delete()){
            return 'success';
        }else{
            return 'fail';
        }

    }


    public function actions() {
        return [

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


                    $model=new GoodsGallery();
                    $model->goods_id=\Yii::$app->request->post('goods_id');
                    $model->path = $action->getWebUrl();
                    $model->save();
                    $action->output['fileUrl'] = $model->path;


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
