<?php

use yii\db\Migration;

/**
 * Handles the creation of table `member`.
 */
class m170918_061257_create_member_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('member', [
            'id' => $this->primaryKey(),
           'username'=>$this->string()->comment('用户名'),
            'auth_key'=>$this->string(),
            'password_hash'=>$this->string()->comment('密码'),
            'email'=>$this->string()->comment('邮箱'),
            'tel'=>$this->string()->comment('电话'),
            'last_login_time'=>$this->integer()->comment('最后登录时间'),
            'last_login_ip'=>$this->string()->comment('最后登录ip'),
            'status'=>$this->smallInteger()->comment('状态,1正常,0删除'),
            'created'=>$this->integer()->comment('添加时间'),
            'updated'=>$this->integer()->comment('修改时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('member');
    }
}
