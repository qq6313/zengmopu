<?php
namespace frontend\controllers;

use backend\models\Goods;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\Request;

class OrderController extends Controller{
    public $enableCsrfValidation = false;
    public function actionIndex(){
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['login/index']);
        }
        $model=new Order();
        $request=new Request();

        if($request->isPost){

            $data=\Yii::$app->request->post();
//            var_dump($data);die;
            $data1=Address::findOne(['id'=>$data['address_id']]);
            $model->member_id=\Yii::$app->user->getId();
            $model->name=$data1->name;
            $model->province=$data1->province;
            $model->city=$data1->city;
            $model->area=$data1->area;
            $model->tel=$data1->tel;
            $model->address=$data1->detail_address;
            $delivery=Order::$deliveries[$data['delivery']];
            $model->delivery_id=$data['delivery'];
            $model->delivery_name=$delivery[0];
            $model->delivery_price=$delivery[1];
            $model->payment_id=$data['pay'];
            $payment=Order::$pay_method[$data['pay']];
            $model->payment_name=$payment[0];
            $model->create_time=time();
            $model->trade_no=uniqid();
            $getid=\Yii::$app->user->getId();
            $carts1=Cart::find()->where(['member_id'=>$getid])->asArray()->all();
            $amount='';
            $carts=[];
            foreach ($carts1 as $cart){
                $carts[$cart['goods_id']]=$cart['amount'];
            }
            $models=Goods::find()->where(['in','id',array_keys($carts)])->asArray()->all();
            foreach($models as $model1){
            foreach($carts as $k=>$v){
                    if($k==$model1['id']){
                        $amount+=$model1['shop_price']*$v;
                    }
                }
           }
          $total=intval($delivery[1]+$amount);
            $model->total=$total;
            $model->status=1;
            // 在操作mysql之前
            $transaction = \Yii::$app->db->beginTransaction();//开始事务
            try{
                $model->save();
                //订单商品详情表
                $carts = Cart::find()->where(['member_id'=>\Yii::$app->user->getId()])->all();
                foreach ($carts as $cart){
                    //检查库存
                    if($cart->amount > $cart->goods->stock){
                        //库存不足,不能下单(抛出异常)
                        throw new Exception($cart->goods->name.'商品库存不足,不能下单');
                    }else{
                        $good=Goods::findOne(['id'=>$cart->goods_id]);
                        $good->stock-=$cart->amount;
                        $good->save();
                    }
                    $order_goods = new OrderGoods();
                    $order_goods->order_id = $model->id;
                    $order_goods->goods_id = $cart->goods_id;
                    $total=Goods::find()->where(['id'=>$cart->goods_id])->asArray()->all();
                    foreach($total as $total1 ){
                        $order_goods->total=$total1['shop_price']*$cart->amount;
                    }
                    $order_goods->goods_name = $cart->goods->name;

                    $order_goods->logo=$cart->goods->logo;
                    $order_goods->price=$cart->goods->shop_price;
                    $order_goods->amount=$cart->amount;
                    $order_goods->save();
                    $cart->delete();


                }


                //提交事务
                $transaction->commit();
                //跳转到下单成功提示页
            }catch (Exception $e){
                //不能下单,需要回滚
                $transaction->rollBack();
            }
            return $this->redirect(['order']);
        }

        $user_id=\Yii::$app->user->getId();
        $address=Address::find()->where(['user_id'=>$user_id])->all();
        $cart=Cart::find()->where(['member_id'=>$user_id])->all();
        return $this->renderPartial('index',['address'=>$address,'carts'=>$cart]);
    }
    public function actionOrder(){
        $models=Order::find()->where(['member_id'=>\Yii::$app->user->getId()])->all();

        return $this->renderPartial('order',['models'=>$models]);
    }

}