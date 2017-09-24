<?php

namespace frontend\controllers;


use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use frontend\models\Cart;
use yii\data\Pagination;
use yii\web\Cookie;
use yii\web\Request;

class IndexController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    public function actionIndex()
    {
        $categorys=\backend\models\GoodsCategory::find()->where(['parent_id'=>0])->all();
        return $this->renderPartial('index',['categorys'=>$categorys]);
    }
    public function actionList($category_id){
        $category = GoodsCategory::findOne(['id'=>$category_id]);

        $query = Goods::find();
        if($category->depth == 2){//3级分类
            $query->andWhere(['goods_category_id'=>$category_id]);
        }else{
            $ids = $category->children()->select('id')->andWhere(['depth'=>2])->column();
            $query->andWhere(['in','goods_category_id',$ids]);
        }
        $pager = new Pagination();
        $pager->totalCount = $query->count();
        $pager->defaultPageSize = 1;
        $models = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->renderPartial('list',['models'=>$models,'pager'=>$pager]);
    }
    public function actionGoods($id){
        $model=Goods::findOne(['id'=>$id]);
        $model1=GoodsGallery::find()->where(['goods_id'=>$id])->all();
        return $this->renderPartial('goods',['model'=>$model,'model1'=>$model1]);
    }
    public function actionAddtocart($goods_id,$amount){
            if(\Yii::$app->user->isGuest){
                $cookies=\Yii::$app->request->cookies;
                $value = $cookies->getValue('carts');//根据名字获取cookie中的值
                if($value){//值存在就反序列化值
                    $carts = unserialize($value);
                }else{
                    $carts = [];
                }
                if(array_key_exists($goods_id,$carts)){
                    $carts[$goods_id] += $amount;//如果购物车存在当前需要添加的商品就累加
                }else{
                    $carts[$goods_id] = intval($amount);//购物车不存在就把当前的id和数量存入数组
                }
                $cookies = \Yii::$app->response->cookies;
                $cookie = new Cookie();
                $cookie->name = 'carts'; //设置一个名字为carts的cookie
                $cookie->value = serialize($carts);//将添加的商品序列化,数组转字符串
                $cookie->expire = time()+7*24*3600;//过期时间戳
                $cookies->add($cookie);//添加cookie
            }else{
                //已登录的情况
                $cookies=\Yii::$app->request->cookies;
                $value = $cookies->getValue('carts');//根据名字获取cookie中的值
                if($value){//值存在就反序列化值
                    $carts = unserialize($value);
                    $model=new Cart();
                    foreach ($carts as $k=>$v){
                        $cart=Cart::findOne(['goods_id'=>$k,'member_id'=>\Yii::$app->user->getId()]);
                        if($cart){
                            $cart->amount+=$amount;
                            $cart->save();
                            $cookie=\Yii::$app->response->cookies;
                            $cookie->remove('carts');
                        }else{
                            $model->member_id=\Yii::$app->user->identity->getId();
                            $model->goods_id=$k;
                            $model->amount=$v;
                            $model->save();
                            $cookie=\Yii::$app->response->cookies;
                            $cookie->remove('carts');
                        }


                    }


                }
                $model=new Cart();
               if($model->validate()){
                   $cart=Cart::findOne(['goods_id'=>$goods_id]);
                   if($cart){
                       $cart->amount+=$amount;
                       $cart->save();
                   }else{
                       $model->member_id=\Yii::$app->user->getId();
                       $model->goods_id=$goods_id;
                       $model->amount=$amount;
                       $model->save();
                   }
               }
            }
        return $this->redirect(['cart']);
    }
    public function actionCart(){
        if(\Yii::$app->user->isGuest){
            $cookies = \Yii::$app->request->cookies;//如果没有登录就从cookie中找值
            $value = $cookies->getValue('carts');
            if($value){//如果cookie中有carts值,就将其反序列化为数组
                $carts = unserialize($value);
            }else{
                $carts = [];
            }
            $models = Goods::find()->where(['in','id',array_keys($carts)])->all();//根据cookie中carts的键为goods的id找所有数据

        }else{
            //登录了的情况

            $member_id=\Yii::$app->user->getId();
            $carts=Cart::find()->where(['member_id'=>$member_id])->all();

           foreach ($carts as $cart){
               $carts[$cart['goods_id']]=$cart['amount'];
           }
            $models=Goods::find()->where(['in','id',array_keys($carts)])->all();
        }
        return $this->renderPartial('cart',['models'=>$models,'carts'=>$carts]);

    }

    //AJAX修改购物车商品数量
    public function actionAjax(){

        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        if(\Yii::$app->user->isGuest){
            $cookies = \Yii::$app->request->cookies;
            $value = $cookies->getValue('carts');
            if($value){
                $carts = unserialize($value);
//              return \Yii::$app->request->post('goods1_id');
                if(\Yii::$app->request->post('goods1_id')){

                    $goods1_id=\Yii::$app->request->post('goods1_id');

                    unset ($carts[$goods1_id]);
                    $cookies = \Yii::$app->response->cookies;
                    $cookie = new Cookie();
                    $cookie->name = 'carts';
                    $cookie->value = serialize($carts);
                    $cookie->expire = time()+7*24*3600;
                    $cookies->add($cookie);
                    return 'success';
                }
            }else{
                $carts = [];
            }
            if(array_key_exists($goods_id,$carts)){//购物车有添加的商品就直接将改变后的数量赋值
                $carts[$goods_id] = $amount;
            }

            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($carts);
            $good=$cookie->value;

            $cookie->expire = time()+7*24*3600;
            $cookies->add($cookie);

        }else{
            $id=\Yii::$app->user->identity->getId();
            $model= Cart::findOne(['member_id'=>$id]);
            $model->amount=$amount;
            $model->save(false);
            if(\Yii::$app->request->post('goods1_id')){
                $model->delete();
                return 'success';
            }
        }
    }
}
