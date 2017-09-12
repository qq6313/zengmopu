<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_category`.
 */
class m170910_034322_create_goods_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_category', [
            'id' => $this->primaryKey(),
            'tree' => $this->integer()->notNull()->comment('树id'),
            'lft' => $this->integer()->notNull()->comment('左id'),
            'rgt' => $this->integer()->notNull()->comment('右id'),
            'depth' => $this->integer()->notNull()->comment('深度'),
            'name' => $this->string()->notNull()->comment('名称'),
            'parent_id' => $this->integer()->notNull()->comment('上级分类id'),
            'intro' => $this->text()->notNull()->comment('简介'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_category');
    }
}
