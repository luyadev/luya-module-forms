<?php

use yii\db\Migration;

/**
 * Class m200910_114548_basetables
 */
class m200910_114548_basetables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%forms_form}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        $this->createTable('{{%forms_submission}}', [
            'id' => $this->primaryKey(),
            'form_id' => $this->integer()->notNull(),
            'useragent' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->createTable('{{%forms_submission_value}}', [
            'id' => $this->primaryKey(),
            'submission_id' => $this->integer(),
            'attribute' => $this->string()->notNull(),
            'label' => $this->string()->notNull(),
            'hint' => $this->string(),
            'value' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%forms_form}}');

        $this->dropTable('{{%forms_submission}}');

        $this->dropTable('{{%forms_submission_value}}');
    }
}
