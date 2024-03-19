<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%country}}`.
 */
class m240318_142220_create_country_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%country}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'country_code' => $this->string(2)->notNull()->unique(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%country}}');
    }
}
