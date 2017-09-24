<?php
namespace frontend\controllers;

use Codeception\Module\Redis;
use frontend\models\Member;
use yii\web\Controller;
use yii\web\Request;

class RegistController extends Controller{

    public function actionIndex(){
        $model=new Member();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post(),'');
            if ($model->validate()){
                $model->save(false);
               return  $this->redirect(['login/index']);
            }else{
                var_dump($model->getErrors());
                exit;
            }
        }

        return $this->renderPartial('index');
    }
    public function actionVilidateUser($username){
       $user= Member::findOne(['username'=>$username]);
        if($user){
            return 'false';
        }else{
            return 'true';
        }


    }
    public function actionSms($phone){
        // 调用示例：
        $code='';
      $demo = new \frontend\models\SmsDemo(
            "LTAIY9EmvG25yMwl",
            "cRYOkuQ92hhnJK4dNSCVdVpryi1lsR"
        );

//        echo "SmsDemo::sendSms\n";

        $response = $demo->sendSms(
            "曾大大的茶馆", // 短信签名
            "SMS_97925001", // 短信模板编号
            $phone, // 短信接收者
          $code=  Array(  // 短信模板中字段的值
                "code"=>rand(10000,99999),

            )

        );

//         $code=rand(10000,99999);
         $redis=new \Redis();
         $redis->connect('127.0.0.1');
         $redis->set('captcha'.$phone,$code);
//         echo $redis->get('captcha'.$phone);
        echo json_encode($code);
      /*  if($response->Message == 'OK'){
            echo json_encode($code);
        }else{
            echo '发送失败';
        }*/


    }
    public function actionValidateSms($sms,$phone)
    {
        $redis=new \Redis();
        $redis->connect('127.0.0.1');
        $code=$redis->get('captcha'.$phone);
        echo json_encode($code);
        if($code==null || $code != $sms){
            return 'false';

        }
        //
        return 'true';
    }
}