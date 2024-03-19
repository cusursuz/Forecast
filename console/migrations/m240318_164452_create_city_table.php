<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%city}}`.
 */
class m240318_164452_create_city_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%city}}', [
            'id' => $this->primaryKey(),
            'country_id' => $this->integer()->notNull(),
            'name' => $this->string(50)->notNull(),
            'lat' => $this->decimal(9,6)->notNull(),
            'lon' => $this->decimal(9,6)->notNull(),
            'state' => $this->string(50)->null(),
        ]);

        $this->createIndex(
            'idx-city-country_id',
            'city',
            'country_id'
        );

        $this->addForeignKey(
            'fk-city-country_id',
            'city',
            'country_id',
            'country',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%city}}');
    }
}
