<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_category`.
 */
class m170907_082741_create_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_category', [
     /*       字段名	类型	注释
id	primaryKey
name	varchar(50)	名称
intro	text	简介
sort	int(11)	排序
status	int(2)	状态(-1删除 0隐藏 1正常)*/
            'id' => $this->primaryKey(),
            'name'=>$this->string('20')->notNull()->comment('名称'),
            'intro'=>$this->text()->notNull()->comment('简介'),
            'sort'=>$this->integer()->comment('排序'),
            'status'=>$this->integer()->comment('状态,-1删除,0隐藏,1正常')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_category');
    }
}
