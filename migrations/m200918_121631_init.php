<?php

use yii\db\Migration;

/**
 * Class m200918_121631_init
 */
class m200918_121631_init extends Migration
{
    const TABLE_NAME = '{{%binar}}';

    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(11)->notNull(),
            'position' => $this->integer(11)->notNull(),
            'path' => $this->string(12288),
            'level' => $this->integer(11),
            'name' => $this->string(255)->defaultValue(null),
        ], null);
    }

    public function safeDown()
    {

        $this->dropTable(self::TABLE_NAME);

        return true;
    }
}
