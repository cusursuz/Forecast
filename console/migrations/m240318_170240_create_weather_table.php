<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%weather}}`.
 */
class m240318_170240_create_weather_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%weather}}', [
            'id' => $this->primaryKey(),
            'city_id' => $this->integer()->notNull(),
            'datetime' => $this->dateTime()->notNull(),
            'temp_min' => $this->decimal(4,2)->notNull(),
            'temp_max' => $this->decimal(4,2)->notNull(),
            'humidity' => $this->integer(2)->notNull(),
        ]);

        $this->createIndex(
            'idx-weather-city_id',
            'weather',
            'city_id'
        );

        $this->addForeignKey(
            'fk-weather-city_id',
            'weather',
            'city_id',
            'city',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%weather}}');
    }
}
