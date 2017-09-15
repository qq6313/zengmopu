<?php
namespace backend\models;
use yii\base\Model;

class Login extends Model{
    public $username;
    public $password_hash;
    public $code;
    public $remember;



    public function rules()
    {
        return [
            [['username','password_hash'],'required'],
            ['code','captcha','captchaAction'=>'login/captcha'],
            ['remember', 'boolean'],
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
        $user = Admin::findOne(['username'=>$this->username]);
        if($user){
            if(\Yii::$app->security->validatePassword($this->password_hash, $user->password_hash) ){
                return \Yii::$app->user->login($user,7*24*3600);
            }else{
                $this->addError('password_hash','密码不正确');
            }
        }else{
            //没有找到该账户
            //echo '账户不存在';exit;
            $this->addError('username','账户不存在');
        }
        return false;
    }
}
