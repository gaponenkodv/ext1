<?php

use yii\db\Migration;

class m160726_215241_CreateTables extends Migration
{
    /**
     * Миграция для оздания 2 таблиц news и likes
     */
    public function up()
    {
        $this->createTable('news', [
            'id' => $this->primaryKey(),
            'title' => $this->string(32)->notNull(),
            'body' => $this->string(243)->notNull(),
        ]);

        $this->createTable('likes', [
            'id' => $this->primaryKey(),
            'news_id' => $this->integer()->notNull(),
            'user' => $this->string(50)->notNull(),
        ]);

        $this->createIndex(
            'news_id',
            'likes',
            'news_id'
        );
    }

    /**
     * Удаление таблиц
     */
    public function down()
    {
        $this->dropTable('news');
        $this->dropTable('likes');
    }
}
