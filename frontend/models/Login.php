<?php
namespace frontend\models;
use yii\base\Model;

class Login extends Model{
    public $username;
    public $password_hash;
    public $remember;
    public $checkcode;

    public function rules()
    {
        return [
            [['username', 'password_hash'], 'required'],
            ['remember', 'boolean'],
            ['checkcode','captcha']
        ];
    }
    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password_hash'=>'密码'
        ];
    }

    public function login(){
        $user = Member::findOne(['username'=>$this->username]);

        if($user){

            if(\Yii::$app->security->validatePassword($this->password_hash, $user->password_hash) ){
                if($this->remember){
                    return \Yii::$app->user->login($user,7*24*3600);
                }else{
                    return \Yii::$app->user->login($user);
                }

            }else{
                \Yii::$app->session->setFlash('success', '密码错误');
                $this->addError('password_hash','密码不正确');
            }
        }else{
            //没有找到该账户
            $this->addError('username','账户不存在');
        }
        return false;
    }
}