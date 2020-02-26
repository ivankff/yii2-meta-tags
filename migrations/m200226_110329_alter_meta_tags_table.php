<?php

namespace ivankff\metaTags\migrations;
use yii\db\Migration;

/**
 */
class m200226_110329_alter_meta_tags_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%meta_tags}}', 'model_id', $this->string(255)->notNull());
        $this->alterColumn('{{%meta_tags}}', 'title', $this->text()->null());
        $this->alterColumn('{{%meta_tags}}', 'keywords', $this->text()->null());
        $this->alterColumn('{{%meta_tags}}', 'description', $this->text()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%meta_tags}}', 'model_id', $this->integer(11)->notNull());
        $this->alterColumn('{{%meta_tags}}', 'title', $this->string(255)->notNull());
        $this->alterColumn('{{%meta_tags}}', 'keywords', $this->string(255)->notNull());
        $this->alterColumn('{{%meta_tags}}', 'description', $this->text()->notNull());
    }

}
