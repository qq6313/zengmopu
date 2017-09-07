<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m170907_064740_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
    /*        字段名	类型	注释
id	primaryKey
name	varchar(50)	名称
intro	text	简介
logo	varchar(255)	LOGO图片
sort	int(11)	排序
status	int(2)	状态(-1删除 0隐藏 1正常)*/
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->comment('名称'),
            'intro'=>$this->string()->comment('简介'),
            'logo'=>$this->string()->comment('logo'),
            'sort'=>$this->integer(11)->comment('排序'),
            'status'=>$this->smallInteger(2)->comment('状态, -1删除 0隐藏 1 正常'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brand');
    }
}
