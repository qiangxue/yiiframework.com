<?php

use app\migrations\BaseMigration;

class m180327_221657_add_report_table extends BaseMigration
{
    public function up()
    {
        $this->createTable('{{%report}}', [
            'id' => $this->primaryKey(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'object_type' => $this->string()->notNull(),
            'object_id' => $this->integer()->notNull(),
            'content' => $this->text()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
            'creator_id' => $this->integer(),
            'updater_id' => $this->integer(),
        ], $this->tableOptions);

        $this->addForeignKey('fk-report-creator_id-user-id', '{{%report}}', 'creator_id', '{{%user}}', 'id', 'SET NULL');
        $this->addForeignKey('fk-report-updater_id-user-id', '{{%report}}', 'updater_id', '{{%user}}', 'id', 'SET NULL');
    }

    public function down()
    {
        $this->dropTable('{{%report}}');
    }
}
