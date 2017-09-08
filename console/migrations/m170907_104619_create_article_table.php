<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m170907_104619_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
      /*      字段名	类型	注释
id	primaryKey
name	varchar(50)	名称
intro	text	简介
article_category_id	int()	文章分类id
sort	int(11)	排序
status	int(2)	状态(-1删除 0隐藏 1正常)
create_time	int(11)	创建时间*/
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('名称'),
            'intro'=>$this->text()->notNull()->comment('简介'),
            'article_category_id'=>$this->integer()->notNull()->comment('文章分类id'),
            'sort'=>$this->integer(11)->notNull()->comment('排序'),
            'status'=>$this->smallInteger()->notNull()->comment('状态,-1删除,0隐藏,1正常'),
            'create_time'=>$this->integer()->notNull()->comment('创建时间')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article');
    }
}
