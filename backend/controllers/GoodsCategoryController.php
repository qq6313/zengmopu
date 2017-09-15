<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\web\Request;

class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model=GoodsCategory::find();
        $pager = new Pagination([
            'totalCount' => $model->count(),//总条数
            'defaultPageSize' => 4//每页多少条
        ]);
        $models = $model->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index', ['pager' => $pager, 'models' => $models]);

    }
    public function actionAdd(){
        $model = new GoodsCategory();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //判断添加顶级分类还是非顶级分类(子分类)
                if($model->parent_id){
                    //非顶级分类(子分类)
                    $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                    //顶级分类
                    $model->makeRoot();
                }
                //$model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id){
        $model=GoodsCategory::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //判断添加顶级分类还是非顶级分类(子分类)
                if($model->parent_id){
                    //非顶级分类(子分类)
                    $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                    if($model->getOldAttribute('parent_id'==0)){
                         $model->makeRoot();
                    }else{
                        $model->save();
                    }

                }
                //$model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDelete(){
       $id=\Yii::$app->request->post('id');
        $node=GoodsCategory::findOne(['id'=>$id]);
      if ($node->isLeaf()){//是否是叶子
          $node->deleteWithChildren();
          return 'success';
      }



    }
}
