<?php
namespace frontend\controllers;
use frontend\models\Address;
use frontend\models\Locations;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Request;

class AddressController extends Controller{
    public function actionIndex(){
        $model=new Address();
        $request=new Request();
        $datas=Address::find()->asArray()->all();

        if($request->isPost){
            $model->load($request->post(),'');
            if ($model->validate()){
                $user_id=\Yii::$app->user->identity->getId();
                $model->user_id=$user_id;
                $model->save(false);

            }else{
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->renderPartial('index',['datas'=>$datas,'model'=>$model]);
    }
    public function actionGetProvince(){
       echo json_encode(Locations::find()->where(['cengji'=>1])->asArray()->all());
    }
    public function actionGetCity($provincecode){
       echo json_encode(Locations::find()->andWhere(['parent_id'=>$provincecode])->asArray()->all());
    }
    public function actionGetArea($citycode){
       echo json_encode(Locations::find()->where(['parent_id'=>$citycode])->asArray()->all());
    }
    public function actionDelete($id){
        $model=Address::findOne(['id'=>$id]);
        $model->delete();
        return 'success';
    }
    public function actionEdit($id){
        $model=Address::findOne(['id'=>$id]);
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['address/index']);
            }else{
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->render('edit',['model'=>$model]);
    }

}